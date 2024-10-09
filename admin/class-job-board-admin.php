<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/admin
 */

class Job_Board_Admin {

    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        add_action( 'init', array( $this, 'register_job_board_block' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/job-board-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/job-board-admin.js', array( 'jquery' ), $this->version, false );

        // Enqueue Select2
        wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css' );
        wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
    }

    /**
     * Add menu item for the plugin
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            __( 'Job Board', 'job-board' ),
            __( 'Job Board', 'job-board' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-businessperson',
            30
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Job Titles', 'job-board' ),
            __( 'Job Titles', 'job-board' ),
            'manage_options',
            'edit.php?post_type=job_titles'
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Add New Job Title', 'job-board' ),
            __( 'Add New Job Title', 'job-board' ),
            'manage_options',
            'post-new.php?post_type=job_titles'
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Job Categories', 'job-board' ),
            __( 'Job Categories', 'job-board' ),
            'manage_options',
            'edit-tags.php?taxonomy=job_category&post_type=job_titles'
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Job Skills', 'job-board' ),
            __( 'Job Skills', 'job-board' ),
            'manage_options',
            'edit-tags.php?taxonomy=job_skill&post_type=job_titles'
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Job Board Settings', 'job-board' ),
            __( 'Settings', 'job-board' ),
            'manage_options',
            $this->plugin_name . '-settings',
            array( $this, 'display_plugin_admin_settings' )
        );
    }

    /**
     * Render the main admin page for the plugin
     */
    public function display_plugin_admin_page() {
        include_once 'partials/job-board-admin-display.php';
    }

    /**
     * Render the settings page for the plugin
     */
    public function display_plugin_admin_settings() {
        include_once 'partials/job-board-admin-settings.php';
    }

    /**
     * Register the Job Board Gutenberg block
     */
    public function register_job_board_block() {
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }
    
        wp_register_script(
            'job-board-block-editor',
            plugin_dir_url( __FILE__ ) . 'js/job-board-block.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
            $this->version
        );
    
        register_block_type( 'job-board/job-listings', array(
            'editor_script' => 'job-board-block-editor',
            'render_callback' => array( $this, 'render_job_board_block' ),
            'attributes' => array(
                'posts_per_page' => array(
                    'type' => 'number',
                    'default' => 10,
                ),
            ),
        ) );
    }

    /**
     * Render callback for the Job Board Gutenberg block
     */
    public function render_job_board_block( $attributes ) {
        $shortcode = new Job_Board_Shortcodes();
        return $shortcode->job_board_listings_shortcode( $attributes );
    }

    /**
     * Register settings for the plugin
     */
    public function register_settings() {
        register_setting(
            'job_board_options',
            'job_board_settings',
            array( $this, 'sanitize_settings' )
        );

        add_settings_section(
            'job_board_general_settings',
            __( 'General Settings', 'job-board' ),
            array( $this, 'general_settings_callback' ),
            'job_board_settings'
        );

        add_settings_field(
            'entries_per_page',
            __( 'Entries per page', 'job-board' ),
            array( $this, 'entries_per_page_callback' ),
            'job_board_settings',
            'job_board_general_settings'
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings( $input ) {
        $sanitized_input = array();
        if ( isset( $input['entries_per_page'] ) ) {
            $sanitized_input['entries_per_page'] = absint( $input['entries_per_page'] );
        }
        return $sanitized_input;
    }

    /**
     * Callback for general settings section
     */
    public function general_settings_callback() {
        echo '<p>' . __( 'Configure general settings for the Job Board plugin.', 'job-board' ) . '</p>';
    }

    /**
     * Callback for entries per page setting
     */
    public function entries_per_page_callback() {
        $options = get_option( 'job_board_settings' );
        $value = isset( $options['entries_per_page'] ) ? $options['entries_per_page'] : 10;
        echo "<input type='number' name='job_board_settings[entries_per_page]' value='" . esc_attr( $value ) . "' min='1' max='100'>";
    }
}
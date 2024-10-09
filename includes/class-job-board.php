<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board {

    protected $loader;

    protected $plugin_name;

    protected $version;

    /**
     * Define the core functionality of the plugin.
     */
    public function __construct() {
        if ( defined( 'JOB_BOARD_VERSION' ) ) {
            $this->version = JOB_BOARD_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'job-board';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-job-board-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-job-board-public.php';

        /**
         * The class responsible for defining custom post types.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-post-types.php';

        /**
         * The class responsible for handling custom fields.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-custom-fields.php';

        /**
         * The class responsible for handling shortcodes.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-shortcodes.php';

        /**
         * The class responsible for handling form submissions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-form-handler.php';

        $this->loader = new Job_Board_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     */
    private function set_locale() {
        $plugin_i18n = new Job_Board_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks() {
        $plugin_admin = new Job_Board_Admin( $this->get_plugin_name(), $this->get_version() );
        $post_types = new Job_Board_Post_Types();
        $custom_fields = new Job_Board_Custom_Fields();

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $post_types, 'register_job_titles_post_type' );
        $this->loader->add_action( 'init', $custom_fields, 'init' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'admin_init', $this, 'register_settings' );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     */
    private function define_public_hooks() {
        $plugin_public = new Job_Board_Public( $this->get_plugin_name(), $this->get_version() );
        $shortcodes = new Job_Board_Shortcodes();
        $form_handler = new Job_Board_Form_Handler();

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $shortcodes, 'register_shortcodes' );
        $this->loader->add_action( 'wp_ajax_submit_job_application', $form_handler, 'handle_job_application' );
        $this->loader->add_action( 'wp_ajax_nopriv_submit_job_application', $form_handler, 'handle_job_application' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     */
    public function get_version() {
        return $this->version;
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
<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/public
 */

class Job_Board_Public {

    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action( 'wp_head', array( $this, 'add_theme_compatibility_styles' ) );
        add_filter( 'single_template', array( $this, 'load_job_title_template' ) );
    }

    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/job-board-public.css', array(), $this->version, 'all' );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/job-board-public.js', array( 'jquery' ), $this->version, false );

        wp_localize_script( $this->plugin_name, 'job_board_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'job_board_submit_application' ),
        ) );
    }

    public function add_theme_compatibility_styles() {
        $current_theme = wp_get_theme();
        $theme_name = $current_theme->get( 'Name' );
        $theme_specific_styles = $this->get_theme_specific_styles( $theme_name );

        if ( ! empty( $theme_specific_styles ) ) {
            echo '<style type="text/css">' . $theme_specific_styles . '</style>';
        }
    }

    private function get_theme_specific_styles( $theme_name ) {
        $styles = '';

        // Theme-specific styles here
        switch ( $theme_name ) {
            case 'Twenty Twenty-One':
                $styles = '
                    .job-board-table { font-family: var(--global--font-secondary); }
                    .job-board-form input[type="text"], .job-board-form select { 
                        font-size: var(--form--font-size);
                        line-height: var(--global--line-height-body);
                    }
                ';
                break;
            default:
                $styles = '
                    .job-board-table { width: 100%; border-collapse: collapse; }
                    .job-board-table th, .job-board-table td { padding: 10px; border: 1px solid #ddd; }
                    .job-board-form input[type="text"], .job-board-form select { width: 100%; padding: 5px; }
                ';
                break;
        }

        return $styles;
    }

    /**
     * Load custom template for single job title.
     */
    public function load_job_title_template( $template ) {
        if ( is_singular( 'job_titles' ) ) {
            $plugin_template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/single-job_titles.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }
}
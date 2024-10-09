<?php
/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board_Activator {

    /**
     * Activate the plugin.
     *
     * Create custom post types, flush rewrite rules, and set default options.
     */
    public static function activate() {
        // Create custom post types
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-job-board-post-types.php';
        $post_types = new Job_Board_Post_Types();
        $post_types->register_job_titles_post_type();

        // Clear the permalinks after the post type has been registered
        flush_rewrite_rules();

        // Create default options
        $default_options = array(
            'version' => JOB_BOARD_VERSION,
            'skills' => array('PHP', 'JavaScript', 'HTML', 'CSS', 'WordPress'), // Default skills
            'entries_per_page' => 10,
        );

        update_option('job_board_options', $default_options);

        // Create a custom role for job board management
        add_role(
            'job_board_manager',
            __('Job Board Manager', 'job-board'),
            array(
                'read' => true,
                'edit_posts' => true,
                'edit_job_titles' => true,
                'edit_others_job_titles' => true,
                'publish_job_titles' => true,
                'read_job_application' => true,
                'edit_job_application' => true,
                'edit_job_applications' => true,
                'edit_others_job_applications' => true,
                'publish_job_applications' => true,
                'delete_job_applications' => true,
            )
        );
    }
}
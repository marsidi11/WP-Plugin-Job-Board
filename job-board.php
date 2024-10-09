<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://wpriders.com/job-board
 * @since             1.0.0
 * @package           Job_Board
 *
 * @wordpress-plugin
 * Plugin Name:       Job Board
 * Plugin URI:        https://wpriders.com/job-board
 * Description:       A simple job board plugin for WordPress
 * Version:           1.0.0
 * Author:            Marsid Zyberi
 * Author URI:        https://wpriders.com
 * Text Domain:       job-board
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'JOB_BOARD_VERSION', '1.0.0' );

function activate_job_board() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-job-board-activator.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-job-board-post-types.php';
    Job_Board_Activator::activate();
    Job_Board_Post_Types::flush_rewrite_rules();
}

function deactivate_job_board() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-job-board-deactivator.php';
    Job_Board_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_job_board' );
register_deactivation_hook( __FILE__, 'deactivate_job_board' );

require plugin_dir_path( __FILE__ ) . 'includes/class-job-board.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-job-board-post-types.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-job-board-admin-columns.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-job-board-application-fields.php';

function run_job_board() {
    $plugin = new Job_Board();
    $plugin->run();

    $post_types = new Job_Board_Post_Types();
    $post_types->register_post_types();

    if ( is_admin() ) {
        new Job_Board_Admin_Columns();
        new Job_Board_Application_Fields();
    }
}

add_action('plugins_loaded', 'run_job_board');
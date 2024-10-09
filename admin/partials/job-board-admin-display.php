<?php
/**
 * Provide a admin area view for the plugin
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p><?php _e( 'Welcome to the Job Board plugin. Use the settings page to configure the plugin.', 'job-board' ); ?></p>
    <h2><?php _e( 'Quick Stats', 'job-board' ); ?></h2>
    <?php
    $job_titles_count = wp_count_posts( 'job_titles' );
    $applications_count = wp_count_posts( 'job_application' );
    ?>
    <ul>
        <li><?php printf( __( 'Total Job Titles: %d', 'job-board' ), $job_titles_count->publish ?? 0 ); ?></li>
        <li><?php printf( __( 'Total Applications: %d', 'job-board' ), $applications_count->publish ?? 0 ); ?></li>
    </ul>
    <p><?php printf( __( 'To displays the list of job listings, use the shortcode %s or the Gutenberg block.', 'job-board' ), '<code>[job_board_listings]</code>' ); ?></p>
    <p><?php printf( __( 'To displays the job application form, use the shortcode %s or the Gutenberg block.', 'job-board' ), '<code>[job_board_form]</code>' ); ?></p>
</div>
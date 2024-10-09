<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete all the custom post types
$post_types = array( 'job_titles', 'job_application' );

foreach ( $post_types as $post_type ) {
    $posts = get_posts( array(
        'post_type'      => $post_type,
        'posts_per_page' => -1,
        'post_status'    => 'any',
    ) );

    foreach ( $posts as $post ) {
        wp_delete_post( $post->ID, true );
    }
}

// Delete all the plugin options
delete_option( 'job_board_version' );
delete_option( 'job_board_settings' );

// Delete transients
delete_transient( 'job_board_skills_filter' );

// Clear cached data that has been removed
wp_cache_flush();

// Remove custom roles or capabilities
remove_role( 'job_board_manager' );
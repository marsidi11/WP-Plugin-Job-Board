<?php
/**
 * Fired during plugin deactivation.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board_Deactivator {

    public static function deactivate() {
        // Unregister the post type, so the rules are no longer in memory
        unregister_post_type('job_titles');
        
        // Clear the permalinks to remove our post type's rules from the database
        flush_rewrite_rules();

        // Remove the custom role
        remove_role('job_board_manager');
    }
}
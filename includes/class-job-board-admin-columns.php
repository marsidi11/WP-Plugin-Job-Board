<?php
/**
 * Handle custom admin columns for the plugin.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board_Admin_Columns {

    public function __construct() {
        add_filter( 'manage_job_application_posts_columns', array( $this, 'set_custom_edit_job_application_columns' ) );
        add_action( 'manage_job_application_posts_custom_column', array( $this, 'custom_job_application_column' ), 10, 2 );
    }

    public function set_custom_edit_job_application_columns($columns) {
        $columns['job_title'] = __( 'Job Title', 'job-board' );
        $columns['applicant_name'] = __( 'Applicant', 'job-board' );
        $columns['applicant_email'] = __( 'Email', 'job-board' );
        $columns['application_date'] = __( 'Application Date', 'job-board' );

        // Remove the default 'date' column
        unset($columns['date']);

        return $columns;
    }

    public function custom_job_application_column( $column, $post_id ) {
        switch ( $column ) {
            case 'job_title' :
                echo get_post_meta( $post_id, '_job_title', true ); 
                break;
            case 'applicant_name' :
                $first_name = get_post_meta( $post_id, '_first_name', true );
                $last_name = get_post_meta( $post_id, '_last_name', true );
                echo $first_name . ' ' . $last_name;
                break;
            case 'applicant_email' :
                echo get_post_meta( $post_id, '_email', true );
                break;
            case 'application_date' :
                echo get_the_date( '', $post_id );
                break;
        }
    }
}
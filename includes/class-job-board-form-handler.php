<?php
class Job_Board_Form_Handler {
    public function handle_job_application() {
        check_ajax_referer( 'job_board_submit_application', 'security' );

        // Get the job ID from the referrer URL
        $referer = wp_get_referer();
        $job_id = url_to_postid($referer);

        // If we couldn't get the job ID from the referrer, try to get it from the form data
        if (!$job_id && isset($_POST['job_id'])) {
            $job_id = intval($_POST['job_id']);
        }

        // If we still don't have a job ID, return an error
        if (!$job_id) {
            wp_send_json_error( array( 'message' => __( 'Unable to determine which job you are applying for. Please try again.', 'job-board' ) ) );
        }

        // Get the job title
        $job_title = get_the_title($job_id);

        $first_name = isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '';
        $email = isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field( $_POST['phone'] ) : '';
        $cover_letter = isset($_POST['cover_letter']) ? sanitize_textarea_field( $_POST['cover_letter'] ) : '';

        // Validate input
        if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'job-board' ) ) );
        }

        // Handle CV upload
        $cv_url = '';
        if ( ! empty( $_FILES['cv'] ) ) {
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }

            $uploadedfile = $_FILES['cv'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            if ( $movefile && ! isset( $movefile['error'] ) ) {
                $cv_url = $movefile['url'];
            } else {
                wp_send_json_error( array( 'message' => __( 'Error uploading CV. Please try again.', 'job-board' ) ) );
            }
        }

        // Save application to database
        $application_id = wp_insert_post( array(
            'post_title'  => sprintf( __( 'Application: %s - %s %s', 'job-board' ), $job_title, $first_name, $last_name ),
            'post_type'   => 'job_application',
            'post_status' => 'publish',
        ) );

        if ( is_wp_error( $application_id ) ) {
            wp_send_json_error( array( 'message' => __( 'An error occurred while submitting your application. Please try again.', 'job-board' ) ) );
        }

        // Save application meta
        update_post_meta( $application_id, '_job_id', $job_id );
        update_post_meta( $application_id, '_job_title', $job_title );
        update_post_meta( $application_id, '_first_name', $first_name );
        update_post_meta( $application_id, '_last_name', $last_name );
        update_post_meta( $application_id, '_email', $email );
        update_post_meta( $application_id, '_phone', $phone );
        update_post_meta( $application_id, '_cover_letter', $cover_letter );
        update_post_meta( $application_id, '_cv_url', $cv_url );

        wp_send_json_success( array( 'message' => __( 'Your application has been submitted successfully.', 'job-board' ) ) );
    }
}
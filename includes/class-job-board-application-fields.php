<?php
class Job_Board_Application_Fields {
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_job_application', array( $this, 'save_meta_boxes' ) );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'job_application_details',
            __( 'Application Details', 'job-board' ),
            array( $this, 'render_meta_box' ),
            'job_application',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        $email = get_post_meta( $post->ID, '_email', true );
        $phone = get_post_meta( $post->ID, '_phone', true );
        $cv_url = get_post_meta( $post->ID, '_cv_url', true );
        $cover_letter = get_post_meta( $post->ID, '_cover_letter', true );

        wp_nonce_field( 'job_application_details', 'job_application_details_nonce' );

        echo '<p><strong>' . __( 'Email:', 'job-board' ) . '</strong> ' . esc_html( $email ) . '</p>';
        echo '<p><strong>' . __( 'Phone:', 'job-board' ) . '</strong> ' . esc_html( $phone ) . '</p>';
        
        if ( $cv_url ) {
            echo '<p><strong>' . __( 'CV:', 'job-board' ) . '</strong> <a href="' . esc_url( $cv_url ) . '" target="_blank">' . __( 'View CV', 'job-board' ) . '</a></p>';
        } else {
            echo '<p><strong>' . __( 'CV:', 'job-board' ) . '</strong> ' . __( 'No CV uploaded', 'job-board' ) . '</p>';
        }

        echo '<p><strong>' . __( 'Cover Letter:', 'job-board' ) . '</strong></p>';
        echo '<textarea name="cover_letter" rows="5" cols="50" style="width:100%;">' . esc_textarea( $cover_letter ) . '</textarea>';
    }

    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['job_application_details_nonce'] ) || ! wp_verify_nonce( $_POST['job_application_details_nonce'], 'job_application_details' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['cover_letter'] ) ) {
            update_post_meta( $post_id, '_cover_letter', sanitize_textarea_field( $_POST['cover_letter'] ) );
        }
    }
}
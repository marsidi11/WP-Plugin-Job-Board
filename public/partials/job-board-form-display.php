<?php
/**
 * Provide a public-facing view for the job application form
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/public/partials
 */
?>

<div class="job-board-form-wrapper">
    <h2><?php _e( 'Apply for', 'job-board' ); ?> <span class="job-title-placeholder"></span></h2>
    <form id="job-board-application-form" class="job-board-form" enctype="multipart/form-data">
        <input type="hidden" id="job-id" name="job_id">
        <input type="hidden" id="job-title" name="job_title">
        
        <div class="form-group">
            <label for="first-name"><?php _e( 'First Name', 'job-board' ); ?></label>
            <input type="text" id="first-name" name="first_name" required>
        </div>
        
        <div class="form-group">
            <label for="last-name"><?php _e( 'Last Name', 'job-board' ); ?></label>
            <input type="text" id="last-name" name="last_name" required>
        </div>
        
        <div class="form-group">
            <label for="email"><?php _e( 'Email', 'job-board' ); ?></label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="phone"><?php _e( 'Phone', 'job-board' ); ?></label>
            <input type="tel" id="phone" name="phone">
        </div>
        
        <div class="form-group">
            <label for="cv"><?php _e( 'Upload CV', 'job-board' ); ?></label>
            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
        </div>
        
        <div class="form-group">
            <label for="cover-letter"><?php _e( 'Cover Letter', 'job-board' ); ?></label>
            <textarea id="cover-letter" name="cover_letter" rows="5"></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit"><?php _e( 'Submit Application', 'job-board' ); ?></button>
        </div>
    </form>
    <div id="job-board-form-message"></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#job-board-application-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'submit_job_application');
        formData.append('security', job_board_ajax.nonce);

        $.ajax({
            url: job_board_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Remove the form
                    $('.job-board-form-wrapper').empty();
                    
                    // Display the success message
                    $('.job-board-form-wrapper').html('<div class="success">' + response.data.message + '</div>');
                    
                    // Optionally, scroll to the message
                    $('html, body').animate({
                        scrollTop: $('.job-board-form-wrapper').offset().top
                    }, 500);
                } else {
                    $('#job-board-form-message').html('<div class="error">' + response.data.message + '</div>');
                }
            },
            error: function() {
                $('#job-board-form-message').html('<div class="error"><?php _e( 'An error occurred. Please try again.', 'job-board' ); ?></div>');
            }
        });
    });
});
</script>
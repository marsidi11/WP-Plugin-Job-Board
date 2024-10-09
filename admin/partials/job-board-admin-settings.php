<?php
/**
 * Provide a admin area view for the plugin settings
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form method="post" action="options.php">
    <?php
        settings_fields( 'job_board_options' );
        do_settings_sections( 'job_board_settings' );
        submit_button();
    ?>
    </form>
</div>
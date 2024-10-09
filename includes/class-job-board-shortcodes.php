<?php
class Job_Board_Shortcodes {

    public function register_shortcodes() {
        add_shortcode( 'job_board_listings', array( $this, 'job_board_listings_shortcode' ) );
        add_shortcode( 'job_board_form', array( $this, 'job_board_form_shortcode' ) );
    }

    public function job_board_listings_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 10,
        ), $atts, 'job_board_listings' );
    
        $args = array(
            'post_type'      => 'job_titles',
            'posts_per_page' => intval($atts['posts_per_page']),
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
    
        $job_listings = new WP_Query( $args );
    
        ob_start();
        include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/job-board-listings-display.php';
        return ob_get_clean();
    }

    public function job_board_form_shortcode() {
        ob_start();
        include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/job-board-form-display.php';
        return ob_get_clean();
    }
}
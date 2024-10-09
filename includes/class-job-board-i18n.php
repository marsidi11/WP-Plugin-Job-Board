<?php
/**
 * Define the internationalization functionality.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board_i18n {

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'job-board',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }
}
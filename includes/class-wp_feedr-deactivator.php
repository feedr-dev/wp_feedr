<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.feedr.nl
 * @since      1.0.0
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_feedr
 * @subpackage Wp_feedr/includes
 * @author     Feedr <info@feedr.nl>
 */
class Wp_feedr_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('wp_feedr_countries');
		delete_option('wp_feedr_regions');
		delete_option('wp_feedr_cities');

		delete_option('wp_feedr_apikey');
		delete_option('wp_feedr_cron_period');
		delete_option('wp_feedr_accommodations_box_position');
		delete_option('wp_feedr_active_post_types');
		delete_option('wp_feedr_json_callback_function');
		delete_option('wp_feedr_json_accommodation_template');
	}

}

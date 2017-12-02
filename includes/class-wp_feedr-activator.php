<?php
/**
 * Fired during plugin activation
 *
 * @link       http://www.feedr.nl
 * @since      1.0.0
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_feedr
 * @subpackage Wp_feedr/includes
 * @author     Feedr <info@feedr.nl>
 */
class Wp_feedr_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('wp_feedr_countries');
		add_option('wp_feedr_regions');
		add_option('wp_feedr_cities');

		add_option('wp_feedr_apikey');
		add_option('wp_feedr_cron_period');
		add_option('wp_feedr_accommodations_box_position');
		add_option('wp_feedr_active_post_types');
		add_option('wp_feedr_json_callback_function');
		add_option('wp_feedr_json_accommodation_template');

		update_option('wp_feedr_json_accommodation_template', file_get_contents(plugin_dir_path( __FILE__ ) .'../admin/template/default.php'));
	}

}

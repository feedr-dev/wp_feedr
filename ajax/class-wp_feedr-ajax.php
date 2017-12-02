<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.feedr.nl
 * @since      1.0.0
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/public
 * @author     Feedr <info@feedr.nl>
 */
class Wp_feedr_Ajax {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_result = [];
	}
	
	public function list_regions_by_country(){

		$regions = get_option( 'wp_feedr_regions' );
		$country_id = $_POST[ 'country_id' ];

		wp_send_json($regions[$country_id]);

		wp_die();
	}

	public function list_cities_by_region(){

		$cities = get_option( 'wp_feedr_cities' );
		$region_id = $_POST[ 'region_id' ];

		wp_send_json($cities[$region_id]);

		wp_die();
	}


}

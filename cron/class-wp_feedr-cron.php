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
class Wp_feedr_Cron {

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

	private $apikey;
	private $stream_context;
	private $api_result;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_result = [];
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_feedr_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_feedr_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp_feedr-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_feedr_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_feedr_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp_feedr-public.js', array( 'jquery' ), $this->version, false );

	}

	public function import_locations(){
		$this->apikey = get_option( 'wp_feedr_apikey' );

		$this->stream_context = stream_context_create([
		    "http" => [
		        "header" => "x-api-key: ". $this->apikey ."\r\n"
		    ]
		]);

		
		if (!get_option('wp_feedr_countries'))
		{
			add_option('wp_feedr_countries');
		}
		if (!get_option('wp_feedr_regions'))
		{
			add_option('wp_feedr_regions');
		}
		if (!get_option('wp_feedr_cities'))
		{
			add_option('wp_feedr_cities');
		}

		$this->import_countries();
		$this->import_regions();
		$this->import_cities();
	}

	private function import_countries(){
		$countries = json_decode(file_get_contents('http://manage.feedr.nl/api/v2/countries', false, $this->stream_context));
		$result = [];

		foreach($countries as $country)
		{
			$result[$country->id] = [
				"name" 		=> $country->name,
			];
		}

		update_option('wp_feedr_countries', $result);
		return true;
	}

	private function import_regions(){
		$regions = json_decode(file_get_contents('http://manage.feedr.nl/api/v2/regions', false, $this->stream_context));
		$result = [];

		foreach($regions as $region)
		{
			$result[$region->country_id][] = [
				"id"	=> $region->id,
				"name" 	=> $region->name
			];
		}
		update_option('wp_feedr_regions', $result);
		return;
	}

	private function import_cities(){
		$cities = json_decode(file_get_contents('http://manage.feedr.nl/api/v2/cities', false, $this->stream_context));
		$result = [];

		foreach($cities as $city)
		{
			$result[$city->region_id][] = [
				"id"	=> $city->id,
				"name" 	=> $city->name
			];
		}
		update_option('wp_feedr_cities', $result);
		return;
	}
}

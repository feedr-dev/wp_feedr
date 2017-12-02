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
class Wp_feedr_Public {

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

	public function render_accommodations($content){

		if (!is_single()) return $content;

		global $post;

		$position = get_option( 'wp_feedr_accommodations_box_position' );

		//$wp_feedr['type']			= 'accommodations';
		$wp_feedr['json_callback']		= get_option('wp_feedr_json_callback_function');
		$wp_feedr['render_type']		= get_post_meta($post->ID, 'wp_feedr_render_type', true);
		$wp_feedr['accommodation_type'] = get_post_meta($post->ID, 'wp_feedr_selected_accommodation_type', true);
		$wp_feedr['holiday_type']		= get_post_meta($post->ID, 'wp_feedr_selected_type', true);
		$wp_feedr['api_key']			= get_option( 'wp_feedr_apikey' );
		$wp_feedr['show'] 				= get_post_meta($post->ID, 'wp_feedr_show_accommodations', true);
		$wp_feedr['country'] 			= get_post_meta($post->ID, 'wp_feedr_selected_country', true);
		$wp_feedr['region']				= get_post_meta($post->ID, 'wp_feedr_selected_region', true);
		$wp_feedr['city'] 				= get_post_meta($post->ID, 'wp_feedr_selected_city', true);
		$wp_feedr['template']			= get_option('wp_feedr_accommodation_template');

		if (!$wp_feedr['show']) return $content;
		$new_content = "";

		$output = '
			<div class="wp_feedr_accommodations" id="wp_feedr_accommodations_container" style="clear: both;">

			</div>
			<div class="wp_feedr_accommodations_pagination" id="wp_feedr_accommodations_pagination" style="clear: both;">
				<ul>

				</ul>
			</div>
			<script>
				var wp_feedr_api_params = '. json_encode($wp_feedr) .';
			</script>
		';

		if ($wp_feedr['render_type'] == "json")
		{
			$output .= '
				<script>
					var wp_feedr_json_data = "";
				</script>
			';
		}

		if ($position == 'top')
			$new_content .= $output;

		$new_content .= $content;

		if ($position == 'bottom')
			$new_content .= $output;

		return $new_content;
	}

}

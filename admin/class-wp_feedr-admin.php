<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.feedr.nl
 * @since      1.0.0
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_feedr
 * @subpackage Wp_feedr/admin
 * @author     Feedr <info@feedr.nl>
 */

class Wp_feedr_Admin {

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
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */

	private $default_template;
	private $option_name = 'wp_feedr';

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		$this->default_template = file_get_contents(plugin_dir_path( __FILE__ ) .'template/default.php');


		if (get_option('wp_feedr_apikey') != '' && get_option('wp_feedr_countries') == '' )
		{
			$plugin_cron = new Wp_feedr_Cron( $this->plugin_name, $this->version );
			$plugin_cron->import_locations();
		}
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp_feedr-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp_feedr-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'WP Feedr Settings', 'wp-feedr' ),
			__( 'WP Feedr', 'wp-feedr' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}

	public function display_options_page() {
		include_once 'partials/wp_feedr-admin-display.php';
	}

	public function register_setting(){
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', 'wp-feedr' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->option_name . '_apikey',
			__( 'Api key', 'wp-feedr' ),
			array( $this, $this->option_name . '_apikey_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_apikey' )
		);

		add_settings_field(
		    $this->option_name . '_cron_period',
		    __( 'Cron period', 'wp-feedr' ),
		    array( $this, $this->option_name . '_cron_period_cb' ),
		    $this->plugin_name,
		    $this->option_name . '_general',
		    array( 'label_for' => $this->option_name . '_cron_period')
		);

		add_settings_field(
		    $this->option_name . '_accommodations_box_position',
		    __( 'Accommodations box position', 'wp-feedr' ),
		    array( $this, $this->option_name . '_accommodations_box_position_cb' ),
		    $this->plugin_name,
		    $this->option_name . '_general',
		    array( 'label_for' => $this->option_name . '_accommodations_box_position')
		);

		add_settings_field(
		    $this->option_name . '_active_post_types',
		    __( 'Which post types should contain holidays?', 'wp-feedr' ),
		    array( $this, $this->option_name . '_active_post_types_cb' ),
		    $this->plugin_name,
		    $this->option_name . '_general',
		    array( 'label_for' => $this->option_name . '_active_post_types')
		);

		add_settings_field(
			$this->option_name . '_json_callback_function',
			__( 'Json callback function', 'wp-feedr' ),
			array( $this, $this->option_name . '_json_callback_function_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_json_callback_function' )
		);

		add_settings_field(
			$this->option_name . '_accommodation_template',
			__( 'Accommodation template', 'wp-feedr' ),
			array( $this, $this->option_name . '_accommodation_template_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_accommodation_template' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_apikey');
		register_setting( $this->plugin_name, $this->option_name . '_cron_period');
		register_setting( $this->plugin_name, $this->option_name . '_accommodations_box_position');
		register_setting( $this->plugin_name, $this->option_name . '_active_post_types');
		register_setting( $this->plugin_name, $this->option_name . '_json_callback_function');
		register_setting( $this->plugin_name, $this->option_name . '_accommodation_template');

	}

	public function wp_feedr_active_post_types_cb(){
		$data = get_option( $this->option_name . '_active_post_types' );

		$post_types = get_post_types([
			'public' => true
		]);

		$html = '<select id="' . $this->option_name . '_active_post_types[]" name="' . $this->option_name . '_active_post_types[]" multiple="multiple" class="regular-text">';

			foreach($post_types as $post_type)
			{
				$selected = "";

				if (is_array($data))
				{
					if (in_array($post_type, $data))
					{
						$selected = ' selected="selected"';
					}
				}

				$html .= '<option value="'. $post_type .'"' . $selected . '>'. $post_type .'</option>';
			}
    	$html .= '</select> ';

    	$html .= '<p>&nbsp;</p><input type="checkbox" name="show_advanced" /> Geavanceerde opties tonen?';

    	echo $html;
		
	}

	public function wp_feedr_general_cb() {
		//echo '<p>' . __( 'Please change the settings accordingly.', 'wp_feedr' ) . '</p>';
	}

	public function wp_feedr_apikey_cb(){

		$key = get_option( $this->option_name . '_apikey' );

		echo '<input type="text" name="' . $this->option_name . '_apikey' . '" id="' . $this->option_name . '_apikey' . '" value="' . $key . '" class="regular-text"> '. __( 'Fill in the generated api key for this website in the Feedr software', 'wp-feedr' );
	}

	public function wp_feedr_json_callback_function_cb(){

		$key = get_option( $this->option_name . '_json_callback_function' );

		echo '<input type="text" name="' . $this->option_name . '_json_callback_function' . '" id="' . $this->option_name . '_json_callback_function' . '" value="' . $key . '" class="regular-text" data-advanced="1">';
	}

	public function wp_feedr_cron_period_cb(){

		$scheduler = new WP_Feedr_Schedulehelper;
		$current_schedule = $scheduler->get_schedule('wp_feedr_cron_import');

		$options = get_option( $this->option_name . '_cron_period' );

		$html = '<select id="' . $this->option_name . '_cron_period" name="' . $this->option_name . '_cron_period">';
	        $html .= '<option value="0">- Select an option -</option>';
	        $html .= '<option value="daily"' . selected( $options, 'daily', false) . '>Daily</option>';
	        $html .= '<option value="twicedaily"' . selected( $options, 'twicedaily', false) . '>Twice daily</option>';
	        $html .= '<option value="hourly"' . selected( $options, 'hourly', false) . '>Hourly</option>';
    	$html .= '</select> ';

    	$html .= '<strong>Current period: <span style="color: Red;">'. $current_schedule .'</span></strong> ';

    	$html .=  __( 'How often the locations database is filled (this counts for the number of api calls you make)', 'wp-feedr' );

    	echo $html;
	}

	public function wp_feedr_accommodations_box_position_cb(){

		$options = get_option( $this->option_name . '_accommodations_box_position' );

		$html = '<select id="' . $this->option_name . '_accommodations_box_position" name="' . $this->option_name . '_accommodations_box_position">';
	        $html .= '<option value="0">- Select an option -</option>';
	        $html .= '<option value="top"' . selected( $options, 'top', false) . '>Above post content</option>';
	        $html .= '<option value="bottom"' . selected( $options, 'bottom', false) . '>Below post content</option>';
    	$html .= '</select> ';

    	$html .=  __( 'Show accommodations above or below the post content', 'wp-feedr' );

    	echo $html;
	}

	public function create_new_schedule($old_value){
		
		$saved_value = get_option( $this->option_name . '_cron_period' );
		$scheduler = new WP_Feedr_Schedulehelper;

		$scheduler->remove_scheduled_event('wp_feedr_cron_import');
		$scheduler->schedule_event('wp_feedr_cron_import', $saved_value);

		return true;
	}


	public function wp_feedr_accommodation_template_cb(){

		$key = get_option( $this->option_name . '_accommodation_template' );

		if ($key == '')
		{
			$key = $this->default_template;
		}

		//$key = sanitize_text_field($key);

		echo '<textarea name="' . $this->option_name . '_accommodation_template' . '" id="' . $this->option_name . '_accommodation_template' . '" data-advanced="1" class="large-text" style="height: 275px;">'. $key .'</textarea>';
	}

}

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

class Wp_feedr_Admin_Metaboxes {

	private $active_post_types;

	public function __construct(){
		$this->active_post_types = get_option( 'wp_feedr_active_post_types' );
	}

	public function render_meta_boxes(){

		foreach($this->active_post_types as $post_type)
		{
			add_meta_box(
	            'wp_feedr_settings_selector',           // Unique ID
	            'WP Feedr Settings',  // Box title
	            array($this, 'render_html'),  // Content callback, must be of type callable
	            $post_type,                   // Post type
	            'advanced',
	            'low'
	        );
		}
	}

	public function render_html(){
		global $post;


		$countries = get_option( 'wp_feedr_countries' );
		$c_list = [];

		foreach($countries as $key => $country)
		{
			$c_list[$key] = $country['name'];
		}
		asort($c_list);

		echo '<input type="hidden" name="wp_feedr_metabox_nonce" id="wp_feedr_metabox_nonce" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

		// fields
		$wp_feedr_show_accommodations 		= get_post_meta($post->ID, 'wp_feedr_show_accommodations', true);
		$wp_feedr_type 						= get_post_meta($post->ID, 'wp_feedr_selected_type', true);
		$wp_feedr_accommodation_type 		= get_post_meta($post->ID, 'wp_feedr_selected_accommodation_type', true);
		$wp_feedr_selected_country 			= get_post_meta($post->ID, 'wp_feedr_selected_country', true);
		$wp_feedr_selected_region			= get_post_meta($post->ID, 'wp_feedr_selected_region', true);
		$wp_feedr_selected_city 			= get_post_meta($post->ID, 'wp_feedr_selected_city', true);
		$wp_feedr_render_type 				= get_post_meta($post->ID, 'wp_feedr_render_type', true);

		?>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							Toon reizen?
						</th>
						<th>
							<input type="checkbox" name="wp_feedr_show_accommodations" id="wp_feedr_show_accommodations" <?php if ($wp_feedr_show_accommodations) echo 'checked="checked"'; ?> />
						</th>
					</tr>
					<tr>
						<th scope="row">
							Render type
						</th>
						<th>
							<select name="wp_feedr_render_type" id="wp_feedr_render_type" class="postbox">
						        <option value="">- Maak een keuze -</option>
						        <option value="template" <?php echo selected( 'template', $wp_feedr_render_type, false); ?>>template (zie settings)</option>
						        <option value="json" <?php echo selected( 'json', $wp_feedr_render_type, false); ?>>json</option>
						    </select>
						</th>
					</tr>
					<tr>
						<th scope="row">
							Selecteer een accommodatie type
						</th>
						<th>
							<select name="wp_feedr_selected_accommodation_type" id="wp_feedr_selected_accommodation_type" class="postbox">
						        <option value="">- Alles -</option>
						        <option value="1" <?php echo selected( '1', $wp_feedr_accommodation_type, false); ?>>Hotels</option>
						        <option value="2" <?php echo selected( '2', $wp_feedr_accommodation_type, false); ?>>Appartementen</option>
						        <option value="3" <?php echo selected( '3', $wp_feedr_accommodation_type, false); ?>>Aparthotels</option>
						        <option value="3" <?php echo selected( '3', $wp_feedr_accommodation_type, false); ?>>Bed & Breakfast</option>
						    </select>
						</th>
					</tr>
					<tr>
						<th scope="row">
							Selecteer een vakantie type
						</th>
						<th>
							<select name="wp_feedr_selected_type" id="wp_feedr_selected_type" class="postbox">
						        <option value="">- Maak een keuze -</option>
						        <option value="1" <?php echo selected( '1', $wp_feedr_type, false); ?>>Winter</option>
						        <option value="2" <?php echo selected( '2', $wp_feedr_type, false); ?>>Zomer</option>
						        <option value="3" <?php echo selected( '3', $wp_feedr_type, false); ?>>Stedentrip</option>
						    </select>
						</th>
					</tr>
					<tr>
						<th scope="row">
							Selecteer een land
						</th>
						<th>
							<select name="wp_feedr_selected_country" id="wp_feedr_selected_country" class="postbox">
						        <option value="">- Maak een keuze -</option>
						        <?php
						        	foreach($c_list as $key => $val)
						        	{
						        		echo '<option value="'. $key .'" '. selected( $key, $wp_feedr_selected_country, false) .'>'. $val .'</option>';
						        	}
						        ?>
						    </select>
						</th>
					</tr>
					<tr>
						<th scope="row">
							Selecteer een regio
						</th>
						<th>
							<select name="wp_feedr_selected_region" id="wp_feedr_selected_region" class="postbox" disabled="disabled">
						        <option value="">- Selecteer eerst een land -</option>
						    </select>
						</th>
					</tr>
					<tr>
						<th scope="row">
							Selecteer een plaats
						</th>
						<th>
							<select name="wp_feedr_selected_city" id="wp_feedr_selected_city" class="postbox" disabled="disabled">
						        <option value="">- Selecteer eerst een regio -</option>
						    </select>
						</th>
					</tr>
				</tbody>
			</table>

			<script>

				(function( $ ){

					function fill_select(select, data, selected_value){
			 			empty_select(select);
			 			select.append($("<option />").val(0).text('- Maak een keuze -'));
			 			$.each(data, function() {
			 				var new_option = $("<option />").val(this.id).text(this.name);

			 				if (this.id == selected_value){
			 					new_option.prop('selected','selected');
			 				}


						    select.append(new_option);
						});
						select.prop('disabled', false);
			 		}

			 		function empty_select(select){
			 			select.empty();
			 			select.prop('disabled', true);
			 		}


					$(document).ready(function(){
						<?php

							if ($wp_feedr_selected_country)
							{
								?>

									var data = {
					 					'action': 'wp_feedr_get_regions_by_country',
					 					'country_id': <?php echo $wp_feedr_selected_country; ?>
					 				};

					 				$.post(ajaxurl, data, function(data) {
					 					fill_select($("select#wp_feedr_selected_region"), data, <?php echo $wp_feedr_selected_region; ?>);
									});

								<?php
							}

							if ($wp_feedr_selected_region)
							{
								?>

									var data = {
					 					'action': 'wp_feedr_get_cities_by_region',
					 					'region_id': <?php echo $wp_feedr_selected_region; ?>
					 				};

					 				$.post(ajaxurl, data, function(data) {
					 					fill_select($("select#wp_feedr_selected_city"), data, <?php echo $wp_feedr_selected_city; ?>);
									});

								<?php
							}

						?>
					});

				})(jQuery);

			</script>
		<?php
	}

	public function save($post_id){
		$post = get_post($post_id);
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['wp_feedr_metabox_nonce'], plugin_basename(__FILE__) )) {
			return $post->ID;
		}
		// Is the user allowed to edit the post or page?
		if ( !current_user_can( 'edit_post', $post->ID ))
			return $post->ID;

		$wp_feedr_meta = [];
		$wp_feedr_meta['wp_feedr_show_accommodations'] 			= $_POST['wp_feedr_show_accommodations'];
		$wp_feedr_meta['wp_feedr_selected_type'] 				= $_POST['wp_feedr_selected_type'];
		$wp_feedr_meta['wp_feedr_selected_accommodation_type'] 	= $_POST['wp_feedr_selected_accommodation_type'];
		$wp_feedr_meta['wp_feedr_selected_country'] 			= $_POST['wp_feedr_selected_country'];
		$wp_feedr_meta['wp_feedr_selected_region'] 				= $_POST['wp_feedr_selected_region'];
		$wp_feedr_meta['wp_feedr_selected_city'] 				= $_POST['wp_feedr_selected_city'];
		$wp_feedr_meta['wp_feedr_render_type']					= $_POST['wp_feedr_render_type'];

		if (!$wp_feedr_meta['wp_feedr_show_accommodations'])
		{
			$wp_feedr_meta['wp_feedr_render_type'] 					= false;
			$wp_feedr_meta['wp_feedr_selected_type'] 				= false;
			$wp_feedr_meta['wp_feedr_selected_country'] 			= false;
			$wp_feedr_meta['wp_feedr_selected_region'] 				= false;
			$wp_feedr_meta['wp_feedr_selected_city'] 				= false;
			$wp_feedr_meta['wp_feedr_selected_accommodation_type']	= false;
		}

		foreach($wp_feedr_meta as $key => $value)
		{
			if( $post->post_type == 'revision' ) return;

			if(get_post_meta($post->ID, $key, FALSE))
			{
				update_post_meta($post->ID, $key, $value);
			}
			else
			{
				add_post_meta($post->ID, $key, $value);
			}
			if(!$value) delete_post_meta($post->ID, $key);
		}
	}

}
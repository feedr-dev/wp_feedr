(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $(document).ready(function(){
	 	wp_feedr.init();
	 });


	 var wp_feedr = {

	 	init: function(){
	 		this.handle_location_metaboxes();
	 	},

	 	handle_location_metaboxes: function(){

	 		var country_select 		= $("select#wp_feedr_selected_country");
	 		var region_select		= $("select#wp_feedr_selected_region");
	 		var city_select			= $("select#wp_feedr_selected_city");

	 		country_select.each(function(){

	 			$(this).change(function(){
	 				empty_select(region_select);
	 				empty_select(city_select);

	 				var data = {
	 					'action': 'wp_feedr_get_regions_by_country',
	 					'country_id': $(this).val()
	 				};

	 				$.post(ajaxurl, data, function(data) {
	 					fill_select(region_select, data);
					});

	 			});
	 		});

	 		region_select.each(function(){

	 			empty_select(city_select);

	 			$(this).change(function(){
	 				console.log($(this).val());

	 				var data = {
	 					'action': 'wp_feedr_get_cities_by_region',
	 					'region_id': $(this).val()
	 				};

	 				$.post(ajaxurl, data, function(data) {
	 					fill_select(city_select, data);
					});

	 			});
	 		});

	 		function fill_select(select, data){
	 			empty_select(select);
	 			select.append($("<option />").val(0).text('- Maak een keuze -'));
	 			$.each(data, function() {
				    select.append($("<option />").val(this.id).text(this.name));
				});
				select.prop('disabled', false);
	 		}

	 		function empty_select(select){
	 			select.empty();
	 			select.prop('disabled', true);
	 		}
	 	},

	 }


})( jQuery );

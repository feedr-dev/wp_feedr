Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	 	wp_feedr_api_request.init();
	 });

	 var wp_feedr_api_request = {

	 	url: '',
	 	data: null,
	 	paged_data: [],
	 	pagination: {
			'window_size': 6
		},

	 	init: function(){
	 		if (typeof wp_feedr_api_params === "undefined") return false;
	 		if (wp_feedr_api_params.show != "on") return false;

	 		this.build_api_url();
	 		this.get_data();
	 	},

	 	build_api_url: function(){

	 		//this.url = "//manage.feedr.nl/api/v1/accommodations?callback=?&x-api-key="+ wp_feedr_api_params.api_key;

	 		if (wp_feedr_api_params.city != ""){
	 			this.url = '//manage.feedr.nl/api/v2/cities/'+wp_feedr_api_params.city +'/accommodations?callback=?&x-api-key='+ wp_feedr_api_params.api_key;
	 		}
	 		else
	 		{
	 			// city not set, go check region
	 			if (wp_feedr_api_params.region != "")
	 			{
	 				this.url = '//manage.feedr.nl/api/v2/regions/'+wp_feedr_api_params.region +'/accommodations?callback=?&x-api-key='+ wp_feedr_api_params.api_key;
	 			}
	 			else
	 			{
	 				if (wp_feedr_api_params.country != "")
		 			{
		 				this.url = '//manage.feedr.nl/api/v2/countries/'+wp_feedr_api_params.country +'/accommodations?callback=?&x-api-key='+ wp_feedr_api_params.api_key;
		 			}
	 			}
	 		}




	 		if (wp_feedr_api_params.holiday_type != "") this.url += "&holiday_type="+ wp_feedr_api_params.holiday_type;
	 		
	 		// if (wp_feedr_api_params.country != "") this.url += "&country="+ wp_feedr_api_params.country;
	 		// if (wp_feedr_api_params.region != "") this.url += "&region="+ wp_feedr_api_params.region;
	 		// if (wp_feedr_api_params.city != "") this.url += "&city="+ wp_feedr_api_params.city;
	 		// if (wp_feedr_api_params.accommodation_type != "") this.url += "&accommodation_type="+ wp_feedr_api_params.accommodation_type;
	 	},

	 	get_data: function(){
	 		
	 		var self = this;
	 		var container = $("#wp_feedr_accommodations_container").addClass('narrow blog-posts-alt');
			$.ajax({
				url: this.url,
				type: 'GET',
				dataType: 'json',
				success: function(data) { 

					if (wp_feedr_api_params.render_type == "json"){
						//wp_feedr_json_data = data;

						window[wp_feedr_api_params.json_callback](data);

						// var callback_function = window[wp_feedr_api_params.json_callback];

						//  if (typeof callback_function === "function") {
					 //        callback_function(data);
					 //    }
					}
					else
					{
						// RENDER FROM TEMPLATE IN SETTINGS
						self.data = data;
						self.cache_pages();
						// $.each(data.data, function(k,v){

						// 	var html = self.render_accommodation(v);
						// 	container.append(html);
						// });
						self.init_pagination(data.last_page);
					}
				},
				error: function() { 
						console.log(" api call failed, url: "+ this.url) 
				}
			});
	 	},

	 	render_accommodation: function(data){

	 		var prices = '';
	 		var found_operators = [];
	 		$.each(data.listprices, function(k,v){
	 			if (found_operators.indexOf(v.operator.id) == -1)
	 			{
	 				prices += '<tr style="cursor: pointer;" onclick="window.open(\''+ v.url +'\')">';
		 			prices += '<td><img src="//manage.feedr.nl/'+v.operator.image+'" width="50" style="width: 50px;" alt="'+v.operator.name+'" /></td>';
		 			prices += '<td>'+v.departure_date+'</td>';
		 			prices += '<td>&euro;'+v.price+'</td>';
		 			prices += '</tr>';
	 			}

	 			found_operators.push(v.operator.id);
	 		});

	 		var t = wp_feedr_api_params.template;
	 		t = t.replace(/{{link}}/g, data.listprices[0].url);
	 		t = t.replace(/{{title}}/g, data.name);
	 		t = t.replace(/{{breadcrumbs}}/g, data.country.name +' &raquo; '+ data.region.name +' &raquo; '+ data.city.name);
	 		t = t.replace(/{{content}}/g, this.text_trim(data.description, 175));
	 		t = t.replace(/{{main_price}}/g, "&euro;"+parseFloat(data.listprices[0].price).format(2, 3, '.', ','));
	 		t = t.replace(/{{prices}}/g, prices);
	 		t = t.replace(/{{image}}/g, '//manage.feedr.nl/uploads/accommodations/m/'+data.images[0].url);

	 		return $(t);

	 	},

	 	cache_pages: function(){
			var self = this;
			var total_pages = this.data.last_page;
			var last_cached_page = this.paged_data[this.paged_data.length-1];
			if (total_pages == 1)
			{
				this.render_page(1);
		    	//self.render_pagination(total_pages);
		    	self.init_pagination(total_pages);
			}
			else
			{
				if (this.paged_data.length < total_pages)
		    	{
		    		cache_page(this.data.next_page_url);
		    		this.render_page(1);
		    		//self.render_pagination(total_pages);
		    		self.init_pagination(total_pages);
		    	}
			}

	    	

	    	function cache_page(url){
	    		if (typeof url == "object") return;
	    		jQuery.ajax({
				url: url+"&callback=?",
				type: 'GET',
				dataType: 'json',
				success: function(data) { 
					self.paged_data.push(data);
					if (self.paged_data.length < total_pages)
					{
						cache_page(self.paged_data[self.paged_data.length-1].next_page_url);
					}
					else
					{
						//self.render_pagination();
					}
				},
				error: function() { 
					console.log(" api call failed, url: "+ this.url) 
				}
			});

	    	}
		},

		render_page: function(nr){
			var self = this;
			var container = $("#wp_feedr_accommodations_container");
			container.children().remove();
			// handle if not loaded yet
			if (typeof self.paged_data[nr-1] == 'undefined')
			{
				//container.find(".loader-icon").show();
				window.setTimeout(function(){
					self.render_page(nr);
				}, 250);

				return;
			}

			$.each(self.paged_data[nr-1].data, function(k,v){
				var html = self.render_accommodation(v);
				container.append(html);
			});

		},

	 	text_trim: function(text, length) {
		    if (text == null) {
		        return "";
		    }
		    if (text.length <= length) {
		        return text;
		    }
		    text = text.substring(0, length);
		    var last = text.lastIndexOf(" ");
		    text = text.substring(0, last);
		    return text + "&hellip;";
		},

		init_pagination: function(nr){
	    	var self = this;

	    	// set initial data
	    	self.pagination.total_pages = nr;
	    	self.pagination.current_page = 1;

	    	var start_page = get_start_page(self.pagination.current_page);
	    	render_pagination(start_page);

	    	function get_start_page(current){
	    		// set paging start nr
	    		self.pagination.current_page = current;

	    		if (self.pagination.current_page < (self.pagination.window_size/2))
	    		{
	    			// current page is smaller then window
	    			return 0;
	    		}
	    		else if (self.pagination.current_page > (self.pagination.total_pages - (self.pagination.window_size/2)) && self.pagination.current_page < self.pagination.total_pages)
	    		{
	    			// current page is larger then total pages - window but smaller then total pages
	    			return self.pagination.total_pages - self.pagination.window_size;
	    		}
	    		else if (self.pagination.current_page >= self.pagination.total_pages)
	    		{
	    			// current page is last page or larger
	    			return self.pagination.total_pages - self.pagination.window_size;
	    		}
	    		else
	    		{
	    			return (self.pagination.current_page - (self.pagination.window_size/2))
	    		}
	    	}

	    	function render_pagination(start_page){
	    		var container = jQuery("#wp_feedr_accommodations_pagination");
	    		container.find("ul").html("");

	    		// handle last page link
		    	if (self.pagination.current_page > (self.pagination.window_size/2))
		    	{
		    		container.find("ul").append('<li style="display: inline; padding-right: 10px;"><a href="#wp_feedr_accommodations_container" data-scrollto="#wp_feedr_accommodations_container" data-page="1" class="btn btn-default btn-xs">&laquo; eerste</a></li>');
		    	}

	    		var counter = 0;
	    		for (var i = start_page; i < (start_page + self.pagination.window_size); i++)
		    	{
		    		var cssclass = "";
		    		if ((i+1) == self.pagination.current_page)
		    		{
		    			cssclass = "btn-primary";
		    		}

		    		if ((i+1) <= (self.pagination.total_pages-1) && (i+1) > 0)
		    		{
		    			container.find("ul").append('<li style="display: inline; padding-right: 10px;"><a href="#wp_feedr_accommodations_container" data-scrollto="#wp_feedr_accommodations_container" data-page="'+(i+1)+'" class="btn btn-default btn-xs '+ cssclass +'">'+(i+1)+'</a></li>');
		    		}
		    		counter++;
		    	}

		    	// handle last page link
		    	if ((start_page + self.pagination.window_size) < self.pagination.total_pages)
		    	{
		    		container.find("ul").append('<li style="display: inline; padding-right: 10px;"><a href="#wp_feedr_accommodations_container" data-scrollto="#wp_feedr_accommodations_container" data-page="'+(self.pagination.total_pages-1)+'" class="btn btn-default btn-xs">laatste &raquo;</a></li>');
		    	}

		    	container.find("ul").find("a").click(function(e){
		    		e.preventDefault();
		    		var start = jQuery(this).data("page");
		    		self.render_page(start);
		    		render_pagination(get_start_page(start));

		    		// animate to top
		    		var el = $($(this).data("scrollto"));
		    		$('html, body').animate({
				        scrollTop: (el.offset().top - 100)
				    }, 250);

		    	});
	    	}
	    }
	 }

})( jQuery );

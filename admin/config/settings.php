<?php

	return [
		[
			'page_title' 	=> 'WP Feedr Settings',
			'menu_title' 	=> 'WP Feedr',
			'capability' 	=> 'manage_options',
			'menu_slug'		=> 'wp_feedr',
			'sections'		=> [
				[
					'id'		=> 'general',
					'title'		=> 'General',
					'fields'	=> [
						[
							'id'		=> 'apikey',
							'title'		=> 'Api key'
						]
					]
				]
			]
		]
	];

?>
<?php
/**
Plugin Name: WP Catalyzer Widgets
Plugin URI: http://www.wpcatalyzer.com/
Description: A shortcode plugin that adds functionality like buttons, tabs, flexible columns and more to your theme.
Version: 1.0.1
Author: WP Catalyzer
Author URI: http://www.wpcatalyzer.com/
*/

/**
 * Copyright (c) 2015 WP Catalyzer. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */


 
if(!class_exists('WP_Catalyzer_Widgets')) {

	class WP_Catalyzer_Widgets {

		function __construct() {
			
			/* Set the paths needed by the plugin. */
			add_filter('plugin_action_links_' . basename(dirname(__FILE__)) . '/' . basename(__FILE__), array(__CLASS__, 'plugin_action_links'));
			require_once( plugin_dir_path( __FILE__ ) .'/inc/widgets.php' );
			require_once( plugin_dir_path( __FILE__ ) .'settings.php' );
		}
		
		  // add settings link to plugins page
		function plugin_action_links($links) {
			$settings_link = '<a href="' . admin_url('options-general.php?page=wp-catalyzer-widgets%2Fsettings.php') . '" title="' . __('Settings for WP Catalyzer Widgets', 'wp_catalyzer') . '">' . __('Settings', 'wp_catalyzer') . '</a>';
			array_unshift($links, $settings_link);

			return $links;
		} // plugin_action_links
		
	}
}
$wp_catalyzer_widgets = new WP_Catalyzer_Widgets();
?>
<?php
/**
Plugin Name: Geo
Description: This plugin uses API Google Maps. This plugin creates a custom post type called "Experts" with a custom field to enter the expert's address. After that, this plugin creates the [expertsmap] shortcode to display a table of experts and a google map with expert markers corresponding to the addresses. Parameters: google-maps-api-key, height-map, width-map, width-table, zoom, quantity. Ex.: [expertsmap width-table=400px]
Version:     1.0
Author:      Denis Tikhonov
*/
define( 'GEO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once (GEO__PLUGIN_DIR. '/includes/class-geo_activator.php');
require_once (GEO__PLUGIN_DIR. '/includes/class-features.php');
require_once (GEO__PLUGIN_DIR. '/includes/class-main_class.php');
function call_mainClass() {
	new mainClass();
}
if ( is_admin() ) {
	add_action( 'load-post.php', 'call_mainClass' );
	add_action( 'load-post-new.php', 'call_mainClass' );
}
require_once (GEO__PLUGIN_DIR. '/includes/class-test.php');
require_once (GEO__PLUGIN_DIR. '/shortcode/shortcode.php');
?>
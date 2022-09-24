<?php
/**
Plugin Name: Geo
*/
define( 'GEO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once (GEO__PLUGIN_DIR. '/includes/class-geo_activator.php');
require_once (GEO__PLUGIN_DIR. '/includes/class-main_class.php');
require_once (GEO__PLUGIN_DIR. '/includes/class-test.php');
function call_mainClass() {
	new mainClass();
	
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'call_mainClass' );
	add_action( 'load-post-new.php', 'call_mainClass' );
}
require_once (GEO__PLUGIN_DIR. '/shortcode/shortcode.php');
?>
<?php
   // Shortcode for visualizing coordinates of Experts on Google maps
	add_shortcode( 'expertsmap', 'experts_map_shortcode' );
	function experts_map_shortcode( $atts )
	{	
		ob_start();
		$params = shortcode_atts
			( 
				array
				(
					'google-maps-api-key' => 'AIzaSyAxhrKP2nTR9aE-FRMMQ-GQ-03FthtEcaI',
					'width-map' => '640px', 
					'height-map' => '400px',
					'width-table' => '640px',
					'zoom' => 13, // zoom param (recommends 0-18)
					'quantity' => -1 // number of experts
				), 
			$atts 
			);	
		$args = array
		(
				'post_type' => 'rg_experts',
				'posts_per_page' => $params['quantity']
		);
		
		$experts = new WP_Query( $args );
		
		// include test posts if we have nothing
		if ($experts->posts == null)
		{
			new Test();
			$experts = new WP_Query( $args );
		}
		
		// include views
		require_once (GEO__PLUGIN_DIR. '/shortcode/shortcode-view.php');
		
		// include snippet-js for custom map
		$strurl2 = '/js/shortcode-js.js';
		wp_enqueue_script("shortcode-js", plugins_url($strurl2,__FILE__), null, null, true);
		
		// incluse google maps api key
		if ($params["google-maps-api-key"])
		{
			$strurl = "https://maps.googleapis.com/maps/api/js?key=".$params['google-maps-api-key']."&callback=initMap";			
		wp_enqueue_script('google-maps',$strurl,array('shortcode-js'),null,false);
		
		}
		else
		{
			return ('Your Google API key is empty');
		}
		
	wp_reset_postdata();
	$output = ob_get_clean();	
	return $output;
	}
?>

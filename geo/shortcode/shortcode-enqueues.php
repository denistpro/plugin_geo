<?php

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

?>
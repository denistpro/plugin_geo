<?php
class features{
		public function __construct() 
		{
			add_filter( 'script_loader_tag', 'geo_defer_attribute', 10, 2 );
		}
		// put defer attr to google-key link
		public function geo_defer_attribute( $tag, $handle ) 
		{
			if ( ('google-maps' !== $handle) || ('shortcode-js' !== $handle) ) 
			{
				return $tag;
			}	
			return str_replace( ' src', ' defer="defer" src', $tag );
		}
		
}
<?php
/**
Plugin Name: Geo
*/
define( 'GEO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once (GEO__PLUGIN_DIR. '/includes/class-geo_activator.php');


function call_someClass() {
	new someClass();
	
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'call_someClass' );
	add_action( 'load-post-new.php', 'call_someClass' );
}

class someClass {

	/**
	 * We install hooks at the time of class initialization.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		
	}

	/**
	 * Adding an extra block.
	 */
	public function add_meta_box( $post_type ){

			// Set the types of posts to which the block will be added
		$post_types = array('rg_experts');

		if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'some_meta_box_name',
				__( 'Address', 'geo_textdomain' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'advanced',
				'high',
			);
		}
	}

	/**
	 * We save data when saving a post.
	 *
	 * @param int $post_id The ID of the post that is being saved.
	 */
	public function save( $post_id ) 
	{

		/*
		 * We need to do a check to make sure the request came from our page,
		 * because save_post can be called anywhere else.
		 */

		// Check if nonce is set.
		if ( ! isset( $_POST['geo_inner_custom_box_nonce'] ) )
			return $post_id;
   
 
		$nonce = $_POST['geo_inner_custom_box_nonce'];

		// Check if the nonce is correct.
		if ( ! wp_verify_nonce( $nonce, 'geo_inner_custom_box' ) )
			return $post_id;
		
		// If it's autosave we don't do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// Checking user rights.
		if ( 'rg_experts' == $_POST['post_type'] ) 
		{

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		}    else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		// OK, everything is clean, you can save the data.

		// Clearing the input field.
		$mydata = sanitize_text_field( $_POST['geo_new_field'] );

		// We update the data.
		

		
		update_post_meta( $post_id, '_my_meta_value_key', $mydata );
		
		
	}

	/**
	 * Additional block code.
	 *
	 * @param WP_Post $post Post object.
	 */
	public function render_meta_box_content( $post ) 
	{

		// We add a nonce field that we will check when saving.
		wp_nonce_field( 'geo_inner_custom_box', 'geo_inner_custom_box_nonce' );

		// We get the existing data from the database.
		$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

		// We display the form fields using the received data.
		echo '<label for="geo_new_field">';
		echo __( 'Expert address', 'geo_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="geo_new_field" name="geo_new_field"';
		echo ' value="' . esc_attr( $value ) . '" size="25" />';
		
	}
	public function add_posts_for_test($name, $address) 
	{
		$post_data = array
		(
			  'post_name' => $name,
	          'post_title'    => $name,
	          'post_type'  => 'rg_experts',
			  'meta_input'     => [ '_my_meta_value_key'=>$address], 	
	          'post_status'   => 'publish'
        );
		wp_insert_post( wp_slash($post_data) );
		
	}

} 
    // Shortcode for visualizing coordinates of Experts on Google maps
	add_shortcode( 'expertsmap', 'experts_map_shortcode' );

		function experts_map_shortcode( $atts ){
		unset($experts[0]);
		ob_start();	
		$args = array
		(
				'post_type' => 'rg_experts',
				'posts_per_page' => -1
		);
	
		$experts = new WP_Query( $args );
		if ($experts->posts == null)
		{
			someClass::add_posts_for_test('Den-test','g.k. Lyulin 5 512В, 1359 zh.k. Lyulin 5, Sofia');
			someClass::add_posts_for_test('Mike-test','D-r, bul. "Doctor Peter Dertliev" 104, 1336 zh.k. Lyulin 5, Sofia');
			someClass::add_posts_for_test('Bob-test','ul. Nikola Belovezhdov 1, 1359 zh.k. Lyulin 4, Sofia');
			someClass::add_posts_for_test('John-test','bul. Dobrinova skala 546В, 1359 zh.k. Lyulin 5, Sofia');
			$experts = new WP_Query( $args );
		}
		?>
		 <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
               
            </tr>
        </thead>
        <tbody>
		<?php
		
		foreach ($experts->posts as $person):
		
			if ($person->post_title){
			$addr[] = str_replace('"','',get_post_meta( $person->ID, '_my_meta_value_key', true ));			
			?> 			
			<tr>
				<td><?php echo $person->post_title; ?></td>
				<td><?php echo get_post_meta( $person->ID, '_my_meta_value_key', true ); ?></td>
				
			</tr>
			<?php } ?>
				<?php
			
		endforeach;  ?>
		 </tbody>
    </table>
	<div id="map" style="width: 640px; height: 400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxhrKP2nTR9aE-FRMMQ-GQ-03FthtEcaI&callback=initMap" async defer></script>
	<script >
			 var map;
			var geo;
			
			function initMap() {
				var opt = {
				zoom:13,
				mapTypeId : google.maps.MapTypeId.ROADMAP
				}
				map=new google.maps.Map(document.getElementById("map"),opt);
				geocoder = new google.maps.Geocoder();
			}
				function codeAddress(addressList) {
					try {
						let icount = addressList.length;
						console.log('icount ',icount)
						for (var i = 0; i < icount; i++) {
						getGeoCoder(addressList[i]);
						}
					} catch (error) {
					alert(error);
					}
				}

				
				 function getGeoCoder(address) {
				 geocoder.geocode({
							'address' : address
				 }, function(results, status) {
					if (status == "OK") {
					map.setCenter(results[0].geometry.location);
					var p = results[0].geometry.location;
					var lat=p.lat();
                    var lng=p.lng();
                    createMarker(address,lat,lng);
				} else {
                geterrorMgs(address); // address not found handler
				}
                   });
                  }
				 function createMarker(add,lat,lng) {
				     var contentString = add;
                     var marker = new google.maps.Marker({
                         position: new google.maps.LatLng(lat,lng),
                         map: map,
                     });

				}
	var addressList = <?php echo '["' . implode('", "', $addr) . '"]' ?>;
	
	setTimeout(() => {
    codeAddress(addressList);
    }, 3000)
    </script>
		
	<?php
			
		wp_reset_postdata();
		$output = ob_get_clean();
		
		return $output;
}
    ?>
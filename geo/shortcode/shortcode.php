<?php
   // Shortcode for visualizing coordinates of Experts on Google maps
	add_shortcode( 'expertsmap', 'experts_map_shortcode' );

		function experts_map_shortcode( $atts ){	
		ob_start();
		$params = shortcode_atts( 
			array(
				'google-maps-api-key' => 'AIzaSyAxhrKP2nTR9aE-FRMMQ-GQ-03FthtEcaI',
				'width-map' => '640px', 
				'height-map' => '400px',
				'width-table' => '640px',
				'quantity' => -1
			), 
			$atts 
		);	
		$args = array
		(
				'post_type' => 'rg_experts',
				'posts_per_page' => $params['quantity']
		);
	
		$experts = new WP_Query( $args );
		if ($experts->posts == null)
		{
			new Test();
			$experts = new WP_Query( $args );
		}
		
		
		echo "<table class='table' style='width:".$params['width-table']."'>";
		echo<<<EOL
		<thead>
			<tr>
				<th>Name</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody>
		EOL;
		
		
		foreach ($experts->posts as $person):
		
			if ($person->post_title){
			$addr[] = str_replace('"','',get_post_meta( $person->ID, '_my_meta_value_key', true ));
			$person_name[]=$person->post_title;
			 			
			echo '<tr>';
				echo '<td>'.$person->post_title.'</td>';
				echo '<td>'.get_post_meta( $person->ID, '_my_meta_value_key', true ).'</td>';
				
			echo '</tr>';
			 }
		endforeach;
		echo <<<EOL
		</tbody>
		</table>
		EOL;
	echo '<div id="map"style="width:'.$params["width-map"].'; height:'.$params["height-map"].';"></div>';
	// function for script defer attribute
	function geo_defer_attribute( $tag, $handle ) {
    if ( 'google-maps' !== $handle ) {
        return $tag;
    }
	
    return str_replace( ' src', ' defer="defer" src', $tag );
}
	add_filter( 'script_loader_tag', 'geo_defer_attribute', 10, 2 );
	$strurl = 'https://maps.googleapis.com/maps/api/js?key='.$params["google-maps-api-key"].'&callback=initMap';
	wp_enqueue_script('google-maps',$strurl);
	?>
	<script>
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
						getGeoCoder(addressList[i], personName[i]);
						
						}
					} catch (error) {
					alert(error);
					}
					
				}
				 function getGeoCoder(address, name) {
				 geocoder.geocode({
							'address' : address
				 }, function(results, status) {
					if (status == "OK") {
					map.setCenter(results[0].geometry.location);
					var p = results[0].geometry.location;
					var lat=p.lat();
                    var lng=p.lng();
                    createMarker(address,lat,lng, name);
				} else {
                geterrorMgs(address); // address not found handler
				}
                   });
                  }
				 function createMarker(add,lat,lng,name) {
				     var contentString = add;
					 var pName = name;
                     marker = new google.maps.Marker({
                         position: new google.maps.LatLng(lat,lng),
                         map: map,
                     });
					 var bounds = new google.maps.LatLngBounds();
					 var infowindow = new google.maps.InfoWindow({
						 ariaLabel: pName,
						 content: '<p><b>'+pName+'</b></p>'+contentString
					});
					google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map, marker);
					});
					bounds.extend(marker.position);
				}
	var addressList = <?php echo '["' . implode('", "', $addr) . '"]'; ?>;
	var personName = <?php echo '["' . implode('", "', $person_name) . '"]';  ?>
	
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
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
		if ($params["google-maps-api-key"])
		{
			$strurl = 'https://maps.googleapis.com/maps/api/js?key='.$params["google-maps-api-key"].'&callback=initMap';			
			wp_enqueue_script('google-maps',$strurl);
		}
		else
		{
			return ('Your Google API key is empty');
		}
		echo "<table class='table' style='width:".$params['width-table']."'>";
		echo<<<EOL
		<thead>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Positions</th>
			</tr>
		</thead>
		<tbody>
		EOL;
		
		$countPerson =0;
		foreach ($experts->posts as $person):
			if ($person->post_title){
			$addr[] = str_replace(array('"',','),'',get_post_meta( $person->ID, '_my_meta_value_key', true ));
			
			$person_name[]=$person->post_title;
			 			
			echo '<tr>';
				echo '<td>'.$person->post_title.'</td>';
				echo '<td>'.get_post_meta( $person->ID, '_my_meta_value_key', true ).'</td>';
				echo '<td><a id="per'.$countPerson.'" onclick="clickPer('.$countPerson.')" style="cursor: pointer;">Find it</a></td>';
				
			echo '</tr>';
			 }
			$countPerson++; 
		endforeach;
		echo <<<EOL
		</tbody>
		</table>
		EOL;
	echo '<div id="map"style="width:'.$params["width-map"].'; height:'.$params["height-map"].';"></div>';
	?>
	<script>
			var map;
			var geo;
			var elem =[];
			const addressList = <?php echo '["' . implode('", "', $addr) . '"]'; ?>;
			const personName = <?php echo '["' . implode('", "', $person_name) . '"]';  ?>;
			var coordArr=[];
			let markers = [];
			function initMap() {
				var opt = {
				zoom:13,
				mapTypeId : google.maps.MapTypeId.ROADMAP
				}
				map=new google.maps.Map(document.getElementById("map"),opt);
				geocoder = new google.maps.Geocoder();
				codeAddress(addressList);
			}
			function gm_authFailure() { alert('Yout Google API key is not valid!'); }
			function codeAddress(addressList) {
					try {
						var icount = addressList.length;
						for (var i = 0; i < icount; i++) {
						getGeoCoder(addressList[i], personName[i]);
						elem[i]=document.getElementById('per'+ i);
						}
						
					} catch (error) {
					alert(error);
					}
					
			}
			function getGeoCoder(address, name=null) {
				 geocoder.geocode({
							'address' : address
				 }, function(results, status) {
					if (status == "OK") {
					var p = results[0].geometry.location;
					coordArr.push(p);
					map.setCenter(p);
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
					 var marker = new google.maps.Marker({
                         position: new google.maps.LatLng(lat,lng),
						
                         map: map,
                     });
					 
					 var bounds = new google.maps.LatLngBounds();
					 var infowindow = new google.maps.InfoWindow({
						 ariaLabel: pName,
						 content: '<p><b>'+pName+'</b></p>'+contentString
					});
					google.maps.event.addListener(marker, 'click', function(e) {
					    
						infowindow.open({
						anchor: this,
						map,
						shouldFocus: true,
						

						});
						if (this.getAnimation() !== null) {
						this.setAnimation(null);
  
						}
					
					});
					bounds.extend(marker.position);
					
					markers.push(marker);
			 }

	function clickPer(num)
	{
					console.log('coordArr ',coordArr[0]);
					map.panTo(coordArr[num]);
					for (var x = 0; x < markers.length; x++){
						if (x == num)
						{
						markers[x].setAnimation(google.maps.Animation.BOUNCE);
						
						} else{
							markers[x].setAnimation(google.maps.Animation.DROP);
						}
					}
	}

    </script>
		
	<?php
			
		wp_reset_postdata();
		$output = ob_get_clean();
		
		return $output;
}
?>
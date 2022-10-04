var map;
			var geo;
			var addressList = JSON.parse(document.getElementById("map").getAttribute('data-addresslist'));
			var personName = JSON.parse(document.getElementById("map").getAttribute('data-personname'));
			var zoompar = Math.floor(document.getElementById("map").getAttribute('zoom'));
			var coordArr=[];
			var markers = [];
			var ver;
			function initMap()
			{
				
				var opt =
				{
					zoom:zoompar,
					mapTypeId : google.maps.MapTypeId.ROADMAP
				}
				map=new google.maps.Map(document.getElementById("map"),opt);
				geocoder = new google.maps.Geocoder();
				codeAddress(addressList);
			}
			function gm_authFailure() { alert('Yout Google API key is not valid!'); }
			function codeAddress(addressList) 
			{
					try {
						var icount = addressList.length;
						for (var i = 0; i < icount; i++) {
						getGeoCoder(addressList[i], personName[i], i);
						}
						
					} catch (error) 
					{
					alert(error);
					}
			}
			function getGeoCoder(address, name, num) {
				 geocoder.geocode
				 ({	'address' : address }, 
					function(results, status) {
						if (status == "OK") {
						var p = results[0].geometry.location;
						coordArr[num]=p;
						map.setCenter(p);
						var lat=p.lat();
						var lng=p.lng();
						createMarker(address,lat,lng, name, num);
						} else {
						geterrorMgs(address); // address not found handler
						}
					}
				  );
             }
			 function createMarker(add,lat,lng,name,num) {
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
					markers[num]=marker;
					infowindow.close({
						anchor: this,
						map,
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
					
			 }
// function for html links
	function clickPer(num)
	{
					map.panTo(coordArr[num]);
					for (var x = 0; x < addressList.length; x++){
						if (x == num)
						{
						markers[x].setAnimation(google.maps.Animation.BOUNCE);
						} else{
						markers[x].setAnimation(google.maps.Animation.DROP);
						}
					}
	}
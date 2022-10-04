let map;
			let geo;
			let addressList = JSON.parse(document.getElementById("map").getAttribute('data-addresslist'));
			let personName = JSON.parse(document.getElementById("map").getAttribute('data-personname'));
			let zoompar = Math.floor(document.getElementById("map").getAttribute('zoom'));
			let coordArr=[];
			let markers = [];
			let ver;
			function initMap()
			{
				
				let opt =
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
						let icount = addressList.length;
						for (let i = 0; i < icount; i++) {
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
						let p = results[0].geometry.location;
						coordArr[num]=p;
						map.setCenter(p);
						let lat=p.lat();
						let lng=p.lng();
						createMarker(address,lat,lng, name, num);
						} else {
						geterrorMgs(address); // address not found handler
						}
					}
				  );
             }
			 function createMarker(add,lat,lng,name,num) {
				     let contentString = add;
					 let pName = name;                    
					 let marker = new google.maps.Marker({
                         position: new google.maps.LatLng(lat,lng),
                         map: map,
                     });
					 let bounds = new google.maps.LatLngBounds();
					 let infowindow = new google.maps.InfoWindow({
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
					for (let x = 0; x < addressList.length; x++){
						if (x == num)
						{
						markers[x].setAnimation(google.maps.Animation.BOUNCE);
						} else{
						markers[x].setAnimation(google.maps.Animation.DROP);
						}
					}
	}
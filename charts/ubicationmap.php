
<div id="map_stgo" class="col s6 card hoverable widget" style="height:265px;" overflow:auto;></div>
<div id="map_vina" class="col s6 card hoverable widget" style="height:265px;" overflow:auto;></div>

<script>

function initMap() {
	var vina = 'Hola Viña del Mar!';
  	var stgo = 'Hola Santiago!';

	map_stgo = new google.maps.Map(document.getElementById('map_stgo'), {
		zoom: 10,
	  	center: {lat: -33.4548564, lng: -70.680945},
   	  	mapTypeId: google.maps.MapTypeId.ROADMAP	
  	});

	map_vina = new google.maps.Map(document.getElementById('map_vina'), {
		zoom: 10,
		center: {lat: -33.0236804, lng: -71.5670454},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

  	var infowindow_stgo = new google.maps.InfoWindow({
		content: stgo
	});

  	var infowindow_vina = new google.maps.InfoWindow({
		content: vina
	});

  	marker_stgo = new google.maps.Marker({
    	position: {lat: -33.4548564, lng: -70.680945},
    	map: map_stgo,
    	title: 'Hola Santiago!'
  	});

  	marker_vina = new google.maps.Marker({
		position: {lat: -33.0236804, lng: -71.5670454},
		map: map_vina,
		title: 'Hola Viña del Mar!'
	});

  	marker_stgo.addListener('click', function() {
		infowindow_stgo.open(map_stgo, marker_stgo);
	});

  	marker_vina.addListener('click', function() {
	    infowindow_vina.open(map_vina, marker_vina);
	});
}
</script>

<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDaN1s1LrPWKAIKMBqo2VbHT6oY-qg06iA&libraries=visualization&callback=initMap">
	</script>
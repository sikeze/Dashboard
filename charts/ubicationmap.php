<?php 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

$coordinates = location_map();

echo "
		<div class='col s12'>
			<div id='map_stgo' class='col s6 card hoverable widget' overflow:auto;></div>
			<div id='map_vina' class='col s6 card hoverable widget' overflow:auto;></div>
		</div>
		<div class='col s12 center-align' overflow:auto;>
        		<div id='center_stgo' class='col s6' overflow:auto;>
    				<a onclick='centerstgo()' class='waves-effect waves-light btn blue-text hoverable widget' style='background-color:white;margin-top:3%' overflow:auto;>".get_string('centerstgo','local_dashboard')."</a>
    			</div>
				<div id='center_vina' class='col s6' overflow:auto;>
    				<a onclick='centervina()' class='waves-effect waves-light btn blue-text hoverable widget' style='background-color:white;margin-top:3%' overflow:auto;>".get_string('centervina','local_dashboard')."</a>
    			</div>
        </div>
    <script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map_stgo'), {
          zoom: 9,
          center: {lat: -33.4548564, lng: -70.680945}
        });
		
		var map_vina = new google.maps.Map(document.getElementById('map_vina'), {
          zoom: 9,
          center: {lat: -33.0236804, lng: -71.5670454}
        });


        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location
          });
        });
		
		var markers_vina = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location
          });
        });

        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
		
		var markerCluster_vina = new MarkerClusterer(map_vina, markers_vina,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
		
		var infowindow_stgo = new google.maps.InfoWindow({
    		content: 'Universidad Adolfo Ibáñez (Sede Peñalolén)'
  		});
		
		var infowindow_vina = new google.maps.InfoWindow({
    		content: 'Universidad Adolfo Ibáñez (Sede Viña del Mar)'
  		});

  		var uai_stgo = new google.maps.Marker({
    		position: {lat:  -33.4894193, lng: -70.5188557},
    		map: map,
    		title: 'Universidad Adolfo Ibáñez',
			label: {
				text: 'UAI',
				fontSize: '8pt'
				}
  		});
		
		var uai_vina = new google.maps.Marker({
    		position: {lat: -33.0194716, lng: -71.5324613},
    		map: map_vina,
    		title: 'Universidad Adolfo Ibáñez',
			label: {
				text: 'UAI',
				fontSize: '8pt'
				}
  		});
  		
		uai_stgo.addListener('click', function() {
    		infowindow_stgo.open(map, uai_stgo);
  		});
		
		uai_vina.addListener('click', function() {
    		infowindow_vina.open(map_vina, uai_vina);
  		});
      }
		
	function centerstgo() {
		var map = new google.maps.Map(document.getElementById('map_stgo'), {
        	zoom: 9,
			center: {lat: -33.4548564, lng: -70.680945}
        });
		
		var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location
          });
        });
		
		var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
		
		var infowindow_stgo = new google.maps.InfoWindow({
    		content: 'Universidad Adolfo Ibáñez (Sede Peñalolén)'
  		});
		
		var uai_stgo = new google.maps.Marker({
    		position: {lat:  -33.4894193, lng: -70.5188557},
    		map: map,
    		title: 'Universidad Adolfo Ibáñez',
			label: {
				text: 'UAI',
				fontSize: '8pt'
				}
  		});
		
		uai_stgo.addListener('click', function() {
    		infowindow_stgo.open(map, uai_stgo);
  		});
	}
		
	function centervina() {
		var map_vina = new google.maps.Map(document.getElementById('map_vina'), {
          zoom: 9,
          center: {lat: -33.0236804, lng: -71.5670454}
        });	
		
		var markers_vina = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location
          });
        });
		
		var markerCluster_vina = new MarkerClusterer(map_vina, markers_vina,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
		
		var infowindow_vina = new google.maps.InfoWindow({
    		content: 'Universidad Adolfo Ibáñez (Sede Viña del Mar)'
  		});
		
		var uai_vina = new google.maps.Marker({
    		position: {lat: -33.0194716, lng: -71.5324613},
    		map: map_vina,
    		title: 'Universidad Adolfo Ibáñez',
			label: {
				text: 'UAI',
				fontSize: '8pt'
				}
  		});
		
		uai_vina.addListener('click', function() {
    		infowindow_vina.open(map_vina, uai_vina);
  		});
	}
		
      var locations = [";
		foreach ($coordinates as $coor) {
        echo "{lat: ".$coor->latitude.", lng: ".$coor->longitude."},";
       }
       echo "
      ]
    </script>
    		
	<script async defer
    	src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDaN1s1LrPWKAIKMBqo2VbHT6oY-qg06iA&callback=initMap'>
	</script>";
?>


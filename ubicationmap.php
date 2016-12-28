<div id="buttons" style="height:20px;">	
	<a class="btn btn-flat white blue-text tooltipped waves-effect waves-blue" onclick="changeSantiago()" style="padding:0.1rem;"><font size="2">Santiago</font></a>
	<a class="btn btn-flat white blue-text tooltipped waves-effect waves-blue" onclick="changeVina()" style="padding:0.1rem;"><font size="2">Viña del Mar</font></a>
</div><br>
<div id="map" class="card white-text hoverable widget" style="height:265px;"></div>

<script>
var map, heatmap;

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 10,
	  center: {lat: -33.4548564, lng: -70.680945},
   	  mapTypeId: google.maps.MapTypeId.ROADMAP	
  });

  heatmap = new google.maps.visualization.HeatmapLayer({
    data: getPoints(),
    map: map
  });
}


function changeGradient() {
  var gradient = [
    'rgba(0, 255, 255, 0)',
    'rgba(0, 255, 255, 1)',
    'rgba(0, 191, 255, 1)',
    'rgba(0, 127, 255, 1)',
    'rgba(0, 63, 255, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(0, 0, 223, 1)',
    'rgba(0, 0, 191, 1)',
    'rgba(0, 0, 159, 1)',
    'rgba(0, 0, 127, 1)',
    'rgba(63, 0, 91, 1)',
    'rgba(127, 0, 63, 1)',
    'rgba(191, 0, 31, 1)',
    'rgba(255, 0, 0, 1)'
  ]
  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
}

function changeRadius() {
  heatmap.set('radius', heatmap.get('radius') ? null : 20);
}

function changeOpacity() {
  heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
}

function changeVina() {
	  map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 10,
	    center: {lat: -33.0236804, lng: -71.5670454},
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  });
	  heatmap = new google.maps.visualization.HeatmapLayer({
		    data: getPoints(),
		    map: map
		  });
	}

	function changeSantiago() {
	  map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 10,
	    center: {lat: -33.4548564, lng: -70.680945},
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  });
	  heatmap = new google.maps.visualization.HeatmapLayer({
		    data: getPoints(),
		    map: map
		  });
	}

// Heatmap data
function getPoints() {
  return [
    new google.maps.LatLng(-33.02368041, -71.3670254),
    new google.maps.LatLng(-33.02368041, -71.5670254),
    new google.maps.LatLng(-33.02368041, -71.2678454),
    new google.maps.LatLng(-33.02368041, -71.5670854),
    new google.maps.LatLng(-33.02368041, -71.5670454),
    new google.maps.LatLng(-33.02368041, -71.5670454),
    new google.maps.LatLng(-33.02368041, -71.5670454),
    new google.maps.LatLng(-33.02368041, -71.5670454),
    new google.maps.LatLng(-33.02368041, -71.5670454),
    new google.maps.LatLng(-33.02368041, -71.5670454)
  ];
}

    </script>
      <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDaN1s1LrPWKAIKMBqo2VbHT6oY-qg06iA&libraries=visualization&callback=initMap">
    </script>
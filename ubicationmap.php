<div id="buttons" style="height:20px;">	
	<a class="btn btn-flat white blue-text tooltipped waves-effect waves-blue" onclick="changeSantiago()" style="padding:0.1rem;"><font size="2">Santiago</font></a>
	<a class="btn btn-flat white blue-text tooltipped waves-effect waves-blue" onclick="changeVina()" style="padding:0.1rem;"><font size="2">Viña del Mar</font></a>
</div><br>
<div id="map" class="card hoverable widget" style="height:265px;" overflow:auto;></div>

<script>

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 10,
	  center: {lat: -33.4548564, lng: -70.680945},
   	  mapTypeId: google.maps.MapTypeId.ROADMAP	
  });

  var hola = 'Hola Santiago!';

  var infowindow = new google.maps.InfoWindow({
	    content: hola
	  });

  marker = new google.maps.Marker({
    position: {lat: -33.4548564, lng: -70.680945},
    map: map,
    title: 'Hello World!'
  });

  marker.addListener('click', function() {
	    infowindow.open(map, marker);
	  });
}

function changeVina() {
	  map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 10,
	    center: {lat: -33.0236804, lng: -71.5670454},
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  });
	 var contentString = "Hola Viña del Mar!";
	  
	  var infowindow = new google.maps.InfoWindow({
		    content: contentString
		  });

	  marker = new google.maps.Marker({
	    position: {lat: -33.0236804, lng: -71.5670454},
	    map: map,
	    title: "Hello World!"
	  });

	  marker.addListener('click', function() {
		    infowindow.open(map, marker);
		  });
	}

	function changeSantiago() {
	  map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 10,
	    center: {lat: -33.4548564, lng: -70.680945},
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  });

	  var contentString = "Hola Santiago!";
	  
	  var infowindow = new google.maps.InfoWindow({
		    content: contentString
		  });

	  marker = new google.maps.Marker({
	    position: {lat: -33.4548564, lng: -70.680945},
	    map: map,
	    title: "Hello World!"
	  });

	  marker.addListener('click', function() {
		    infowindow.open(map, marker);
		  });
	}

    </script>
      <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDaN1s1LrPWKAIKMBqo2VbHT6oY-qg06iA&libraries=visualization&callback=initMap">
    </script>
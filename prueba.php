<?php 
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/locallib.php');
?>	
	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	<link type="text/css" rel="stylesheet" href="css/styles.css"  media="screen,projection"/>
	<!-- Google Maps Api -->
	<link href="https://developers.google.com/maps/documentation/javascript/examples/default.css" rel="stylesheet">
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!--Import jQuery before materialize.js-->
	<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.sparkline.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="https://rawgit.com/kimmobrunfeldt/progressbar.js/1.0.0/dist/progressbar.js"></script>
  <div class="row">	
<div class="input-field col s4 blue-text" >
    <select id="dispersionselect">
      <option value="0" disabled selected>Choose your option</option>
      <option value="1"><span class="blue-text">Mensual</span></option>
      <option value="2"><span class="blue-text">Semanal</span></option>
      <option value="3"><span class="blue-text">Diario</span></option>
      <option value="4"><span class="blue-text">Hora</span></option>
    </select>
    <label>Opción de Dispersión</label>
  </div>
   </div>
  <div id="usersinfo"></div>  		  			  
  <script>

  $('select').material_select();
  $('#dispersionselect').change(function () {
  	  var dispersion = $('#dispersionselect :selected').val();
  	  $.ajax({
	  	  	url: 'prueba2.php',
	        data: {'disperssion': dispersion},
	        method: 'POST',
	        success: function (output) {
	        	$( "#usersinfo" ).html(output);
	        	alert(output);
	        }
	  	});
    });
 </script>
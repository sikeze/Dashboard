<?php 
require_once(dirname(__FILE__) . '/header.php');
?>
<html>
<body>
<!-- CONTENT -->
	<main class="grey lighten-3">	
	<!-- Users text row -->
    	<div class="row">
        	<div class="col s8">
        		<h5><b>Visitas del usuario</b></h5>
        	</div>
        	<div class="col s4" style="margin-top:20px;">
        		<b>Opciones: </b><i class="material-icons prefix blue-text md-24">file_download</i>
        	</div>
        	<div class="input-field left-align col s4 blue-text">
        		<select id="dataselect">
      				<option value="0" disabled selected>Choose your option</option>
      				<option value="1"><span class="blue-text">Sesiones</span></option>
      				<option value="2"><span class="blue-text">Tiempo Promedio Sesiones</span></option>
      				<option value="3"><span class="blue-text">Usuarios</span></option>
      				<option value="4"><span class="blue-text">Usuarios Nuevos</span></option>
      				<option value="5"><span class="blue-text">Cursos Vistos</span></option>
    			</select>
    			<label>Selección de Datos</label>
        	</div>
        </div>
        
	<!-- Users information chart-->
    	<div class="row">
        	<div id="userschart" class="col s12 l12 card white-text hoverable widget" overflow:auto;"></div>
		</div>

	<!-- Divider line -->
    	<div class="row">
       		<hr width=95% align="middle" class="grey lighten-2">
        </div>
        
	<!-- Users sparkline charts -->
    	<div class="row">
        	<div class="col s12" style="margin-left:15px;" overflow:auto;>
        		<b>Opciones: </b><i class="material-icons prefix blue-text md-24">file_download</i>
        	</div>
        	<div id="userinfo" class="col s12" overflow:auto;></div>
        </div>
        
	<!-- Location text row-->
        <div class="row">
        	<div class="col s7">
        		<h5><b><?php echo get_string('ubication', 'local_dashboard'); ?></b></h5>
        	</div>
        	<div class="col s2" style="margin-top:20px;">
        		<b>Opciones: </b><i class="material-icons prefix blue-text md-24">file_download</i>
        	</div>
        </div>
        
	<!-- Location Maps-->
    	<div class="row">
        	<div id="ubicationmap" class="col s12" overflow:auto;></div>
        </div> 
        
    <!-- Location Table --> 
    	<div class="row">
    		<div id="locationtable" class="col s12 hoverable widget" overflow:auto;></div>
    	</div>      
	</main>
<!-- CONTENT -->
<!-- FOOTER -->
	<footer class="blue page-footer">
		<div class="footer-copyright">
			<div class="container">
            	© 2017 Copyright Text
            	<a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
		</div>
	</footer>
<!-- FOOTER -->
</body>

<script>
//Send function tu userschart and fill the chart
var users_sessions = <?php echo json_encode(users_sessions_dates());?>;
var users_info = <?php echo json_encode(users_info());?>;
var users_labels = <?php echo json_encode(users_info_labels());?>;
$(document).ready(function () {
    $.ajax({
        url: 'charts/userschart.php',
        data: {sessions: users_sessions},
        method: 'POST',
        success: function (output) {
        	$( "#userschart" ).html(output);
        }
  	});
    $.ajax({
        url: 'charts/usersinfo.php',
        data: {users: users_info, labels: users_labels},
        method: 'POST',
        success: function (output) {
        	$( "#userinfo" ).html(output);
        }
  	});
    $('#dispersionselect').change(function () {
  	  var datos  = $('#dataselect :selected').val();
  	  var dispersion = $('#dispersionselect :selected').val();
  	  	$.ajax({
  	  	  	url: 'charts/changeuserschart.php',
  	        data: {'select': datos, 'disperssion': dispersion},
  	        method: 'POST',
  	        success: function (output) {
  	        	$( "#userschart" ).html(output);
  	        }
  	  	});
  	  	$.ajax({
  	  	  	url: 'charts/changeusersinfo.php',
  	        data: {'disperssion': dispersion, 'labels':users_labels},
  	        method: 'POST',
  	        success: function (output) {
  	        	$( "#userinfo" ).html(output);
  	        }
  	  	});
    });
    $("#dataselect").change(function () { //Change div of charts
  	  var datos  = $("#dataselect :selected").val();
  	  var dispersion = $('#dispersionselect :selected').val();
    	$.ajax({
        	url: 'charts/changeuserschart.php',
        	data: {'select': datos, 'disperssion': dispersion},
        	method: 'POST',
        	success: function (output) {
            	$('#userschart').html(output);
        	}
  		});
    });
    $( "#locationtable" ).load( "charts/locationtable.php" );
    $( "#ubicationmap" ).load( "charts/ubicationmap.php" );
});
</script>
</html>
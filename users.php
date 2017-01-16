<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Dashboard</title>
	<?php
		require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
		require_once(dirname(__FILE__) . '/locallib.php');
	
		$frontpageurl = new moodle_url('/local/dashboard/frontpage.php');
		$usersurl = new moodle_url('/local/dashboard/users.php');
		$resourcesurl = new moodle_url('/local/dashboard/resources.php');
		
		require_login();
		if (isguestuser()) {
			die();
		}
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
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.sparkline.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="https://rawgit.com/kimmobrunfeldt/progressbar.js/1.0.0/dist/progressbar.js"></script>
</head>
<body>
<!-- TOP NAV -->
	<div class="navbar-fixed">
    	<nav class="grey lighten-2 blue-text">
        	<div class="nav-wraper">
            	<a href="<?php echo $frontpageurl; ?>" class="brand-logo"><img class="responsive-img" src="images/webcursoslogo.gif" style="width:60%"></a>
               	<ul id="nav-mobile" class="right hide-on-med-and-down">
               		<li class="input-field blue-text">
               			<i class="material-icons prefix blue-text">date_range</i>
        				<input type="date" class="datepicker" id="datepickertwo">
        				<label class="active" for="datepickertwo">Elija primera fecha</label>
               		</li>
               		<li class="input-field blue-text">
               			<i class="material-icons prefix blue-text">date_range</i>
        				<input type="date" class="datepicker" id="datepickerone">
        				<label class="active" for="datepickerone">Elija segunda fecha</label>
        			</li>
               		<li class="input-field blue-text">
    					<select id="dispersionselect">
     	 					<option value="0" disabled selected>Opciones de Dispersión</option>
      						<option value="1"><span class="blue-text">Mensual</span></option>
      						<option value="2"><span class="blue-text">Semanal</span></option>
      						<option value="3"><span class="blue-text">Diario</span></option>
      						<option value="4"><span class="blue-text">Hora</span></option>
    					</select>
               		</li>
               		<li><a href="badges.html" class=""><i class="material-icons blue-text">edit</i></a></li>
                    <li><a href="collapsible.html" class=""><i class="material-icons blue-text">refresh</i></a></li>
                    <li><a href="mobile.html" class=""><i class="material-icons blue-text">help</i></a></li>
			</div>
            <a href="#" data-activates="slide-out" class="button-collapse left grey-text"><i class="material-icons">menu</i></a>
    	</nav>
	</div>
<!-- TOP NAV -->
<!-- SIDENAV -->
	<ul id="slide-out" class="z-depth-2 side-nav fixed blue">
    	<li>
        	<div class="card-panel blue darken-3 z-depth-1 no-padding" style="margin-top:0px;">
            	<div class="row valign-wrapper">
                	<div class="col s12">
                    	<!-- Dropdown Trigger -->
                        <a class='dropdown-button btn btn-flat white blue-text tooltipped waves-effect waves-blue' data-beloworigin="true" data-position="right" data-tooltip="Opciones de usuario" href='#' data-activates='dropdown1'>
                        	<?php echo $USER->username." ".$USER->lastname; ?> <i class="material-icons right">arrow_drop_down</i>
                        </a>
                        <!-- Dropdown Structure -->
                        <ul id='dropdown1' class='dropdown-content'>
                        	<li  class="red darken-4"><a class="white-text" href="#!"><?php echo get_string('logout', 'local_dashboard'); ?></a></li>
                        </ul>
                	</div>
            	</div>
        	</div>
		</li>
        <li><a href="<?php echo $frontpageurl;?>" class="white-text menu-item"><?php echo get_string('home', 'local_dashboard'); ?><i class="material-icons white-text">home</i></a></li>
        <li><a href="<?php echo $usersurl; ?>" class="white-text menu-item"><?php echo get_string('users', 'local_dashboard'); ?><i class="material-icons white-text">supervisor_account</i></a></li>
        <li><a href="<?php echo $resourcesurl; ?>" class="white-text menu-item"><?php echo get_string('resources', 'local_dashboard'); ?><i class="material-icons white-text">description</i></a></li>
		<li><a href="/moodle/my/" class="white-text menu-item">Volver a Moodle<i class="material-icons white-text">undo</i></a></li>
	</ul>
<!-- SIDENAV -->
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
var users_devices = <?php echo json_encode(users_devices_table());?>;
$(document).ready(function () {
    //init sidenav
    $('.button-collapse').sideNav({
        menuWidth: 250, // Default is 240
        edge: 'left' // Choose the horizontal origin
    });
    $('select').material_select();
    $('.materialboxed').materialbox();
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        format: 'dd-mm-yy'
      });
    $.ajax({
        url: 'charts/userschart.php',
        data: {sessions: users_sessions},
        method: 'POST',
        success: function (output) {
        	$( "#userschart" ).html(output);
        }
  	});
    $('#dispersionselect').change(function () {
  	  var datos  = $('#dataselect :selected').val();
  	  var dispersion = $('#dispersionselect :selected').val();
  	  	$.ajax({
  	  	  	url: 'changeuserschart.php',
  	        data: {'select': datos, 'disperssion': dispersion},
  	        method: 'POST',
  	        success: function (output) {
  	        	$( "#userschart" ).html(output);
  	        }
  	  	});
    });
    $("#dataselect").change(function () { //Change div of charts
  	  var datos  = $("#dataselect :selected").val();
  	  var dispersion = $('#dispersionselect :selected').val();
    	$.ajax({
        	url: 'changeuserschart.php',
        	data: {'select': datos, 'disperssion': dispersion},
        	method: 'POST',
        	success: function (output) {
            	$('#userschart').html(output);
        	}
  		});
    });
    $.ajax({
        url: 'charts/usersinfo.php',
        data: {users: users_info, labels: users_labels},
        method: 'POST',
        success: function (output) {
        	$( "#userinfo" ).html(output);
        }
  	});
    $( "#locationtable" ).load( "charts/locationtable.php" );
    $( "#ubicationmap" ).load( "charts/ubicationmap.php" );
    $('.menu-item').click(function () {
        ref = $(this).attr('href').replace('#', '') + '.html';
        $('.progress > div').toggleClass('determinate').toggleClass('indeterminate');
        $('#content').load(ref, function () {
            $('.progress > div').toggleClass('determinate').toggleClass('indeterminate');
        });
    }); 
});
</script>
</html>
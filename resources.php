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
                    	<li><a href="badges.html" class=""><i class="material-icons blue-text">edit</i></a></li>
                        <li><a href="collapsible.html" class=""><i class="material-icons blue-text">refresh</i></a></li>
                        <li><a href="mobile.html" class=""><i class="material-icons blue-text">help</i></a></li>
                    </ul>
           	</div>
            <a href="#" data-activates="slide-out" class="button-collapse left grey-text"><i class="material-icons">menu</i></a>
            <div class="progress">
            	<div class="determinate blue" style="width: 100%"></div>
            </div>
        </nav>
	</div>
<!-- TOP NAV -->
<!-- SIDENAV -->
	<ul id="slide-out" class="z-depth-2 side-nav fixed blue ">
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
       		<li><a href="<?php echo $frontpageurl; ?>" class="white-text menu-item"><?php echo get_string('home', 'local_dashboard'); ?><i class="material-icons white-text">home</i></a></li>
            <li><a href="<?php echo $usersurl; ?>" class="white-text menu-item"><?php echo get_string('users', 'local_dashboard'); ?><i class="material-icons white-text">supervisor_account</i></a></li>
            <li><a href="<?php echo $resourcesurl; ?>" class="white-text menu-item"><?php echo get_string('resources', 'local_dashboard'); ?><i class="material-icons white-text">description</i></a></li>
	</ul>
<!-- SIDENAV -->
<!-- CONTENT -->
	<main class="grey lighten-3">
	
		<!-- Data buttons (month, week or day) -->
    	<div class="right-align row">
    		<a class="blue btn dropdown-button" href="#!" data-activates="dropdown"><span id="selected">Opciones de dispersión</span><i class="mdi-navigation-arrow-drop-down right" style="margin:auto;"></i></a>
        	<ul id="dropdown" class="dropdown-content">
        		<li><a href="#!" class="center-align"><span class="blue-text">Anual</span></a></li>
    			<li><a href="#!" class="center-align"><span class="blue-text">Mensual</span></a></li>
    			<li><a href="#!" class="center-align"><span class="blue-text">Semanal</span></a></li>
    			<li><a href="#!" class="center-align"><span class="blue-text">Diario</span></a></li>
  		  	</ul>
		</div>
		
		<!-- Utilization vs time text row -->
    	<div class="row">
        	<div class="col s12">
        		<h5><b>Utilización v/s Tiempo</b></h5>
        	</div>
        </div>
        
		<!-- Data select and datepicker -->
		<div class="row">
			<div class="col s4">
				<a class="blue btn dropdown-button" href="#!" data-activates="datadropdown"><span id="selected2">Selección de datos</span><i class="mdi-navigation-arrow-drop-down right" style="margin:auto;"></i></a>
        		<ul id="datadropdown" class="dropdown-content">
    				<li value="0"><a href="#" class="center-align"><span class="blue-text">Todos</span></a></li>
    				<li value="1"><a href="#" class="center-align"><span class="blue-text">Turnitin</span></a></li>
    				<li value="2"><a href="#" class="center-align"><span class="blue-text">Paper Attendance</span></a></li>
    				<li	value="3"><a href="#" class="center-align"><span class="blue-text">PDF</span></a></li>
    				<li value="4"><a href="#" class="center-align"><span class="blue-text">Tareas</span></a></li>
    				<li value="5"><a href="#" class="center-align"><span class="blue-text">eMarking</span></a></li>
  		  		</ul>
        	</div>
        	<div class="col s8">
        		<div class="input-field col s6"> <!-- CHANGE COLOR TO CALENDAR!!! -->
        			<i class="material-icons prefix blue-text">date_range</i>
        			<input type="date" class="datepicker" id="datepickerone">
        			<label class="active" for="datepickerone">Elija primera fecha</label>
        		</div>
        		<div class="input-field col s6">
        			<i class="material-icons prefix blue-text">date_range</i>
        			<input type="date" class="datepicker" id="datepickertwo">
        			<label class="active" for="datepickertwo">Elija segunda fecha</label>
        		</div>
        	</div>
		</div>
		
		<!-- Utilization vs time chart -->
		<div class="row">
			<div id="utimechart" class="col s12 card white-text hoverable widget" style="height:270px;"></div>
		</div>
		
		<!-- Divider line -->
		<div class="row">
       		<hr width=95% align="middle" color="black">
        </div>
        
        <!-- Resources and costs texts and charts-->
        <div class="row">
        	<div class="col s6">
        		<div class="col s12">
        			<h5><b>Recursos v/s % de Utilización</b></h5>
        		</div>
        		<div id="uresources" class="col s12 card white-text hoverable widget" style="height:262px;" overflow:auto;></div>
        	</div>
        	<div class="col s6">
        		<div class="col s12">
        			<h5><b>Costo eMarking</b></h5>
        		</div>
        		<!-- eMarking costs panels -->
        		<div class="col s12">
        			<div class="col s4 m4">
        				<div class="card-panel blue white-text center-align hoverable widget">
        				Largo promedio prueba: 3
        				</div>
        			</div>
        			<div class="col s4 m4">
        				<div class="card-panel blue white-text center-align hoverable widget">
        				Páginas impresas: 829
        				</div>
        			</div>
        			<div class="col s4 m4">
        				<div class="card-panel blue white-text center-align hoverable widget">
        				Costo total impresiones: $0
        				</div>
        			</div>
        		</div>
        		<div class="col s12">
        			<h5><b>Usuarios linkeados a Facebook</b></h5>
        		</div>
        		<!-- Facebook users chart -->
        		<div id="facebookusers" class="col s12 card hoverable widget" style="height:100px;">
        		</div>
        	</div>
        </div>
        
        <div class="row">
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

<script src="js/utimechart.js"></script>
<script src="js/uresources.js"></script>
<script>
$(document).ready(function () {
    //init sidenav
    $('.button-collapse').sideNav({
        menuWidth: 250, // Default is 240
        edge: 'left' // Choose the horizontal origin
    });
    $('.materialboxed').materialbox();
    $('#dropdown a').click(function(){
        $('#selected').text($(this).text());
      });
    $('#datadropdown a').click(function(){
        $('#selected2').text($(this).text());
      });
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15 // Creates a dropdown of 15 years to control year
      });
    $("#datadropdown li").click(function () { //Change div of charts
    	var data = $(this).val();
        $.ajax({
            url: 'changechart.php',
            data: {"select": data},
            method: 'POST',
            success: function (output) {
                $('#utimechart').html(output);
            }
      });
    });
    $( "#facebookusers" ).load( "charts/fbusers.php" );
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
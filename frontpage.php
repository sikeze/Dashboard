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
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.sparkline.js"></script>
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
    <!-- Data buttons (year, month, week or day) -->
    	<div class="right-align row">
          <ul id="dropdown" class="dropdown-content">
    		<li><a href="#!" class="center-align"><span class="blue-text">Anual</span></a></li>
    		<li><a href="#!" class="center-align"><span class="blue-text">Mensual</span></a></li>
    		<li><a href="#!" class="center-align"><span class="blue-text">Semanal</span></a></li>
    		<li><a href="#!" class="center-align"><span class="blue-text">Diario</span></a></li>
  		  </ul>
  		  <a class="blue btn dropdown-button" href="#!" data-activates="dropdown">Opciones de dispersión<i class="mdi-navigation-arrow-drop-down right" style="margin:auto;"></i></a>
        </div>

	<!-- Text row -->
		<div class="row">
        	<div class="col s6" style="height:15px;">
        		<h5><b><?php echo get_string('turnitin','local_dashboard'); ?></b></h5>
        	</div>
        	<div class="col s6" style="height:15px;">
        		<h5><b><?php echo get_string('courseresources','local_dashboard'); ?></b></h5>
        	</div>
        </div>
        
	<!-- Turnitin and course resources charts -->
    	<div class="row">
        	<div id="turnitinchart" class="col s6 l6 card white-text hoverable widget" overflow:auto;></div>
            <div id="resourcebarchart" class="col s6 l6 card white-text hoverable widget" style="width:50%;" overflow: auto;></div>
        </div>
        
	<!-- Ubication, devices and user information text -->
    	<div class="row">
        	<div class="col s4" style="height:20px;">
        		<h5><b><?php echo get_string('ubication','local_dashboard'); ?></b></h5>
        	</div>
        	<div class="col s5" style="height:20px;">
        		<h5><b><?php echo get_string('devices','local_dashboard'); ?></b></h5>
        	</div>
        	<div class="col s3" style="height:45px;">
        		<h5><b><?php echo get_string('userinfo','local_dashboard'); ?></b></h5>
        	</div>
        </div>

	<!-- Ubication, devices and user information charts -->
		<div class="row">
			<div id="ubicationmap" class="col s4" overflow:auto;></div>
			<div id="deviceschart" class="col s5 card hoverable widget" style="height:265px;" overflow:auto;></div>
			<div id="userinfo" class="col s3" overflow:auto;></div>
        </div>
        
	<!-- Map buttons -->
        <div class="row">
        	<div id="buttons" class="col s4">	
				<a class="btn btn-flat white blue-text waves-effect waves-blue" onclick="changeSantiago()" style="padding:0.1rem;"><font size="2">Santiago</font></a>
				<a class="btn btn-flat white blue-text waves-effect waves-blue" onclick="changeVina()" style="padding:0.1rem;"><font size="2">Viña del Mar</font></a>
			</div>
        </div>
        
	<!-- Facebok data -->
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
 <script>
        $(document).ready(function () {
            //init sidenav
            $('.button-collapse').sideNav({
                menuWidth: 250, // Default is 240
                edge: 'left' // Choose the horizontal origin
            });
            $('.materialboxed').materialbox();
            $( "#userinfo" ).load( "charts/userfrontinfo.php" );
            $( "#ubicationmap" ).load( "charts/ubicationmap.php" );
            $( "#deviceschart" ).load( "charts/deviceschart.php" );
            $('.menu-item').click(function () {
                ref = $(this).attr('href').replace('#', '') + '.html';
                $('.progress > div').toggleClass('determinate').toggleClass('indeterminate');
                $('#content').load(ref, function () {
                    $('.progress > div').toggleClass('determinate').toggleClass('indeterminate');
                });
            }); 
        });
    </script>
    <script src="js/turnitinchart.js"></script>
    <script src="js/resourcebarchart.js"></script>
</html>
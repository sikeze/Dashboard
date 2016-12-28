<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Dashboard</title>
	<?php
		require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
		require_once(dirname(__FILE__) . '/locallib.php');
	
		$frontpageurl = new moodle_url('/local/dashboard/frontpage.php');
	
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
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<!-- TOP NAV -->
        <div class="navbar-fixed">
            <nav class="grey lighten-2 blue-text">
                <div class="nav-wraper">
                    <a href="<?php $frontpageurl ?>" class="brand-logo"><img class="responsive-img" src="images/webcursoslogo.gif" style="width:60%"></a>
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
            <li><a href="#home" class="white-text menu-item"><?php echo get_string('home', 'local_dashboard'); ?><i class="material-icons white-text">home</i></a></li>
            <li><a href="#users" class="white-text menu-item"><?php echo get_string('users', 'local_dashboard'); ?><i class="material-icons white-text">supervisor_account</i></a></li>
            <li><a href="#resource" class="white-text menu-item"><?php echo get_string('resources', 'local_dashboard'); ?><i class="material-icons white-text">description</i></a></li>
        </ul>
        <!-- SIDENAV -->
                <!-- CONTENT -->
        <main class="grey lighten-3">
        <!-- Text row -->
        <div class="row">
        	<div class="col s6" style="height:15px;">
        	<h5><b>Users information</b></h5>
        	</div>
        	<div class="col s6" style="height:15px;">
        	<h5><b>Course Resources</b></h5>
        	</div>
        </div>
        
        <!-- User information and course resources charts -->
        <div class="row">
        	<div id= "userinfo" class="col s6"></div>
            <div id="resourcebarchart" class="col s6 l6 card white-text hoverable widget" style="width:50%;" overflow: auto;></div>
        </div>
        
        <!-- Ubication, devices and turnitin text -->
        <div class="row">
        	<div class="col s3" style="height:20px;">
        	<h5><b>Ubication</b></h5>
        	</div>
        	<div class="col s3" style="height:20px;">
        	<h5><b>Devices</b></h5>
        	</div>
        	<div class="col s6" style="height:20px;">
        	<h5><b>Turnitin</b></h5>
        	</div>
        </div>
        
        <!-- Ubication, devices and turnitin charts -->
		<div class="row">
			<div id="ubicationmap" class="col s3" style="height:300px;" overflow:auto;></div>
			<div id="devices" class="col s3" style="height:308px;" overflow:auto;>
				<div id="deviceschart" class="col s12 card white-text hoverable widget" overflow:auto;></div>
			</div><br><br>
			<div id="turnitinchart" class="col s6 l6 card white-text hoverable widget" overflow:auto;></div>
        </div>
        <!-- Facebook data -->
        <div class="row"></div>
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
</body>
 <script>
        $(document).ready(function () {
            //init sidenav
            $('.button-collapse').sideNav({
                menuWidth: 250, // Default is 240
                edge: 'left' // Choose the horizontal origin
            });
            $('.materialboxed').materialbox();
            $( "#userinfo" ).load( "userinformation.php" );
            $( "#ubicationmap" ).load( "ubicationmap.php" );
            $('.menu-item').click(function () {
                ref = $(this).attr('href').replace('#', '') + '.html';
                $('.progress > div').toggleClass('determinate').toggleClass('indeterminate');
                $('#content').load(ref, function () {
                    $('.progress > div').toggleClass('determinate').toggleClass('indeterminate');
                });
            }); 
        });
    </script>
    <script src="js/resourcebarchart.js"></script>
    <script src="js/devicesbarchart.js"></script>
    <script src="js/turnitinchart.js"></script>
</html>
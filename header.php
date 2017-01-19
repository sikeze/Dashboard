<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

$frontpageurl = new moodle_url('/local/dashboard/frontpage.php');
$usersurl = new moodle_url('/local/dashboard/users.php');
$resourcesurl = new moodle_url('/local/dashboard/resources.php');
$time = time();
$timenow =  gmdate("d-m-Y", $time);
$timemonthless = date('d-m-Y',strtotime($timenow . "-1 month"));
require_login();
if (isguestuser()) {
	die();
}
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
               		<li class="input-field">
               			<i class="material-icons prefix blue-text">date_range</i>
        				<input type="date" class="datepicker" id="datepickerone" data-value="<?php echo $timemonthless;?>">
               		</li>
               		<li class="input-field">
               			<i class="material-icons prefix blue-text">date_range</i>
        				<input type="date" class="datepicker" id="datepickertwo"  data-value="<?php echo $timenow;?>">
        			</li>
               		<li class="input-field blue-text">
    					<select id="dispersionselect">
      						<option value="1"><span class="blue-text"><?php echo get_string('monthly', 'local_dashboard');?></span></option>
      						<option value="2"><span class="blue-text"><?php echo get_string('daily', 'local_dashboard');?></span></option>
      						<option value="3"><span class="blue-text"><?php echo get_string('hour', 'local_dashboard');?></span></option>

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
       	<li><a href="<?php echo $frontpageurl; ?>" class="white-text menu-item"><?php echo get_string('home', 'local_dashboard');?><i class="material-icons white-text">home</i></a></li>
        <li><a href="<?php echo $usersurl; ?>" class="white-text menu-item"><?php echo get_string('users', 'local_dashboard');?><i class="material-icons white-text">supervisor_account</i></a></li>
        <li><a href="<?php echo $resourcesurl; ?>" class="white-text menu-item"><?php echo get_string('resources', 'local_dashboard');?><i class="material-icons white-text">description</i></a></li>
		<li><a href="<?php echo $backtomoodleurl; ?>" class="white-text menu-item"><?php echo get_string('backhome', 'local_dashboard');?><i class="material-icons white-text">undo</i></a></li>	
	</ul>
<!-- SIDENAV -->
</body>
<script>
$(document).ready(function () {
	//initialize sidenav
	$('.button-collapse').sideNav({
	    menuWidth: 250, // Default is 240
	    edge: 'left' // Choose the horizontal origin
	});
	//initialize materialize select
	$('select').material_select();
	//initialize materialize box
	$('.materialboxed').materialbox();
	//datepicker config
	$('.datepicker').pickadate({
	    selectMonths: true, // Creates a dropdown to control month
	    selectYears: 15, // Creates a dropdown of 15 years to control year
	    format: 'dd-mm-yyyy'
	  });
	

});
</script>
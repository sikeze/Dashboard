<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 *
 * @package local
 * @subpackage dashboard
 * @copyright 2017 Mihail Pozarski <mipozarski@alumnos.uai.cl>
 * @copyright 2017 Danielle Alves <dalves@alumnos.uai.cl>
 * @copyright 2017 Hans Jeria <hansjeria@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

ob_start();
?>
<!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	    <meta http-equiv="Pragma" content="no-cache" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    
		<title>Dashboard</title>
	
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
		<!--  google chart loader -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script src="https://rawgit.com/kimmobrunfeldt/progressbar.js/1.0.0/dist/progressbar.js"></script>
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
	</head>
<body>
<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
// Require the locallib
require_once(dirname(__FILE__) . '/locallib.php');
//Require that the user is logged
require_login();
if (isguestuser()) {
	die();
}
// Generate all url used later on the html
$frontpageurl = new moodle_url('/local/dashboard/frontpage.php');
$usersurl = new moodle_url('/local/dashboard/users.php');
$resourcesurl = new moodle_url('/local/dashboard/resources.php');
$backtomoodleurl = new moodle_url('/my');
$logouturl = new moodle_url("/login/logout.php?");

// Initial time generation
$time = time();
$timenow =  gmdate("d-m-Y", $time);
$timemonthless = date('d-m-Y',strtotime($timenow . "-1 month"));
?>
<!-- TOP NAV -->
	<div class="navbar-fixed">
    	<nav class="grey lighten-2 blue-text">
        	<div class="nav-wraper">
        	<!-- Logo -->
            	<a href="<?php echo $frontpageurl; ?>" class="brand-logo"><img class="responsive-img" src="images/webcursoslogo.gif" style="width:60%"></a>
               	<ul id="nav-mobile" class="right hide-on-med-and-down">
               		<!-- Initial date daypicker -->
               		<li class="input-field">
               		    <i class="material-icons prefix blue-text">date_range</i>
        				<input type="date" class="datepicker" id="datepickerone" data-value="<?php echo $timemonthless;?>">
               		</li>
               		<!-- END date daypicker -->
               		<li class="input-field">
               			<i class="material-icons prefix blue-text">date_range</i>
        				<input type="date" class="datepicker" id="datepickertwo"  data-value="<?php echo $timenow;?>">
        			</li>
        			<!--  -->
               		<li class="input-field blue-text">
    					<select id="dispersionselect">
      						<option value="1"><span class="blue-text"><?php echo get_string('monthly', 'local_dashboard');?></span></option>
      						<option value="2"><span class="blue-text"><?php echo get_string('daily', 'local_dashboard');?></span></option>
      						<option value="3"><span class="blue-text"><?php echo get_string('hour', 'local_dashboard');?></span></option>

    					</select>
               		</li>
               		<!-- Future edit position, refesh page and help bottons -->
               		<li><a href="" class="disabled"><i class="material-icons grey-text">edit</i></a></li>
                    <li><a href="" class="disabled"><i class="material-icons grey-text">refresh</i></a></li>
                    <li><a href="" class="disabled"><i class="material-icons grey-text">help</i></a></li>
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
                                <li  class="red darken-4"><a class="white-text" href="<?php echo $logouturl;?>"><?php echo get_string('logout', 'local_dashboard'); ?></a></li>
                            </ul>
                    </div>
               </div>
            </div>
       </li>
       <!-- Sidenav structure -->
       	<li><a href="<?php echo $frontpageurl; ?>" class="white-text menu-item"><?php echo get_string('home', 'local_dashboard');?><i class="material-icons white-text">home</i></a></li>
        <li><a href="<?php echo $usersurl; ?>" class="white-text menu-item"><?php echo get_string('users', 'local_dashboard');?><i class="material-icons white-text">supervisor_account</i></a></li>
        <li><a href="<?php echo $resourcesurl; ?>" class="white-text menu-item"><?php echo get_string('resources', 'local_dashboard');?><i class="material-icons white-text">description</i></a></li>
		<li><a href="<?php echo $backtomoodleurl; ?>" class="white-text menu-item"><?php echo get_string('backhome', 'local_dashboard');?><i class="material-icons white-text">undo</i></a></li>	
	</ul>
<!-- SIDENAV -->

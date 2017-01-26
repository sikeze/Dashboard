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

include('header.php');
?>
<!-- CONTENT -->
	<main class="grey lighten-3">

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
        
    	<div class="row">
    		<div class="col s4" style="height:45px;">
        		<h5><b><?php echo get_string('userinfo','local_dashboard'); ?></b></h5>
        	</div>
        </div>

		<!-- Users sparklines chart-->
    	<div class="row">
        	<div id="userinfo" class="col s12" overflow:auto;></div>
		</div>
		
		<div class="row">
			<div class="col s12" style="height:20px;">
        		<h5><b><?php echo get_string('ubication','local_dashboard'); ?></b></h5>
        	</div>
		</div>
	<!-- Ubication charts -->
	<!--  <div class="row">
			<script src='https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js'></script>
			<div id="maps" class="col s12" overflow:auto;></div>
        </div>-->

	<!-- Facebok data -->
        <div class="row">
        </div>
	</main>
<!-- CONTENT -->
<!-- FOOTER -->
	<footer class="blue page-footer">
    	<div class="footer-copyright">
        	<div class="container">
            	<?php echo get_string('copyright','local_dashboard');?>
            	<a class="grey-text text-lighten-4 right" href="#!"><?php echo get_string('links','local_dashboard');?></a>
            </div>
     	</div>
	</footer>
<!-- FOOTER -->
</body>
<script>
	var users_labels = <?php echo json_encode(dashboard_usersinfolabels());?>;
	var dispersion = $('#dispersionselect :selected').val();
	var $datepickerone = $('#datepickerone');
	var $datepickertwo = $('#datepickertwo');
		$(document).ready(function () {
			var datepickerone = $datepickerone.val();
			var datepickertwo = $datepickertwo.val();
			$.ajax({
		        url: 'charts/usersinfo.php',
		        data: {'dispersion': dispersion, 'labels': users_labels},
		        method: 'POST',
		        success: function (output) {
		        	$( "#userinfo" ).html(output);
		        }
		  	});
			/*$.ajax({
		  		url: 'charts/ubicationmap.php',
		        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
		        method: 'POST',
		        success: function (output) {
		        	$( "#maps" ).html(output);
		        }
		  	});*/
			$('#dispersionselect').change(function () {
			  	var dispersion = $('#dispersionselect :selected').val();
			  	$.ajax({
		  	  	  	url: 'charts/usersinfo.php',
		  	        data: {'dispersion': dispersion, 'labels':users_labels},
		  	        method: 'POST',
		  	        success: function (output) {
		  	        	$( "#userinfo" ).html(output);
		  	        }
		  	  	});
			});
			//Get data on change of the end date datepicker
		    $datepickertwo.change(function () { 
		 	  var datepickerone = $datepickerone.val();
		 	  var datepickertwo = $datepickertwo.val();
		 	/* $.ajax({
			  		url: 'charts/ubicationmap.php',
			        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
			        method: 'POST',
			        success: function (output) {
			        	$( "#maps" ).html(output);
			        }
			  	});*/
		    });
		  //Get data on change of the initial date datepicker
		    $datepickerone.change(function () { 
		  	  var datepickerone = $datepickerone.val();
		  	  var datepickertwo = $datepickertwo.val();
		  	/*	$.ajax({
		  			url: 'charts/ubicationmap.php',
		        	data: {'initialdate': datepickerone, 'enddate': datepickertwo},
		        	method: 'POST',
		       	 	success: function (output) {
		        		$( "#maps" ).html(output);
		        	}
		  		});*/
		    });
	    });
</script>
</html>
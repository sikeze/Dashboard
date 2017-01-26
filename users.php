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
        		<h5><b><?php echo get_string('uservisit','local_dashboard');?></b></h5>
        	</div>
        	<div class="col s4" style="margin-top:20px;">
        		<b><?php echo get_string('options','local_dashboard');?>: </b><i class="material-icons prefix blue-text md-24">file_download</i>
        	</div>
        	<div class="input-field left-align col s4 blue-text">
        		<select id="dataselect">
      				<option value="1"><span class="blue-text"><?php echo get_string('sessions','local_dashboard');?></span></option>
      				<option value="2"><span class="blue-text"><?php echo get_string('avgtime','local_dashboard');?></span></option>
      				<option value="3"><span class="blue-text"><?php echo get_string('users','local_dashboard');?></span></option>
      				<option value="4"><span class="blue-text"><?php echo get_string('newusers','local_dashboard');?></span></option>
      				<option value="5"><span class="blue-text"><?php echo get_string('courses','local_dashboard');?></span></option>
    			</select>
    			<label><?php echo get_string('datasel','local_dashboard');?></label>
        	</div>
        </div>
        
	<!-- Users information chart-->
    	<div class="row">
        	<div id="userschart" class="col s12 l12 card white-text hoverable widget" overflow:auto;"></div>
		</div>

	<!-- Divider line -->
    	<div class="row">
       		<hr width=95% align="middle" style="height:2px; background-color:#757575; border:0;">
        </div>
        
	<!-- Users sparkline charts -->
    	<div class="row">
        	<div class="col s12" style="margin-left:15px;" overflow:auto;>
        		<b><?php echo get_string('options','local_dashboard');?>: </b><i class="material-icons prefix blue-text md-24">file_download</i>
        	</div>
        	<div id="userinfo" class="col s12" overflow:auto;></div>
        </div>
        
	<!-- Location text row-->
        <div class="row">
        	<div class="col s7">
        		<h5><b><?php echo get_string('ubication', 'local_dashboard'); ?></b></h5>
        	</div>
        	<div class="col s2" style="margin-top:20px;">
        		<b><?php echo get_string('options','local_dashboard');?>: </b><i class="material-icons prefix blue-text md-24">file_download</i>
        	</div>
        </div>
        
	<!-- Location Maps-->
	<script src='https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js'></script>
    	<div id="maps" class="row"></div> 
        
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
            	<?php echo get_string('copyright','local_dashboard');?>
            	<a class="grey-text text-lighten-4 right" href="#!"><?php echo get_string('links','local_dashboard');?></a>
            </div>
		</div>
	</footer>
<!-- FOOTER -->
</body>

<script>
//Send function tu userschart and fill the chart
var users_labels = <?php echo json_encode(dashboard_usersinfolabels());?>;

var $dispersionselect = $('#dispersionselect');
var $dataselect = $("#dataselect");
var $datepickerone = $('#datepickerone');
var $datepickertwo = $('#datepickertwo');

$(document).ready(function () {
	var datos  = $('#dataselect :selected').val();
	var dispersion = $('#dispersionselect :selected').val();
	var datepickerone = $datepickerone.val();
	var datepickertwo = $datepickertwo.val();
		$.ajax({
	  		url: 'charts/userschart.php',
	        data: {'select': datos, 'dispersion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#userschart" ).html(output);
	        }
	  	});
	  	$.ajax({
		  	url: 'charts/usersinfo.php',
		  	data: {'dispersion': dispersion, 'labels': users_labels},
		  	method: 'POST',
		  	success: function (output) {
			  	$("#userinfo").html(output);
		  	}
	  	});
	  	$.ajax({
	  		url: 'charts/locationtable.php',
	        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#locationtable" ).html(output);
	        }
	  	});
	  	$.ajax({
	  		url: 'charts/ubicationmap.php',
	        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#maps" ).html(output);
	        }
	  	});
    $('#dispersionselect').change(function () {
  	  var datos  = $('#dataselect :selected').val();
  	  var dispersion = $('#dispersionselect :selected').val();
  	  var datepickerone = $datepickerone.val();
	  var datepickertwo = $datepickertwo.val();
  	  	$.ajax({
  	  	  	url: 'charts/userschart.php',
  	        data: {'select': datos, 'dispersion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
  	        method: 'POST',
  	        success: function (output) {
  	        	$( "#userschart" ).html(output);
  	        }
  	  	});
  	  	$.ajax({
  	  	  	url: 'charts/usersinfo.php',
  	        data: {'dispersion': dispersion, 'labels':users_labels},
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
        	url: 'charts/userschart.php',
        	data: {'select': datos, 'dispersion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
        	method: 'POST',
        	success: function (output) {
            	$('#userschart').html(output);
        	}
  		});
    });
  //Get data on change of the end date datepicker
    $datepickertwo.change(function () { 
 	  var datos  = $('#dataselect :selected').val();
 	  var dispersion = $('#dispersionselect :selected').val();
 	  var datepickerone = $datepickerone.val();
 	  var datepickertwo = $datepickertwo.val();
 	  //Send ajax call to get the main chart
 	   	$.ajax({
 	       	url: 'charts/userschart.php',
 	       	data: {'select': datos, 'dispersion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
 	       	method: 'POST',
 	       	success: function (output) {
 	           	$('#userschart').html(output);
 	      }
 		});
	  	$.ajax({
	  		url: 'charts/locationtable.php',
	        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#locationtable" ).html(output);
	        }
	  	});
	  	$.ajax({
	  		url: 'charts/ubicationmap.php',
	        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#maps" ).html(output);
	        }
	  	});
 	});
  //Get data on change of the initial date datepicker
    $datepickerone.change(function () { 
  	  var datos  = $('#dataselect :selected').val();
  	  var dispersion = $('#dispersionselect :selected').val();
  	  var datepickerone = $datepickerone.val();
  	  var datepickertwo = $datepickertwo.val();
  	 //Send ajax call to get the main chart
  	   	$.ajax({
  	       	url: 'charts/userschart.php',
  	       	data: {'select': datos, 'dispersion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
  	       	method: 'POST',
  	       	success: function (output) {
  	           	$('#userschart').html(output);
  	      }
  		});
	  	$.ajax({
	  		url: 'charts/locationtable.php',
	        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#locationtable" ).html(output);
	        }
	  	});
	  	$.ajax({
	  		url: 'charts/ubicationmap.php',
	        data: {'initialdate': datepickerone, 'enddate': datepickertwo},
	        method: 'POST',
	        success: function (output) {
	        	$( "#maps" ).html(output);
	        }
	  	});
  	});
    //Redraw the chart on the window change
    $( window ).resize(function() {
 	   var datos  = $('#dataselect :selected').val();
   	  var dispersion = $('#dispersionselect :selected').val();
   	  var datepickerone = $datepickerone.val();
   	  var datepickertwo = $datepickertwo.val();
   	 //Send ajax call to get the main chart
   	   	$.ajax({
   	       	url: 'charts/userschart.php',
   	       	data: {'select': datos, 'dispersion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
   	       	method: 'POST',
   	       	success: function (output) {
   	           	$('#userschart').html(output);
   	      }
   	});
   });
});
</script>
</html>
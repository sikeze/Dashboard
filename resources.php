<?php 
require_once(dirname(__FILE__) . '/header.php');

?>
<html>
<body>
<!-- CONTENT -->
	<main class="grey lighten-3">
		
		<!-- Page tittle -->
    	<div class="row">
        	<div class="col s12">
        		<h5><?php echo get_string('resource_tittle', 'local_dashboard'); ?></h5>
        	</div>
        </div>
        
		<!-- Data selector -->
		<div class="row">
			<div class="input-field left-align col s4 blue-text">
        		<select id="dataselect">
        		<?php 
        		$modules = dashboard_getresourcemoduleid();
        		echo "<option value='0'><span class='blue-text'>".get_string('all', 'local_dashboard')."</span></option>";
    			foreach($modules as $module){
        			echo "<option value=".$module->id."><span class='blue-text'>".get_string("$module->name", 'local_dashboard')."</span></option>";
        		}
        		?>
    			</select>
    			<label>Selección de Datos</label>
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
<script src="js/uresources.js"></script>
<script>
$(document).ready(function () {

	 var $dispersionselect = $('#dispersionselect');
	 var $dataselect = $("#dataselect");
	 var $datepickerone = $('#datepickerone');
	 var $datepickertwo = $('#datepickertwo');

	 var datos  = $('#dataselect :selected').val();
 	  var dispersion = $('#dispersionselect :selected').val();
 	  var datepickerone = $datepickerone.val();
 	  var datepickertwo = $datepickertwo.val();
 	  	$.ajax({
 	  	  	url: 'changeresourceschart.php',
 	        data: {'select': datos, 'disperssion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
 	        method: 'POST',
 	        success: function (output) {
 	        	$( "#utimechart" ).html(output);
 	        }
 	  	});

    //Ajax call for the selected data
      $dispersionselect.change(function () {
  	  var datos  = $('#dataselect :selected').val();
  	  var dispersion = $('#dispersionselect :selected').val();
  	  var datepickerone = $datepickerone.val();
  	  var datepickertwo = $datepickertwo.val();
  	  	$.ajax({
  	  	  	url: 'changeresourceschart.php',
  	        data: {'select': datos, 'disperssion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
  	        method: 'POST',
  	        success: function (output) {
  	        	$( "#utimechart" ).html(output);
  	        }
  	  	});
    });
       $dataselect.change(function () { //Change div of charts
    	var datos  = $('#dataselect :selected').val();
    	var dispersion = $('#dispersionselect :selected').val();
    	var datepickerone = $datepickerone.val();
    	var datepickertwo = $datepickertwo.val();
    	$.ajax({
        	url: 'changeresourceschart.php',
        	data: {'select': datos, 'disperssion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
        	method: 'POST',
        	success: function (output) {
            	$('#utimechart').html(output);
        	}
  		});
    });
       $datepickertwo.change(function () { //Change div of charts
    	  var datos  = $('#dataselect :selected').val();
    	  var dispersion = $('#dispersionselect :selected').val();
    	  var datepickerone = $datepickerone.val();
    	  var datepickertwo = $datepickertwo.val();
    	   	$.ajax({
    	       	url: 'changeresourceschart.php',
    	       	data: {'select': datos, 'disperssion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
    	       	method: 'POST',
    	       	success: function (output) {
    	           	$('#utimechart').html(output);
    	      }
    	});
    });
       $datepickerone.change(function () { //Change div of charts
     	  var datos  = $('#dataselect :selected').val();
     	  var dispersion = $('#dispersionselect :selected').val();
     	  var datepickerone = $datepickerone.val();
     	  var datepickertwo = $datepickertwo.val();
     	   	$.ajax({
     	       	url: 'changeresourceschart.php',
     	       	data: {'select': datos, 'disperssion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
     	       	method: 'POST',
     	       	success: function (output) {
     	           	$('#utimechart').html(output);
     	      }
     	});
     });
     
    

    //load the facebook users chart
    $( "#facebookusers" ).load( "charts/fbusers.php" );
});
</script>
</html>
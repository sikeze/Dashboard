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
      				<option value="1"><span class="blue-text"><?php echo get_string('all', 'local_dashboard'); ?></span></option>assi<option value="2"><span class="blue-text"><?php echo get_string('assign', 'local_dashboard'); ?></span></option>
      				<option value="3"><span class="blue-text"><?php echo get_string('assignment', 'local_dashboard'); ?></span></option>
      				<option value="4"><span class="blue-text"><?php echo get_string('book', 'local_dashboard'); ?></span></option>
      				<option value="5"><span class="blue-text"><?php echo get_string('chat', 'local_dashboard'); ?></span></option>
      				<option value="6"><span class="blue-text"><?php echo get_string('choice', 'local_dashboard'); ?></span></option>
      				<option value="7"><span class="blue-text"><?php echo get_string('data', 'local_dashboard'); ?></span></option>
      				<option value="8"><span class="blue-text"><?php echo get_string('feedback', 'local_dashboard'); ?></span></option>
      				<option value="9"><span class="blue-text"><?php echo get_string('forum', 'local_dashboard'); ?></span></option>
      				<option value="10"><span class="blue-text"><?php echo get_string('glosary', 'local_dashboard'); ?></span></option>
      				<option value="11"><span class="blue-text"><?php echo get_string('imscp', 'local_dashboard'); ?></span></option>
      				<option value="12"><span class="blue-text"><?php echo get_string('lesson', 'local_dashboard'); ?></span></option>
      				<option value="13"><span class="blue-text"><?php echo get_string('lti', 'local_dashboard'); ?></span></option>
      				<option value="14"><span class="blue-text"><?php echo get_string('page', 'local_dashboard'); ?></span></option>
      				<option value="15"><span class="blue-text"><?php echo get_string('quiz', 'local_dashboard'); ?></span></option>
      				<option value="16"><span class="blue-text"><?php echo get_string('resource', 'local_dashboard'); ?></span></option>
      				<option value="17"><span class="blue-text"><?php echo get_string('scorm', 'local_dashboard'); ?></span></option>
      				<option value="18"><span class="blue-text"><?php echo get_string('survey', 'local_dashboard'); ?></span></option>
      				<option value="19"><span class="blue-text"><?php echo get_string('url', 'local_dashboard'); ?></span></option>
      				<option value="20"><span class="blue-text"><?php echo get_string('wiki', 'local_dashboard'); ?></span></option>
      				<option value="21"><span class="blue-text"><?php echo get_string('workshop', 'local_dashboard'); ?></span></option>
      				<option value="22"><span class="blue-text"><?php echo get_string('turnitin', 'local_dashboard'); ?></span></option>
      				<option value="23"><span class="blue-text"><?php echo get_string('emarking', 'local_dashboard'); ?></span></option>
      				<option value="24"><span class="blue-text"><?php echo get_string('papperattendance', 'local_dashboard'); ?></span></option>
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

<script src="js/utimechart.js"></script>
<script src="js/uresources.js"></script>
<script>
$(document).ready(function () {
    //Ajax call for the selected data
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
    //load the facebook users chart
    $( "#facebookusers" ).load( "charts/fbusers.php" );
});
</script>
</html>
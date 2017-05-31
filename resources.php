<?php 
// Include header.php to verifty session, get top and side var, with the dispersion select
// and the two datepickers
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
        		$modules = explode(',',$CFG->dashboard_resourcetypes);
        		list ( $sqlin, $parametros ) = $DB->get_in_or_equal ( $modules );
        		
        		$modulesdata = $DB->get_records_sql("SELECT * FROM {modules} WHERE name $sqlin",$parametros);
        		echo "<option value='0' modname='all'><span class='blue-text'>".get_string('all', 'local_dashboard')."</span></option>";
    			foreach($modulesdata as $module){
    				$name = get_string("$module->name", 'local_dashboard');
    				echo "<option value=".$module->id." modname=".$module->name."><span class='blue-text'>".$name."</span></option>";
    			}
				//paraver las weas var_dump($modulesdata);
        		//case for paperattendance
        		if(in_array('paperattendance',$modules)){
        			echo "<option value='paper' modname='paper'><span class='blue-text'>".get_string("papperattendance", 'local_dashboard')."</span></option>";
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
		<div class="row" id="dividerline">
       		<hr width=95% align="middle" color="black">
        </div>
        
        <!-- PaperAttendance Charts -->
        <div class="row hideresources" id="paper">
        </div>
        
        <!-- Turnitin Charts -->
        <div class="row hideresources" id="turnitin">
        </div>
        
        <!-- Resources and costs texts and charts-->
        <div class="row showallways">
        	<div class="col s6">
        		<div class="col s12">
        			<h5><b>Recursos v/s % de Utilización</b></h5>
        		</div>
        		<div id="uresources" class="col s12 card white-text hoverable widget" style="height:262px;" overflow:auto;></div>
        	</div>
        	<!-- Emarking cost center tittle-->
        	<div class="col s6">
        		<div class="col s12">
        			<h5><b>Costo eMarking</b></h5>
        		</div>
        		<div class = "col s12">
        		<!-- EMarking costs center buttons -->
        		<?php 
        		require_once($CFG->dirroot."/mod/emarking/lib.php");
        		require_once($CFG->dirroot."/mod/emarking/reports/locallib.php");
        		$categoryid = 1;
				echo " <a class='waves-effect waves-light btn'>".get_string('meanexamlength', 'emarking').": ".emarking_get_original_pages($categoryid)."</a>
						<a class='waves-effect waves-light btn'>".get_string('totalprintedpages', 'emarking'). ": " . emarking_get_total_pages($categoryid)."</a>
						<a class='waves-effect waves-light btn'>".get_string('totalprintingcost', 'emarking').": " . '$' .number_format(emarking_get_printing_cost($categoryid))."</a>
        		";
        	?>
        		</div>
        		<!--  Facebook users tittle -->
        		<div class="col s12">
        			<h5><b>Usuarios linkeados a Facebook</b></h5>
        		</div>
        		<!-- Facebook users chart -->
        		<div id="facebookusers" class="col s12 card hoverable widget" style="height:100px;">
        		</div>
        	</div>
        </div>
	</main>
<!-- CONTENT -->
<!-- FOOTER -->
	<footer class="blue page-footer">
    	<div class="footer-copyright">
        	<div class="container">
            </div>
       </div>
    </footer>
<!-- FOOTER -->
</body>
<script>
// Wait for the document to load
$(document).ready(function () {
	
	function getdata(){
		var id  = $('#dataselect :selected').val();
    	var datos  = $('#dataselect :selected').attr("modname");
    	var dispersion = $('#dispersionselect :selected').val();
    	var datepickerone = $('#datepickerone').val();
    	var datepickertwo = $('#datepickertwo').val();
    	var url = 'charts/changeresourceschart'+datos+'.php';
    	if (datos != "paper" && datos != "all"){	
    		url = 'charts/changeresourceschart.php';
		}
    	else{
    		url = 'charts/changeresourceschart'+datos+'.php';
        }
      	
    	 //Send ajax call to get the main chart
    	$.ajax({
        	url: url,
        	data: {'select': id, 'disperssion': dispersion, 'initialdate': datepickerone, 'enddate': datepickertwo},
        	method: 'POST',
        	success: function (output) {
            	$('#utimechart').html(output);
        	}
  		});
          
  }
	
    //Get data on change of the dispersion select
$("#dispersionselect, #dataselect, #datepickerone, #datepickertwo").on("change", function () {
	getdata();
});

//Redraw the chart on the window change
$( window ).resize(function() {
	getdata();
});	
    //load the facebook users chart
$( "#facebookusers" ).load( "charts/fbusers.php" );
getdata();
});
</script>
<script>
var $dataselect = $("#dataselect");
$dataselect.on('change', function () { //Change div of charts
	var datos  = $('#dataselect :selected').attr("modname");
		//console.log(datos);
		$('.hideresources').empty();
		$('#'+datos+'').show();
	if(datos == "paper" || datos == "emarking" || datos == "turnitin"){
		$('.showallways').hide();
	}
	else{
		$('.showallways').show();
	}
});
</script>
</html>
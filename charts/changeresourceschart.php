<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

//recibe the ajax data
$select = $_POST['select'];
$disperssion = $_POST['disperssion'];
$initialdate = $_POST['initialdate'];
$enddate = $_POST['enddate'];

//verify if there is a date selected
if($enddate == '' OR $initialdate == ''){
	$enddate = null;
	$initialdate = null;
}else{
	$initialdate = strtotime($initialdate);
	$enddate = strtotime($enddate);
}
// if select = 0 means that user want all the resources
if($select == '0'){
	global $DB, $CFG;

	$modules = explode(',',$CFG->dashboard_resourcetypes);
	list ( $sqlin, $parametros ) = $DB->get_in_or_equal ( $modules );
	
	$modulesdata = $DB->get_records_sql("SELECT * FROM {modules} WHERE name $sqlin",$parametros);
	echo "<script type='text/javascript'>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = new google.visualization.DataTable();
      					data.addColumn('string', 'Time of Day');
      					"; 
      					foreach($modulesdata as $module){
      					echo "data.addColumn('number', '$module->name');";
      					}

      					echo "data.addRows(".json_encode(dashboard_allresourcesdata($disperssion, $initialdate, $enddate)).")

    var options = {chartArea: {
			       			top: 28,
			       			height: '50%'
			    		},
      							hAxis: {
							title: '".get_string('date','local_dashboard')."',
						},
    };

    var chart = new google.visualization.AreaChart(document.getElementById('utimechart'));
    chart.draw(data, options);
  }
    </script>";
} elseif($select == 'paper'){
	//This is the info for paperattendance
	echo "
	<script>
	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawBasic);
		
	function drawBasic() {
	
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Time of Day');
	data.addColumn('number', 'paperattendance');
	
	data.addRows(".json_encode(dashboard_getpaperdata($disperssion, $initialdate, $enddate)).")
	
      					var options = {
        					chartArea: {
			       			top: 28,
			       			height: '50%'
			    		},
      							hAxis: {
							title: '".get_string('date','local_dashboard')."',
						},
									vAxis: {
							title: 'paperattendance'
						}
      					};
	
      					var chart = new google.visualization.AreaChart(
        				document.getElementById('utimechart'));
	
      					chart.draw(data, options);
			}
			</script>";
}else{
	//Get the specific selected resource data
	$dataname = $DB->get_record('modules', array('id'=>$select));
		echo "
				<script>
					google.charts.load('current', {packages: ['corechart', 'bar']});
					google.charts.setOnLoadCallback(drawBasic);
					
					function drawBasic() {

      					var data = new google.visualization.DataTable();
      					data.addColumn('string', 'Time of Day');
      					data.addColumn('number', '$dataname->name');

      					data.addRows(".json_encode(dashboard_resourcedata($select,$disperssion, $initialdate, $enddate)).")

      					var options = {
        					chartArea: {
			       			top: 28,
			       			height: '50%'
			    		},
      							hAxis: {
							title: '".get_string('date','local_dashboard')."',
						},
									vAxis: {
							title: '".$dataname->name."'
						}
      					};

      					var chart = new google.visualization.AreaChart(
        				document.getElementById('utimechart'));

      					chart.draw(data, options);
			}
			</script>";
	}
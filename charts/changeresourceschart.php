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
	
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
if($select == 0){
	global $DB;
	$modules = $DB->get_records('modules');
	echo "<script type='text/javascript'>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = new google.visualization.DataTable();
      					data.addColumn('string', 'Time of Day');
      					"; 
      					foreach($modules as $module){
      					echo "data.addColumn('number', '$module->name');";
      					}

      					echo "data.addRows(".json_encode(dashboard_allresourcesdata($disperssion, $initialdate, $enddate)).")

    var options = {
      title : 'Monthly Coffee Production by Country',
      vAxis: {title: 'Cups'},
      hAxis: {title: 'Month'},
    };

    var chart = new google.visualization.AreaChart(document.getElementById('utimechart'));
    chart.draw(data, options);
  }
    </script>";
}else{
	//Get the specific selected resource data
	$dataname = $DB->get_record('modules', array('id'=>$select));
	var_dump($dataname);
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
        					hAxis: {
          						title: 'Date',
        					},
        					vAxis: {
        					}
      					};

      					var chart = new google.visualization.AreaChart(
        				document.getElementById('utimechart'));

      					chart.draw(data, options);
			}
			</script>";
	}
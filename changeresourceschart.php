<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/locallib.php');
$select = $_POST['select'];
$disperssion = $_POST['disperssion'];

dashboard_resourcedata(9,1);
if($select == 'all'){
	echo "hola";
}else{
		echo "
				<script>
					google.charts.load('current', {packages: ['corechart', 'bar']});
					google.charts.setOnLoadCallback(drawBasic);
					
					function drawBasic() {

      					var data = new google.visualization.DataTable();
      					data.addColumn('string', 'Time of Day');
      					data.addColumn('number', 'Motivation Level');

      					data.addRows(".json_encode(dashboard_resourcedata($select,$disperssion)).")

      					var options = {
        					title: 'Motivation Level Throughout the Day',
        					hAxis: {
          						title: 'Time of Day',
        					},
        					vAxis: {
        					}
      					};

      					var chart = new google.visualization.ColumnChart(
        				document.getElementById('utimechart'));

      					chart.draw(data, options);
			}
			</script>";
	}
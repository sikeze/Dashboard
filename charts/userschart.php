<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');
$select = $_POST['select'];
$dispersion = $_POST['dispersion'];
$initialdate = $_POST['initialdate'];
$enddate = $_POST['enddate'];
if($enddate == '' OR $initialdate == ''){
	$enddate = null;
	$initialdate = null;
}else{
	$initialdate = strtotime($initialdate);
	$enddate = strtotime($enddate);
}

if ($select == 1) {
	$dataname = get_string('sessions','local_dashboard');
	$vaxisname = get_string('sessions','local_dashboard');
} elseif ($select == 2) {
	$dataname = get_string('avgtime','local_dashboard');
	$vaxisname = get_string('seconds','local_dashboard');
} elseif ($select == 3) {
	$dataname = get_string('users','local_dashboard');
	$vaxisname = get_string('users','local_dashboard');
} elseif ($select == 4) {
	$dataname = get_string('newusers','local_dashboard');
	$vaxisname = get_string('users','local_dashboard');
} else {
	$dataname = get_string('courses','local_dashboard');
	$vaxisname = get_string('courses','local_dashboard');
}

echo "<script>
		$(document).ready(function () {
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);
				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', '".$vaxisname."');
					var datos =".json_encode(dashboard_userschart($select,$dispersion,$initialdate,$enddate))."
					console.log(datos[0]);
					console.log('El largo es: ' + datos.length);
					
					data.addRows(datos);
					var options = {
						chartArea: {
			       			top: 28,
			       			height: '50%'
			    		},
						hAxis: {
							title: '".get_string('date','local_dashboard')."',
							textStyle: {fontSize: 9}
						},
						vAxis: {
							title: '".$dataname."'
						}
					};
					var chart = new google.visualization.LineChart(document.getElementById('userschart'));
					chart.draw(data, options);
				}
		});
			</script>";

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
switch ($select) {
	case 1:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);
				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', '".get_string('sessions','local_dashboard')."');
					var datos =".json_encode(users_sessions_dispersion($dispersion))."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});
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
							title: '".get_string('sessions','local_dashboard')."'
						}
					};
					var chart = new google.visualization.LineChart(document.getElementById('userschart'));
					chart.draw(data, options);
				}
			</script>";
	break;
	case 2:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);
				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', '".get_string('seconds','local_dashboard')."');
					var datos =".json_encode(users_avgsessions_dispersion($dispersion))."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});
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
							title: '".get_string('avgtime','local_dashboard')."'
						}
					};
					var chart = new google.visualization.LineChart(document.getElementById('userschart'));
					chart.draw(data, options);
				}
			</script>";
	break;
	case 3:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);
				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', '".get_string('users','local_dashboard')."');
					var datos =".json_encode(users_dates_dispersion($dispersion))."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});
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
							title: '".get_string('users','local_dashboard')."'
						}
					};
					var chart = new google.visualization.LineChart(document.getElementById('userschart'));
					chart.draw(data, options);
				}
			</script>";
	break;
	case 4:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);
				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', '".get_string('users','local_dashboard')."');
					var datos =".json_encode(newusers_dates_dispersion($dispersion))."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});
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
							title: '".get_string('newusers','local_dashboard')."'
						}
					};
					var chart = new google.visualization.LineChart(document.getElementById('userschart'));
					chart.draw(data, options);
				}
			</script>";
	break;
	case 5:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);
				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', '".get_string('courses','local_dashboard')."');
					var datos =".json_encode(courseview_dates_dispersion($dispersion))."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});
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
							title: '".get_string('courses','local_dashboard')."'
						}
					};
					var chart = new google.visualization.LineChart(document.getElementById('userschart'));
					chart.draw(data, options);
				}
			</script>";
	break;
}
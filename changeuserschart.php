<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

$select = $_POST['select'];
$users_sessions  = $_POST['sessions'];
$users_avgtime  = $_POST['avgtime'];
$users_dates  = $_POST['users'];
$newusers_dates  = $_POST['newusers'];
$courseviews_dates  = $_POST['courseviews'];
switch ($select) {
	case 0:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);

				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', 'Sesiones');

					var datos =".json_encode($users_sessions)."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});

					data.addRows(datos);

					var options = {
						hAxis: {
							title: 'Fecha',
							textStyle: {fontSize: 9}
						},
						vAxis: {
							title: 'Sesiones'
						}
					};

					var chart = new google.visualization.LineChart(document.getElementById('userschart'));

					chart.draw(data, options);
				}
			</script>";
	break;
	
	case 1:
		echo "<script>
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawBasic);

				function drawBasic() {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'X');
					data.addColumn('number', 'Segundos');

					var datos =".json_encode($users_avgtime)."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});

					data.addRows(datos);

					var options = {
						hAxis: {
							title: 'Fecha',
							textStyle: {fontSize: 9}
						},
						vAxis: {
							title: 'Tiempo Promedio Sesión'
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
					data.addColumn('number', 'Alumnos');
	
					var datos =".json_encode($users_dates)."
					$.each(datos, function( key, value ) {
		  				value[1]= parseFloat(value[1]);
					});
	
					data.addRows(datos);
	
					var options = {
						hAxis: {
							title: 'Fecha',
							textStyle: {fontSize: 9}
						},
						vAxis: {
							title: 'Usuarios'
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
				data.addColumn('number', 'Alumnos');
		
				var datos =".json_encode($newusers_dates)."
				$.each(datos, function( key, value ) {
		 			value[1]= parseFloat(value[1]);
				});
		
				data.addRows(datos);
		
				var options = {
					hAxis: {
						title: 'Fecha',
						textStyle: {fontSize: 9}
					},
					vAxis: {
						title: 'Nuevos Usuarios'
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
				data.addColumn('number', 'Cursos');
		
				var datos =".json_encode($courseviews_dates)."
				$.each(datos, function( key, value ) {
	  				value[1]= parseFloat(value[1]);
				});
		
				data.addRows(datos);
		
				var options = {
					hAxis: {
						title: 'Fecha',
						textStyle: {fontSize: 9}
					},
					vAxis: {
						title: 'Cursos Vistos'
					}
				};
		
				var chart = new google.visualization.LineChart(document.getElementById('userschart'));
		
				chart.draw(data, options);
			}
		</script>";
	break;
}
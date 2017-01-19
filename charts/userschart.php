<?php 
$users_sessions = $_POST['sessions'];
?>
<script>
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'X');
	data.addColumn('number', 'Sesiones');

	var datos =  <?php echo json_encode($users_sessions);?>;
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
</script>

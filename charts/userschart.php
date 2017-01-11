<?php
$userschart  = $_POST['param']; 
?>
<script>
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'X');
	data.addColumn('number', 'Sesiones');

	var datos =  <?php echo json_encode($userschart);?>;
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
</script>

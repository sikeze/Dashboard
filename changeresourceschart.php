<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

$select = $_POST['select'];

switch ($select) {
	case 0:
		echo "
			<script>
      			google.charts.load('current', {'packages':['corechart']});
      			google.charts.setOnLoadCallback(drawVisualization);

			 	function drawVisualization() {
        			// Some raw data (not necessarily accurate)
        			var data = google.visualization.arrayToDataTable([
         				['Month', 'Turnitin', 'Paper Attendance', 'PDF', 'Tareas', 'eMarking', 'Average'],
         				['2004/05',  165,      938,         522,             998,           450,      614.6],
         				['2005/06',  135,      1120,        599,             1268,          288,      682],
         				['2006/07',  157,      1167,        587,             807,           397,      623],
         				['2007/08',  139,      1110,        615,             968,           215,      609.4],
         				['2008/09',  136,      691,         629,             1026,          366,      569.6]
      				]);

    				var options = {
      					title : 'Monthly Coffee Production by Country',
      					vAxis: {title: 'Cups'},
      					hAxis: {title: 'Month'},
      					seriesType: 'bars',
      					series: {5: {type: 'line'}}
    				};

    				var chart = new google.visualization.ComboChart(document.getElementById('utimechart'));
    				chart.draw(data, options);
 	 			}
			</script>";
		break;
	
	case 1:
		echo "
				<script>
					google.charts.load('current', {packages: ['corechart', 'bar']});
					google.charts.setOnLoadCallback(drawBasic);
					
					function drawBasic() {

      					var data = new google.visualization.DataTable();
      					data.addColumn('timeofday', 'Time of Day');
      					data.addColumn('number', 'Motivation Level');

      					data.addRows([
        					[{v: [8, 0, 0], f: '8 am'}, 1],
        					[{v: [9, 0, 0], f: '9 am'}, 2],
        					[{v: [10, 0, 0], f:'10 am'}, 3],
        					[{v: [11, 0, 0], f: '11 am'}, 4],
        					[{v: [12, 0, 0], f: '12 pm'}, 5],
       		 				[{v: [13, 0, 0], f: '1 pm'}, 6],
        					[{v: [14, 0, 0], f: '2 pm'}, 7],
        					[{v: [15, 0, 0], f: '3 pm'}, 8],
        					[{v: [16, 0, 0], f: '4 pm'}, 9],
        					[{v: [17, 0, 0], f: '5 pm'}, 10],
      					]);

      					var options = {
        					title: 'Motivation Level Throughout the Day',
        					hAxis: {
          						title: 'Time of Day',
          						format: 'h:mm a',
          						viewWindow: {
           	 					min: [7, 30, 0],
            					max: [17, 30, 0]
          						}
        					},
        					vAxis: {
          						title: 'Rating (scale of 1-10)'
        					}
      					};

      					var chart = new google.visualization.ColumnChart(
        				document.getElementById('utimechart'));

      					chart.draw(data, options);
			}
			</script>";
		break;
}
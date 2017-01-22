<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/locallib.php');
$select = $_POST['select'];
$disperssion = $_POST['disperssion'];
$initialdate = $_POST['initialdate'];
$enddate = $_POST['enddate'];

if($enddate == '' OR $initialdate == ''){
	$enddate = null;
	$initialdate = null;
}else{
	$initialdate = strtotime($initialdate);
	$enddate = strtotime($enddate);
}
if($select == 0){
	echo "<script type='text/javascript'>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);


      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
         ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
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
}else{
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

      					var chart = new google.visualization.ColumnChart(
        				document.getElementById('utimechart'));

      					chart.draw(data, options);
			}
			</script>";
	}
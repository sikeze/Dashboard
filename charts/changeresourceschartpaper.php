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

?>
<script>
	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawBasic);
		
	function drawBasic() {
	
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Time of Day');
	data.addColumn('number', 'paperattendance');

	data.addRows(<?php echo json_encode(dashboard_getpaperdata($disperssion, $initialdate, $enddate));?>)
	
      					var options = {
        					chartArea: {
			       			top: 28,
			       			height: '50%'
			    		},
      							hAxis: {
							title: '<?php echo get_string('date','local_dashboard');?>',
						},
									vAxis: {
							title: 'paperattendance'
						}
      					};
	
      					var chart = new google.visualization.AreaChart(
        				document.getElementById('utimechart'));
	
      					chart.draw(data, options);
			}

	$('#paper').html('<div id="chartdos"></div><div id="charttres"></div>')
	//$('#dividerline').after('<div><p>hola</p></div>')
	var chart = "<div class='row'><div id='testchart' class='col s12 card white-text hoverable widget' style='height:270px;'></div></div>";
	$('#chartdos').html(chart);

	var charttres = "<div class='row'><div id='testchart' class='col s12 card white-text hoverable widget' style='height:270px;'></div></div>";
	$('#charttres').html(charttres);
	
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

    var chart = new google.visualization.ComboChart(document.getElementById('testchart'));
    chart.draw(data, options);

    }	

  
			
</script>
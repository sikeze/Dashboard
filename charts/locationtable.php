<?php 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

$initialdate = $_POST['initialdate'];
$enddate = $_POST['enddate'];
if($enddate == '' OR $initialdate == ''){
	$enddate = null;
	$initialdate = null;
}else{
	$initialdate = strtotime($initialdate);
	$enddate = strtotime($enddate);
}

$location_table = dashboard_locationtable($initialdate,$enddate);

echo "<table class='striped bordered responsive-table'>
	<thead>
    	<tr>
       		<th data-field='regions'>".get_string('cities','local_dashboard')."</th>
        	<th data-field='sessions'>".get_string('users','local_dashboard')."</th>
        </tr>
   	</thead>

    <tbody>";
foreach($location_table as $location){
	if ($location->city == "" || $location->city == NULL) {
		$location->city = get_string('nolocation','local_dashboard');
	}
	echo " <tr>
	<td> $location->city</td>
	<td> $location->usersid</td>
	</tr>";
}
echo "
  	</tbody>
</table>";
?>
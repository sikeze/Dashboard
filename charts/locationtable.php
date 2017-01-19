<?php 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

$location_table = location_table();

echo "<table class='striped bordered responsive-table'>
	<thead>
    	<tr>
       		<th data-field='regions'>Regions</th>
        	<th data-field='sessions'>Sesiones</th>
        </tr>
   	</thead>
        			
    <tbody>";
		foreach($location_table as $location){
			echo " <tr>
			<td> $location->region</td>
			<td> $location->usersid</td>
        </tr>";
		}
		echo "       
  	</tbody>
</table>";
?>
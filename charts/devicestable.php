<?php
$labels = $_POST['labels'];
$devices_info = $_POST['system'];
echo "<body>
<table class='striped bordered responsive-table'>		
    <tbody>
    	<tr>
    		<th data-field='opsystem'>Sistema Operativo</th>
    		<td></td>
    		<td>Windows</td>
        	<td>Linux</td>
        	<td>Macintosh</td>
        	<td>iOS</td>
        	<td>Android</td>
        </tr>
        <tr>
        	<th data-field='sessions'>Sesiones</th>
        	<td><font size='3'>".$labels[0]."</font><br><font size='2'>% del total: 100%</font></td>";
        	//Devices_info array always will get 2 data: sessions (index 0) and the porcentage (index 1)
        	//The one that changes determinate the operative system such as windows, linux, etc.
        	for ($i=0;$i<count($devices_info);$i++) {
        		echo "<td><font size='3'>".$devices_info[$i][0]."</font><font size='2'> (".$devices_info[$i][1]."%)</font></td>";
        	}   		
        echo "</tr>
  	</tbody>
</table>
</body>";
?>
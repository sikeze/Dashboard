<?php 
$usersinfo  = $_POST['users'];
$labels = $_POST['labels'];
?>

<div class="col s12">
<div class="col s4 l4 card hoverable widget" overflow: auto;>
Sesiones<br>
<?php echo $labels[0];?>
<div id="sessions"  overflow: auto;></div>
</div>
<div class="col s4 l4 card hoverable widget" overflow: auto;>
Avg Session Duration <br>
<?php echo $labels[1];?>
<div id="sessionduration"  overflow: auto;></div>
</div>
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
New Users <br>
<?php echo $labels[2];?>
<div id="newusers"  overflow: auto;></div>
</div>
</div>

<div class="col s12">
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
Usuarios<br>
<?php echo $labels[3];?>
<div id="users"  overflow: auto;></div>
</div>
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
Course views<br>
<?php echo $labels[4];?>
<div id="courseviews"  overflow: auto;></div>
</div>
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
Course/session <br>
<?php echo $labels[5];?>
<div id="coursesession"  overflow: auto;></div>
</div>
</div>


<script>
var datos =  <?php echo json_encode($usersinfo);?>;
$(document).ready(function () {
	$("#sessions").sparkline(datos[0], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: true});
	$("#users").sparkline(datos[2], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$("#courseviews").sparkline(datos[3], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$("#coursesession").sparkline(datos[4], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$("#sessionduration").sparkline(datos[1], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$("#newusers").sparkline(datos[5], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
});
	</script>
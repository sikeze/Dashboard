<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');

$dispersion = $_POST['dispersion'];
$labels = $_POST['labels'];
?>
<!-- Labels array index represent the info that we need for every sparkline -->
<div class="col s12">
<div class="col s4 l4 card hoverable widget" overflow: auto;>
<?php echo get_string('sessions','local_dashboard')."<br>".$labels[0];?>
<div id="sessions"  overflow: auto;></div>
</div>
<div class="col s4 l4 card hoverable widget" overflow: auto;>
<?php echo get_string('avgtime','local_dashboard')."<br>".gmdate("H:i:s",$labels[1]);?> <!-- No toma más de 86400 segundos -->
<div id="sessionduration"  overflow: auto;></div>
</div>
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
<?php echo get_string('newusers','local_dashboard')."<br>".$labels[2];?>
<div id="newusers"  overflow: auto;></div>
</div>
</div>

<div class="col s12">
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
<?php echo get_string('users','local_dashboard')."<br>".$labels[3];?>
<div id="users"  overflow: auto;></div>
</div>
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
<?php echo get_string('courses','local_dashboard')."<br>".$labels[4];?>
<div id="courseviews"  overflow: auto;></div>
</div>
<div class ="col s4 l4 card hoverable widget" overflow:auto;>
<?php echo get_string('coursesession','local_dashboard')."<br>".$labels[5];?>
<div id="coursesession"  overflow: auto;></div>
</div>
</div>

<?php 
echo "
<script>
var data_dispersion =".json_encode(users_info_dispersion($dispersion))."
//Data array index represent if the info we need is for sessions or courseviews, etc.
$(document).ready(function () {
	$('#sessions').sparkline(data_dispersion[0], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: true});
	$('#users').sparkline(data_dispersion[2], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$('#courseviews').sparkline(data_dispersion[3], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$('#coursesession').sparkline(data_dispersion[4], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$('#sessionduration').sparkline(data_dispersion[1], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
	$('#newusers').sparkline(data_dispersion[5], {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: false});
});
</script>";
?>
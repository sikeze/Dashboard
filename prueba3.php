<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery.sparkline.js"></script>
<?php 
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/locallib.php');

$usersinfo_disperssion = $_POST['disperssion_info'];
highlight_string("<?php\n\$data =\n" . var_export($usersinfo_disperssion, true) . ";\n?>");
?>

<div class="col s12">
<div class="col s4 l4 card hoverable widget" overflow: auto;>
Sesiones<br>
<div id="sessions"  overflow: auto;></div>
</div>
</div>

<script>
var data_disperssion = <?php echo json_encode($usersinfo_disperssion);?>;

//alert(data_disperssion);
//Data array index represent if the info we need is for sessions or courseviews, etc.
$(document).ready(function () {

	$("#sessions").sparkline(data_disperssion, {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: true});
});
</script>

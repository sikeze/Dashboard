<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery.sparkline.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<?php 
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/locallib.php');

$disperssion = $_POST['disperssion'];

echo "
<script>
$(document).ready(function () {
	$('#usersinfo').sparkline(".json_encode(users_info_disperssion($disperssion)).", {
		type: 'line',
		tooltipFormat: null,
		drawNormalOnTop: true});
});
</script>
";
?>
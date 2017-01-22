<?php 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');
echo "
<div class='row'>
	<div class='col s2'>
		<img class='responsive-img' src='images/fblogo.png'>
	</div>
	<div id='fbusers' class='col s10'><h5>".dashboard_getfacebookusers()."%</h5></div>
</div>

<script>
var bar = new ProgressBar.Line(fbusers, {
	  strokeWidth: 5,
	  easing: 'easeInOut',
	  duration: 1400,
	  color: '#0698ec',
	  trailColor: '#ABA8A8',
	  trailWidth: 5,
	  svgStyle: {width: '100%'},
	  text: {
	    style: {
	      // Text color.
	      // Default: same as stroke color (options.color)
	      color: '#000',
	      position: 'absolute',
	      right: '0',
	      top: '30px',
	      padding: 0,
	      margin: 0,
	      transform: null
	    },
	    autoStyleContainer: false
	  },

	});

	bar.animate(".dashboard_getfacebookusers().");  // Number from 0.0 to 1.0
</script>";
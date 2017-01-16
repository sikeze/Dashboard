<style>
#fbusers {
  margin-left:60px;
  width: 400px;
  height: 8px;
  position: relative;
}
</style>

<div class="row">
	<div class="col s2">
		<img class="responsive-img" src="images/fblogo.png" style="margin-top:25px;width:100%;">
	</div>
	<div id="fbusers" class="col s10" style="margin-top:-40px;"></div>
</div>

<script>
var bar = new ProgressBar.Line(fbusers, {
	  strokeWidth: 4,
	  easing: 'easeInOut',
	  duration: 1400,
	  color: '#0698ec',
	  trailColor: '#ABA8A8',
	  trailWidth: 3,
	  svgStyle: {width: '100%', height: '200%'},
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
		step: (state, bar) => {
	    bar.setText(Math.round(bar.value() * 100) + ' %');
	  }
	});

	bar.animate(0.64);  // Number from 0.0 to 1.0
</script>
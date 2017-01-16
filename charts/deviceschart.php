<style>
#containerone {
  width: 33%;
  position: relative;
}
#containertwo {
  width: 33%;
  position: relative;
}
#containerthree {
  width: 33%;
  position: relative;
}
</style>
<div class="row">
	<font size="4">Sesiones según tipo de equipo</font>
	<hr width=100% align="left" color="grey">
</div>
<div class="row">
	<div class="col s4"><b><font size="4">Desktop</font></b></div>
	<div class="col s4"><b><font size="4">Móvil</font></b></div>
	<div class="col s4"><b><font size="4">Tablet</font></b></div>
</div>
<div class="row">
	<div id="containerone" class="col s4"></div>
	<div id="containertwo" class="col s4"></div>
	<div id="containerthree" class="col s4"></div>
</div>

<script>
var barone = new ProgressBar.Circle(containerone, {
		  strokeWidth: 6,
		  easing: 'easeInOut',
		  duration: 1400,
		  color: '#18A0C2',
		  trailColor: '#ABA8A8',
		  trailWidth: 4,
		  svgStyle: null,
		  text: {
			    autoStyleContainer: false
			  },
		  from: { color: '#18A0C2', width: 6 },
		  to: { color: '#18A0C2', width: 6 },
		  step: function(state, circle) {
			    circle.path.setAttribute('stroke', state.color);
			    circle.path.setAttribute('stroke-width', state.width);

			    var value = Math.round(circle.value() * 100);
			    if (value === 0) {
			      circle.setText('');
			    } else {
			      circle.setText(value + '%');
			    }

			  }
		});
var bartwo = new ProgressBar.Circle(containertwo, {
	  strokeWidth: 6,
	  easing: 'easeInOut',
	  duration: 1400,
	  color: '#62C218',
	  trailColor: '#ABA8A8',
	  trailWidth: 4,
	  svgStyle: null,
	  text: {
		    autoStyleContainer: false
		  },
	  from: { color: '#62C218', width: 6 },
	  to: { color: '#62C218', width: 6 },
	  step: function(state, circle) {
		    circle.path.setAttribute('stroke', state.color);
		    circle.path.setAttribute('stroke-width', state.width);

		    var value = Math.round(circle.value() * 100);
		    if (value === 0) {
		      circle.setText('');
		    } else {
		      circle.setText(value + '%');
		    }

		  }
});

var barthree = new ProgressBar.Circle(containerthree, {
	  strokeWidth: 6,
	  easing: 'easeInOut',
	  duration: 1400,
	  color: '#F7880A',
	  trailColor: '#ABA8A8',
	  trailWidth: 4,
	  svgStyle: null,
	  text: {
		    autoStyleContainer: false
		  },
	  from: { color: '#F7880A', width: 6 },
	  to: { color: '#F7880A', width: 6 },
	  step: function(state, circle) {
		    circle.path.setAttribute('stroke', state.color);
		    circle.path.setAttribute('stroke-width', state.width);

		    var value = Math.round(circle.value() * 100);
		    if (value === 0) {
		      circle.setText('');
		    } else {
		      circle.setText(value + '%');
		    }

		  }
	});
		barone.text.style.fontSize = '1.5rem';
		barone.animate(0.33);  // Number from 0.0 to 1.0
		bartwo.text.style.fontSize = '1.5rem';
		bartwo.animate(0.54);  // Number from 0.0 to 1.0
		barthree.text.style.fontSize = '1.5rem';
		barthree.animate(0.13);  // Number from 0.0 to 1.0
</script>

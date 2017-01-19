<div class="col s12">
	<div class="col s6 l6 card hoverable widget" overflow: auto;>
		Sesiones<br>
		13
		<div id="sessions"  overflow: auto;></div>
	</div>
	<div class ="col s6 l6 card hoverable widget" overflow:auto;>
		Usuarios<br>
		399
		<div id="users"  overflow: auto;></div>
	</div>
</div>

<div class="col s12">
	<div class="col s6 l6 card hoverable widget" overflow: auto;>
		Avg Session Duration<br>
		00:08:16
		<div id="sessionduration"  overflow: auto;></div>
	</div>
	<div class ="col s6 l6 card hoverable widget" overflow:auto;>
		% New Sessions <br>
		10,11%
	<div id="newsessions"  overflow: auto;></div>
	</div>
</div>

<div class="col s12">
	<div class ="col s6 l6 card hoverable widget" overflow:auto;>
		Pageviews<br>
		8.233
	<div id="pageviews"  overflow: auto;></div>
	</div>
	<div class ="col s6 l6 card hoverable widget" overflow:auto;>
		Pages/session<br>
		2,76
		<div id="pagesession"  overflow: auto;></div>
	</div>
</div>

<script>
$(document).ready(function () {
$("#sessions").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    type: 'line',
    drawNormalOnTop: false});
$("#users").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    type: 'line',
    drawNormalOnTop: false});
$("#pageviews").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    type: 'line',
    drawNormalOnTop: false});
$("#pagesession").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    type: 'line',
    drawNormalOnTop: false});
$("#sessionduration").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    type: 'line',
    drawNormalOnTop: false});
$("#newsessions").sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    type: 'line',
    drawNormalOnTop: false});
});
</script>
<?php 
require_once(dirname(__FILE__) . '/header.php');
?>
<html>
<body>
<!-- CONTENT -->
	<main class="grey lighten-3">

	<!-- Text row -->
		<div class="row">
        	<div class="col s6" style="height:15px;">
        		<h5><b><?php echo get_string('turnitin','local_dashboard'); ?></b></h5>
        	</div>
        	<div class="col s6" style="height:15px;">
        		<h5><b><?php echo get_string('courseresources','local_dashboard'); ?></b></h5>
        	</div>
        </div>
        
	<!-- Turnitin and course resources charts -->
    	<div class="row">
        	<div id="turnitinchart" class="col s6 l6 card white-text hoverable widget" overflow:auto;></div>
            <div id="resourcebarchart" class="col s6 l6 card white-text hoverable widget" style="width:50%;" overflow: auto;></div>
        </div>
        
	<!-- Ubication, devices and user information text -->
    	<div class="row">
        	<div class="col s8" style="height:20px;">
        		<h5><b><?php echo get_string('ubication','local_dashboard'); ?></b></h5>
        	</div>

        	<div class="col s4" style="height:45px;">
        		<h5><b><?php echo get_string('userinfo','local_dashboard'); ?></b></h5>
        	</div>
        </div>

	<!-- Ubication, devices and user information charts -->
		<div class="row">
			<div id="ubicationmap" class="col s8" overflow:auto;></div>
			<div id="userinfo" class="col s4" overflow:auto;></div>
        </div>

	<!-- Facebok data -->
        <div class="row">
        </div>
	</main>
<!-- CONTENT -->
<!-- FOOTER -->
	<footer class="blue page-footer">
    	<div class="footer-copyright">
        	<div class="container">
            	© 2017 Copyright Text
            	<a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
     	</div>
	</footer>
<!-- FOOTER -->
</body>
<script src="js/turnitinchart.js"></script>
<script src="js/resourcebarchart.js"></script>
<script>
	$(document).ready(function () {
    	$( "#userinfo" ).load( "charts/userfrontinfo.php" );
        $( "#ubicationmap" ).load( "charts/ubicationmap.php" );
    });
</script>
</html>
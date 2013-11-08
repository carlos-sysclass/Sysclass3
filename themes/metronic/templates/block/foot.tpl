<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->   
<!--[if lt IE 9]>
<script src="assets/metronic/plugins/respond.min.js"></script>
<script src="assets/metronic/plugins/excanvas.min.js"></script> 
<![endif]-->
{foreach item="script" from=$T_SCRIPTS}
	<script src="{Plico_GetResource file=$script}"></script>
{/foreach}
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/metronic/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>	
<script src="assets/metronic/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/metronic/plugins/select2/select2.min.js"></script>     
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/metronic/scripts/app.js" type="text/javascript"></script>
<script src="assets/metronic/scripts/login-soft.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function() {     
	  App.init();
	  Login.init();
	});
</script>
<!-- END JAVASCRIPTS -->
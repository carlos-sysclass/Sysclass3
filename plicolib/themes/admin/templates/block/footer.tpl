	<!-- JQueryUI v1.9.2 -->
	<script src="{Plico_GetResource file='theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js'}"></script>
	
	<!-- JQueryUI Touch Punch -->
	<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
	<script src="{Plico_GetResource file='theme/scripts/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js'}"></script>
	
	<!-- MiniColors -->
	<!--
	<script src="{Plico_GetResource file='theme/scripts/jquery-miniColors/jquery.miniColors.js'}"></script>
	-->
	<!-- Themer -->
	<!--
	<script>
	var themerPrimaryColor = '#47759e';
	</script>
	-->
	<script src="{Plico_GetResource file='theme/scripts/jquery.cookie.js'}"></script>
	<!--
	<script src="{Plico_GetResource file='theme/scripts/themer.js'}"></script>
	-->
	<!-- Resize Script -->
	<script src="{Plico_GetResource file='theme/scripts/jquery.ba-resize.js'}"></script>
	
	<!-- Uniform -->
	<script src="{Plico_GetResource file='theme/scripts/pixelmatrix-uniform/jquery.uniform.min.js'}"></script>
	
	<!-- Bootstrap Script -->
	<script src="{Plico_GetResource file='bootstrap/js/bootstrap.min.js'}"></script>
	
	<!-- Bootstrap Extended -->
	
	<script src="{Plico_GetResource file='bootstrap/extend/bootstrap-select/bootstrap-select.js'}"></script>
	<script src="{Plico_GetResource file='bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js'}"></script>
	<!--
	<script src="{Plico_GetResource file='bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js'}"></script>
	<script src="{Plico_GetResource file='bootstrap/extend/jasny-bootstrap/js/jasny-bootstrap.min.js'}" type="text/javascript"></script>
	-->
	<!--
	<script src="{Plico_GetResource file='bootstrap/extend/jasny-bootstrap/js/bootstrap-fileupload.js'}" type="text/javascript"></script>
	-->
	<script src="{Plico_GetResource file='bootstrap/extend/bootbox.js'}" type="text/javascript"></script>

	<!-- Custom Onload Script -->
	<script src="{Plico_GetResource file='theme/scripts/load.js'}"></script>



	
	<!-- google-code-prettify -->
	<!--
	<script src="{Plico_GetResource file='theme/scripts/google-code-prettify/prettify.js'}"></script>
	<script>
		$(function(){
			if ($('.prettyprint').length)
				prettyPrint();
		});
	</script>
	-->
	<script src="{Plico_GetResource file='js/startup.js'}"></script
	{foreach item="script" from=$T_SCRIPTS}
		<script src="{Plico_GetResource file=$script}"></script>
	{/foreach}
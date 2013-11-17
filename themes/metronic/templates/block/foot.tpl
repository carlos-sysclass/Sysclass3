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
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function() {     
	  App.init("{$T_PATH.resource}");
	  if (typeof Login == 'object') {
	  	Login.init();
	  }
	  if (typeof Lock == 'object') {
	  	Lock.init();
	  }
	  if (typeof Calendar == 'object') {
	  	Calendar.init();
	  }
	  if (typeof PortletDraggable == 'object') {
	  	PortletDraggable.init();
	  }

	

	jQuery('#lastest-news-pager').bootpag({
    	maxVisible: 0,
		next: '<i class="icon-angle-right"></i>',
		prev: '<i class="icon-angle-left"></i>',
		leaps: false,
		total: 2,
		page: 1,
		cycle : true,
		linkClass: 'btn btn-sm yellow'
    }).on("page", function(event, num) {
		$("#lastest-news-content").html("Page " + num + " content here"); // or some ajax content loading...
	});
	});
</script>
<!-- END JAVASCRIPTS -->
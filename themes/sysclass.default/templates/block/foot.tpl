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


{foreach item="script" from=$T_MODULE_SCRIPTS}
    <script src="{$script}"></script>
{/foreach}
<!-- END JAVASCRIPTS -->

{if (isset($T_SECTION_TPL['foot']) &&  ($T_SECTION_TPL['foot']|@count > 0))}
    <div id="foot-tempĺates">
    {foreach $T_SECTION_TPL['foot'] as $template}
        {include file=$template}
    {/foreach}
    </div>
{/if}

<script>
    jQuery(document).ready(function() {   
        var options = {
            theme_path : "{$T_PATH.resource}",
            theme_app  : App  
        };
        $SC.start(options);
/*
        //App.init("{$T_PATH.resource}");
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
        //if (typeof Inbox == 'object') {
            //Inbox.init();
        //}
        if (jQuery.fn.easyPieChart) {
            $('.easy-pie-chart .number.transactions').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: App.getLayoutColorCode('blue')
            });

            $('.easy-pie-chart .number.visits').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: App.getLayoutColorCode('yellow')
            });
             
            $('.easy-pie-chart .number.bounce').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: App.getLayoutColorCode('red')
            });
        }
*/
    });
</script>
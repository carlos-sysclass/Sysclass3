<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->   
<!--[if lt IE 9]>
<script src="assets/metronic/plugins/respond.min.js"></script>
<script src="assets/metronic/plugins/excanvas.min.js"></script> 
<![endif]-->

<ul id="tlyPageGuide">
    <li class="tlypageguide_left" data-tourtarget="#users-panel">
        <div>
        pageguide.js attaches CSS pseudo elements to whatever features you define on your pages.
        The numbered arrows can be placed on top, bottom, left or right of whatever you are trying to highlight.
        </div>
    </li>
    <li class="tlypageguide_left" data-tourtarget="#courses-widget">
        <div>
        Change the style of the pageguide as much as you want. We include both CSS and LESS in the repo. We even attach a class
        to the body of the page when the guide is open.
        </div>
    </li>
    <li class="tlypageguide_left" data-tourtarget="#news-widget">
        <div>
        We include custom tracking for all the actions of the pageguide. Simply toss in your tracking code from Mixpanel, KISSMetrics, HubSpot, etc.
        and find out what people are interested in.
        </div>
    </li>
    <li class="tlypageguide_left" data-tourtarget="#tutoria-widget">
        <div>
        We include custom tracking for all the actions of the pageguide. Simply toss in your tracking code from Mixpanel, KISSMetrics, HubSpot, etc.
        and find out what people are interested in.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget="#institution-widget">
        <div>
        The page guide also scrolls for you.  Just use the forward and back arrows on the left to move been elements.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget="#advisor-widget">
        <div>
        The page guide also scrolls for you.  Just use the forward and back arrows on the left to move been elements.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget=".messages-panel">
        <div>
        The page guide also scrolls for you.  Just use the forward and back arrows on the left to move been elements.
        </div>
    </li>
    
    <li class="tlypageguide_left" data-tourtarget="#calendar-widget">
        <div>
        We include custom tracking for all the actions of the pageguide. Simply toss in your tracking code from Mixpanel, KISSMetrics, HubSpot, etc.
        and find out what people are interested in.
        </div>
    </li>
    
</ul>

<div class="tlyPageGuideWelcome">
    <button class="btn btn-sm btn-danger tlypageguide_dismiss pull-right">[ Close ]</button>
    <h2><strong>Welcome to SysClass</strong></h2>
    <ul>
        <li>This is a demo version of our new interface. There is a long way ahead, but we are working hard to have it ready for you this fall.</li>
        <li>By clicking in the diferents fields, a number will appear with a description of the funcionalities on the bottom of the page. Easy!</li>
        <li>If you have any sugestions, you can send us on the contact area on the right column of the page.</li>
        <li>Don't forget to like us or follow us.</li>
    </ul>
    <p class="text-center">
        <!-- <button class="btn btn-lg btn-warning tlypageguide_dismiss">No, thanks!</button> -->
        <button class="btn btn-lg btn-success tlypageguide_start">Start Tour.</button>
    </p>

    <!--
    <p>
        This button will launch pageguide:
        <button class="tlypageguide_start">let's go</button>
    </p>
    <p>
        This button will close the modal without doing anything:
        <button class="">not now</button>
    </p>
    <p>
        This button will close the modal and prevent it from being opened again:
        <button class="tlypageguide_dismiss">got it, thanks</button>
    </p>
    -->
</div>

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
        // pageguide init
        tl.pg.init({
            /* pg_caption : "" */
            custom_open_button : "#open-pageguide-action"
        });
        
        var options = {
            theme_path : "{$T_PATH.resource}",
            theme_app  : App  
        };
        $SC.start(options);
    });
</script>
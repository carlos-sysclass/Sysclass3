<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/assets/default/plugins/respond.min.js"></script>
<script src="/assets/default/plugins/excanvas.min.js"></script>
<![endif]-->
<ul id="tlyPageGuide">
    <li class="tlypageguide_left hidden-xs" data-tourtarget="#users-panel">
        <div>
        Here you will find the most relevant information about the student’s status, history, progress, and the roadmap for the completion of the course.
        </div>
    </li>
    <li class="tlypageguide_left" data-tourtarget="#courses-widget">
        <div>
        This area contains all the information about the course and the instructors teaching it. In this area you find the entire educational content, as well as the video class, materials, tests and grades.
        </div>
    </li>
    <li class="tlypageguide_left" data-tourtarget="#news-widget">
        <div>
        The announcements related to the course such as, holidays, special activities, meeting with advisors, and all information related to the course and school.
        </div>
    </li>
    <li class="tlypageguide_left" data-tourtarget="#tutoria-widget">
        <div>
        In this area the students can ask questions to the instructors or advisors. Depending on how the course is setup, only the instructor or advisor can answer questions, or other students can be allowed answer each other.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget="#institution-widget">
        <div>
        This area is dedicated to the university, school, training center, or private teacher. Here you will find the name of the school, homepage, Facebook page, contact information, and everything you need to know about the institution hosting the course.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget="#advisor-widget">
        <div>
        Here the student will find information about the instructor, advisor or coach. In this area you can also schedule meetings with your instructor or advisor, chat with him or her, and even have calls or videoconferences.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget="#advertising-panel">
        <div>
        This area is dedicated to general advertising or advertising and promotions of the school or company hosting the program.
        </div>
    </li>
    <li class="tlypageguide_right" data-tourtarget=".messages-panel">
        <div>
        Depending on how your course is set up, the school or instructor can determined what type of contact he or she will make available to the students.
        </div>
    </li>

    <li class="tlypageguide_left" data-tourtarget="#calendar-widget">
        <div>
        Here students and faculty will be able to see all relevant dates pertaining to the course such as, delivery of papers, holidays, special activities, meetings online with other students, and meetings with instructors or advisors.
        </div>
    </li>

</ul>

{if (isset($T_SECTION_TPL['foot']) &&  ($T_SECTION_TPL['foot']|@count > 0))}
    <div id="foot-tempĺates">
    {foreach $T_SECTION_TPL['foot'] as $template}
        {include file=$template}
    {/foreach}
    </div>
{/if}

<script type="text/javascript" src="/{$T_SCRIPT_TARGET}"></script>

<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!-- END PAGE LEVEL SCRIPTS -->


{foreach $T_MODULE_SCRIPTS as $script}
    <script src="{$script}"></script>
{/foreach}
<!-- END JAVASCRIPTS -->

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

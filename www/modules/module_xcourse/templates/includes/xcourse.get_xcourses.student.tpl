{capture name="t_xcourses_feature_list"}
<div class="flat_area">
	<ul class="xcourse-feature-list">
		{foreach name = 'xcourses_list' key = 'key' item = 'course' from = $T_XCOURSE_USER_LIST}
			<li><a href="{$T_XCOURSE_BASEURL}&action=view_course_dashboard&xcourse_id={$course.id}">{$course.name}</a></li>
		{/foreach}
	</ul>
</div>
{/capture}


{eF_template_printBlock 
	title=$smarty.const.__XCOURSE_SELECT_COURSE
	data=$smarty.capture.t_xcourses_feature_list
	contentclass="blockContents"
}


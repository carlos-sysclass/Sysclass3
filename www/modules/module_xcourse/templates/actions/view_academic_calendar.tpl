<div class="blockContents">
	<h3>{$T_XCOURSE_BLOCK_TITLE}</h3>
	
	<div id="lessonGanttChart"></div>
	<script type="text/javascript">
	{literal}
	refreshGanttChart({
		'xcourse_id'	: $_xcourse_mod_data['edited_course']['id'],
		'xlesson_id'	: $_xcourse_mod_data['edited_lesson']['id'],
		'xclasse_id'	: 4
	});
	{/literal}
	</script>
</div>
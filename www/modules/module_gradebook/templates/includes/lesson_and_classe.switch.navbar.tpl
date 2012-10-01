<div>
	<select id="switch_lesson" name="switch_lesson">
		{foreach name = 'lessons_loop' key = "course_id" item = "course" from = $T_GRADEBOOK_GRADEBOOK_LESSONS}
			<optgroup label="{$course.name}">
			{foreach name = 'lessons_loop' key = "lesson_id" item = "lesson" from = $course.lessons}
					<option value="{$lesson.id}" {if $lesson.id == $T_GRADEBOOK_LESSON_ID}selected="selected"{/if}>{$lesson.name}</option>
			{/foreach}
			</optgroup>
		{/foreach}
	</select>
	<!--  LOAD CLASSES BY AJAX -->
	<select id="switch_classe" name="switch_classe">
		{*foreach name = 'lessons_loop' key = "lesson_id" item = "lesson" from = $course.lessons*}
<!-- 				<option value="{$lesson.id}">{$lesson.name}</option>  -->
		{*/foreach*}
	</select>
	
	&nbsp;<img src="{$T_GRADEBOOK_BASELINK|cat:'images/arrow_right.png'}" alt="{$smarty.const._GRADEBOOK_SWITCH_TO}" title="{$smarty.const._GRADEBOOK_SWITCH_TO}" style="vertical-align:middle">
 
	<a href="javascript: _sysclass('load', 'gradebook').switchToLessonClasse(jQuery('#switch_lesson').val(), jQuery('#switch_classe').val());">{$smarty.const._GRADEBOOK_SWITCH_TO}</a>
</div>
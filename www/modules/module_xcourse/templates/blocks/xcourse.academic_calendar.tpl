{foreach key="course_id" item="course_academic" from=$T_XCOURSE_ACADEMIC_CALENDAR}
	{assign var="course_calendar_capture" value="course_calendar_`$course_id`"}
	
	{if $course_academic.lessons}
		{capture name="calendar_table_header"}
			<tr>
				<th>{$smarty.const.__LESSON}</th>
				<th width="15%">{$smarty.const.__START_DATE}</th>
				<th width="15%">{$smarty.const.__END_DATE}</th>
			</tr>
		{/capture}
		{capture name=$course_calendar_capture}
			{foreach key="lesson_id" item="lessons_times" from=$course_academic.lessons}
				{if !is_null($lessons_times.start_date) || !is_null($lessons_times.end_date)}
					<tr>
						<td>

							<a href="{$T_XCOURSE_BASEURL}student.php?ctg=module&op=module_xcourse&action=load_academic_calendar_lesson&course_id={$course_id}&lesson_id={$lesson_id}&popup=1" 
							   target="POPUP_FRAME"
							   onclick="eF_js_showDivPopup('{$lessons_times.name}', 2)"
							>
								{$lessons_times.name|eF_truncate:70}
							</a>

							
						</td>
						<td align="center">{if $lessons_times.start_date}#filter:date-{$lessons_times.start_date}#{else}N/A{/if}</td>
						<td align="center">{if $lessons_times.end_date}#filter:date-{$lessons_times.end_date}#{else}N/A{/if}</td>
					</tr>
				{/if}
			{/foreach}
		{/capture}
	{elseif $course_academic.series}
		{capture name="calendar_table_header"}
			<tr>
				<th>{$smarty.const.__DESCRIPTION}</th>
				<th width="15%">{$smarty.const.__START_DATE}</th>
				<th width="15%">{$smarty.const.__END_DATE}</th>
			</tr>
		{/capture}

	
	
		{capture name=$course_calendar_capture}
			{foreach key="lesson_id" item="lessons_times" from=$course_academic.series}
					<tr>
						<td>{$lessons_times.name|eF_truncate:70}</td>
						<td align="center">{if $lessons_times.start}#filter:date-{$lessons_times.start}#{else}N/A{/if}</td>
						<td align="center">{if $lessons_times.end}#filter:date-{$lessons_times.end}#{else}N/A{/if}</td>
					</tr>
				
			{/foreach}
		{/capture}
	{/if}
{/foreach}

<ul id="xcourse-academic-calendar">
	{foreach key="course_id" item="course_academic" from=$T_XCOURSE_ACADEMIC_CALENDAR}
		{assign var="course_calendar_capture" value="course_calendar_`$course_id`"}
		{if $smarty.capture.$course_calendar_capture|@strlen > 100}
		
			{if $course_academic.lessons}
				{assign var="course_calendar_class" value="course_`$course_id`"}
			{elseif $course_academic.series}
				{assign var="course_calendar_class" value="course_lesson_`$course_id`_`$course_academic.lesson.id`"}
			{/if}
					
			<li class="{$course_calendar_class}">
				<ul class="default-list">
					<li>
						{if $course_academic.lessons}
							<!-- 
							<div style="text-align: center">{$course_academic.course.name|eF_truncate:80}</div>
							 -->
						{elseif $course_academic.series}
							<div style="text-align: center">{$course_academic.lesson.name|eF_truncate:80}</div>
						{/if}
					</li>
					<li style="border-bottom: none;">
						<table class="style1 default-table">
							<thead>
								{$smarty.capture.calendar_table_header}
							</thead>
							<tbody>
								{$smarty.capture.$course_calendar_capture}
							</tbody>
						</table>
					</li>
				</ul>
			</li>
		{/if}
	{/foreach}
</ul>



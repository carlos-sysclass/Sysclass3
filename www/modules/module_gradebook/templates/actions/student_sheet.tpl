{capture name = 't_gradebook_sheet_code'}

	{include file="$T_GRADEBOOK_BASEDIR/templates/includes/action.switch.navbar.tpl"}
	<div class="clear"></div>

	<!-- THIS CODE BELOW MUST BE MOVED TO YOUR OWN SPACE -->
	<!--
	<div class="course-lesson-autocomplete-container">
	    <label>Selecione uma disciplina:</label>
	    <input class="course-lesson-autocomplete" value="" />
	</div>
	-->
	
	<ul>
	{foreach item="lesson" from=$T_GRADEBOOK_LESSONS_SCORES}
		
		{capture name="t_gradebook_lesson"}
			<table class="style1">
				<thead>
					<tr>
						<th>{$smarty.const._GRADEBOOK_COLUMN_NAME}</th>
						<th style="text-align: center;">{$smarty.const.__GRADEBOOK_WEIGHT}</th>
						<th style="text-align: center;">{$smarty.const.__GRADEBOOK_FINAL_SCORE}</th>
					</tr>
				</thead>
				<tbody>
					{if $lesson.columns|@count > 0}
						{foreach item="group" from=$lesson.groups}
							{assign var="group_id" value=$group.id}
							{assign var="column_group_children" value=false}
							
							{foreach item="column" from=$lesson.columns}
								{assign var="column_id" value=$column.id}
								{if $group.id == $column.group_id}
									<tr>
										<td>{$column.name}</td>
										<td align="center">{$column.weight}</td>
										<td align="center">{$lesson.scores.columns[$column_id]}</td>
									</tr>
									{assign var="column_group_children" value=true}
								{/if}
							{/foreach}
							{if $column_group_children}
								<tr>
									<th colspan="2">{$group.name}</th>
									<th style="text-align: center;">{$lesson.scores.groups[$group_id]}</th>
								</tr>
							{/if}
						{/foreach}
					{else}
						<tr>
							<td colspan="3">{$smarty.const.__NO_DATA_FOUND}</td>
						</tr>
					{/if}
				</tbody>
				<tfoot>
					<tr>
						<th>{$smarty.const.__GRADEBOOK_TOTAL}</th>
						<th style="text-align: center;">{$smarty.const.__GRADEBOOK_WEIGHT}</th>
						<th style="text-align: center;">{$lesson.scores.final_score}</th>
					</tr>
				</tfoot>
			</table>
		{/capture}
		
		<li class="gradebook-course-lesson" id ="gradebook-course-lesson-{$lesson.course_id}_{$lesson.id}">
			<div class="collapse-title"><a href="javascript: void(0);">{$lesson.name}</a></div>
			<div class="collapse-content" style="display: none;">{$smarty.capture.t_gradebook_lesson}</div>
		</li>
	{/foreach}
</ul>	
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_sheet_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}

	
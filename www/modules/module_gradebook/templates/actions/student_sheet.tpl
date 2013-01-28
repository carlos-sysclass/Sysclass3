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
	{foreach item="course" from=$T_GRADEBOOK_LESSONS_SCORES}
		<li class="gradebook-course" id ="gradebook-course-{$course.id}">
			<div class="collapse-title"><a href="javascript: void(0);">
				<img class="gradebook-tree-indicator" src="images/16x16/navigate_right.png" />{$course.name}</a>
			</div>
			<ul class="collapse-content">
			{foreach item="lesson" from=$course.lessons}
				
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
								<th colspan="2">{$smarty.const.__GRADEBOOK_TOTAL}</th>
								<th style="text-align: center;">{$lesson.scores.final_score}</th>
							</tr>
							<tr>
								<th colspan="2">Situação final</th>
								<th style="text-align: center;">{$lesson.scores.final_status}</th>
							</tr>
						</tfoot>
					</table>
				{/capture}
				{$lesson.course_name}
				<li class="gradebook-course-lesson" id ="gradebook-course-lesson-{$lesson.course_id}_{$lesson.id}">
					<div class="collapse-title"><a href="javascript: void(0);">
						<img class="gradebook-tree-indicator" src="images/16x16/navigate_right.png" />{$lesson.name}</a>
					</div>
					<div class="collapse-content">{$smarty.capture.t_gradebook_lesson}</div>
				</li>
			{/foreach}
			</ul>
		</li>
	{/foreach}
</ul>	
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_sheet_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}

	

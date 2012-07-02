{capture name="t_edit_schedule_times"}
<div class="blockContents form-list-itens">
	<!-- 
	<div class="grid_12">
		<label>{$smarty.const.__XCONTENT_START_DATE}:</label>
		<span>dasdas</span>
	</div>
	<div class="grid_12">
		<label>{$smarty.const.__XCONTENT_END_DATE}:</label>
		<span>dasdas</span>
	</div>
	 -->
	<div class="grid_24">
		<label>{$smarty.const.__XCONTENT_SCOPE}:</label>
		<span>{$T_XCONTENT_SCHEDULE.scope}</span>
	</div>
	
	{if $T_XCONTENT_SCOPE_FIELDS|@count > 0}
		{foreach item="field" from=$T_XCONTENT_SCOPE_FIELDS}
			<div class="grid_24">
				<label>{$T_XCONTENT_SCHEDULE.$field.label}:</label>
				<span>{$T_XCONTENT_SCHEDULE.$field.value}</span>
			</div>
		{/foreach}			
	{/if}
	<!-- 
	<div class="grid_24">
		<label>{$msarty.const.__XCONTENT_CONTENT}</label>
	</div>
	 -->
	<ul class="default-list">
		<li>
			<div class="grid_24">
				<img class="sprite16 sprite16-arrow_right" src="images/others/transparent.gif" />
				<a href="{$T_XCONTENT_BASEURL}&action=append_new_content_to_schedule&xschedule_id={$T_XCONTENT_SCHEDULE.id}">
					Adicionar um novo conteúdo
				</a>
			</div>
		</li>
	{foreach name="it_course" key="course_id" item="by_course" from=$T_XCONTENT_SCHEDULE.grouped_content}
		<li>
			<div class="grid_12">
				<label>{$smarty.foreach.it_course.iteration}.</label>
				<a href="javascript: void(0);" onclick="jQuery(this).parent().next().slideToggle('fast');">{$T_XCONTENT_COURSES[$course_id]}</a>
			</div>
			<div class="colapse" style="margin-left: 20px;">
		{foreach name="it_lesson" key="lesson_id" item="by_lesson" from=$by_course}
				<div class="grid_24">
					<label>{$smarty.foreach.it_lesson.iteration}.</label>
					<a href="javascript: void(0);" onclick="jQuery(this).parent().next().slideToggle('fast');">
						{$T_XCONTENT_LESSONS[$lesson_id]} ({$by_lesson|@count} conteúdos)
					</a>
				</div>
				<div class="colapse">
					<ul>
					{foreach name="it_content" item="content_data" from=$by_lesson}
						<li style="margin-left: 40px;">
							<div class="grid_24 {if $content_data.required == 1}xcontentRequired{elseif $content_data.required == 0}xcontentNoRequired{/if}">
								<label>{$smarty.foreach.it_content.iteration}.</label>
								<!-- 
								<span>{$content_data.course}</span> &raquo;
								<span>{$content_data.lesson}</span> &raquo;
								 --> 
								<span>{$content_data.content}</span>
								<button class="form-icon ScheduleContentDelete" 
									onclick="doAjaxDeleteScheduleContent({$T_XCONTENT_SCHEDULE.id}, {$content_data.course_id}, {$content_data.content_id}, this); return false;">
									<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
								</button>
							</div>			
						</li>
					{/foreach}
					</ul>
				</div>
		{/foreach}
			</div>
		</li>
	{/foreach}
		<li>
			<div class="grid_24">
				<img class="sprite16 sprite16-arrow_right" src="images/others/transparent.gif" />
				<a href="{$T_XCONTENT_BASEURL}&action=append_new_content_to_schedule&xschedule_id={$T_XCONTENT_SCHEDULE.id}">
					Adicionar um novo conteúdo
				</a>
			</div>
		</li>
	</ul>
	<!-- 
	<ul class="default-list">
	{foreach name="it_content" item="content_data" from=$T_XCONTENT_SCHEDULE.contents}
		<li>
			<div class="grid_24 {if $content_data.required == 1}xcontentRequired{elseif $content_data.required == 0}xcontentNoRequired{/if}">
				<label>{$smarty.foreach.it_content.iteration}.</label>
				<span>{$content_data.course}</span> &raquo;
				<span>{$content_data.lesson}</span> &raquo; 
				<span>{$content_data.content}</span>
				<button class="form-icon ScheduleContentDelete" 
					onclick="doAjaxDeleteScheduleContent({$T_XCONTENT_SCHEDULE.id}, {$content_data.course_id}, {$content_data.content_id}, this); return false;">
					<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
				</button>
			</div>			
			
		</li>
	{/foreach}
		
	</ul>
	 -->
</div>

<form action="{$smarty.server.request_uri}" method="post">

	<input type="hidden" name="xschedule_id" value="{$T_XCONTENT_SCHEDULE_ID}" />

	<ul class="default-list">
		<!-- MODELO DE LISTA PARA HORÁRIOS DO CURSO -->
		<li class="container_24" id="schedule_clonable" style="display: none;">
			<input type="hidden" name="index[new][]" value="-1" class="indexField" />
			<div class="grid_7">
				<label>Data:</label>
				<input name="date[new][]" value="" alt="date" class="no-button dateField" />
			</div>
			<div class="grid_7">
				<label>Início:</label>
				<input name="start[new][]" value="{$schedule.start}" alt="time" class="startField" />
			</div>
			<div class="grid_7">
				<label>Término:</label>
				<input name="end[new][]" value="{$schedule.end}" alt="time" class="endField" />
			</div>
			<div class="grid_3">
				<button class="form-icon contentScheduleEdit">
					<img class="sprite16 sprite16-edit" src="images/others/transparent.gif" />
				</button>
				<button class="form-icon contentScheduleDelete">
					<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
				</button>
				<button class="form-icon contentScheduleSave" style="display:none">
					<img class="sprite16 sprite16-success" src="images/others/transparent.gif" />
				</button>			
				<button class="form-icon contentScheduleCancel" style="display:none">
					<img class="sprite16 sprite16-arrow_left" src="images/others/transparent.gif" />
				</button>
			</div>
		</li>
		{foreach name="schedule_it" key="schedule_key" item="schedule" from=$T_XCONTENT_SCHEDULE_TIMES}
			<li class="container_24">
				<input type="hidden" name="index[new][]" value="{$schedule.index}" class="indexField" />
				<div class="grid_7">
					<label>Data:</label>
					<input name="date[{$schedule.index}]" value="#filter:date-{$schedule.start}#" alt="date" class="no-button dateField" readonly="readonly" style="background: transparent; border-color: transparent"/>
				</div>
				<div class="grid_7">
					<label>Início:</label>
					<input name="start[{$schedule.index}]" value="#filter:time-{$schedule.start}#" alt="time" class="startField" readonly="readonly" style="background: transparent; border-color: transparent" />
				</div>
				<div class="grid_7">
					<label>Término:</label>
					<input name="end[{$schedule.index}]" value="#filter:time-{$schedule.end}#" alt="time" class="endField" readonly="readonly" style="background: transparent; border-color: transparent" />
				</div>
				<div class="grid_3">
					<button class="form-icon contentScheduleEdit">
						<img class="sprite16 sprite16-edit" src="images/others/transparent.gif" />
					</button>
					<button class="form-icon contentScheduleDelete">
						<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
					</button>
					<button class="form-icon contentScheduleSave" style="display:none">
						<img class="sprite16 sprite16-success" src="images/others/transparent.gif" />
					</button>			
					<button class="form-icon contentScheduleCancel" style="display:none">
						<img class="sprite16 sprite16-arrow_left" src="images/others/transparent.gif" />
					</button>
				</div>
			</li>
		{/foreach}
		<!-- FIM : MODELO DE LISTA PARA HORÁRIOS DO CURSO -->
	</ul>
	<div class="grid_24" style="margin-top: 20px;" align="center">
		<button class="form-button icon-add contentScheduleInsert" type="button" name="contentScheduleSubmit" value="contentScheduleSubmit">
			<img width="29" height="29" src="images/transp.png">
			<span>{$smarty.const.__XCONTENT_SCHEDULE_ADD}</span>
		</button>		
		<!-- 
		<button class="form-button icon-save contentScheduleSave" type="button" name="contentScheduleSubmit" value="contentScheduleSubmit">
			<img width="29" height="29" src="images/transp.png">
			<span>{$smarty.const.__XCONTENT_SCHEDULE_SAVE}</span>
		</button>
		 -->
	</div>
</form>
{/capture}
{eF_template_printBlock
	title 			= $smarty.const.__XCONTENT_EDIT_SCHEDULE
	data			= $smarty.capture.t_edit_schedule_times
	contentclass	= "blockContents"
}
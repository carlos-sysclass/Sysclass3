{* CAPTURE STEPS *}





{$T_XCOURSE_ACADEMIC_FILTER_FORM.javascript}
<form {$T_XCOURSE_ACADEMIC_FILTER_FORM.attributes}>
	{$T_XCOURSE_ACADEMIC_FILTER_FORM.hidden}
	
	<div class="blockContents" metadata="{Mag_Json_Encode data=$course}">
		<h4>{$smarty.const.__XCOURSE_ACADEMIC_CALENDAR}</h4>
		<ul class="xcourse-calendar-checklist " style="list-style: none; width: 100%; float: left;">
			<li class="step-1">
				<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/users.png" class="checklist-image">
				<span {if $T_XENROLLMENT_SELECTED_USER}class="check-item-ok"{else}class="check-item-pendente"{/if}>
					{$T_XCOURSE_ACADEMIC_FILTER_FORM.classe_filter.label}
				</span>
				<span class="xenrollment-register-checklist-status">
					{$T_XCOURSE_ACADEMIC_FILTER_FORM.classe_filter.html}
				</span>
			</li>
			{if $T_XCOURSE_STEP >= 2 && $T_XCOURSE_LESSONS_CALENDAR}
			<li class="step-2">
				<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/users.png" class="checklist-image">
				<span {if $T_XENROLLMENT_SELECTED_USER}class="check-item-ok"{else}class="check-item-pendente"{/if}>
					{$smarty.const.__XCOURSE_INSERT_LESSONS_DATES}
				</span>
				<span class="xenrollment-register-checklist-status">
					<button class="button_colour round_all" type="submit" name="{$T_XCOURSE_ACADEMIC_FILTER_FORM.submit_apply.name}" value="{$T_XCOURSE_ACADEMIC_FILTER_FORM.submit_apply.value}">
						<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
						{$smarty.const.__XCOURSE_CALENDAR_SAVE}
					</button>
				</span>
				<div class="clear"></div>
				<br />
				
				
				<table class="static">
					<thead>
						<tr>
							<th width="1%">&nbsp;</th>
							<th width="40%">{$smarty.const.__XCOURSE_LESSON_NAME}</th>
							<th width="25%">{$smarty.const.__XCOURSE_LESSON_START_DATE}</th>
							<th width="25%">{$smarty.const.__XCOURSE_LESSON_END_DATE}</th>
						</tr>
					</thead>
					<tbody>
						{foreach key = 'key' item = 'lesson' from = $T_XCOURSE_LESSONS_CALENDAR}
							<tr metadata="{Mag_Json_Encode data=$lesson}">
								<td>
									<div class="button_display">
									{if $lesson.start_date && $lesson.end_date}
										<button class="skin_colour round_all openGanttChart">
											<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_down.png">
											<span>Open gantt</span>
										</button>
									{/if}
									</div>
								</td>
								<td>{$lesson.name}</td>
								<td align="center"><input type="text" name="start_date[{$lesson.lesson_id}]" value="{if $lesson.start_date}#filter:date-{$lesson.start_date}#{/if}" alt="date" class="no-button medium datepicker-in-table" /></td>
								<td align="center"><input type="text" name="end_date[{$lesson.lesson_id}]" value="{if $lesson.end_date}#filter:date-{$lesson.end_date}#{/if}" alt="date" class="no-button medium datepicker-in-table" /></td>
							</tr>
							{assign var=lesson_id value=$lesson.lesson_id}
							{foreach item = 'serie' from = $T_XCOURSE_LESSONS_CALENDAR_SERIES.$lesson_id.series}
								<tr class="xcourse-lessons-calendar-details xcourse-lessons-calendar-details-for-lesson-{$lesson.lesson_id}"  metadata="{Mag_Json_Encode data=$serie}">
									<td>&nbsp;</td>
									<td>{$serie.name}</td>
									<td align="center"><input type="text" name="start_date_series[{$lesson.lesson_id}][{$serie.serie_id}]" value="{if $serie.start}#filter:date-{$serie.start}#{/if}" alt="date" class="no-button medium datepicker-in-table" /></td>
									<td align="center"><input type="text" name="end_date_series[{$lesson.lesson_id}][{$serie.serie_id}]" value="{if $serie.end}#filter:date-{$serie.end}#{/if}" alt="date" class="no-button medium datepicker-in-table" /></td>
								</tr>
							{/foreach}
						{/foreach}
					</tbody>
				</table>
			</li>
			{else}
			<li class="step-not-found">
				<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/alert.png" class="checklist-image">
				<span {if $T_XENROLLMENT_SELECTED_USER}class="check-item-ok"{else}class="check-item-pendente"{/if}>
					{$smarty.const.__XCOURSE_NO_LESSONS_FOUND}
				</span>
			</li>
			{/if}
			
		</ul>
	
	

<!-- 
<div class="flat_area">
	<ul class="grid_8" id="_XCOURSE_LESSONS_SORTABLE_LIST">
		{foreach key = 'key' item = 'lesson' from = $T_XCOURSE_LESSONS}
			<li id="lessonid_{$lesson.id}">
				<span class="lesson-name">{$lesson.name}</span>
				<span class="lesson-name">{$lesson.start_date} {$smarty.const.__UNTIL} {$lesson.end_date}</span>
				<span class="lesson-name"></span>
				<button class="skin_colour round_all" title="{$smarty.const._DEACTIVATE}" style="float: right;">
					<img width="16" height="16" alt = "{$smarty.const._DEACTIVATE}" src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/alert_2.png" />
				</button>
			</li>
		{/foreach}
	</ul>
</div>
<div class="clear"></div>


<br />
1



{$T_XCOURSE_ACADEMIC_FILTER_FORM.javascript}
<form {$T_XCOURSE_ACADEMIC_FILTER_FORM.attributes}>
	{$T_XCOURSE_ACADEMIC_FILTER_FORM.hidden}
	<div class="grid_8">
		<label>{$T_XCOURSE_ACADEMIC_FILTER_FORM.lesson_filter.label}</label>
		{$T_XCOURSE_ACADEMIC_FILTER_FORM.lesson_filter.html}
	</div>
	<div class="grid_8">
		<label>{$T_XCOURSE_ACADEMIC_FILTER_FORM.classe_filter.label}</label>
		{$T_XCOURSE_ACADEMIC_FILTER_FORM.classe_filter.html}
	</div>
	
	<div class="flat_area grid_16">
		<table>
			<thead></thead>
			<tbody>
				{foreach key = 'key' item = 'lesson' from = $T_XCOURSE_LESSONS}
				<tr>
					<td>{$lesson.name}</td>
					<td>{$lesson.name}</td>
					<td>{$lesson.name}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	<div class="clear"></div>
	
	

	<div class="grid_8">		
		<label>{$T_XCOURSE_ACADEMIC_FILTER_FORM.start_date.label}</label>
		{$T_XCOURSE_ACADEMIC_FILTER_FORM.start_date.html}
	</div>
	<div class="grid_8">
		<label>{$T_XCOURSE_ACADEMIC_FILTER_FORM.end_date.label}</label>
		{$T_XCOURSE_ACADEMIC_FILTER_FORM.end_date.html}
	</div>
	<div class="grid_16">
		<button class="button_colour round_all" type="submit" name="{$T_XCOURSE_ACADEMIC_FILTER_FORM.submit_apply.name}" value="{$T_XCOURSE_ACADEMIC_FILTER_FORM.submit_apply.value}">
			<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
			<span>{$T_XCOURSE_ACADEMIC_FILTER_FORM.submit_apply.value}</span>
		</button>
	</div>
</form>
<div class="clear"></div>

-->


<div class="clear"></div>

<div id="_XCOURSE_LOADING_OUTER">
	<div id="_XCOURSE_LOADING">
		<div>{$smarty.const.__LOADING_MESSAGE}</div>
	</div>
</div>
<br />
<div id="ganttChart"></div>
<br />
<div id="eventMessage"></div>
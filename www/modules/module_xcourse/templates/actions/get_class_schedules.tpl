<form action="{$T_FORM_ACTION}" method="post">
	<ul id="_XCOURSE_CLASS_CALENDAR_LIST" class="main_container">
		<!-- MODELO DE LISTA PARA HORÁRIOS DO CURSO -->
		<li class="container_16" id="schedule_clonable" style="display: none;">
			<div class="grid_8 button_display">
				<label>Dia da Semana</label>
				<button class="red skin_colour round_all classScheduleDelete" title="{$smarty.const.__XCOURSE_CLASS_SCHEDULE_DELETE_HINT}">
					<img width="16" height="16" alt="{$smarty.const.__XCOURSE_CLASS_SCHEDULE_DELETE}" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png">
					<span>{$smarty.const.__XCOURSE_CLASS_SCHEDULE_DELETE_HINT}</span>
				</button>
				<select name="week_day[new][]" class="_XCOURSE_CLASS_CALENDAR_WEEKDAY inline large">
					{foreach key="week_key" item="week_day" from=$T_L10N_DATA.weekdays}
						<option value="{$week_key}" {if $schedule.week_day == $week_key} selected="selected"{/if}>{$week_day}</option>
					{/foreach}
				</select>
			</div>
			<div class="grid_4">
				<label>Início</label>
				<input name="start[new][]" class="_XCOURSE_CLASS_CALENDAR_START inline large" value="{$schedule.start}" alt="time" />
			</div>
			<div class="grid_4">
				<label>Término</label>
				<input name="end[new][]" class="_XCOURSE_CLASS_CALENDAR_END inline large" value="{$schedule.end}" alt="time" />
			</div>
		</li>
		{foreach name="schedule_it" key="schedule_key" item="schedule" from=$T_XCOURSE_CLASS_SCHEDULES}
			<li class="container_16">
				<div class="grid_8 button_display">
					<label>Dia da Semana</label>
					<button class="red skin_colour round_all classScheduleDelete" title="{$smarty.const.__XCOURSE_CLASS_SCHEDULE_DELETE_HINT}">
						<img width="16" height="16" alt="{$smarty.const.__XCOURSE_CLASS_SCHEDULE_DELETE}" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png">
						<span>{$smarty.const.__XCOURSE_CLASS_SCHEDULE_DELETE_HINT}</span>
					</button>
					<select name="week_day[{$schedule.id}]" class="_XCOURSE_CLASS_CALENDAR_WEEKDAY inline large">
						{foreach key="week_key" item="week_day" from=$T_L10N_DATA.weekdays}
							<option value="{$week_key}" {if $schedule.week_day == $week_key} selected="selected"{/if}>{$week_day}</option>
						{/foreach}
					</select>
				</div>
				<div class="grid_4">
					<label>Início</label>
					<input name="start[{$schedule.id}]" class="_XCOURSE_CLASS_CALENDAR_START inline large" value="{$schedule.start}" alt="time" />
				</div>
				<div class="grid_4">
					<label>Término</label>
					<input name="end[{$schedule.id}]" class="_XCOURSE_CLASS_CALENDAR_END inline large" value="{$schedule.end}" alt="time" />
				</div>
			</li>
		{/foreach}
		<!-- FIM : MODELO DE LISTA PARA HORÁRIOS DO CURSO -->
		<li class="container_16">
			<div class="grid_16 headerTools">
	            <span>
					<a class="classScheduleInsert" href="javascript: void(0);">
	                    <img src="/themes/sysclass/images/icons/small/grey/day_calendar.png" title="{$smarty.const.__XCOURSE_CLASS_SCHEDULE_ADD_HINT}" alt="{$smarty.const.__XCOURSE_CLASS_SCHEDULE_ADD}">
	                    {$smarty.const.__XCOURSE_CLASS_SCHEDULE_ADD}
					</a>
				</span>
			</div>
		</li>
	</ul>
</form>
	<div style="margin-top: 15px;" class="grid_16 box border">
		<div class="headerTools">
            <span>
				<a href="javascript: void(0);" class="addClassLink">
                    <img alt="{$smarty.const.__XCOURSE_CLASS_ADD}" title="{$smarty.const.__XCOURSE_CLASS_ADD_HINT}" src="/themes/sysclass/images/icons/small/grey/user.png">
                    {$smarty.const.__XCOURSE_CLASS_ADD}
				</a>
			</span>
		</div>
	</div>
	<div class="clear"></div>
	
	<table class = "display" id="_XCOURSE_CLASSES_LIST">
		<thead>
			<tr>
				<th>{$smarty.const._NAME} </th>
				<th>{$smarty.const._CLASSSTARTON}</th>
				<th>{$smarty.const._CLASSFINISHON}</th>
				<th>{$smarty.const._MAXSTUDENTS}</th>
				<th>{$smarty.const._STUDENTSCOUNT}</th>
				<th>{$smarty.const._FUNCTIONS}</th>
			</tr>
		</thead>
		<tbody>
			{foreach name = 'classes_list2' key = 'key' item = 'classe' from = $T_XCOURSE_CLASSES_LIST}
				<tr metadata="{Mag_Json_Encode data = $classe}">
					<td>
						<a href="javascript: void(0);" class="editClassLink">{$classe.name}</a>
					</td>
					<td class = "center">#filter:timestamp-{$classe.start_date}#</td>
					<td class = "center">#filter:timestamp-{$classe.end_date}#</td>
					<td class = "center">{$classe.max_users}</td>
					<td class = "center">{$classe.count_users}</td>
					<td class = "center">
						<div class="button_display">
							<button class="skin_colour round_all editClassLink">
								<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
								<span>Editar</span>
							</button>
							<button class="skin_colour round_all usersClassLink" title="{$smarty.const.__XCOURSE_USERINCLASS_HINT}">
								<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/users.png">
								<span>Usuário</span>
							</button>

							<button class="skin_colour round_all calendarClassLink">
								<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/day_calendar.png">
								<span>Calendário</span>
							</button>
							{if $classe.count_users == 0}
							<button onclick="xcourse_deleteCourseClass(this, {$classe.courses_ID}, {$classe.id});" class="red skin_colour round_all">
								<img width="16" height="16" title="Deletar" alt="Deletar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png">
								<span>Deletar</span>
							</button>
							{/if}
						</div>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<div class="clear"></div>
	<script type="text/javascript" >
	var slider_value = {$T_XCOURSE_CLASS_FORM.max_users.value};
	</script>
	
	{literal}
	<style type="text/css">
	.form-box {
	    float: left;
	    width: 100%;
	    margin: 0;
	    padding: 0;
	    border: none;
	}
	.form-box .ui-slider{
	    float: left;
	    margin-top: 7px;
	    padding-bottom: 0;
	    padding-left: 5px;
	    padding-right: 5px;
	    padding-top: 0;
	    width: 250px;
	}
	#max-users-text {
	    float: left;
	    font-weight: bold;
	    margin-top: 7px;
	    padding: 0 5px;
	}
	</style>
	{/literal}
	
	
	<div id="_XCOURSE_CLASS_FORM" class="blockContents">
		{$T_XCOURSE_CLASS_FORM.javascript}
		<form {$T_XCOURSE_CLASS_FORM.attributes}>
			{$T_XCOURSE_CLASS_FORM.hidden}
			<div class="flat_area form-box">
				<div class="grid_16">
					<label for="{$T_XCOURSE_CLASS_FORM.name.name}">{$T_XCOURSE_CLASS_FORM.name.label}:&nbsp;</label>
					{$T_XCOURSE_CLASS_FORM.name.html}
					<label for="{$T_XCOURSE_CLASS_FORM.max_users.name}">{$T_XCOURSE_CLASS_FORM.max_users.label}:&nbsp;</label>
					{$T_XCOURSE_CLASS_FORM.max_users.html}
					
					<div id="max-users-slider"></div>
					<div id="max-users-text">{$T_XCOURSE_CLASS_FORM.max_users.value}</div>
					<label for="{$T_XCOURSE_CLASS_FORM.start_date.name}">{$T_XCOURSE_CLASS_FORM.start_date.label}:&nbsp;</label>
					{$T_XCOURSE_CLASS_FORM.start_date.html}
					<label for="{$T_XCOURSE_CLASS_FORM.end_date.name}">{$T_XCOURSE_CLASS_FORM.end_date.label}:&nbsp;</label>
					{$T_XCOURSE_CLASS_FORM.end_date.html}
					
					<label for="{$T_XCOURSE_CLASS_FORM.active.name}">{$T_XCOURSE_CLASS_FORM.active.label}:</label>
					{$T_XCOURSE_CLASS_FORM.active.html}
				</div>		
				<div class="clear"></div>
				
				<div class="grid_16" style="margin-top: 20px;">
					<button class="button_colour round_all" type="submit" name="{$T_XCOURSE_CLASS_FORM.submit_xcourse_class.name}" value="{$T_XCOURSE_CLASS_FORM.submit_xcourse_class.value}">
						<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
						<span>{$T_XCOURSE_CLASS_FORM.submit_xcourse_class.value}</span>
					</button>
				</div>
			</div>
		</form>
	</div>
	
	<div id="_XCOURSE_CLASS_CALENDAR_DIALOG" class="blockContents">

	</div>
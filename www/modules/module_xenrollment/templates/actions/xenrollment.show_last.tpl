{if $T_XENROLLMENT_LIST_OPTIONS}
<div class="grid_16 box border flat_area" style="margin-top: 15px">
<h2 style="margin-bottom: 0;">Filtros</h2>
<div class="grid_16">
	
	<div class="headerTools">
		{foreach name="xenrollment_iteration" key="option_index" item="option" from=$T_XENROLLMENT_LIST_OPTIONS}
			{if $option.selected}
				<span>
					<a href="javascript: void(0);" class="selected no-color-link" hint="{$option.hint}">
						<img src="{$option.image}">
						{$option.text}
					</a>
				</span>
			{else}
				<span>
					<a href="{$option.href}" hint="{$option.hint}">
						<img src="{$option.image}">
						{$option.text}
					</a>
				</span>
			{/if}
		{/foreach}
	</div>
</div>
</div>
<div class="clear"></div>
{/if}


<table class = "display" id="_XENROLLMENT_LAST_LIST">
	<thead>
		<tr>
			<th>{$smarty.const.__XENROLLMENT_REGISTER_DATE} </th>
			<th>{$smarty.const.__IES_NAME} </th>
			<th>{$smarty.const._STUDENT}</th>
			<th>{$smarty.const._COURSE}</th>
			<th>{$smarty.const._STATUS}</th>
			<th>{$smarty.const._OPERATIONS}</th>
		</tr>
	</thead>
	<tbody>
		{foreach name = 'enrollment_list' key = 'key' item = 'enroll' from = $T_LAST_ENROLLMENTS_LIST}
			<tr metadata="{Mag_Json_Encode data = $enroll}">
				<td class="center">#filter:date-{$enroll.data_registro}#</td>
				<td class="center">
					<a href="{$smarty.session.s_type}.php?ctg=module&op=module_ies&action=edit_ies&ies_id={$enroll.ies_id}">{$enroll.ies_name}</a>
				</td>
				<td class="center">
					<a href="{$smarty.session.s_type}.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_id={$enroll.users_id}&xuser_login={$enroll.login}">{$enroll.username}</a>
				</td>
				<td class="center">
					<a href="{$smarty.session.s_type}.php?ctg=module&op=module_xcourse&action=edit_xcourse&xcourse_id={$enroll.courses_id}">{$enroll.course_name}
				</td>
				<td class="center">{$enroll.status}</td>
				<td class = "center">
					<div class="button_display">
						<button class="skin_colour round_all editClassLink" onclick="window.location.href = '{$T_XENROLLMENT_BASEURL}&action=edit_xenrollment&xenrollment_id={$enroll.id}'">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
							<span>Editar</span>
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
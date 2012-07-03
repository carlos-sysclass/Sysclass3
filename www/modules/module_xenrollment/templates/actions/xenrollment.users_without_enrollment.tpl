<table class = "display" id="_USERS_WITHOUT_ENROLLMENTS_LIST">
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
		{foreach name = 'enrollment_list' key = 'key' item = 'enroll' from = $T_USERS_WITHOUT_ENROLLMENTS_LIST}
			<tr metadata="{Mag_Json_Encode data = $enroll}">
				<td class="center">#filter:date-{$enroll.data_registro}#</td>
				<td class="center">
					<a href="{$smarty.session.s_type}.php?ctg=module&op=module_ies&action=edit_ies&ies_id={$enroll.ies_id}">{$enroll.ies_name}</a>
				</td>
				<td class="center">
					<a href="{$smarty.session.s_type}.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_id={$enroll.users_id}&xuser_login={$enroll.login}" class="{if $enroll.ativo == 1}xuser-ativo{else}xuser-inativo{/if}">{$enroll.username}</a>
				</td>
				<td class="center">
					<a href="{$smarty.session.s_type}.php?ctg=module&op=module_xcourse&action=edit_xcourse&xcourse_id={$enroll.courses_id}">{$enroll.course_name}
				</td>
				<td class="center">{$enroll.status}</td>
				<td class = "center">
					<div class="button_display">
						
						<button hint="{$smarty.const.__XENROLLMENT_REGISTER_HINT}" class="skin_colour round_all createNewEnrollment" onclick="window.location.href = '{$T_XENROLLMENT_BASEURL}&action=register_xenrollment&xuser_id={$enroll.users_id}&xcourse_id={$enroll.courses_id}&xpayment_id={$enroll.payment_id}'">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/cloud_upload.png">
							<span>Editar</span>
						</button>
					</div>
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>

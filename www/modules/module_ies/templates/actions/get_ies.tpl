{capture name="t_ies_table"}
		<div class="grid_16 box border" style="margin-top: 15px;">
			<div class = "headerTools">
				<span>
			    	<img src = "images/16x16/add.png" title = "{$smarty.const._IES_ADDIES}" alt = "{$smarty.const._IES_ADDIES}">
			        <a href = "{$T_IES_BASEURL}&action=add_ies&ies_id=1"  title = "{$smarty.const._IES_ADDIES}">{$smarty.const._IES_ADDIES}</a>
				</span>
			</div>
		</div>
		<div class="clear"></div>
		<div class="blockContents">
			<table class="display datatable">
				<thead>
					<tr>
						<th>{$smarty.const._NAME}</th>
						<th>{$smarty.const._CITY}</th>
						<th>{$smarty.const._STATE}</th>
						<th>{$smarty.const._PHONE}</th>
						<th>{$smarty.const._ACTIVE}</th>
						<th>{$smarty.const._OPTIONS}</th>
					</tr>
				</thead>
				<tbody>
				{foreach name = 'users_list' key = 'key' item = 'ies' from = $T_IES}
					<tr>
						<td>
							<a href="{$T_IES_BASEURL}&action=edit_ies&ies_id={$ies.id}" id="edit-link-{$ies.id}">{$ies.nome}</a>
						</td>
						<td>{$ies.cidade}</td>
						<td>{$ies.estado}</td>
						<td>{$ies.telefone}</td>
						<td align = "center">
					       	{if $ies.active}
					           	<img class = "ajaxHandle" id="module_status_img" src = "images/16x16/trafficlight_green.png" alt = "{$smarty.const._DEACTIVATE}" title = "{$smarty.const._DEACTIVATE}" onclick = "deactivatePolo(this, {$ies.id})">
							{else}
					           	<img class = "ajaxHandle" id="module_status_img" src = "images/16x16/trafficlight_red.png" alt = "{$smarty.const._ACTIVATE}" title = "{$smarty.const._ACTIVATE}" onclick = "activatePolo(this, {$ies.id})">
							{/if}
						</td>
						<td align = "center">
							<div class="button_display">
								<button class="skin_colour round_all" onclick="window.location.href = '{$T_IES_BASEURL}&action=edit_ies&ies_id={$ies.id}';">
									<img width="16" height="16" src = "/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png" alt = "{$smarty.const._EDIT}" title = "{$smarty.const._EDIT}">
									<span>{$smarty.const._EDIT}</span>
								</button>
								<button class="skin_colour round_all" onclick="window.location.href = '{$T_IES_BASEURL}&action=delete_ies&ies_id={$ies.id}';">
									<img width="16" height="16" src = "/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png" alt = "{$smarty.const._DELETE}" title = "{$smarty.const._DELETE}">
									<span>{$smarty.const._DELETE}</span>
								</button>
							</div>
							
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{/capture}

{sC_template_printBlock 
	title=$smarty.const.__IES_MANAGEMENT
	data=$smarty.capture.t_ies_table
	contentclass=""
}
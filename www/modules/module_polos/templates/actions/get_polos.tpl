{capture name="t_polos_table"}
		<div class = "headerTools">
			<span>
		    	<img src = "images/16x16/add.png" title = "{$smarty.const._MODULE_POLOS_ADDPOLO}" alt = "{$smarty.const._MODULE_POLOS_ADDPOLO}">
		         <a href = "{$T_MODULE_POLOS_BASEURL}&action=add_polo&polo_id=1"  title = "{$smarty.const._MODULE_POLOS_ADDPOLO}">{$smarty.const._MODULE_POLOS_ADDPOLO}</a>
			</span>
		</div>
		 <div class="blockContents">
			<table class="display style1"  id="_XPOLOS_LIST" >
					<thead>
					<tr>
						<th>{$smarty.const.__IES_NAME}</th>
						<th>{$smarty.const._NAME}</th>
						<th>{$smarty.const._CITY}/{$smarty.const.__STATE}</th>
						<th>{$smarty.const.__PHONE}</th>
						<th>{$smarty.const._ACTIVE}</th>
						<th>{$smarty.const._OPTIONS}</th>
					</tr>
				</thead>
				<tbody>
			 
				{foreach name = 'users_list' key = 'key' item = 'polo' from = $T_POLOS}
					<tr>
					
						<td>
							<a href="administrator.php?ctg=module&op=module_ies&action=edit_ies&ies_id={$polo.ies_id}" id="edit-ies-link-{$polo.id}">{$polo.ies}</a>
						</td>
						<td>
							<a href="{$T_MODULE_POLOS_BASEURL}&action=edit_polo&polo_id={$polo.id}" id="edit-link-{$polo.id}">{$polo.nome}</a>
						</td>
						<td>{$polo.cidade} - {$polo.uf}</td>
						<td>{$polo.telefone}</td>
						<td align = "center">
					       	{if $polo.active}
					           	<img class = "ajaxHandle" id="module_status_img" src = "images/16x16/trafficlight_green.png" alt = "{$smarty.const._DEACTIVATE}" title = "{$smarty.const._DEACTIVATE}" onclick = "deactivatePolo(this, {$polo.id})">
							{else}
					           	<img class = "ajaxHandle" id="module_status_img" src = "images/16x16/trafficlight_red.png" alt = "{$smarty.const._ACTIVATE}" title = "{$smarty.const._ACTIVATE}" onclick = "activatePolo(this, {$polo.id})">
							{/if}
						</td>
						<td align = "center">
							<div class="button_display">
								<button class="skin_colour round_all" onclick="window.location.href = '{$T_MODULE_POLOS_BASEURL}&action=edit_polo&polo_id={$polo.id}';">
									<img width="16" height="16" src = "/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png" alt = "{$smarty.const._EDIT}" title = "{$smarty.const._EDIT}">
									<span>{$smarty.const._EDIT}</span>
								</button>
								<button class="skin_colour round_all" onclick="window.location.href = '{$T_MODULE_POLOS_BASEURL}&action=delete_polo&polo_id={$polo.id}';">
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

{eF_template_printBlock 
	title=$smarty.const._MODULE_POLOS_MANAGEMENT
	data=$smarty.capture.t_polos_table
	contentclass=""
}
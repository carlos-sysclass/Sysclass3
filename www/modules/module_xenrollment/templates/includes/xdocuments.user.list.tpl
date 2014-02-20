<ul class="xdocuments_list">
	{foreach name="course_document_list_iteration" key="doc_key" item="doc" from=$T_USER_DOCUMENT_LIST}
		<li class="{if $doc.status}{$doc.status}{else}pendente{/if}" metadata="{Mag_Json_Encode data = $doc}">
			<span class="xdocuments_name">{$doc.name}</span>
			{if $T_SELECTED_COURSE_DOCUMENT && $T_USER_DOCUMENT_LIST_EDITABLE}
				<span class="xdocuments_required">
					<select name="xdocuments_required">
						<option value="1" {if $doc.required == 1}selected="selected"{/if}>{$smarty.const.__XDOCUMENTS_REQUIRED}</option>
						<option value="0" {if $doc.required == 0}selected="selected"{/if}>{$smarty.const.__XDOCUMENTS_OPTIONAL}</option>
					</select>
				</span>
			{else}
				<span class="xdocuments_required">{if $doc.required == 1}{$smarty.const.__XDOCUMENTS_REQUIRED}{else}{$smarty.const.__XDOCUMENTS_OPTIONAL}{/if}</span>
			{/if}
			<span class="xdocuments_status">{$smarty.const.__XDOCUMENTS_STATUS}: <strong>{if $doc.status}{$doc.status}{else}pendente{/if}</strong></span>
			<span class="xdocuments_operations">
				<div class="button_display">
					{if $doc.status != 2}
						<button class="{if $doc.status_id == 1}red{elseif $doc.status_id == 2}green{/if} skin_colour round_all update-user-document-status">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/facebook_like.png">
							<span>Editar</span>
						</button>
					{/if}
					<button class="skin_colour round_all">
						<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/mail.png">
						<span>Editar</span>
					</button>
				</div>
			</span>
		</li>
<!-- 					
    ["course_id"]=>
    string(2) "20"
    ["document_id"]=>
    string(1) "1"
    ["name"]=>
    string(8) "Xerox RG"
    ["description"]=>
    string(41) "Fotocópia do RG ou documento equivalente"
    ["data_registro"]=>
    string(19) "2011-07-20 23:27:00"
    ["type_id"]=>
    string(1) "1"
    ["type"]=>
    string(5) "check"
    ["required"]=>
    string(1) "1"
    ["user_responsible"]=>
    string(1) "1"
    ["user_authority"]=>
    string(1) "1"
    ["enrollment_id"]=>
    string(1) "3"
    ["status_id"]=>
    string(1) "1"
    ["status"]=>
    string(8) "pendente"
    ["register_exists"]=>
    bool(true)
				 -->	
		
	
	{/foreach}
</ul>
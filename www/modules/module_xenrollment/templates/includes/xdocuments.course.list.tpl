<ul class="xdocuments_list">
	{foreach name="course_document_list_iteration" key="doc_key" item="doc" from=$T_COURSE_DOCUMENT_LIST}
		<li class="{if $doc.status}{$doc.status}{else}pendente{/if}" {if $T_SELECTED_COURSE_DOCUMENT}metadata="{Mag_Json_Encode data = $doc}"{/if}>
			<span class="xdocuments_name">{$doc.name}</span>
			{if $T_SELECTED_COURSE_DOCUMENT}
				<span class="xdocuments_required">
					<select name="xdocuments_required">
						<option value="1" {if $doc.required == 1}selected="selected"{/if}>{$smarty.const.__XDOCUMENTS_REQUIRED}</option>
						<option value="0" {if $doc.required == 0}selected="selected"{/if}>{$smarty.const.__XDOCUMENTS_OPTIONAL}</option>
					</select>
				</span>
			{else}
				<span class="xdocuments_required">{if $doc.required == 1}{$smarty.const.__XDOCUMENTS_REQUIRED}{else}{$smarty.const.__XDOCUMENTS_OPTIONAL}{/if}</span>
			{/if}
			<span class="xdocuments_status">&nbsp;</span>
			<span class="xdocuments_operations">
				<div class="button_display">
					{if $T_SELECTED_COURSE_DOCUMENT}
						<button class="red skin_colour round_all delete-course-document-from-list" hint="{$smarty.const.__XDOCUMENTS_DELETE_HINT}">
							<img width="16" height="16" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png">
							<span>{$smarty.const.__XDOCUMENTS_DELETE}</span>
						</button>
					{/if}
				</div>
			</span>
		</li>
	{/foreach}
</ul>
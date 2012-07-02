<ul class="xdocuments_list">
	{foreach name="course_document_list_iteration" key="doc_key" item="doc" from=$T_DOCUMENT_LIST}
		<li metadata="{Mag_Json_Encode data = $doc}">
			<span class="xdocuments_name">
				<input type="radio" name="add_document" value="{$doc.document_id}"/>
				{$doc.name}
			</span>
			<span class="xdocuments_required">{if $doc.required == 1}{$smarty.const.__XDOCUMENTS_REQUIRED}{else}{$smarty.const.__XDOCUMENTS_OPTIONAL}{/if}</span>
			<span class="xdocuments_status">&nbsp;</span>
			<span class="xdocuments_operations">&nbsp;</span>
		</li>
	{/foreach}
</ul>
{capture name="t_get_language_block"}
	<table class="datatable style1 languageDataTable">
		<thead>
			<tr>
				<th>{$smarty.const.__LANGUAGE_NAME_IN_ENGLISH}</th>
				<th>{$smarty.const.__LANGUAGE_LOCALIZED_NAME}</th>
				<th align="center">{$smarty.const.__LANGUAGE_TRANSLATED_TERMS}</th>
				<th align="center">{$smarty.const.__LANGUAGE_MODULES}</th>
				<th>{$smarty.const._OPTIONS}</th>
			</tr>
		</thead>
		<tbody>
			{foreach name="languages_list_iteration" key="lang_key" item="lang_item" from=$T_LANGUAGES}
				<tr>
					<td>{$lang_item.english_name}</td>
					<td>{$lang_item.localized_name}</td>
					<td align="center">{$lang_item.translated_terms} / {$lang_item.translated_terms_total}</td>
					<td align="center">{$lang_item.translated_modules} / {$lang_item.translated_modules_total}</td>
					<td>ff</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{/capture}


{sC_template_printBlock
	title 			= $smarty.const.__LANGUAGE_LIST_BLOCK_TITLE
	data			= $smarty.capture.t_get_language_block
	contentclass	= "no_padding"
}
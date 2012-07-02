{capture name="t_get_language_block"}
	<table class="display datatable">
		<thead>
			<tr>
				<th>{$smarty.const.__LANGUAGE_NAME_IN_ENGLISH}</th>
				<th>{$smarty.const.__LANGUAGE_LOCALIZED_NAME}</th>
				<th>{$smarty.const.__LANGUAGE_TRANSLATED_TERMS}</th>
				<th>{$smarty.const.__LANGUAGE_MODULES}</th>
				<th>{$smarty.const._OPTIONS}</th>
			</tr>
		</thead>
		<tbody>
			{foreach name="languages_list_iteration" key="lang_key" item="lang_item" from=$T_LANGUAGES}
				<tr>
					<td>{$lang_item.english_name}</td>
					<td>{$lang_item.localized_name}</td>
					<td class="center">{$lang_item.translated_terms} / {$lang_item.translated_terms_total}</td>
					<td class="center">{$lang_item.translated_modules} / {$lang_item.translated_modules_total}</td>
					<td></td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{/capture}


{eF_template_printBlock
	title 			= $smarty.const.__LANGUAGE_LIST_BLOCK_TITLE
	data			= $smarty.capture.t_get_language_block
	contentclass	= "no_padding"
}
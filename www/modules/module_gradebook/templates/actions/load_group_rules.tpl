<table class="style1">
	<thead>
		<tr>
			<th>Nome</th>
			<th>Tipo</th>
			<th>Peso</th>
			<th>Conteúdo</th>
			<th>Opções</th>
		</tr>
	</thead>
	<tbody>
		{foreach name = 'columns_loop' key = "id" item = "column" from = $T_GRADEBOOK_LESSON_COLUMNS}
			<tr>
				<th>{$column.name}</th>
				<th>{$column.refers_to_type}</th>
				<th>{$column.weight}</th>
				<th>{$column.content_name}</th>
				<th>
					{if $column.refers_to_type != 'real_world'}
						<a href="{$T_GRADEBOOK_BASEURL}&import_grades={$column.id}" onclick="return confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')"><img src="{$T_GRADEBOOK_BASELINK}images/import.png" alt="{$smarty.const._GRADEBOOK_IMPORT_GRADES}" title="{$smarty.const._GRADEBOOK_IMPORT_GRADES}" border="0"></a>
					{/if}
					<a href="{$T_GRADEBOOK_BASEURL}&delete_column={$column.id}" onclick="return confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')"><img src="{$T_GRADEBOOK_BASELINK}images/delete.png" alt="{$smarty.const._GRADEBOOK_DELETE_COLUMN}" title="{$smarty.const._GRADEBOOK_DELETE_COLUMN}" border="0"></a>
				</th>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="5">{$smarty.const._NODATAFOUND}</td>
			</tr>
		{/foreach}
	</tbody>
</table>
<table class="dynamicTable table table-bordered table-striped table-primary">
	<thead>
		<tr>
		{foreach item="field" from=$T_DATA.columns}
			<th>{$field}</th>
		{/foreach}
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript">
	var tableData 	= {Plico_JsonEncode data=$T_DATA.data};
	$(document).ready(function() {
		jQuery(".dynamicTable").dataTable(tableData);		
	});
</script>
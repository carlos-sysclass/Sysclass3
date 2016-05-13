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
		console.log(tableData);
		jQuery(".dynamicTable").dataTable(
			jQuery.extend(
				true,
				tableData,
				{
					"aoColumns": [
			            { "mData": "nome" },
			            { "mData": "email" },
			            { "mData": "funcao_1" },
			            { "mData": "experiencia_1" },
			            { 
			                "bSortable"	: false,
			                "mData": "action",
							"fnRender" : function(o, val) {
								var links = "";
								for (i in val) { 
									links = links + '[ <a href="' + val[i].href + '">' + val[i].text + '</a> ]'
								}
								return links;
							}
						}
		        	]
				}
			)
		);		
	});
</script>
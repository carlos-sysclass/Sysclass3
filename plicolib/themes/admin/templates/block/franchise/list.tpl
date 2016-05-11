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
		/*
		jQuery.culture = jQuery.cultures["pt-BR"];

		var asCurrency = function(o, val) {
			return jQuery.format(parseInt(val), 'c');
		}
		*/
		jQuery(".dynamicTable").dataTable(
			jQuery.extend(
				true,
				tableData,
				{
					"aoColumns": [
			            { "mData": "firstname" },
			            { "mData": "lastname" },
			            { "mData": "email" },
			            { "mData": "telefone" },
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
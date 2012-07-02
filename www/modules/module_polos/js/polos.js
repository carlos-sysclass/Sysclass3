jQuery(function($) {
	if (jQuery("#_XPOLOS_LIST").size() > 0) {
		var defaults = {
			"bProcessing": true,
			"bJQueryUI": false,
			"bDeferRender" : true,
			"bSortClasses": false,
			"bAutoWidth": true,
			"bInfo": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength"	: 20,
			"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
			//"sDom" : '<"H"lf<"dataTables_outerprocessing"r>>t<"F"ip>',
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			}
		};
		var opt = defaults;

		oPolosTable = jQuery("#_XPOLOS_LIST").dataTable( opt );
	}
	

	
});

jQuery(function($) {
	
	defaults = {
		"bProcessing": true,
		"bDeferRender" : true,
		"bJQueryUI": true,
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bSort": true,
		"bInfo": true,
		"bAutoWidth": true,
		"sScrollY": "100%",	
		"sScrollX": "100%",
		"bScrollCollapse": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength" : 50,
		"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Tudo"]],
		"sDom" : '<"H"lf<"dataTables_outerprocessing"r>>t<"F"ip>',
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	opt = defaults;
				
	var oDashboardTable = jQuery("#_PAGAMENTO_BOLETO_FILE_RETURN_LIST").dataTable( opt );
	
	
	$('form#module_pagamento_boleto_configuration').submit(function () {
//		boleto_demonstrativo.post();
	});
	
	jQuery("#_PAGAMENTO_BOLETO_FILE_RETURN_LIST .invoicePrintLink").click(function() {

	});
	if (typeof(T_MODULE_PAGAMENTO_BOLETO_BASELINK) != "undefined") {

		if ($.fn.fileTree) {
			result = $('.file_tree_sended_files').fileTree({ 
				root: 'retorno/', 
				script: T_MODULE_PAGAMENTO_BOLETO_BASELINK + 'jqueryFileTreeConector.php',
				loadMessage:'Loading...'  
			}, function(file, evt) {
				// VIEW FILE DETAILS
				//console.log(evt);
				
				if ($(evt.currentTarget).parent('li').hasClass("file")) {
					$('.file_tree_sended_files').find('li').removeClass("selected");
					$(evt.currentTarget).parent('li').addClass("selected");
					if (evt.type == 'dblclick') {
						var url = T_MODULE_PAGAMENTO_BOLETO_BASEURL + '&action=update_processed_file&filename=' + file;
						window.location.href = url;
					}
				}
			});
			
			result.find("ul li a").live('click', function() {
			});
			
//			console.log(result.find("ul li a"));
		}
	}
});



(function( $ ){
	var methods = {
		toggleContactListBlock : function(index) {
	    		jQuery(".quick_mails-contact-list").hide();
			jQuery(".quick_mails-contact-list-" + index).toggle(1000, 'easeOutCubic');
		},
		toggleUserInRecipientList : function(recipient_id, user_id, inputRef) {
			var url = 
				window.location.protocol + "//" +
				window.location.hostname +
				window.location.pathname + 
				"?ctg=module&op=module_quick_mails" +
				"&action=toggle_user_in_recipient_list&output=json";
		
			// do Ajax request to toogle user in list
			jQuery.post(
				url,
				{
					'recipient_id'	: recipient_id,
					'user_id'		: user_id
				},
				function(data, status) {
					jQuery.messaging.show(data);
				},
				'json'
			);
		}
	};
	_sysclass("register", "quick_mails", methods).toggleContactListBlock(1);

	  	// DATATABLES WRAPPER
	dataTableDefaults = {
		"bJQueryUI": false,
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bSort": false,
		"bInfo": false,
		"bAutoWidth": true,
		"iDisplayLength"	: 10,
		"aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Tudo"]],
		"bDeferRender" : true,
		"sPaginationType": "full_numbers",
		"bScrollCollapse": true,
		//"sDom": 't<"datatables-header-controls"ilrp>',
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	
	/// CREATE DEFAULT DATATABLES
	jQuery(".quickMailDataTable").dataTable( dataTableDefaults );
})( jQuery );

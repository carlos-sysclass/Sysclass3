xPayAPI = {
	saveSimulatedNegociationAction : function(sendData, callback, output) {
		var actionName = "save_simulated_negociation";
		this._doAction(actionName, sendData, callback, output);
	},
	updateSentInvoiceStatusAction : function(sendData, callback, output) {
		var actionName = "update_send_invoice_status";
		this._doAction(actionName, sendData, callback, output);
	},
	mailInvoicesAdviseAction : function(sendData, callback, output) {
		var actionName = "mail_invoices_advise";
		
		this._doAction(actionName, sendData, callback, output);
	},
	_doAction : function(actionName, sendData, callback, output) {
		var url = 
			window.location.protocol + "//" +
			window.location.hostname +
			window.location.pathname + 
			"?ctg=module&op=module_xpay" +
			"&action=" + actionName + "&output=json";
			
		if (typeof(output) === "undefined" || output === null || output === "") {
			output = "json";
		}

		jQuery.post(
			url,
			sendData,
			function(data, status) {
				if (output == "json") { 
					jQuery.messaging.show(data);
				}
					
				if (typeof(callback) == 'function') {
					callback(data, status);
				}
			},
			'json');
	}
};

jQuery(function($) {
	
	// DATATABLES WRAPPER
	dataTableDefaults = {
		"bJQueryUI": false,
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true,
		"iDisplayLength"	: 25,
		"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Tudo"]],
		"bDeferRender" : true,
		"sPaginationType": "full_numbers",
		//"bScrollCollapse": true,
		//"sDom" : 't',
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	
	/// CREATE DEFAULT DATATABLES
    jQuery.datepicker.regional[""].dateFormat = 'dd/mm/yy';
    jQuery.datepicker.setDefaults($.datepicker.regional['']);
	jQuery(".xpayDataTable").dataTable( dataTableDefaults );

	debtsTableDefaults = {
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
		"sDom": 't<"datatables-header-controls"ilrp>',
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	
	jQuery("#xpay-view_users-in-debts-table").dataTable(debtsTableDefaults).columnFilter({ 
		//sPlaceHolder: "head:after",
		aoColumns: [ 
			{ type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
			{ type: "text" },
			{ type: "text" },
			null,
			null,
			null
		]
	});
	
	//var oTable = jQuery("#xpay-view_users-in-debts-table").dataTable( dataTableDefaults );
/*	 
    jQuery("#xpay-view_users-in-debts-table thead input").keyup( function () {
    	jQuery("#xpay-view_users-in-debts-table").dataTable().fnFilter( this.value, jQuery("#xpay-view_users-in-debts-table thead input").index(this) );
    });
*/    
//    var asInitVals = new Array();
	     
    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
     * the footer
     */
    /*
    jQuery("#xpay-view_users-in-debts-table tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
	     
    jQuery("#xpay-view_users-in-debts-table tfoot input").focus( function () {
        if ( this.className == "search_init" ) {
	        this.className = "";
	        this.value = "";
        }
    } );
	     
    jQuery("#xpay-view_users-in-debts-table tfoot input").blur( function (i) {
        if ( this.value == "" ) {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );
	*/


	
	// GLOBAL HANDLERS 
	jQuery(":input[name='pagamentos']").click(function() {
		window.location.href = "student.php?ctg=module&op=module_xpay&action=do_payment";
	});

	if (typeof($_xpay_mod_data) != 'undefined') {
		if ($_xpay_mod_data["xpay.action"] == "simulate_due_balance_negociation") {
			// BUTTON ACTIONS
			jQuery(".saveNegociation").click(function() {
				// SAVE NEGOCIATION BY HASH $_xpay_mod_data['xpay.negociation_hash']
				alert("Saving " + $_xpay_mod_data['xpay.negociation_hash']);
				
				xPayAPI.saveSimulatedNegociationAction({
					'negociation_hash' : $_xpay_mod_data['xpay.negociation_hash']
				}, function(response, status) {
					alert(response);
				});
			});
			
			jQuery("#xpay-invoice-params-selection").dialog({
				autoOpen: false, 
				show: "fade",
				hide: "fade",
				modal: true,
				width: 'auto',
				resizable: false,
				buttons : {
					'Salvar'	: function() {
						jQuery(this).find("form").submit();
					},
					'Cancelar' 	: function() {
						jQuery(this).dialog('close');
					}
				}
			});
			
			jQuery(".openInvoiceNegociationDialog").click(function() {
				jQuery("#xpay-invoice-params-selection").dialog('open');
			});
			
		}
		
		if ($_xpay_mod_data["xpay.action"] == "view_to_send_invoices_list") {
			var xpayViewToSendInvoicesListTable = jQuery("#xpay-view-to-send-invoices-list-table").dataTable( dataTableDefaults );
		}
	}
	
	// JQuery Tooltip
	jQuery(".applied_rules_link").each(function() {
		jQuery(this).next(".applied_rules").position({
			"my": "left top",
			"at": "right top",
			"of": jQuery(this)
		}).hide();
	}).hover(function() {
		jQuery(this).next(".applied_rules").show();
	},function() {
		jQuery(this).next(".applied_rules").hide();
	});
	
	jQuery(".base_price_details").hide();
	
	// JQuery Tooltip
	jQuery(".base_price_details_link").hover(function() {
		jQuery(".base_price_details").show().position({
			"my": "left top",
			"at": "right top",
			"of": jQuery(this)
		});
	},function() {
		jQuery(".base_price_details").hide();
	});



	//jQuery(".__XPAY_INVOICE_LIST").dataTable( opt );
});

function xPayUpdateSentInvoiceStatus(negociation_id, invoice_index, checkBox) {
	xPayAPI.updateSentInvoiceStatusAction({
		"negociation_id" : negociation_id, 
		"invoice_index"  : invoice_index,
		"active"		 : checkBox.checked 
	}, function(data, response) {
		console.log(data);
	});
}

function xPayMailAllInvoicesAdviseAction() {
	xPayAPI.mailInvoicesAdviseAction({
		"send_all" : true
	}, function(data, response) {
		//console.log(data);
	});
}

function xPayMailInvoicesAdviseAction(negociation_id, invoice_index) {
	xPayAPI.mailInvoicesAdviseAction({
		"negociation_id" : negociation_id, 
		"invoice_index"  : invoice_index 
	}, function(data, response) {
		//console.log(data);
	});
}


/* MODULE CREATING */
(function( $ ) {
	var methods = {
		viewFileDetails : function(method_index, name) {
			this._loadAction(
				"view_file_details",
				{"method_index" : method_index, "name" : name},
				"#xpay-file-details-container",
				function() {
					jQuery("#xpay-file-details-container").dialog('open');		
				}
			);
		},
		importFileToSystem : function(method_index, name) {
			this._postAction(
				"import_file_to_system",
				{"method_index" : method_index, "name" : name},
				function() {},
				'json'
			);
		},
		startUI : function() {
			jQuery("#xpay-file-details-container").dialog({
				autoOpen	: false,
				height		: "auto",
				width		: "auto",
				modal		: true,
				resizable	: false,
				buttons		: {
					"Fechar" : function() {
						jQuery( this ).dialog( "close" );
					}
				},
				close: function() {
				}
			});
			
			jQuery("#xpay-negociation-base-price-details").dialog({
				autoOpen	: false,
				height		: "auto",
				width		: "auto",
				modal		: true,
				resizable	: false,
				buttons		: {
					"Fechar" : function() {
						jQuery( this ).dialog( "close" );
					}
				},
				close: function() {
				}
			});
		}
	};

	_sysclass("register", "xpay", methods);
})( jQuery );


/* MODULE FLOW-LOGIC */

(function( $ ){
	_sysclass('load', 'xpay').startUI();
})( jQuery );




















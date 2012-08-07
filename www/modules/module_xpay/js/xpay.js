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
	jQuery(".xpayDataTable").dataTable( dataTableDefaults );
	
	// GLOBAL HANDLERS 
	jQuery(":input[name='pagamentos']").click(function() {
		window.location.href = "student.php?ctg=module&op=module_xpay&action=do_payment";
	});

	if (typeof($_xpay_mod_data) != 'undefined') {
		if ($_xpay_mod_data["xpay.action"] == "do_payment") {
			jQuery(":input[name='pay_methods']").click(function() {
				jQuery(this).parents("form").submit();
			});
		}
	
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
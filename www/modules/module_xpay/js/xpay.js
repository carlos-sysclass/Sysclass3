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
	
	/// CREATE DEFAULT DATATABLES
    jQuery.datepicker.regional[""].dateFormat = 'dd/mm/yy';
    jQuery.datepicker.setDefaults($.datepicker.regional['']);
	jQuery(".xpayDataTable").dataTable( dataTableDefaults );
	
	if (jQuery("#xpay-last-paid-invoices-table").size() > 0) {
		
		dataTablePaidDefaults = jQuery.extend(true, dataTableDefaults, {
			fnInitComplete : function(oSettings, json) {
				jQuery(":input[name='filter_column_3']").change(function() {
					jQuery(oSettings.nTable).dataTable().fnFilter( 
		    			jQuery(this).val(),
		    			4, 	
		    			true
			    	);
				});
			},
			fnFooterCallback : function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
				/*
				var iTotalValor = 0;
				var iTotalPago = 0;
				var iTotalSaldo = 0;

				for ( var i=0 ; i<aaData.length ; i++ )
				{
					iTotalValor += parseFloat(aaData[i][3].replace('R$', '').replace('.', '').replace(',','.'));
					iTotalPago += parseFloat(aaData[i][4].replace('R$', '').replace('.', '').replace(',','.'));
					iTotalSaldo += parseFloat(aaData[i][5].replace('R$', '').replace('.', '').replace(',','.'));
				}
				*/
				// Calculate the market share for browsers on this page
				var iFilterValor = 0;
				var iFilterPago = 0;
				var iFilterSaldo = 0;
				for ( var i=0 ; i<aiDisplay.length ; i++ )
				{
					iFilterValor += parseFloat(aaData[ aiDisplay[i] ][6].replace('R$', '').replace('.', '').replace(',','.'));
					iFilterPago += parseFloat(aaData[ aiDisplay[i] ][7].replace('R$', '').replace('.', '').replace(',','.'));
					iFilterSaldo += parseFloat(aaData[ aiDisplay[i] ][8].replace('R$', '').replace('.', '').replace(',','.'));
				}
				
				// Calculate the market share for browsers on this page
				var iPageValor = 0;
				var iPagePago = 0;
				var iPageSaldo = 0;
				for ( var i=iStart ; i<iEnd ; i++ ) {
					iPageValor += parseFloat(aaData[ aiDisplay[i] ][6].replace('R$', '').replace('.', '').replace(',','.'));
					iPagePago += parseFloat(aaData[ aiDisplay[i] ][7].replace('R$', '').replace('.', '').replace(',','.'));
					iPageSaldo += parseFloat(aaData[ aiDisplay[i] ][8].replace('R$', '').replace('.', '').replace(',','.'));
				}

				jQuery(nRow).next().children().eq(1).html(
					Globalize.format( iPageValor, "c" )
				);
				jQuery(nRow).next().children().eq(2).html(
					Globalize.format( iPagePago, "c" )
				);
				jQuery(nRow).next().children().eq(3).html(
					Globalize.format( iPageSaldo, "c" )
				);
				
				jQuery(nRow).next().next().children().eq(1).html(
					Globalize.format( iFilterValor, "c" )
				);
				jQuery(nRow).next().next().children().eq(2).html(
					Globalize.format( iFilterPago, "c" )
				);
				jQuery(nRow).next().next().children().eq(3).html(
					Globalize.format( iFilterSaldo, "c" )
				);
			}
		});		
		
		
		jQuery("#xpay-last-paid-invoices-table").dataTable(dataTablePaidDefaults).columnFilter({ 
			aoColumns: [
			    { type: "text" },
				{ type: "text" },
				{ type: "text" },
				null,
				{ type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
				{ type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
				/* { type: "select", values: ["manual", "boleto"] }, */
				null,
				null,
				null
			]
		});
	}
	
	if (jQuery("#xpay-view-to-send-invoices-list-table").size() > 0) {
		jQuery("#xpay-view-to-send-invoices-list-table").dataTable(dataTableDefaults).columnFilter({ 
			aoColumns: [ 
				{ type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
				null,
				{ type: "text" },
				{ type: "text" },
				null,
				null,
				null,
				null,
				null
			]
		});
	}
	
	
	if (jQuery("#xpay-view-unpaid-invoices-table").size() > 0 || jQuery("#xpay-view_users-in-debts-table").size() > 0) {
		dataTableDebtsAndUnpaidDefaults = jQuery.extend(true, dataTableDefaults, {
			fnInitComplete : function(oSettings, json) {
				jQuery(":input[name='filter_column_4']").change(function() {
					jQuery(oSettings.nTable).dataTable().fnFilter( 
		    			jQuery(this).val(),
		    			4, 	
		    			true
			    	);
				});
			},
			fnFooterCallback : function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
				
				
				/*
				var iTotalValor = 0;
				var iTotalPago = 0;
				var iTotalSaldo = 0;

				for ( var i=0 ; i<aaData.length ; i++ )
				{
					iTotalValor += parseFloat(aaData[i][3].replace('R$', '').replace('.', '').replace(',','.'));
					iTotalPago += parseFloat(aaData[i][4].replace('R$', '').replace('.', '').replace(',','.'));
					iTotalSaldo += parseFloat(aaData[i][5].replace('R$', '').replace('.', '').replace(',','.'));
				}
				*/
				// Calculate the market share for browsers on this page
				var iFilterValor = 0;
				var iFilterPago = 0;
				var iFilterSaldo = 0;
				for ( var i=0 ; i<aiDisplay.length ; i++ )
				{
					iFilterValor += parseFloat(aaData[ aiDisplay[i] ][5].replace('R$', '').replace('.', '').replace(',','.'));
					iFilterPago += parseFloat(aaData[ aiDisplay[i] ][6].replace('R$', '').replace('.', '').replace(',','.'));
					iFilterSaldo += parseFloat(aaData[ aiDisplay[i] ][7].replace('R$', '').replace('.', '').replace(',','.'));
				}
				
				// Calculate the market share for browsers on this page
				var iPageValor = 0;
				var iPagePago = 0;
				var iPageSaldo = 0;
				for ( var i=iStart ; i<iEnd ; i++ ) {
					iPageValor += parseFloat(aaData[ aiDisplay[i] ][5].replace('R$', '').replace('.', '').replace(',','.'));
					iPagePago += parseFloat(aaData[ aiDisplay[i] ][6].replace('R$', '').replace('.', '').replace(',','.'));
					iPageSaldo += parseFloat(aaData[ aiDisplay[i] ][7].replace('R$', '').replace('.', '').replace(',','.'));
				}

				jQuery(nRow).next().children().eq(1).html(
					Globalize.format( iPageValor, "c" )
				);
				jQuery(nRow).next().children().eq(2).html(
					Globalize.format( iPagePago, "c" )
				);
				jQuery(nRow).next().children().eq(3).html(
					Globalize.format( iPageSaldo, "c" )
				);
				
				jQuery(nRow).next().next().children().eq(1).html(
					Globalize.format( iFilterValor, "c" )
				);
				jQuery(nRow).next().next().children().eq(2).html(
					Globalize.format( iFilterPago, "c" )
				);
				jQuery(nRow).next().next().children().eq(3).html(
					Globalize.format( iFilterSaldo, "c" )
				);
			}
		});
	}
	

	if (jQuery("#xpay-view-unpaid-invoices-table").size() > 0) {
		jQuery("#xpay-view-unpaid-invoices-table").dataTable(dataTableDebtsAndUnpaidDefaults).columnFilter({ 
			aoColumns: [ 
				{ type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
				{ type: "text" },
				{ type: "select", values: ["ULT", "FATI"] }, // GET THOSE VALUES FROM JSON
				{ type: "text" },
				null,
				null,
				null,
				null
			]
		});
	}
	
	


	if (jQuery("#xpay-view_users-in-debts-table").size() > 0) {
		jQuery("#xpay-view_users-in-debts-table").dataTable(dataTableDebtsAndUnpaidDefaults).columnFilter({ 
			aoColumns: [ 
				{ type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
				{ type: "text" },
				{ type: "select", values: ["ULT", "FATI"] }, // GET THOSE VALUES FROM JSON
				{ type: "text" },
				null,
				null,
				null,
				null
			]
		});
	}
	
	if (jQuery("#xpay-edit-negociation-table tbody tr").size() > 0) {
		
		if (jQuery("#xpay-edit-negociation-table tbody tr td.datatable-not-found").size == 0) {
			// DATATABLES WRAPPER
			EditNegociationDataTableDefaults = {
				"bJQueryUI": false,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": false,
				"bSort": true,
				"bInfo": false,
				"bAutoWidth": true,
				"bDeferRender" : true,
				"bScrollCollapse": true,
				"sDom": 't',
				"fnFooterCallback" : function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
					var iTotalValor = 0, iTotalReajuste = 0, iTotalPago = 0, iTotalSaldo = 0;
	
					for ( var i=0 ; i<aaData.length ; i++ )
					{
						iTotalValor 	+= parseFloat(aaData[i][2].replace('R$', '').replace('.', '').replace(',','.'));
						iTotalReajuste	+= parseFloat(aaData[i][3].replace('R$', '').replace('.', '').replace(',','.'));
						iTotalPago 		+= parseFloat(aaData[i][4].replace('R$', '').replace('.', '').replace(',','.'));
						iTotalSaldo 	+= parseFloat(aaData[i][5].replace('R$', '').replace('.', '').replace(',','.'));
					}
	
	
					jQuery(nRow).children().eq(2).html(
						Globalize.format( iTotalValor, "c" )
					);
					jQuery(nRow).children().eq(3).html(
						Globalize.format( iTotalReajuste, "c" )
					);
					jQuery(nRow).children().eq(4).html(
						Globalize.format( iTotalPago, "c" )
					);
					jQuery(nRow).children().eq(5).html(
						Globalize.format( iTotalSaldo, "c" )
					);
				}
			};
			jQuery("#xpay-edit-negociation-table").dataTable(EditNegociationDataTableDefaults);
		}
	}	
	
	
	
	
	
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
		//console.log(data);
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
		viewInstanceOptions : function(instance_index) {
			this._loadAction(
				"view_instance_options",
				{"instance_index" : instance_index},
				"#xpay-submodule-options-container",
				function() {
					//jQuery("#xpay-file-details-container").dialog('open');		
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
		saveInvoices : function() {
			/*
			if (negociationType != "id" || negociationType != "hash") {
				negociationType == 'hash';
			}
			*/

			this._postAction(
				"save_invoices",
				{"negociation_id" : this.opt.negociation_hash},
				function(data, status) {
					
				},
				'json'
			);
		},
		updateNegociationAction : function(negociation_id, data) {
			var sendData = jQuery.extend(true, {"negociation_id" : negociation_id}, data);
			this._postAction(
				"update_negociation",
				sendData,
				function(data, status) {
				},
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

			jQuery(":input[name='invoice_indexes']").click(function() {
				if (jQuery(this).parents("tr").hasClass("xpay-paid")) {
					jQuery("#xpay-do-payment-button span").html(
						_sysclass("load", "i18n").text("__XPAY_VIEW_COPY")	
					);
				} else {
					jQuery("#xpay-do-payment-button span").html(
						_sysclass("load", "i18n").text("__XPAY_DO_PAY")
					);
				}
			});
			
			
			jQuery(":input[name='pay_methods']").live('click', function() {
				_sysclass("load", "xpay").viewInstanceOptions(jQuery(this).val());
				
				jQuery("#xpay-do-payment-button")
					.removeAttr("disabled")
					.removeClass("ui-state-disabled");
			});
			
			// CREATE DIALOG FORM DO-PAY OPTIONS
			jQuery("#xpay-do_payment-options-dialog").dialog({
				autoOpen	: false,
				height		: "auto",
				width		: "auto",
				modal		: true,
				resizable	: false,
				close: function() {
					
				}
			});
			/// DISABLE PAY BUTTON
			jQuery("#xpay-do-payment-button")
				.attr("disabled", "disabled")
				.addClass("ui-state-disabled");
			
			jQuery(".xpay-do_payment-options-dialog-link").click(function() {
				var url = jQuery(this).attr("href");
				jQuery("#xpay-do_payment-options-dialog-inner").empty();
				jQuery("#xpay-do_payment-options-dialog-loader").show();
				jQuery("#xpay-do_payment-options-dialog").dialog('open');
				
				jQuery("#xpay-do_payment-options-dialog-inner").load(url, function() {
					jQuery("#xpay-do_payment-options-dialog-loader").hide();
					
					if (jQuery(":input[name='pay_methods']:checked").size() > 0) {
						jQuery(":input[name='pay_methods']:checked").click();
					};
					
					jQuery("#xpay-do_payment-options-dialog").dialog("widget").position({
					   my: "center",
					   at: "center",
					   of: window
					});
				});
				
				
				return false;
			});
			
			// CREATE DIALOG FORM DO-PAY OPTIONS
			jQuery("#xpay-add_discount_rule-options-dialog").dialog({
				autoOpen	: false,
				height		: "auto",
				width		: "auto",
				modal		: true,
				resizable	: false,
				buttons : {
					"Salvar" : function() {
						$postData = jQuery(this).find("form").serialize();
						
						var url = jQuery(this).find("form").attr("action");
						
						jQuery.post(
							url,
							$postData,
							function(data, status) {
								
								var url = window.location.href + "&message=" + data.message + "&message_type=" + data.message_type;
								window.location.href = url;
							},
							'json'
						);
						//jQuery(this).dialog("close");
						
						
					},
					"Cancelar" : function() {
						jQuery(this).dialog("close");
						
					}
				},
				close: function() {
					
				}
			});
			
			jQuery(".xpay-add_discount_rule-dialog-link").click(function() {
				var url = jQuery(this).attr("href");
				
				jQuery("#xpay-add_discount_rule-options-dialog-inner").empty();
				jQuery("#xpay-add_discount_rule-options-dialog-loader").show();
				jQuery("#xpay-add_discount_rule-options-dialog").dialog('open');
				
				jQuery("#xpay-add_discount_rule-options-dialog-inner").load(url, function() {
					jQuery(this).find('input:text').setMask({autoTab: false});
					
					jQuery(this).find(".xpay-show-on-percentual").hide();
					jQuery(this).find("#xpay-show-on-percentual-" + jQuery(this).find(":input[name='percentual']").val()).show();
					
					jQuery(this).find(":input[name='percentual']").change(function() {
						jQuery(this).parents("form").find(".xpay-show-on-percentual").hide();
						jQuery(this).parents("form").find("#xpay-show-on-percentual-" + jQuery(this).val()).show();
						
					});
					
					jQuery("#xpay-add_discount_rule-options-dialog-loader").hide();
					
					jQuery("#xpay-add_discount_rule-options-dialog").dialog("widget").position({
					   my: "center",
					   at: "center",
					   of: window
					});
				});
				
				
				return false;
			});
			/*
			jQuery(":input[name='xpay-sendto-option']").change(function() {
				alert(jQuery(this).val());
				
				
				this.updateNegociationAction(, {send_to: jQuery(this).val()});
				
			})
			*/;

		}
	};

	_sysclass("register", "xpay", methods);
})( jQuery );


/* MODULE FLOW-LOGIC */

(function( $ ){
	_sysclass('load', 'xpay').startUI();
})( jQuery );

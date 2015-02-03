xPaymentAPI = {
	registerEnrollmentPaymentAction : function(token, sendData, callback) {
		var actionName = "register_enrollment_payment";
		this._doAction(actionName, token, sendData, callback);
	},
	registerEnrollmentPaymentAction : function(token, sendData, callback) {
		var actionName = "register_enrollment_payment";
		this._doAction(actionName, token, sendData, callback);
	},
	insertIntoSendListAction : function(p_payment_id, p_parcela_index, callback) {
		var actionName = "insert_into_send_list";
		
		var sendData = {
			payment_id 		: p_payment_id,
			parcela_index 	: p_parcela_index
		};
		this._doAction(actionName, null, sendData, callback);
	},
	removeFromSendListAction : function(p_payment_id, p_parcela_index, callback) {
		var actionName = "remove_from_send_list";
		
		var sendData = {
			payment_id 		: p_payment_id,
			parcela_index 	: p_parcela_index
		};
		this._doAction(actionName, null, sendData, callback);
	},
	/*
	updateCourseDocumentsAction : function(token, sendData, callback) {
		var actionName = "update_course_documents";
		this._doAction(actionName, token, sendData, callback);
	},
	deleteCourseDocumentsAction : function(token, sendData, callback) {
		var actionName = "delete_course_documents";
		this._doAction(actionName, token, sendData, callback);
	},
	updateUserDocumentsAction : function(token, sendData, callback) {
		var actionName = "update_user_documents";
		this._doAction(actionName, token, sendData, callback);
	},
	*/
	_doAction : function(actionName, token, sendData, callback) {
		var url = 
			window.location.protocol + "//" +
			window.location.hostname +
			window.location.pathname + 
			"?ctg=module&op=module_pagamento" +
			"&action=" + actionName + "&output=json";
			
		jQuery.post(
				url,
				sendData,
				function(data, status) {
					jQuery.messaging.show(data);
					
					if (typeof(callback) == 'function') {
						callback(data, status);
					}
				},
				'json');
	}
};

(function(jQuery){
	if (jQuery.fn.slider) {

		checkSender = function() {
			if (jQuery(this).is(":checked")) {
				jQuery(this).nextAll(".ui-slider").removeClass("ui-state-disabled").slider("enable");
				jQuery(this).nextAll(".ui-slider-indicator").removeClass("ui-state-disabled");
			} else {
				jQuery(this).nextAll(".ui-slider").addClass("ui-state-disabled").slider("disable");
				jQuery(this).nextAll(".ui-slider-indicator").addClass("ui-state-disabled");
			}
		};
	
		jQuery(".sender_toogle").click(checkSender);
		jQuery("#sender_student_ammount_slider").slider({
			range	: "min",
			min 	: 0,
			max		: 100,
			value 	: jQuery(":input[name='sender_student_ammount']").val(),
			slide: function( event, ui ) {
				jQuery(this).prev(".ui-slider-indicator").html("<strong>" + ui.value + "%</strong>");
				// REFRESH OTHER, TO MANTAIN 100% ON ALL
			},
			change: function( event, ui ) {
				jQuery(":input[name='sender_student_ammount']").val(ui.value);
				// REFRESH OTHER, TO MANTAIN 100% ON ALL
			}
		});
		jQuery("#sender_student_ammount_slider").prev(".ui-slider-indicator").html("<strong>" + jQuery(":input[name='sender_student_ammount']").val() + "%</strong>");
		
		jQuery("#sender_parent_ammount_slider").slider({
			range	: "min",
			min 	: 0,
			max		: 100,
			value 	: jQuery(":input[name='sender_parent_ammount']").val(),
			slide: function( event, ui ) {
				jQuery(this).prev(".ui-slider-indicator").html("<strong>" + ui.value + "%</strong>");
				// REFRESH OTHER, TO MANTAIN 100% ON ALL
				
			},
			change: function( event, ui ) {
				jQuery(":input[name='sender_parent_ammount']").val(ui.value);
				// REFRESH OTHER, TO MANTAIN 100% ON ALL
				
			}
		});
		jQuery("#sender_parent_ammount_slider").prev(".ui-slider-indicator").html("<strong>" + jQuery(":input[name='sender_parent_ammount']").val() + "%</strong>");
		
		jQuery("#sender_financial_ammount_slider").slider({
			range	: "min",
			min 	: 0,
			max		: 100,
			value 	: jQuery(":input[name='sender_financial_ammount']").val(),
			slide: function( event, ui ) {
				jQuery(this).prev(".ui-slider-indicator").html("<strong>" + ui.value + "%</strong>");
				// REFRESH OTHER, TO MANTAIN 100% ON ALL
				
			},
			change: function( event, ui ) {
				jQuery(":input[name='sender_financial_ammount']").val(ui.value);
				// REFRESH OTHER, TO MANTAIN 100% ON ALL
				
			}
		});
		jQuery("#sender_financial_ammount_slider").prev(".ui-slider-indicator").html("<strong>" + jQuery(":input[name='sender_financial_ammount']").val() + "%</strong>");
		
		jQuery(".sender_toogle").each(checkSender);
	}

	// INITAL CHECK
	defaults = {
		"bProcessing": true,
		//"bDeferRender" : true,
		"bJQueryUI": true,
		//"bPaginate": true,
		"bSortClasses": false,
		"bAutoWidth": true,
		//"bLengthChange": true,
		//"bFilter": true,
		//"bSort": true,
		"bInfo": true,
		"sScrollY": "100%",	
		"sScrollX": "100%",
		"bScrollCollapse": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength" : 50,
		"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Tudo"]],
		//"sDom" : '<"H"lf<"dataTables_outerprocessing"r>>t<"F"ip>',
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	
	opt = defaults;
				
	var oLastReceivedTable = jQuery("#_XPAYMENT_LAST_RECEIVED_LIST").dataTable( opt );
	/*
	defaults = {
			"bProcessing": true,
			"bJQueryUI": true,
			"bSortClasses": false,
			"bAutoWidth": true,
			"bInfo": true,
			"sScrollY": "100%",	
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength"	: 10,
			"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			}
		};
		opt = defaults;
		
		var o_xCourseClassTable = jQuery("#_XENROLLMENT_LAST_LIST").dataTable( opt );	
	*/
	
	
	defaults = {
			"bProcessing": true,
			"bJQueryUI": true,
			"bSortClasses": false,
			"bAutoWidth": true,
			"bInfo": true,
			"sScrollY": "100%",	
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength"	: 10,
			"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			}/*,
			aoColumns : [
			    null,
			    { "sType": "d_m_Y" },
			    null,
			    null,
			    null
			]*/
		};
		opt = defaults;
				
	var oPaymentTypesTable = jQuery("#_XPAYMENT_TYPES_LIST").dataTable( opt );
	
	//
	
	defaults = {
		"bProcessing": true,
		"bJQueryUI": true,
		"bSortClasses": false,
//		"bSort" : false,
		"bAutoWidth": true,
		"bInfo": true,
		"sScrollY": "100%",	
		"sScrollX": "99%",
		"bScrollCollapse": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength"	: 50,
		"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	
	if (typeof($_xpayment_mod_data) != 'undefined' && $_xpayment_mod_data['list_is_group']) {
		groupDefaults = {
			"bSort" : false
		};
		jQuery.extend(defaults, groupDefaults);
	} else {
		ungroupDefaults = {
			aoColumns : [
			    null,
			    null,
			    null,
			    null,
			    { "sType": "d_m_Y" },
			    { "sType": "d_m_Y" },
			    null,
			    null,
			    null
			]
		};
		jQuery.extend(defaults, ungroupDefaults);
	}
	opt = defaults;
	
	var oPaidInvoicesTable = jQuery("#_PAGAMENTO_PAID_INVOICES_LIST").dataTable( opt );
	
	
	defaults = {
		"bProcessing": true,
		"bJQueryUI": true,
		"bSortClasses": false,
		"bAutoWidth": true,
		"bInfo": true,
		"sScrollY": "100%",	
		"sScrollX": "100%",
		"bScrollCollapse": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength"	: 50,
		"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
		"oLanguage": {
			"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	
	if (typeof($_xpayment_mod_data) != 'undefined' && $_xpayment_mod_data['list_is_group']) {
		groupDefaults = {
			"bSort" : false
		};
		jQuery.extend(defaults, groupDefaults);
	} else {
		ungroupDefaults = {
			aoColumns : [
			    null,
			    null,
			    null,
			    null,
			    { "sType": "d_m_Y" },
			    null,
			    null
			]
		};
		jQuery.extend(defaults, ungroupDefaults);
	}
	
	opt = defaults;
	
	var oUnpaidInvoicesTable = jQuery("#_PAGAMENTO_UNPAID_INVOICES_LIST").dataTable( opt );
	/*
	var oUnpaidInvoicesTable = jQuery("#_PAGAMENTO_UNPAID_INVOICES_LIST").dataTable();
	oUnpaidInvoicesTable.fnAdjustColumnSizing();
	*/
	
/*	
	jQuery('#_PAGAMENTO_PAID_INVOICES_LIST tbody tr').click( function () {
		var that = this;
		oPaidInvoicesTable.fnOpen( this, "Temporary row opened", "info_row" );
		jQuery('#_PAGAMENTO_PAID_INVOICES_LIST .info_row').click( function () {
			oPaidInvoicesTable.fnClose(that);
		});
	});
*/
	
	defaults = {
			"bJQueryUI": false,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": true,
			"bDeferRender" : true,
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
		}
	};
	opt = defaults;
				
	var oDashboardTable = jQuery("#_PAGAMENTO_DASHBOARD_INVOICES").dataTable( opt );
	
	
	if (typeof($_xpayment_mod_data) != 'undefined' && $_xpayment_mod_data['pagamento.action'] == 'view_sended_invoices_list') {
		defaults = {
			"bProcessing": true,
			"bJQueryUI": true,
			"bSortClasses": false,
			"bAutoWidth": true,
			"bInfo": true,
			"sScrollY": "100%",	
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength"	: 50,
			"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			},
			aoColumns : [
			    { "sType": "d_m_Y" },
			    null,
			    null,
			    null,
			    null
			]
		};
		opt = defaults;
					
		var oLastSendedInvoices = jQuery("#_XPAYMENT_LAST_SENDED_LIST").dataTable( opt );	
	}
	
	if (typeof($_xpayment_mod_data) != 'undefined' && $_xpayment_mod_data['pagamento.action'] == 'view_to_send_invoices_list') {
		defaults = {
			"bProcessing": true,
			"bJQueryUI": true,
			"bSortClasses": false,
			"bAutoWidth": true,
			"bInfo": true,
			"sScrollY": "100%",	
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength"	: 50,
			"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			},
			aoColumns : [
			    { "sType": "d_m_Y" },
			    null,
			    null,
			    null,
			    null,
			    null,
			    null
			]
		};
		opt = defaults;
					
		var oToSendListTable = jQuery("#_XPAYMENT_TO_SEND_LIST").dataTable( opt );
		
		jQuery(".appendToEmailList").click(function() {
			item = jQuery(this).parents("tr").metadata();
			
			// SEND ID BY AJAX
			xPaymentAPI.insertIntoSendListAction(item['payment_id'], item['parcela_index']);
			
			// PUT ON SEND LIST
			var template = 
				'<li>' + 
				'	<a onclick="xPaymentAPI.removeFromSendListAction(' + item['payment_id'] + ',' + item['parcela_index'] +'); jQuery(this).parents(\'li\').remove();" href="javascript: void(0);">' +
				'		<img width="24" height="24" src="/themes/sysclass/images/icons/small/grey/delete.png" alt="Check">' +
				'	</a>' +
				'	<span class="check-item-ok">' + 
				'		' + item['name'] + ' ' + item['surname'] + ' - ' + 
				'		Parcela ' + item['parcela_index'] + '/' + item['parcela_total'] + '-' + 
				'		Venc.:' + item['data_vencimento_string'] + ' - ' + 
				'		Valor: ' + item['valor_string'] + 
				'	</span>' + 
				'</li>';
			
				jQuery("#xpayment_email_send_list").append(template);
				
				oToSendListTable.fnDeleteRow( jQuery(this).parents("tr") );
		});
	}

	
	
	
	jQuery("#__PAGAMENTO_INVOICES_LIST .paidLink").click(function() {
		// LOCK OR UNLOCK INVOICE!
		invoiceData = jQuery(this).parents("tr").metadata();
		
		var pago = null;
		if (jQuery(this).hasClass('red')) { // PENDENTE! PAGAR
			pago = 1;
		} else { // PAGO! PENDENTE
			pago = 0;
		}
		
		var self = this;
		
		updateInvoiceByID(invoiceData['payment_id'], invoiceData['parcela_index'], {"pago" : pago}, function(data, status) {
   			jQuery(self).toggleClass('red').toggleClass('green');
   			
   				jQuery(self).parents("tr").toggleClass("pago");
   				
   				if (pago == 1) {
   					jQuery(self).parents("tr").find(".invoicePrintLink").css("visibility", "hidden");
   				} else {
   					jQuery(self).parents("tr").find(".invoicePrintLink").css("visibility", "visible");
   				}
   				
		});
	});
	
	jQuery("#__PAGAMENTO_INVOICES_LIST .lockLink").click(function() {
		// LOCK OR UNLOCK INVOICE!
		invoiceData = jQuery(this).parents("tr").metadata();
		var bloqueio = null;
		if (jQuery(this).hasClass('red')) { // LOCKED! Unlock
			bloqueio = 0;
		} else { // UNLOCKED! lock
			bloqueio = 1;
		}
		
		var self = this;
		
		updateInvoiceByID(invoiceData['payment_id'], invoiceData['parcela_index'], {"bloqueio" : bloqueio}, function(data, status) {
   			jQuery(self).toggleClass('red').toggleClass('green');
   			
			jQuery(self).parents("tr").toggleClass("bloqueio");
		});
	});
	
	if (jQuery.fn.dialog) {
		jQuery("#_PAGAMENTO_METHOD_SELECT").dialog({
			autoOpen: false, 
			show: "fade",
			hide: "fade",
			modal: true,
			width: 'auto',
			buttons : {
				'Salvar'	: function() {
					// SAVE AND CLOSE, UPDATE TABLE TOO.
					sentData = {};
					user_login = jQuery(this).data('user_login');
					payment_id = jQuery(this).data('payment_id');
					
					jQuery.post(
						window.location.pathname + "?ctg=module&op=module_pagamento&action=save_user_payment&xuser_login=" + user_login + "&payment_id=" + payment_id,
						jQuery("#module_pagamento_payment_type_select").serializeArray(),
						function(data, status) {
							// REFRESH WINDOW, OR REFRESH SCREEN ???
							
							jQuery("#_PAGAMENTO_METHOD_SELECT_DESCRIPTION").html(data['method_description']);
							
							window.location.href = 
								window.location.protocol + "//" +
								window.location.hostname +
								window.location.pathname +
								window.location.search + 
								"#Detalhes_financeiros";
							
							window.location.reload(true);
							
							//window.location.reload(true);
							/*
							jQuery("#_PAGAMENTO_COURSETYPE_CHANGELINK-" + sentData.course_id).html(
								sentData.course_type	
							);
							 */
						},
						'json');
			
					jQuery(this).dialog('close');
				},
				'Cancelar' 	: function() {
					jQuery(this).dialog('close');
				}
			}
		});
		
		jQuery("#_PAGAMENTO_NOTIMPLEMENTEDYET_DIALOG").dialog({
			autoOpen: false, 
			//show: "fade",
			//hide: "fade",
			modal: true,
			width: 'auto',
			buttons : {
				'ok'	: function() {
					// SAVE AND CLOSE, UPDATE TABLE TOO.
					jQuery(this).dialog('close');
				}
			}
		});
		
		jQuery("#_PAGAMENTO_INVOICES_DIALOG").dialog({
			autoOpen: false, 
			show: "fade",
			hide: "fade",
			modal: true,
			width: 'auto',
			buttons : {
				'Salvar'	: function() {
					// SAVE AND CLOSE, UPDATE TABLE TOO.
					user_login = jQuery(this).data('user_login');
					payment_id = jQuery(this).data('payment_id');
					invoice_index = jQuery(this).data('invoice_index');
					
					if (jQuery("#module_pagamento_update_invoice :input[name='vencimento']").datepicker("getDate")) {
	
						jQuery("#module_pagamento_update_invoice :input[name='vencimento']").val(
							jQuery("#module_pagamento_update_invoice :input[name='vencimento']").datepicker("getDate").toString("yyyy-MM-dd HH:mm:ss"));
					} else {
						jQuery("#module_pagamento_update_invoice :input[name='vencimento']").val('');
					}
			
					jQuery.post(
						window.location.pathname + "?ctg=module&op=module_pagamento&action=save_user_invoice&user_login=" + user_login + "&payment_id=" + payment_id + "&invoice_index=" + invoice_index,
						jQuery("#module_pagamento_update_invoice").serializeArray(),
						function(data, status) {
							window.location.href = 
								window.location.protocol + "//" +
								window.location.hostname +
								window.location.pathname +
								window.location.search + 
								"#Detalhes_financeiros";
							
							window.location.reload(true);
						}
					);
			
					jQuery(this).dialog('close');
				},
				'Cancelar' 	: function() {
					jQuery(this).dialog('close');
				}
			}
		});
	}
	
	jQuery("#__PAGAMENTO_INVOICES_LIST .editLink").click(function() {
		invoiceMetadata = jQuery(this).parents('tr').metadata();
		
		jQuery("#_PAGAMENTO_INVOICES_DIALOG").find(":input[name='status_id']").val(invoiceMetadata['status_id']);
		
		//uery.uniform.update(":input[name='status_id']");
		
		if (/*invoiceMetadata['parcela_index'] == 1 || */ invoiceMetadata['data_vencimento'] === null) {
			/*
			jQuery("#_PAGAMENTO_INVOICES_DIALOG")
				.find(":input[name='vencimento_mes']")
				.val(new Date().getMonth() + 1);
			*/
			
			
			jQuery("#_PAGAMENTO_INVOICES_DIALOG").find(":input[name='vencimento']").datepicker('setDate', Date.today());

		} else {
			/*
			jQuery("#_PAGAMENTO_INVOICES_DIALOG")
				.find(":input[name='vencimento_mes']")
				.val(Date.parse(invoiceMetadata['data_vencimento']).getMonth() + 1);
			*/
			jQuery("#_PAGAMENTO_INVOICES_DIALOG").find(":input[name='vencimento']").datepicker('setDate', Date.parse(invoiceMetadata['data_vencimento']));
		}

		//jQuery.uniform.update(":input[name='vencimento_mes']");
		
		jQuery("#_PAGAMENTO_INVOICES_DIALOG").data("user_login", _edited_user_login).data("payment_id", invoiceMetadata['payment_id']).data("invoice_index", invoiceMetadata['parcela_index']).dialog('open');
		
		//jQuery("#_PAGAMENTO_NOTIMPLEMENTEDYET_DIALOG").dialog('open');
	});
	
	jQuery("#__PAGAMENTO_INVOICES_LIST .invoicePrintLink, #_PAGAMENTO_BOLETO_FILE_RETURN_LIST .invoicePrintLink").click(function() {
		
		invoiceMetadata = jQuery(this).parents('tr').metadata();
		
		openInvoicePrintWindow(invoiceMetadata.payment_id, invoiceMetadata.parcela_index);
	});
	
	jQuery(".sendInvoiceByEmail").click(function() {
		jQuery("#_PAGAMENTO_NOTIMPLEMENTEDYET_DIALOG").dialog('open');
	});
	
	
})(jQuery);

function insertPaymentMethod(user_login) {
	jQuery("#_PAGAMENTO_METHOD_SELECT").data('user_login', user_login).data('payment_id', -1).data('refresh', false).dialog('open');
}
function changePaymentMethod(user_login, payment_id) {
	jQuery("#_PAGAMENTO_METHOD_SELECT").data('user_login', user_login).data('payment_id', payment_id).data('refresh', true).dialog('open');
}
function deletePaymentType(payment_type_id) {
	var opt = {
		autoOpen: true, 
		show: "fade",
		hide: "fade",
		title : _MODULE_PAGAMENTO_DELETE_PAYMENT_TYPE_CONFIRM_TITLE,
		modal: true,
		width: 'auto',
		buttons : {
			'Sim'	: function() {
				window.location.href = T_MODULE_PAGAMENTO_BASEURL + '&action=delete_payment_type&payment_type_id=' + payment_type_id;			
			},
			'Não' 	: function() {
				jQuery(this).dialog('destroy').remove();
			}
		}
	};
	
	$dialog = jQuery('<div><p>' + _MODULE_PAGAMENTO_DELETE_PAYMENT_TYPE_CONFIRM_TEXT + '</p></div>');
	$dialog.appendTo("body");
	$dialog.dialog(opt);
}
function updateInvoiceByID(payment_id, parcela_index, fields, callback) {
	parameters = {
		"payment_id" 	: payment_id,
		"parcela_index"	: parcela_index,
		"fields"		: fields
	};
	
	var url = window.location.pathname + "?ctg=module&op=module_pagamento&action=update_invoice";
	
	jQuery.post(
		url,
		parameters,
		callback);
	
	return true;
}


/** @todo Functions to move to a object or library, because its is used by other modules */
function openInvoicePrintWindow(payment_id, parcela_index, invoiceId) {
	url = window.location.pathname + 
		"?ctg=module&op=module_pagamento&action=get_invoice" +
		"&payment_id=" + payment_id +
		(typeof(parcela_index) == 'number' ? 
			"&invoice_index=" + parcela_index : 
			"&invoice_id=" + parcela_index);
	
	window.open(url);
}
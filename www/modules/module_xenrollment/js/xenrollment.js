xEnrollmentAPI = {
	updateXenrollmentAction : function(token, sendData, callback) {
		var url = 
			window.location.protocol + "//" +
			window.location.hostname +
			window.location.pathname + 
			"?ctg=module&op=module_xenrollment" + 
			"&action=update_xenrollment&output=json";
		
		jQuery.post(
				url,
				sendData,
				function(data, status) {
					if (callback == undefined || callback == null) {
						window.location.reload();
					} else {
						if (typeof(callback) == 'function') {
							callback(data, status);
						}
					}
				},
				'json'
		).error(function(data, status) {
			window.location.reload();
		});
	},
	commitXenrollmentAction : function(token, sendData, callback) {
		var url = 
			window.location.protocol + "//" +
			window.location.hostname +
			window.location.pathname + 
			"?ctg=module&op=module_xenrollment" + 
			"&action=commit_xenrollment&output=json";
		
		jQuery.post(
				url,
				sendData,
				function(data, status) {
					jQuery.messaging.show({
						message 		: $languageJS["__XENROLLMENT_SAVE_SUCCESS"],
						message_type 	: 'success' 
					});
					
					if (typeof(callback) == 'function') {
						callback(data, status);
					}
				},
				'json'
		).error(function(data, status) {
			window.location.reload();
		});
	}
};

xDocumentsAPI = {
	insertCourseDocumentsAction : function(token, sendData, callback) {
		var actionName = "insert_course_documents";
		this._doAction(actionName, token, sendData, callback);
	},
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
	_doAction : function(actionName, token, sendData, callback) {
		var url = 
			window.location.protocol + "//" +
			window.location.hostname +
			window.location.pathname + 
			"?ctg=module&op=module_xenrollment" + 
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
				'json'
		).error(function(data, status) {
			//window.location.reload();
		});
	},
};
	
jQuery(function($) {



	jQuery(".xenrollment_course_list_item").click(function() {
		if (jQuery(this).next().is(".xdocuments_course_list_item")) {
			jQuery(".xdocuments_course_list_item").not(jQuery(this).next()).hide();
			jQuery(this).next().toggle();
		}
	});

	jQuery(".xenrollment_course_list_item").next(".xdocuments_course_list_item:first").show();
	
	jQuery(".xdocument-add-to-course").click(function() {
		jQuery("#_XDOCUMENTS_ADD_XDOCUMENT_TO_COURSE").dialog('open');
		return false;
	}); 
	
	if (typeof($languageJS) != 'undefined') {
		jQuery("#_XDOCUMENTS_ADD_XDOCUMENT_TO_COURSE").dialog({
			autoOpen: false, 
			modal: true,
			width: 500,
			height: 'auto',
			title : $languageJS["__XDOCUMENTS_ADD_XDOCUMENT_TO_COURSE_TITLE"],
			resizable : false,
			buttons : {
				'Selecionar' : function() {
					if (jQuery(":input[name='add_document']:checked").size() > 0) {

						/*
						var courseData = jQuery(this).metadata();
						
						console.log(jQuery(this));
						console.log(courseData);
						*/
						xDocumentsAPI.insertCourseDocumentsAction(
							null,
							{
								document_id	: jQuery(":input[name='add_document']:checked").val(),
								course_id	: jQuery(":input[name='xenrollment_course_id']").val()
							}, 
							function(data, result) {
								
								var url = 
									window.location.protocol + "//" +
									window.location.hostname +
									window.location.pathname +
									window.location.search;
								
								
								if (data.message && data.message_type) {
	
									url = url + 
										"&message=" + data.message + 
										"&message_type=" + data.message_type;
								}
								url = 
									url + 
									"#" + JS_clearStringSymbols($languageJS["__XENROLLMENT_SHOW_DOCUMENTS_LIST"]);
								
								if (window.location.href == url) {
									window.location.reload();
								} else {
									window.location.href = url;
								}
							}
						);
					}
					jQuery(this).dialog('close');
				},
				'Cancelar' : function() {
					jQuery(this).dialog('close');
				}
			}
		}).bind("dialogbeforeclose", function() {
		});	
		
		
		
		jQuery(".delete-course-document-from-list").click(function() {
			var courseDocumentData = jQuery(this).parents("li").metadata();
			var self = this;
			
			var opt = {
				autoOpen: true, 
				show: "fade",
				hide: "fade",
				title : $languageJS["__XDOCUMENTS_DELETE_COURSE_DOCUMENT_CONFIRM_TITLE"],
				modal: true,
				width: 'auto',
				buttons : {
					'Sim'	: function() {
						xDocumentsAPI.deleteCourseDocumentsAction(
							null,
							{
								document_id	: courseDocumentData['document_id'],
								course_id	: courseDocumentData['course_id']
							},
							function(data, result) {
								jQuery(this).dialog('close').remove();
								
								if (result == 'success') {
									jQuery(self).parents("li").fadeOut(1000, function() {
										jQuery(this).remove();	
									});
								}
								var url = 
									window.location.protocol + "//" +
									window.location.hostname +
									window.location.pathname +
									window.location.search;
	
								if (data.message && data.message_type) {
									url = 
										url + 
										"&message=" + data.message + 
										"&message_type=" + data.message_type;
								}
								url = 
									url + 
									"#" + JS_clearStringSymbols($languageJS["__XENROLLMENT_SHOW_DOCUMENTS_LIST"]);
	
								if (window.location.href == url) {
									window.location.reload();
								} else {
									window.location.href = url;
								}
							}
						);			
						jQuery(this).dialog('destroy').remove();
					},
					'Não' 	: function() {
						jQuery(this).dialog('destroy').remove();
					}
				}
			};
				
			$dialog = jQuery('<div><p>' + $languageJS["__XDOCUMENTS_DELETE_COURSE_DOCUMENT_CONFIRM_TEXT"] + '</p></div>');
			$dialog.appendTo("body");
			$dialog.dialog(opt);		
		});
	
	}
	
	jQuery(".xdocuments_list :input[name='xdocuments_required']").change(function() {
		var courseDocumentData = jQuery(this).parents("li").metadata();
		var self = this;
		
		xDocumentsAPI.updateCourseDocumentsAction(
			null,
			{
				document_id	: courseDocumentData['document_id'],
				course_id	: courseDocumentData['course_id'],
				required	: jQuery(this).val()
			}
		);			
	});

	if (typeof($_xenrollment_mod_data) != 'undefined') {
		
		defaults = {
			"bProcessing": true,
			//"bJQueryUI": true,
			"bJQueryUI": false,
			"bSortClasses": false,
			"bAutoWidth": true,
			//"bInfo": true,
			"bInfo": false,
			///"sScrollY": "100%",	
			//"sScrollX": "100%",
			//"bScrollCollapse": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength"	: 10,
			"aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tudo"]],
			"oLanguage": {
				"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			}
		};
		
		opt = defaults;
		
		var o_xCourseClassTable = jQuery("#_XENROLLMENT_LAST_LIST").addClass("style1 datatable").dataTable( opt );

		defaults = {
			"bProcessing": true,
			//"bJQueryUI": true,
			"bJQueryUI": false,
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
					
		var o_xCourseClassTable = jQuery("#_USERS_WITHOUT_ENROLLMENTS_LIST").dataTable( opt );
		
		
		jQuery(".xenrollment_save_new_enrollment").click(function() {
			xEnrollmentAPI.commitXenrollmentAction(null, {'status_id' : 2});
		});
		

		
		
		
		jQuery("._XENROLLMENT_COURSESELECT_LINK").click(function() {
			jQuery("#_XENROLLMENT_COURSESELECT_DIALOG").dialog('open');
			return false;
		});
		
		jQuery("#_XENROLLMENT_COURSESELECT_DIALOG").dialog({
			autoOpen: false, 
			show: "fade",
			hide: "fade",
			modal: true,
			width: 500,
			height: 400,
			resizable : false,
			buttons : {
				'Selecionar' : function() {
					if (jQuery(this).find(":input[name='courses']:checked").size() > 0) {
						xEnrollmentAPI.updateXenrollmentAction(null, {'courses_id' : jQuery(this).find(":input[name='courses']:checked").val()}, function(data, status) {
							var url = 
								window.location.protocol + "//" +
								window.location.hostname +
								window.location.pathname + 
								"?ctg=module&op=module_xenrollment" + 
								"&action=edit_xenrollment&xenrollment_id=" + data.id;
							
							window.location.href = url;
						});
					}
					
					jQuery(this).dialog('close');
				},
				'Cancelar' : function() {
					jQuery(this).dialog('close');
				}
			}
		}).bind("dialogbeforeclose", function() {
			// IF NOT SELECTED THE COURSE
			if (jQuery(this).find(":input[name='courses']:checked").size() == 0) {
			//	return false;	
			}
		});
		
		jQuery(".xenrollment-course-select-list li").click(function() {
			jQuery(this).find(":input[name='courses']")
				.attr("checked", "checked");
			jQuery.uniform.update(":input[name='courses']"); 
		});
		
		
		jQuery("._XENROLLMENT_CLASSESELECT_LINK").click(function() {
			jQuery("#_XENROLLMENT_CLASSESELECT_DIALOG").dialog('open');
			return false;
		});
		
		jQuery("#_XENROLLMENT_CLASSESELECT_DIALOG").dialog({
			autoOpen: false, 
			show: "fade",
			hide: "fade",
			modal: true,
			width: 400,
			height: 'auto',
			resizable : false,
			buttons : {
				'Selecionar' : function() {
					if (jQuery(this).find(":input[name='classes']:checked").size() > 0) {
						xCourseAPI.putUserInCourseAction(null, {
							'user_id' 		: $_xenrollment_mod_data['enrollment']['users_id'],
							'course_id' 	: $_xenrollment_mod_data['enrollment']['courses_id'],
							'user_type' 	: 'student',
							'course_type'	: jQuery(":input[name='course_type[" + $_xenrollment_mod_data['enrollment']['courses_id'] + "]']:checked").val(),
							'class_id' 		: jQuery(this).find(":input[name='classes']:checked").val()
						}, function() { 
							var url = 
								window.location.protocol + "//" +
								window.location.hostname +
								window.location.pathname + 
								"?ctg=module&op=module_xenrollment" + 
								"&action=edit_xenrollment&xenrollment_id=" + $_xenrollment_mod_data['enrollment']['id'];
							
							if (window.location.href == url) {
								window.location.reload(true);
							} else {
								window.location.href = url;
							}
							
						});
						
						
						/*
						xCourseAPI.putUserInClassAction(null, {
							'user_id' 	: $_xenrollment_mod_data['enrollment']['users_id'],
							'course_id' : $_xenrollment_mod_data['enrollment']['courses_id'],
							'class_id' 	: jQuery(this).find(":input[name='classes']:checked").val()
						});
						*/
					}
					jQuery(this).dialog('close');
				}
			}
		}).bind("dialogbeforeclose", function() {
			// IF NOT SELECTED THE COURSE
			/*
			if (jQuery(this).find(":input[name='classes']:checked").size() == 0) {
				return false;	
			}
		
			xEnrollment.updateXenrollmentAction(null, {'courses_id' : jQuery(this).find(":input[name='courses']:checked").val()});
			*/
		});
		
		jQuery(".xenrollment-classe-select-list li").click(function() {
			jQuery(this).find(":input[name='classes']")
				.attr("checked", "checked");
			jQuery.uniform.update(":input[name='classes']"); 
		});
		
		jQuery("#_XENROLLMENT_PAYMENTSELECT_DIALOG").dialog({
			autoOpen: false, 
			//show: "fade",
			//hide: "fade",
			modal: true,
			width: 'auto',
			buttons : {
				'Salvar'	: function() {
					/*
					$payKeys = array(
						"enrollment_id", *
						"user_id", *
						"course_id" *
						"vencimento", 
						"desconto", 
						"payment_type_id", 
						"data_inicio", 
						"emitir_vencidos", 
					);
					*/
					var paymentForm = jQuery(this).find("form");
					/** @todo Validar informações do formulário */
					var sendData = {
						'enrollment_id'		: $_xenrollment_mod_data['enrollment']['id'],
						'user_id' 			: $_xenrollment_mod_data['enrollment']['users_id'],
						'course_id' 		: $_xenrollment_mod_data['enrollment']['courses_id'],
						'payment_type_id' 	: paymentForm.find(":input[name='payment_type_id']").val(),
						'parcelas' 			: paymentForm.find(":input[name='parcelas']").val(),
						'vencimento'		: paymentForm.find(":input[name='vencimento']").val(),
						'desconto'			: paymentForm.find(":input[name='desconto']").val(),
						'data_inicio'		: paymentForm.find(":input[name='data_inicio']").val(),
						'data_matricula'	: paymentForm.find(":input[name='data_matricula']").val()
					};
			
					xPaymentAPI.registerEnrollmentPaymentAction(null, sendData, function(data, status) {
						if (status == 'success' && data['status'] == 'ok') {
							xEnrollmentAPI.updateXenrollmentAction(null, {'payment_id' : data['id']}, function() {
								var url = 
									window.location.protocol + "//" +
									window.location.hostname +
									window.location.pathname + 
									"?ctg=module&op=module_xenrollment" + 
									"&action=edit_xenrollment&xenrollment_id=" + $_xenrollment_mod_data['enrollment']['id'];
								
								if (window.location.href == url) {
									window.location.reload(true);
								} else {
									window.location.href = url;
								}
							});
						}						
						
					});
			
					/*
					// SAVE AND CLOSE, UPDATE TABLE TOO.
					sentData = {};
					user_login = jQuery(this).data('user_login');
					payment_id = jQuery(this).data('payment_id');
					
					jQuery.post(
						window.location.pathname + "?ctg=module&op=module_pagamento&action=save_user_payment&user_login=" + user_login + "&payment_id=" + payment_id,
						jQuery("#module_pagamento_payment_type_select").serializeArray(),
						function(data, status) {
							// REFRESH WINDOW, OR REFRESH SCREEN ???
							
							jQuery("#_PAGAMENTO_METHOD_SELECT_DESCRIPTION").html(
									data['method_description']
							);
							
							window.location.href = 
								window.location.protocol + "//" +
								window.location.hostname +
								window.location.pathname +
								window.location.search + 
								"#Detalhes_financeiros";
							
							window.location.reload(true);
							
							//window.location.reload(true);
						},
						'json'
					);
			
					jQuery(this).dialog('close');
					*/
				},
				'Cancelar' 	: function() {
					jQuery(this).dialog('close');
				}
			}
		});
		
		jQuery("._XENROLLMENT_PAYMENTSELECT_LINK").click(function() {
			

			
			jQuery("#_XENROLLMENT_PAYMENTSELECT_DIALOG").dialog('open');
			
			
			
		});
		
		
		jQuery("._XENROLLMENT_DOCUMENTSSELECT_LINK").click(function() {
			jQuery("#_XENROLLMENT_DOCUMENTSSELECT_DIALOG").dialog('open');
			return false;
		});
		
		jQuery("#_XENROLLMENT_DOCUMENTSSELECT_DIALOG").dialog({
			autoOpen: false, 
			show: "fade",
			hide: "fade",
			modal: true,
			width: 600,
			height: 400,
			resizable : false,
			buttons : {
				'Fechar' : function() {
					jQuery(this).dialog('close');
				}
			}
		}).bind("dialogbeforeclose", function() {
			// IF NOT SELECTED THE COURSE
			/*
			if (jQuery(this).find(":input[name='classes']:checked").size() == 0) {
				return false;	
			}
		
			xEnrollment.updateXenrollmentAction(null, {'courses_id' : jQuery(this).find(":input[name='courses']:checked").val()});
			*/
		});
		
		jQuery(".xdocuments_list .update-user-document-status").click(function() {
			var userDocumentData = jQuery(this).parents("li").metadata();
			var self = this;
			
			if ($_xenrollment_mod_data['enrollment']) {
				if (jQuery(this).parents("li").hasClass("pendente")) {
					docStatusId = 2;
				}
				if (jQuery(this).parents("li").hasClass("entregue")) {
					docStatusId = 1;
				}
				
				xDocumentsAPI.updateUserDocumentsAction(
					null,
					{
						document_id		: userDocumentData['document_id'],
						enrollment_id	: $_xenrollment_mod_data['enrollment']['id'],
						status_id		: docStatusId
					},
					function(data, status) {
						jQuery(self)
							.toggleClass("red")
							.toggleClass("green")
							.parents("li")
							.removeClass("pendente entregue")
							.addClass(data.object.status)
							.find(".xdocuments_status strong")
							.html(data.object.status);
					}
				);
			}
			
			return false;
		});		
		
		/*
		jQuery(".xenrollment-classe-select-list li").click(function() {
			jQuery(this).find(":input[name='classes']")
				.attr("checked", "checked");
			jQuery.uniform.update(":input[name='classes']"); 
		});
		*/
	}

	// Atualiza relatorio de matriculas
	jQuery('#report_enrollment_search_submit').click(function() {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xenrollment"
				+ "&action=get_report_enrollment";
		var sendData = jQuery('#form_enrollment_search').serialize();
		jQuery('#list_report_enrollment').html('aguarde...');
		jQuery.post(url, sendData, function(data) {
			jQuery('#list_report_enrollment').html(data);
		});
		return false;
	});

	// Relatorio das matriculas para excel ( filtros em sessao )
	jQuery('#report_enrollment_excel').click(function() {
		var url = window.location.protocol + "//" + window.location.hostname
		+ window.location.pathname + "?ctg=module&op=module_xenrollment"
		+ "&action=report_enrollment_excel";
		var sendData = jQuery('#form_enrollment_search').serialize();
		jQuery.post(url, sendData,function(){});
		//return false;
	});

});



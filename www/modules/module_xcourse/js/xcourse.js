xCourseAPI = {
	setCurrentUserLesson : function(token, sendData, callback) {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xcourse"
				+ "&action=set_current_user_lesson&output=json";
		jQuery.post(url, sendData, function(data, status) {
			if (callback !== undefined && callback !== null) {
				if (typeof (callback) === 'function') {
					callback(data, status);
				}
			}
		}, 'json').error(function() {
			window.location.reload();
		});
	},
	putUserInCourseAction : function(token, sendData, callback) {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xcourse"
				+ "&action=put_user_in_course&output=json";
		jQuery.post(url, sendData, function(data, status) {
			if (callback !== undefined && callback !== null) {
				if (typeof (callback) == 'function') {
					callback(data, status);
				}
			}
		}, 'json').error(function() {
			window.location.reload();
		});
	},
	putUserInClassAction : function(token, sendData, callback) {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xcourse"
				+ "&action=put_user_in_class&output=json";
		jQuery.post(url, sendData).complete(function() {
			window.location.reload();
		});
	},
	putLessonsInCourseAction : function(token, sendData, callback) {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xcourse"
				+ "&action=put_lesson_in_course&output=json";
		jQuery.post(url, sendData, function(data, status) {
			if (callback !== undefined && callback !== null) {
				if (typeof (callback) == 'function') {
					callback(data, status);
				}
			}
		}, 'json').error(function() {
			window.location.reload();
		});
	},
	updateLessonsOrderAction : function(token, sendData, callback) {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xcourse"
				+ "&action=update_lessons_order&output=json";
		jQuery.post(url, sendData, function(data, status) {
			if (callback !== undefined && callback !== null) {
				if (typeof (callback) == 'function') {
					callback(data, status);
				}
			}
		}, 'json').error(function() {
			window.location.reload();
		});
	},
	updateAcademicCalendarSeriesAction : function(token, sendData, callback) {
		var url = window.location.protocol + "//" + window.location.hostname
				+ window.location.pathname + "?ctg=module&op=module_xcourse"
				+ "&action=update_academic_calendar_series&output=json";
		jQuery.post(url, sendData, function(data, status) {
			if (callback !== undefined && callback !== null) {
				if (typeof (callback) == 'function') {
					callback(data, status);
				}
			}
		}, 'json');
	}
};

function ImgIconsTree(idUl) {
	var nodeId = 0;
	var dhtmlgoodies_tree = jQuery("#" + idUl);
	var menuItems = dhtmlgoodies_tree.find('li');
	var transparentImg = 'themes/default/images/others/transparent.gif';
	// console.log(jQuery("#" + idUl + " li"));
	jQuery("#" + idUl + " li").each(function(i) {
		// nodeId++;
		var folderImg = jQuery("<img>");
		if (jQuery(this).attr("id")) {
			folderImg.attr("id", 'tree_image_' + jQuery(this).attr("id")
					.replace(/[^0-9]/gi, ''));
		}
		if (jQuery(this).hasClass("theory")) {
			folderImg.attr("src", transparentImg);
			folderImg.addClass('handle sprite16 sprite16-theory');
		}
		if (jQuery(this).hasClass("tests")) {
			folderImg.attr("src", transparentImg);
			folderImg.addClass('handle sprite16 sprite16-tests');
		}
		if (jQuery(this).hasClass("paperclip")) {
			folderImg.attr("src", transparentImg);
			folderImg.addClass('handle sprite16 sprite16-paperclip');
		}
		if (jQuery(this).hasClass("file")) {
			folderImg.attr("src", transparentImg);
			folderImg.addClass('handle sprite16 sprite16-file');
		} else {
			folderImg.attr("src", transparentImg);
			folderImg.addClass('handle sprite16 sprite16-folder');
		}
		jQuery(this).prepend(folderImg);
	});
}

function MergeContentTrees(sourceID, mergeID) {
	jQuery("#" + sourceID).append(jQuery("#" + mergeID + " > li"));
	jQuery("#" + mergeID).remove();
}

(function(jQuery) {
	/* SUB/PUB Pattern */
	jQuery('#xcourse-activity-list > li').hide();
	jQuery('#xcourse-activity-list > li').first().show();
	jQuery('.xcontent_forum_lessons_list li').hide();
	jQuery('.xcontent_forum_lessons_list li').first().show();

	// carrega activity ( video, barra de progresso e menu cinza escuro )
	jQuery.Topic('xcourse_course_lesson_change').subscribe(function(course_id, lesson_id) {
		var class_name = '.course_lesson_' + course_id + '_' + lesson_id;
		jQuery('#xcourse-activity-list > li').hide();
		
		alert(jQuery('#xcourse-activity-list > li' + class_name).size());
		
		
		if ( jQuery('#xcourse-activity-list > li' + class_name).size() ==  0  ) {
			var url = window.location.protocol + '//' + window.location.hostname 
					+ window.location.pathname + '?ctg=module&op=module_xcourse'
					+ '&action=load_course_user_activity&output=json';
			jQuery.post(url, function(data, status) {
				jQuery('#xcourse-activity-list').append(jQuery(data).find('li'));
				jQuery('#xcourse-activity-list > li' + class_name).show();
				jQuery('.ui-progress-bar').each(function() {
					var currentValue = new Number(jQuery(this).html());
					if ( jQuery.isNumeric(currentValue) ) {
						jQuery(this).empty();
						jQuery(this).progressbar({
							value: currentValue.valueOf()
						});
					}
				});
			});
		} else {
			jQuery('#xcourse-activity-list > li' + class_name).show();
		}
	});

	jQuery.Topic("xcourse_course_lesson_change").subscribe(function(course_id, lesson_id) {
		var class_course_name = ".course_" + course_id;
		var class_lesson_name = ".course_lesson_" + course_id + "_"	+ lesson_id;
		jQuery("#xcourse-academic-calendar > li").hide();
		jQuery("#xcourse-academic-calendar > li" + class_course_name).show();
		jQuery("#xcourse-academic-calendar > li" + class_lesson_name).show();
	});

	// carrega mensagens do forum do ultimo curso selecionado pelo aluno
	jQuery.Topic('xcourse_course_lesson_change').subscribe(function(course_id, lesson_id) {
		jQuery('.xcontent_forum_lessons_list li').hide();
		if ( jQuery('.xcontent_forum_lessons_list li.lesson_' + lesson_id).size() ==  0  ) {
			var url = window.location.protocol + '//' + window.location.hostname 
					+ window.location.pathname + '?ctg=module&op=module_xcourse'
					+ '&action=load_course_forum_messages&output=json';
			jQuery.post(url, function(data, status) {
				jQuery('.xcontent_forum_lessons_list').append(jQuery(data).find('li'));
				jQuery('.xcontent_forum_lessons_list li.lesson_' + lesson_id).show();
			});
		} else {
			jQuery('.xcontent_forum_lessons_list li.lesson_' + lesson_id).show();
		}
	});

	jQuery.Topic('xcourse_course_lesson_change').subscribe(function(course_id, lesson_id) {
		var url = window.location.pathname+'?ctg=module&op=module_xcourse&action=load_lesson_top_links';
		jQuery.post(url, {
			'lesson_id' : lesson_id,
			'course_id' : course_id
		}, function(data, status) {
			jQuery('#module_lesson_top_link_change').html(data);
		});
	});

	jQuery.Topic('xcourse_course_lesson_change').subscribe(function(course_id, lesson_id) {
		var url = window.location.pathname + '?ctg=module&op=module_xcourse&action=load_content_tree_xcourse_front';
		jQuery.post( url, {
			'lesson_id' : lesson_id, 
			'course_id' : course_id 
		}, function(data, status) {
			jQuery('#module_xcourse_content_list_tree_frontend_student').html(data);
			MergeContentTrees('xcourse_content_tree', 'xcourse_info_tree');
			ImgIconsTree('xcourse_content_tree');
		});
	});

	if (typeof (FIRST_COURSE_ID) != 'undefined'
			&& typeof (FIRST_LESSON_ID) != 'undefined') {
		switchCourseLesson(FIRST_COURSE_ID, FIRST_LESSON_ID);
	}

	jQuery("#list-event").hide();
	jQuery(".xcourse_content_btn").click(function() {
		jQuery(".course_details").hide();
		jQuery(this).next(".course_details").show();
	});

	if (typeof ($_xcourse_mod_data) != 'undefined') {

		if (jQuery("#_XCOURSE_GETCOURSES_DATATABLE").size() > 0) {
			var defaults = {
				"bProcessing" : true,
				// "bServerSide": true,
				// "sAjaxSource": datatableSourceUrl +
				// "&action=get_xusers_source",
				"bJQueryUI" : true,
				"bDeferRender" : true,
				"bSortClasses" : false,
				"bAutoWidth" : true,
				"bInfo" : true,
				"sScrollY" : "100%",
				"sScrollX" : "100%",
				"bScrollCollapse" : true,
				"sPaginationType" : "full_numbers",
				"iDisplayLength" : 20,
				"aLengthMenu" : [ [ 10, 20, 50, 100, -1 ],
						[ 10, 20, 50, 100, "Tudo" ] ],
				"sDom" : '<"H"lf<"dataTables_outerprocessing"r>>t<"F"ip>',
				/*
				 * "aoColumns": [ { "mDataProp": "login" }, { "mDataProp":
				 * "user_type_name", sClass : "center" }, { "mDataProp":
				 * "courses_num", sClass : "center", "bSortable" : false }, {
				 * "mDataProp": "last_login", sClass : "center" }, {
				 * "mDataProp": "active", sClass : "center", "bSortable" : false } ],
				 */
				"oLanguage" : {
					"sUrl" : window.location.pathname
							+ "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
				}
			};
			var opt = defaults;

			oCourseTable = jQuery("#_XCOURSE_GETCOURSES_DATATABLE").dataTable(
					opt);
		}
		if ($_xcourse_mod_data['xcourse.action'] == 'edit_xcourse'
				&& typeof ($_xcourse_mod_data['edited_course']) != 'undefined') {
			defaults = {
				"bProcessing" : true,
				"bServerSide" : true,
				"sAjaxSource" : window.location.pathname
						+ "?ctg=module&op=module_xcourse&action=get_xcourse_users_source&xcourse_id="
						+ $_xcourse_mod_data['edited_course']['id'],
				"fnServerData" : function(sSource, aoData, fnCallback) {
					/* Add some extra data to the sender */
					if (typeof ($_xcourse_mod_data['xcourse_class_id']) != undefined) {
						aoData.push( {
							"name" : "xcourse_class_id",
							"value" : $_xcourse_mod_data['xcourse_class_id']
						});
					}

					jQuery.getJSON(sSource, aoData, function(json) {
						/*
						 * Do whatever additional processing you want on the
						 * callback, then tell DataTables
						 */
						fnCallback(json);
					});
				},
				"bJQueryUI" : true,
				"bSortClasses" : false,
				"bAutoWidth" : true,
				"bInfo" : true,
				"sScrollY" : "100%",
				"sScrollX" : "100%",
				"bScrollCollapse" : true,
				"sPaginationType" : "full_numbers",
				"iDisplayLength" : 20,
				"aLengthMenu" : [ [ 10, 20, 50, 100, -1 ],
						[ 10, 20, 50, 100, "Tudo" ] ],
				"sDom" : '<"H"lf<"dataTables_outerprocessing"r>>t<"F"ip>',
				"aoColumns" : [ {
					"mDataProp" : "login"
				}, {
					"mDataProp" : "user_type",
					sClass : "center"
				}, {
					"mDataProp" : "active_in_course",
					sClass : "center"
				}, {
					"mDataProp" : "timestamp_completed",
					sClass : "center"
				},
				// { "mDataProp": "status", sClass : "center"},
						// { "mDataProp": "completed", sClass : "center"},
						{
							"mDataProp" : "score",
							sClass : "center"
						}, {
							"mDataProp" : "operations",
							sClass : "center",
							"bSortable" : false
						} ],
				"oLanguage" : {
					"sUrl" : window.location.pathname
							+ "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
				}
			};

			opt = defaults;

			var o_xCourseUserTable = jQuery("#_XCOURSE_USERS_LIST").dataTable(
					opt);

			jQuery(":input[name='_XCOURSE_USERS_CLASSES_FILTER']").change(
					function() {
						if (typeof ($_xcourse_mod_data) == 'object') {
							$_xcourse_mod_data['xcourse_class_id'] = jQuery(
									this).val();
							o_xCourseUserTable.fnDraw();
						}
					});

			jQuery("#_XCOURSE_CLASSES_LIST .usersClassLink")
					.click(
							function() {
								classeData = jQuery(this).parents("tr")
										.metadata();

								jQuery(
										":input[name='_XCOURSE_USERS_CLASSES_FILTER']")
										.val(classeData['id']).change();

								// FOCUS ON TAB
								jQuery(
										"#"
												+ JS_clearStringSymbols($languageJS["__XCOURSE_EDITXCOURSE"]))
										.tabs(
												"select",
												JS_clearStringSymbols($languageJS["__XCOURSE_EDITXCOURSEUSERS"]));
							});

			defaults = {
				"bProcessing" : true,
				"bJQueryUI" : true,
				"sortable" : true,
				"bSortClasses" : false,
				"bAutoWidth" : true,
				"bInfo" : true,
				"sScrollY" : "100%",
				"sScrollX" : "100%",
				"bScrollCollapse" : true,
				"sPaginationType" : "full_numbers",
				"iDisplayLength" : 10,
				"aLengthMenu" : [ [ 10, 20, 50, 100, -1 ],
						[ 10, 20, 50, 100, "Tudo" ] ],
				"oLanguage" : {
					"sUrl" : window.location.pathname
							+ "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
				},
				"aoColumns" : [ null, null, null, {
					"sSortDataType" : "dom-checkbox-reversed"
				} ],
				"aaSorting" : [ [ 3, 'asc' ] ]
			};
			opt = defaults;
			var o_xCourseLessonsTable = jQuery("#_XCOURSE_LESSONS_LIST")
					.dataTable(opt);

			defaults = {
				"bProcessing" : true,
				"bJQueryUI" : true,
				"bSortClasses" : false,
				"bAutoWidth" : true,
				"bInfo" : true,
				"sScrollY" : "100%",
				"sScrollX" : "100%",
				"bScrollCollapse" : true,
				"sPaginationType" : "full_numbers",
				"iDisplayLength" : 10,
				"aLengthMenu" : [ [ 10, 20, 50, 100, -1 ],
						[ 10, 20, 50, 100, "Tudo" ] ],
				"oLanguage" : {
					"sUrl" : window.location.pathname
							+ "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
				}
			};
			opt = defaults;

			var o_xCourseClassTable = jQuery("#_XCOURSE_CLASSES_LIST")
					.dataTable(opt);

			jQuery("#_XCOURSE_CLASS_FORM").dialog( {
				autoOpen : false,
				show : "fade",
				hide : "fade",
				title : $languageJS['__XCOURSE_EDIT_CLASS_DIALOG_TITLE'],
				modal : true,
				width : 'auto',
				minWidth : 450
			});

			jQuery(".addClassLink")
					.click(function() {
						// inject INTO FORM
							jQuery(":input[name='id']",
									jQuery("form#add_courseclass_form"))
									.val(-1);
							// jQuery(":input[name='a']",
							// jQuery("form#add_courseclass_form")).val(classeData['id']);

							jQuery(":input[name='name']",
									jQuery("form#add_courseclass_form"))
									.val('');

							jQuery(":input[name='active']",
									jQuery("form#add_courseclass_form")).attr(
									"checked", "checked");
							jQuery("#max-users-slider").slider('value', 30);

							jQuery(":input[name='start_date']",
									jQuery("form#add_courseclass_form"))
									.datepicker('setDate', new Date());
							jQuery(":input[name='end_date']",
									jQuery("form#add_courseclass_form"))
									.datepicker('setDate', new Date());

							jQuery.uniform.update();

							jQuery("#_XCOURSE_CLASS_FORM")
									.dialog(
											'option',
											'title',
											$languageJS['__XCOURSE_INSERT_CLASS_DIALOG_TITLE']);
							jQuery("#_XCOURSE_CLASS_FORM").dialog('open');

							return false;
						});

			jQuery("#_XCOURSE_CLASSES_LIST .editClassLink").click(function() {
				classeData = jQuery(this).parents("tr").metadata();

				// inject INTO FORM
					jQuery(":input[name='id']",
							jQuery("form#add_courseclass_form")).val(
							classeData['id']);
					// jQuery(":input[name='a']",
					// jQuery("form#add_courseclass_form")).val(classeData['id']);

					jQuery(":input[name='name']",
							jQuery("form#add_courseclass_form")).val(
							classeData['name']);

					if (classeData['active'] == 1) {
						jQuery(":input[name='active']",
								jQuery("form#add_courseclass_form")).attr(
								"checked", "checked");
					} else {
						jQuery(":input[name='active']",
								jQuery("form#add_courseclass_form"))
								.removeAttr("checked");
					}
					jQuery("#max-users-slider").slider('value',
							classeData['max_users']);

					jQuery(":input[name='start_date']",
							jQuery("form#add_courseclass_form")).datepicker(
							'setDate',
							new Date(classeData['start_date'] * 1000));
					endDate = new Date(classeData['end_date'] * 1000);
					endDate.setDate(endDate.getDate() - 1);

					jQuery(":input[name='end_date']",
							jQuery("form#add_courseclass_form")).datepicker(
							'setDate', endDate);

					jQuery.uniform.update();

					jQuery("#_XCOURSE_CLASS_FORM").dialog('option', 'title',
							$languageJS['__XCOURSE_EDIT_CLASS_DIALOG_TITLE']);
					jQuery("#_XCOURSE_CLASS_FORM").dialog('open');

					return false;
				});

			jQuery("#_XCOURSE_CLASS_CALENDAR_DIALOG")
					.dialog(
							{
								autoOpen : false,
								show : "fade",
								hide : "fade",
								title : $languageJS['__XCOURSE_CLASS_CALENDAR_DIALOG_TITLE'],
								modal : true,
								width : 'auto',
								minWidth : 450,
								maxWidth : 800,
								buttons : {
									'Salvar' : function() {
										jQuery(this).find("#schedule_clonable")
												.remove();
										url = jQuery(this).find('form').attr(
												'action');

										var self = this;
										jQuery
												.post(
														url,
														jQuery(this).find(
																'form')
																.serialize(),
														function(data) {
															jQuery.messaging
																	.show(data);
														}, 'json')
												.complete(
														function(data, response) {
															jQuery(self)
																	.dialog(
																			'close');
														});

									},
									'Cancelar' : function() {
										jQuery(this).dialog('close');
									}
								}
							});

			// jQuery('._XCOURSE_CLASS_CALENDAR_START,
			// ._XCOURSE_CLASS_CALENDAR_END').timepicker();

			jQuery("#_XCOURSE_CLASSES_LIST .calendarClassLink")
					.click(
							function() {
								classeData = jQuery(this).parents("tr")
										.metadata();

								var url = window.location.protocol
										+ "//"
										+ window.location.hostname
										+ window.location.pathname
										+ "?ctg=module&op=module_xcourse&action=get_class_schedules"
										+ "&xcourse_id="
										+ classeData['courses_ID']
										+ "&xcourse_class_id="
										+ classeData['id'];

								jQuery
										.get(url)
										.complete(
												function(data, status, result) {
													jQuery(
															"#_XCOURSE_CLASS_CALENDAR_DIALOG")
															.html(
																	data.responseText)
															.dialog('open');

													jQuery(
															"#_XCOURSE_CLASS_CALENDAR_DIALOG :input[alt='time']")
															.setMask('time');
												});

								return false;
							});

			jQuery(".classScheduleDelete").live('click', function() {
				jQuery(this).parents("li").remove();
			});

			jQuery(".classScheduleInsert")
					.live(
							'click',
							function() {
								var classScheduleModel = jQuery(this).parents(
										"ul").find("#schedule_clonable")
										.clone();

								jQuery(this).parents("li").before(
										classScheduleModel.removeAttr('id')
												.show());

								jQuery(
										"#_XCOURSE_CLASS_CALENDAR_DIALOG :input[alt='time']")
										.setMask('time');
							});

			jQuery("#_XCOURSE_TABS").bind(
					"tabsshow",
					function(event, ui) {

						var oTabTable = jQuery(
								'div.dataTables_scrollBody>table.display',
								ui.panel).dataTable();
						if (oTabTable.length > 0) {
							oTabTable.fnAdjustColumnSizing();
						}
					});
			if (typeof (slider_value) != 'undefined') {
				jQuery("#max-users-slider").slider( {
					range : "min",
					value : slider_value,
					min : 0,
					max : 150,
					change : function(event, ui) {
						jQuery("#max-users-text").html(ui.value);
						jQuery(":input[name='max_users']").val(ui.value);
					},
					slide : function(event, ui) {
						jQuery("#max-users-text").html(ui.value);
						jQuery(":input[name='max_users']").val(ui.value);
					}
				});
			}

			jQuery("#max-users-text").html(
					jQuery("#max-users-slider").slider("value"));
			jQuery(":input[name='max_users']").val(
					jQuery("#max-users-slider").slider("value"));

		}

		if ($_xcourse_mod_data['xcourse.action'] == 'edit_xcourse_calendar'
				&& typeof ($_xcourse_mod_data['edited_course']) != 'undefined') {
			// jQuery("#ganttChart", ui.panel).ganttView("setSlideWidth",
			// jQuery('#ganttChart', ui.panel).parent().width() - 240);
			jQuery(":input[name='classe_filter']").change(
					function() {
						if (jQuery(this).val() === 0) {
							jQuery(".xcourse-calendar-checklist .step-2")
									.hide();
						} else {
							url = window.location.protocol + "//"
									+ window.location.hostname + '/'
									+ $_xcourse_mod_data["xcourse.baseUrl"]
									+ "&action="
									+ $_xcourse_mod_data['xcourse.action']
									+ "&xcourse_id="
									+ $_xcourse_mod_data['edited_course']['id']
									+ "&xclasse_id=" + jQuery(this).val();

							if (window.location.href == url) {
								jQuery(".xcourse-calendar-checklist .step-2")
										.show();
							} else {
								window.location.href = url;
								return;
							}
						}
					});

			jQuery(".openGanttChart").click(function() {
				lessonData = jQuery(this).parents("tr").metadata();

				if (lessonData) {
					//					
					/*
					 * course_id "16" lesson_id "32" classe_id "4" name "Lógica
					 * de Programação" start_date "2011-08-10 00:00:00" end_date
					 * "2011-08-25 00:00:00"
					 */
					if (jQuery(
							".xcourse-lessons-calendar-details-for-lesson-"
									+ lessonData['lesson_id'] + ":visible")
							.size() === 0) {
						jQuery(".xcourse-lessons-calendar-details").hide();
						jQuery(
								".xcourse-lessons-calendar-details-for-lesson-"
										+ lessonData['lesson_id']).show();
					} else {
						jQuery(
								".xcourse-lessons-calendar-details-for-lesson-"
										+ lessonData['lesson_id']).hide();
					}
				}
				return false;
			});
			/*
			 * jQuery("#_XCOURSE_LESSONS_SORTABLE_LIST").sortable({ placeholder :
			 * "ui-state-highlight", forcePlaceholderSize : true, opacity : 0.6,
			 * cursor : 'crosshair', axis : 'y', helper : function (event,
			 * context) { return jQuery( '<div class="ui-widget-header">' +
			 * jQuery(context).find('span.lesson-name').html() + '</div>'); }
			 * }); jQuery("#_XCOURSE_LESSONS_SORTABLE_LIST").bind( "sortupdate",
			 * function(event, ui) { sendData = { 'xcourse_id' :
			 * $_xcourse_mod_data['edited_course']['id'], 'lessonid' :
			 * jQuery(this).sortable('toArray') };
			 * 
			 * xCourseAPI.updateLessonsOrderAction(null, sendData,
			 * function(data, status) { if (status == 'success') {
			 * jQuery.messaging.show(data); } }); });
			 */

		}
		if ($_xcourse_mod_data['xcourse.action'] === 'view_course_dashboard') {
			jQuery(".feature_messages")
					.click(
							function() {
								if (jQuery("#loader_feature_messages")
										.children().size() === 0) {

									jQuery("#_XCOURSE_LOADING_OUTER").show();

									jQuery
											.get(
													jQuery(this).attr("href"),
													{
														'lessons_ID' : $_xcourse_mod_data['edited_lesson']['id'],
														'from_course' : $_xcourse_mod_data['edited_course']['id'],
														'output' : 'innerhtml'
													},
													function(response, status) {
														jQuery(
																"#_XCOURSE_LOADING_OUTER")
																.hide();
														jQuery(
																"#loader_feature_messages")
																.html(
																		jQuery(
																				response)
																				.find(
																						".toggle_container .block")
																				.html());
													}, 'html');
								}
								jQuery(".loader_container").hide();
								jQuery("#loader_feature_messages").show('fade');
								return false;
							});

			jQuery(".feature_academic_calendar")
					.click(
							function() {
								if (jQuery("#loader_feature_academic_calendar")
										.children().size() === 0) {
									jQuery("#_XCOURSE_LOADING_OUTER").show();

									jQuery
											.get(
													jQuery(this).attr("href"),
													{
														'xcourse_id' : $_xcourse_mod_data['edited_course']['id'],
														'xlesson_id' : $_xcourse_mod_data['edited_lesson']['id'],
														'output' : 'innerhtml'
													},
													function(response, status) {
														jQuery(
																"#_XCOURSE_LOADING_OUTER")
																.hide();
														jQuery(
																"#loader_feature_academic_calendar")
																.html(
																		jQuery(
																				response)
																				.html());
													});
								}
								jQuery(".loader_container").hide();
								jQuery("#loader_feature_academic_calendar")
										.show('fade');
								return false;
							});
			jQuery(".feature_video")
					.click(
							function() {
								if (jQuery("#loader_feature_video").children()
										.size() === 0) {

									jQuery("#_XCOURSE_LOADING_OUTER").show();

									jQuery
											.get(
													jQuery(this).attr("href"),
													{
														'lessons_ID' : $_xcourse_mod_data['edited_lesson']['id'],
														'from_course' : $_xcourse_mod_data['edited_course']['id'],
														'output' : 'innerhtml'
													},
													function(response, status) {
														jQuery(
																"#_XCOURSE_LOADING_OUTER")
																.hide();
														jQuery(
																"#loader_feature_video")
																.html(
																		jQuery(
																				response)
																				.find(
																						".toggle_container .block")
																				.html());
													}, 'html');
								}
								jQuery(".loader_container").hide();
								jQuery("#loader_feature_video").show('fade');
								return false;
							});

			jQuery(".feature_forum")
					.click(
							function() {
								if (jQuery("#loader_feature_forum").children()
										.size() === 0) {
									jQuery("#_XCOURSE_LOADING_OUTER").show();

									jQuery
											.get(
													jQuery(this).attr("href"),
													{
														'xcourse_id' : $_xcourse_mod_data['edited_course']['id'],
														'xlesson_id' : $_xcourse_mod_data['edited_lesson']['id'],
														'output' : 'innerhtml'
													},
													function(response, status) {

														jQuery(
																"#_XCOURSE_LOADING_OUTER")
																.hide();
														jQuery(
																"#loader_feature_forum")
																.html(
																		jQuery(
																				response)
																				.html());

														jQuery(
																"#loader_feature_forum")
																.find("table")
																.addClass(
																		"static");

													});
								}
								jQuery(".loader_container").hide();
								jQuery("#loader_feature_forum").show('fade');
								return false;
							});

			jQuery(".feature_links")
					.click(
							function() {
								if (jQuery("#loader_feature_links").children()
										.size() === 0) {

									jQuery("#_XCOURSE_LOADING_OUTER").show();

									jQuery
											.get(
													jQuery(this).attr("href"),
													{
														'lessons_ID' : $_xcourse_mod_data['edited_lesson']['id'],
														'from_course' : $_xcourse_mod_data['edited_course']['id'],
														'output' : 'innerhtml'
													},
													function(response, status) {
														jQuery(
																"#_XCOURSE_LOADING_OUTER")
																.hide();
														jQuery(
																"#loader_feature_links")
																.html(
																		jQuery(
																				response)
																				.find(
																						".toggle_container .block")
																				.html())
																.show('fade');
													}, 'html');
								}
								jQuery(".loader_container").hide();
								jQuery("#loader_feature_links").show('fade');
								return false;
							});

		}
		if ($_xcourse_mod_data['xcourse.action'] == 'view_academic_calendar'
				&& typeof ($_xcourse_mod_data['edited_course']) != 'undefined') {
			refreshGanttChart( {
				'xcourse_id' : $_xcourse_mod_data['edited_course']['id'],
				'xlesson_id' : $_xcourse_mod_data['edited_lesson']['id'],
				'xclasse_id' : 4
			});
		}
	}
})(jQuery); // plugin code ends

function refreshGanttChart($selectData) {
	jQuery("#lessonGanttChart").empty();

	jQuery("#_XCOURSE_LOADING_OUTER").show();

	var url = $_xcourse_mod_data['xcourse.baseUrl']
			+ "&action=get_academic_calendar_data&output=json";

	jQuery
			.get(
					url,
					$selectData,
					function(data, status) {
						jQuery("#_XCOURSE_LOADING_OUTER").hide();

						jQuery("#lessonGanttChart").show();

						jQuery("#lessonGanttChart")
								.ganttView(
										{
											data : data,
											slideWidth : jQuery(
													"#lessonGanttChart")
													.parent().width() - 225,
											behavior : {
												clickable : true,
												onClick : function(data) {
													var msg = "You clicked on an event: { start: "
															+ data.start
																	.toString("M/d/yyyy")
															+ ", end: "
															+ data.end
																	.toString("M/d/yyyy")
															+ " }";
													jQuery("#eventMessage")
															.text(msg);
												},
												draggable : false,
												resizable : false
											}
										});
					}, 'json');
}

function xcourse_deleteCourse(el, course) {
	var opt = {
		autoOpen : true,
		show : "fade",
		hide : "fade",
		title : $languageJS['__XCOURSE_DELETE_DIALOG_TITLE'],
		modal : true,
		width : 'auto',
		buttons : {
			'Sim' : function() {
				var url = window.location.pathname + "?ctg=courses";
				parameters = {
					delete_course : course,
					method : 'get'
				};

				jQuery.get(url, parameters, function(data, status) {
					oCourseTable.fnDeleteRow(jQuery(el).parents("tr").get(0));
				});

				jQuery(this).dialog('destroy').remove();
			},
			'Não' : function() {
				jQuery(this).dialog('destroy').remove();
			}
		}
	};

	$dialog = jQuery('<div><p>' + $languageJS['__XCOURSE_DELETE_DIALOG_TEXT'] + '</p></div>');
	$dialog.appendTo("body");
	$dialog.dialog(opt);
}

function xcourse_activateCourse(el, course) {

	if (jQuery(el).hasClass('red')) { // 
		parameters = {
			activate_course : course,
			method : 'get'
		};
	} else {
		parameters = {
			deactivate_course : course,
			method : 'get'
		};
	}
	var url = window.location.pathname + "?ctg=courses";

	jQuery.get(url, parameters, function(data, status) {
		jQuery(el).toggleClass('red').toggleClass('green').attr("title",
				activeStates[data]).attr("original-title", activeStates[data]);

		if (data == 1) {
			jQuery(el).parents("tr").find("a.editLink").css("color", "");
		} else {
			jQuery(el).parents("tr").find("a.editLink").css("color", "red");
		}

	});
}

function xcourse_confirmUser(el, course, user) {
	if (jQuery(el).hasClass('red')) { // 
		parameters = {
			edit_course : course,
			ajax : 'confirm_user',
			user : user,
			method : 'get'
		};
	} else {
		parameters = {
			edit_course : course,
			ajax : 'unconfirm_user',
			user : user,
			method : 'get'
		};
	}
	var url = window.location.pathname + "?ctg=courses";

	jQuery.get(url, parameters, function(data, status) {
		jQuery(el).toggleClass('red').toggleClass('green');

		if (parameters.ajax == 'unconfirm_user') {
			jQuery(el).parents("tr").find("a.editLink").css("color", "red");
		} else {
			jQuery(el).parents("tr").find("a.editLink").css("color", "red");
		}
	});
}

function xcourse_deleteCourseClass(el, course, courseclass) {

	var opt = {
		autoOpen : true,
		show : "fade",
		hide : "fade",
		title : $languageJS['__XCOURSE_DELETE_CLASS_DIALOG_TITLE'],
		modal : true,
		width : 'auto',
		buttons : {
			'Sim' : function() {
				parameters = {};

				parameters = {
					courseclass : course,
					delete_courseclass : courseclass,
					method : 'get'
				};

				var url = window.location.pathname + "?ctg=courses";

				jQuery
						.get(
								url,
								parameters,
								function(data, status) {

									window.location.href = window.location.protocol
											+ "//"
											+ window.location.hostname
											+ window.location.pathname
											+ window.location.search
											+ "#"
											+ JS_clearStringSymbols($languageJS["__XCOURSE_EDITXCOURSECLASSES"]);
									window.location.reload();
								});

				// window.location.href = T_MODULE_PAGAMENTO_BASEURL +
				// '&action=delete_payment_type&payment_type_id=' +
				// payment_type_id;
			},
			'Não' : function() {
				jQuery(this).dialog('destroy').remove();
			}
		}
	};

	$dialog = jQuery('<div><p>' + $languageJS['__XCOURSE_DELETE_CLASS_DIALOG_TEXT'] + '</p></div>');
	$dialog.appendTo("body");
	$dialog.dialog(opt);
}

function xcourse_lessonsAddRemove(course_id, lesson_id) {
	xCourseAPI.putLessonsInCourseAction(null, {
		'xcourse_id' : course_id,
		'xlesson_id' : lesson_id
	}, function(data, status) {
		jQuery.messaging.show(data);
	});
}

function switchCourseLesson(course_id, lesson_id) {
	// var url = window.location.pathname + "?student.php?lessons_ID=" +
	// lesson_id + "&from=" + course_id

	xCourseAPI.setCurrentUserLesson(null, {
		course_id : course_id,
		lesson_id : lesson_id
	}, function(data, status) {
		jQuery.Topic("xcourse_course_lesson_change").publish(course_id,	lesson_id);
	});

}
/* MODULE CREATING */
(function( $ ) {
	

	jQuery("#add-group-rule-dialog").dialog({
		autoOpen: false,
		height: 300,
		width: 400,
		modal: true,
		resizable: false,
		buttons: {
			"Criar": function() {
				jQuery("#name").removeClass( "ui-state-error" );

				if (jQuery("#name").val().length >= 3) {
					jQuery( this ).dialog( "close" );
					
					// PUT ON
					_sysclass("load", "gradebook")._postAction(
						"add_group",
						{
							'name' : jQuery("#name").val(),
							'require_status' : jQuery("#require_status").val(),
							'min_value' : jQuery("#min_value").val()
						},
						function(response, status) {
							if (response.status == 'ok') {
								headerItem = 
									'<span class="gradebook-group-header" id="gradebook-group-header-' + response.data.id + '">' +
									'	<a href="javascript: void(0);" class="indexer-numbered">' + (jQuery(".gradebook-group-header").size() + 1) +'</a>' +
									'	<a href="javascript: _sysclass(\'load\', \'gradebook\').loadGroupRules(' + response.data.id + ');">' +
											response.data.name +
									'	</a>' +
									'</span>';
								
								jQuery(".gradebook-group-header").last().after(headerItem);
							}
						},
						'json'
					);
					
				} else {
					alert("O nome deve ter no mínimo 3 caracteres");
					jQuery("#name").addClass( "ui-state-error" );
				}
			},
			Cancel: function() {
				jQuery( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
	
	
	var dataTableDefaults = {
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
	
	var methods = {
		getSelectedGroup : function() {
			selectedID = jQuery(".gradebook-group-header").filter(".selected").attr("id");
			if (typeof(selectedID) != 'undefined') {
				groupID = selectedID.replace(/\D/g, "");

				return groupID;
			} else {
				return 1;
			}
		},
		addGroup : function() {
			jQuery("#add-group-rule-dialog").dialog('open');
		},
		editGroup : function() {
			//alert(this.getSelectedGroup());
		},
		deleteGroup : function($groupID) {
			if (typeof($groupID) == 'undefined') {
				$groupID = this.getSelectedGroup();
			}
			_sysclass("load", "gradebook")._postAction(
				"delete_group",
				{'group_id' : $groupID},
				function(response, status) {
					if (response.status == 'ok') {
						jQuery("#gradebook-group-header-" + $groupID).remove();
						jQuery("#gradebook-group-row-" + $groupID).remove();
					}
				},
				'json'
			);
		},
		moveGroupUp : function(group_id) {
			if (typeof(group_id) == 'undefined') {
				group_id = this.getSelectedGroup();
			}
			
			var previous = jQuery("#gradebook-group-row-" + group_id).prev("tr");
			var self = this;
			
			if (previous.size() > 0) {
				jQuery("#gradebook-group-row-" + group_id).fadeOut(function() {
					// DO POST
					var selfRow = this;
					self._postAction(
						"move_group",
						{
							'to' : "up",
							'group_id' : group_id,
						},
						function(response, status) {
							if (response.status == 'ok') {
								// ... AND REFRESH UI					
								jQuery(selfRow).insertBefore(previous).fadeIn();
								self.refreshGroupUI();
							} else {
								jQuery(selfRow).show();
							}
						},
						'json'
					);
				});
			} else {
				return false;
			}
		},
		moveGroupDown : function(group_id) {
			if (typeof(group_id) == 'undefined') {
				group_id = this.getSelectedGroup();
			}
			
			var next = jQuery("#gradebook-group-row-" + group_id).next("tr");
			var self = this;
			
			if (next.size() > 0) {
				jQuery("#gradebook-group-row-" + group_id).fadeOut(function() {
					// DO POST
					var selfRow = this;
					self._postAction(
						"move_group",
						{
							'to' : "down",
							'group_id' : group_id,
						},
						function(response, status) {
							if (response.status == 'ok') {
								// ... AND REFRESH UI					
								jQuery(selfRow).insertAfter(next).fadeIn();
								self.refreshGroupUI();
							} else {
								jQuery(selfRow).show();
							}
						},
						'json'
					);
				});
			} else {
				return false;
			}
		},
		loadGroupRules : function(group_id) {
			if (typeof(group_id) == 'undefined') {
				group_id = this.getSelectedGroup();
			}
			
			_sysclass("load", "gradebook")._loadAction(
				"load_group_rules",
				{"group_id" : group_id},
				"#gradebook-group-rules-container"
			);
		},
		loadGroupGrades : function(group_id) {
			if (typeof(group_id) == 'undefined') {
				group_id = this.getSelectedGroup();
			}
			
			jQuery("#gradebook-group-grades-container").empty();
			
			_sysclass("load", "gradebook")._loadAction(
				"load_group_grades",
				{"group_id" : group_id},
				"#gradebook-group-grades-container",
				function() {
					jQuery("#gradebook-group-grades-container table").dataTable(dataTableDefaults);
				}
			);
			
			
			
			
		},
		loadClassesByCourse : function($courseID, $lessonID, callback) {
			this._postAction(
				"load_classes",
				{"course_id" : $courseID, "lesson_id" : $lessonID},
				callback,
				'json'
			);
		},
		deleteColumn : function($columnID) {
			_sysclass("load", "gradebook")._postAction(
				"delete_column",
				{'column_id' : $columnID},
				function(response, status) {
					if (response.status == 'ok') {
						jQuery("#gradebook-column-row-" + $columnID).remove();
					}
				},
				'json'
			);
		},
		importStudentsGrades : function($groupID, $columnID) {
			var self = this;
			this._postAction(
				"import_students_grades",
				{'group_id' : $groupID, 'column_id' : $columnID, "from" : this.opt.action},
				function(response, status) {
					if (response.status == 'ok') {
						self.loadGroupGrades($groupID);
					}
				},
				'json'
			);
		},
		switchToLessonClasse : function(lesson_id, classe_id, course_id) {
			
			classe_id == null ? classe_id = 0 : null;
			
			_sysclass("load", "gradebook")._redirectAction(
				"switch_lesson",
				{'lesson_id' : lesson_id, 'classe_id' : classe_id, 'course_id' : course_id, "from" : this.opt.action}
			);
		},
		setGrade : function(oid, login, grade, callback) {
			//var grade = jQuery(el).prev().val();
			
			var self = this;
			
			this.opt.noMessages = true;
			this._postAction(
				"set_grade",
				{
					"oid"	: oid,
					"login"	: login,
					"grade" : grade
				},
				callback,
				'json'
			);
			this.opt.noMessages = false;
		},
		getStudentScores : function(login) {
			var scores = null;
			
			this.sync(true)._postAction(
				"get_student_scores", 
				{
					"login" : login
				},
				function (data, status) {
					scores = data;
				}
			);
			
			this.sync(false);
			
			return scores;
		},
		refreshGroupUI : function() {
			jQuery(".gradebook-group-row a").show();
			jQuery(".gradebook-group-row:first a.gradebook-group-mode-up").hide();
			jQuery(".gradebook-group-row:last a.gradebook-group-mode-down").hide();
		},
		startUI : function() {
			this.refreshGroupUI();
			
			//jQuery("#switch_lesson").change();
		}
	};

	_sysclass("register", "gradebook", methods);
})( jQuery );

/* MODULE FLOW-LOGIC */
(function( $ ){
	
	
	var courseClasses = [];
	
	jQuery.Topic( "xcourse_course_lesson_change" ).subscribe( function(course_id, lesson_id) {
		var url = window.location.pathname + "?ctg=module&op=module_gradebook&lessons_ID=" + lesson_id;
		jQuery("#modules_gradebook_change_lesson_id").attr("href", url);
	});
	
	jQuery(".gradebook-group-header").live("click", function() {
		jQuery(".gradebook-group-header").removeClass("selected");
		jQuery(this).addClass("selected");
	});
	
	jQuery(".gradebook-group-header").live("dblclick", function() {
		//jQuery(".gradebook-group-header").removeClass("selected");
		//jQuery(this).addClass("selected");
		_sysclass('load', 'gradebook').editGroup();
	});
	if (_sysclass('load', 'gradebook').config().action == 'edit_rule_calculation') {
		if (jQuery(".gradebook-group-header").filter(".selected").size() == 0) {
			jQuery(".gradebook-group-header").first().click();
			_sysclass('load', 'gradebook').loadGroupRules();
		}
	}
	if (_sysclass('load', 'gradebook').config().action == 'students_grades') {
		if (jQuery(".gradebook-group-header").filter(".selected").size() == 0) {
			jQuery(".gradebook-group-header").first().click();
			_sysclass('load', 'gradebook').loadGroupGrades();
		}
	}
	
	
	jQuery("#switch_lesson").change( function() {
		jQuery("#switch_classe option:not(:first)").remove();
		
		CourseAndLesson = jQuery(this).val().split("_");
		if (typeof(courseClasses[CourseAndLesson[0]]) == 'object') {
			injectClasseIntoCombo(courseClasses[CourseAndLesson[0]]);
		} else {
			_sysclass('load', 'gradebook').loadClassesByCourse(
				CourseAndLesson[0], CourseAndLesson[1],
				function(classes) {
					courseClasses[CourseAndLesson[0]] = classes;
					injectClasseIntoCombo(courseClasses[CourseAndLesson[0]]);
				}
				
			);
		}
	});
	
	var injectClasseIntoCombo = function(classes) {
		jQuery("#switch_classe option:not(:first)").remove();
		for(index in classes) {
			jQuery("#switch_classe").append(
				'<option value="' + index + '">' + classes[index] + '</option>'
			);
		}
	}
	
	jQuery("#switch_to_link").click( function() {
		var courseAndLesson = jQuery("#switch_lesson").val().split("_");
		var courseID = courseAndLesson[0];
		var lessonID = courseAndLesson[1];
		var classeID = jQuery('#switch_classe').val();
		_sysclass('load', 'gradebook').switchToLessonClasse(lessonID, classeID, courseID);
		
		return false;
	});
	
	jQuery(".gradebook-grade-input").live('blur', function() {
		var self = this;
		
		jQuery(self).next("img").css("visibility", "visible");
		
		var oid = jQuery(this).data('oid');
		var login = jQuery(this).data('login');
		
		_sysclass('load', 'gradebook').setGrade(
			oid, login, jQuery(this).val(),
			function(data, response) {
				if (typeof(data.scores != undefined)) {
					var $scores = data.scores;
					var cleanedlogin = _sysclass("load", "utils").sanitizeDOMString(login);
					
					for (groupID in $scores.groups) {
						groupDOMID =  "gradebook-group-score-" + groupID + "-" + oid + "-" + cleanedlogin;
						jQuery("#" + groupDOMID).html($scores.groups[groupID]);
					}
					finalDOMID =  "gradebook-final-score-" + oid + "-" + cleanedlogin;
					jQuery("#" + finalDOMID).html(
						$scores.final_score + " - " +
						$scores.final_status
					);
				}
				
				jQuery(self).next("img").css("visibility", "hidden");
			}
		);	
	})

	
	_sysclass('load', 'gradebook').startUI();
	
})( jQuery );

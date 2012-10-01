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
			
			_sysclass("load", "gradebook")._loadAction(
				"load_group_grades",
				{"group_id" : group_id},
				"#gradebook-group-grades-container"
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

		switchToLessonClasse : function(lesson_id, classe_id) {
			
			classe_id == null ? classe_id = 0 : null;
			
			_sysclass("load", "gradebook")._redirectAction(
				"switch_lesson",
				{'lesson_id' : lesson_id, 'classe_id' : classe_id, "from" : this.action}
			);
		},
		refreshGroupUI : function() {
			jQuery(".gradebook-group-row a").show();
			jQuery(".gradebook-group-row:first a.gradebook-group-mode-up").hide();
			jQuery(".gradebook-group-row:last a.gradebook-group-mode-down").hide();
		},
		startUI : function() {
			this.refreshGroupUI();
		}
	};

	_sysclass("register", "gradebook", methods);
})( jQuery );

/* MODULE FLOW-LOGIC */
(function( $ ){
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
	if (_sysclass('load', 'gradebook').action == 'edit_rule_calculation') {
		if (jQuery(".gradebook-group-header").filter(".selected").size() == 0) {
			jQuery(".gradebook-group-header").first().click();
			_sysclass('load', 'gradebook').loadGroupRules();
		}
	}
	if (_sysclass('load', 'gradebook').action == 'students_grades') {
		if (jQuery(".gradebook-group-header").filter(".selected").size() == 0) {
			jQuery(".gradebook-group-header").first().click();
			_sysclass('load', 'gradebook').loadGroupGrades();
		}
	}
	
	_sysclass('load', 'gradebook').startUI();
	
})( jQuery );

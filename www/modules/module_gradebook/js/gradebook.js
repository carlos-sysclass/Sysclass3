/* MODULE CREATING */
(function( $ ) {
	

	jQuery("#add-group-rule-dialog").dialog({
		autoOpen: false,
		height: 250,
		width: 350,
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
			//jQuery("#add-group-rule-dialog").dialog('open');
		},
		deleteGroup : function() {
			//jQuery("#add-group-rule-dialog").dialog('open');
			$groupID = this.getSelectedGroup();
			
			_sysclass("load", "gradebook")._postAction(
				"delete_group",
				{'group_id' : $groupID},
				function(response, status) {
					if (response.status == 'ok') {
						jQuery("#gradebook-group-header-" + $groupID).remove();
					}
				},
				'json'
			);
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
		switchToLessonClasse : function(lesson_id, classe_id) {
			
			classe_id == null ? classe_id = 0 : null;
			
			_sysclass("load", "gradebook")._redirectAction(
				"switch_lesson",
				{'lesson_id' : lesson_id, 'classe_id' : classe_id}
			);
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
	if (jQuery(".gradebook-group-header").filter(".selected").size() == 0) {
		jQuery(".gradebook-group-header").first().click();
		_sysclass('load', 'gradebook').loadGroupRules();
	}
	
})( jQuery );

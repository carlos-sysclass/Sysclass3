/**
 * 
 */
xContentAPI = {
	userContentScheduledLiberation : function(token, sendData, callback, return_type) {
		var actionName = "user_content_scheduled_liberation";
		this._doAction(actionName, token, sendData, callback, return_type);
	},
	userContentNotScheduledLiberation : function(token, sendData, callback, return_type) {
		var actionName = "user_content_not_scheduled_liberation";
		this._doAction(actionName, token, sendData, callback, return_type);
	},
	saveScheduleTimeAction : function(token, sendData, callback, return_type) {
		var actionName = "save_schedule_time";
		this._doAction(actionName, token, sendData, callback, return_type);
	},
	deleteScheduleTimeAction : function(token, sendData, callback, return_type) {
		var actionName = "delete_schedule_time";
		this._doAction(actionName, token, sendData, callback, return_type);
	},
	deleteScheduleContentAction : function(token, sendData, callback, return_type) {
		var actionName = "delete_schedule_content";
		this._doAction(actionName, token, sendData, callback, return_type);
	},
	fetchContentTreeAction : function(token, sendData, callback, return_type) {
		var actionName = "fetch_content_tree";
		this._doAction(actionName, token, sendData, callback, return_type);
	},
	_doAction : function(actionName, token, sendData, callback, return_type) {
		if (return_type === null || typeof return_type === "undefined") {
			return_type = 'json';
		}
		
		var url = 
			window.location.protocol + "//" +
			window.location.hostname +
			window.location.pathname + 
			"?ctg=module&op=module_xcontent" +
			"&action=" + actionName + "&output=" + return_type;
			
		jQuery.post(
				url,
				sendData,
				function(data, status) {
					if (return_type === 'json') {
						jQuery.messaging.show(data);
					}
					if (typeof(callback) === 'function') {
						callback(data, status);
					}
				},
				return_type).error(function(data, status) {
			//window.location.reload();
		});
	}
};
(function(jQuery){
	jQuery("#_XCONTENT_INSERT_DIALOG").dialog({
		autoOpen 	: false,
		height		: 'auto',
		modal 		: true,
		resizable 	: false,
		width		: 'auto'
	});

	
	jQuery("#xcontent_content_tree li").click(function(evt) {
		contentID = new Number(jQuery(this).attr("id").replace(/node/, ""));
		if (jQuery(this).hasClass("tests") || jQuery(this).hasClass("theory")) {
			// GET CONTENT ID AND SET HIDDEN FIELD
			
			jQuery(":input[name='content_id']").val(contentID);
			jQuery("#xcontent_content_tree_text").html(jQuery(this).find("a").attr("title"));
			evt.stopPropagation();
			
			return true;
		}
	});
	
	jQuery(".contentInsert").bind('click', function() {
		jQuery("#_XCONTENT_INSERT_DIALOG").dialog('open');
	});
	
	jQuery("#xcontent_new_schedule_form :input[name='lesson_id']").bind('change', function() {
		
		course_lesson = jQuery(this).val().split("_");
		xContentAPI.fetchContentTreeAction(null, {
			"course_id" : course_lesson[0],
			"lesson_id" : course_lesson[1]
		}, function(data) {
			jQuery("#xcontent_content_tree_container").html(data);
		}, 'html');
	});
	
	jQuery("#xcontent_content_tree_container li").live("click", function(evt) {
		contentID = new Number(jQuery(this).attr("id").replace(/node/, ""));
		if (jQuery(this).hasClass("tests") || jQuery(this).hasClass("theory")) {
			// GET CONTENT ID AND SET HIDDEN FIELD
			
			jQuery(":input[name='content_id']").val(contentID);
			jQuery("#xcontent_content_tree_text").html(jQuery(this).find("a").attr("title"));
			
			
			jQuery("#xcontent_content_tree_container li").removeClass("xcontentSelected");
			jQuery(this).addClass("xcontentSelected");
			
			evt.stopPropagation();
			
			return true;
		}
	});
	// #xcontent_content_tree_container
	
	jQuery(".contentScheduleInsert").live('click', function() {
		var contentScheduleModel = jQuery(this).parents("form").find("#schedule_clonable").clone();

		contentScheduleModel.find(":input[alt='date']").setMask('date');
		contentScheduleModel.find(":input[alt='time']").setMask('time');

		jQuery(this).parents("form").find("ul").append(contentScheduleModel.removeAttr('id').show());

		contentScheduleModel.find(".contentScheduleEdit").click();
	});
	
	jQuery(".contentScheduleEdit").live('click', function() {
		var parentContext = jQuery(this).parents("li");
		
		parentContext.find(".dateField, .startField, .endField").removeAttr("readonly").css({
			"background" : "auto",
			"border-color" : "#999999"
		});
		
		parentContext.find(".contentScheduleSave, .contentScheduleCancel").show();
		parentContext.find(".contentScheduleEdit, .contentScheduleDelete").hide();
		
		return false;
	});
	
	jQuery(".contentScheduleDelete").live('click', function() {
		// CALL FUNCTION TO REMOVE
		var parentContext = jQuery(this).parents("li");
		
		if (parentContext.find(".indexField").val() > 0) {
			var sendData = {
				'xschedule_id'	: jQuery(":input[name='xschedule_id']").val(),
				'index'			: parentContext.find(".indexField").val()
			};
			
			xContentAPI.deleteScheduleTimeAction(null, sendData, function(data, response) {
				if (data.success) {
					parentContext.remove();
				}
			});
		} else {
			parentContext.remove();	
		}
		return false;
	});
	
	jQuery(".contentScheduleSave").live('click', function() {
		var parentContext = jQuery(this).parents("li");
	
		var sendData = {
			'xschedule_id'	: jQuery(":input[name='xschedule_id']").val(),
			'index'			: parentContext.find(".indexField").val(),
			'date'			: parentContext.find(".dateField").val(),
			'start'			: parentContext.find(".startField").val(),
			'end'			: parentContext.find(".endField").val()
		};
		
		xContentAPI.saveScheduleTimeAction(null, sendData, function(data, response) {
			if (data.success) {
				parentContext.find(".indexField").val(data.index);
				parentContext.find(".contentScheduleCancel").click();
			}
		}, 'json');
		
		return false;
	});
	
	jQuery(".contentScheduleCancel").live('click', function() {
		var parentContext = jQuery(this).parents("li");
		
		if (parentContext.find(".indexField").val() > 0) {
			parentContext.find(".dateField, .startField, .endField").attr("readonly", "true").css({
				"background" : "transparent",
				"border-color" : "transparent"
			});
				
			parentContext.find(".contentScheduleSave, .contentScheduleCancel").hide();
			parentContext.find(".contentScheduleEdit, .contentScheduleDelete").show();
			
			return false;
		} else {
			parentContext.find(".contentScheduleDelete").click();
			return true;
		}
	});
	

	
	if (jQuery("._XCONTENT_SCHEDULE_LIST").size() > 0) {
		defaults = {
				"bJQueryUI": false,
				"bPaginate": true,
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": false,
				"bAutoWidth": true,
				"iDisplayLength"	: 50,
				"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Tudo"]],
				"bDeferRender" : true,
				"sPaginationType": "full_numbers",
				//"bScrollCollapse": true,
				//"sDom" : 't',
				"oLanguage": {
					"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			}
		};
		opt = defaults;
					
		jQuery("._XCONTENT_SCHEDULE_LIST").each(function() {
			jQuery(this).dataTable( opt );
		});
	}
	
	if (jQuery("._XCONTENT_SCHEDULED_USERS_LIST").size() > 0) {
		defaults = {
				"bJQueryUI": false,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": false,
				"bSort": true,
				"bInfo": false,
				"bAutoWidth": true,
				//"iDisplayLength"	: 50,
				//"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Tudo"]],
				"bDeferRender" : true,
				//"sPaginationType": "full_numbers",
				//"bScrollCollapse": true,
				//"sDom" : 't',
				"oLanguage": {
					"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
			}
		};
		opt = defaults;
					
		jQuery("._XCONTENT_SCHEDULED_USERS_LIST").each(function() {
			jQuery(this).dataTable( opt );
		});
	}
	
	
	
})(jQuery);


function doAjaxContentScheduledLiberation(schedule_id, user_id, content_id, el) {
	if (schedule_id && user_id && content_id) {
		xContentAPI.userContentScheduledLiberation(
			null, 
			{
				"schedule_id" 	: schedule_id, 
				"user_id" 		: user_id,
				"content_id"	: content_id,
				"liberation" : (el.checked ? 1 : 0)
			},
			function() {
				
			}
		);
	}
}
function doAjaxContentNotScheduledLiberation(schedule_id, user_id, course_id, el) {
	if (schedule_id && user_id) {
		xContentAPI.userContentNotScheduledLiberation(
			null, 
			{
				"schedule_id" 	: schedule_id, 
				"user_id" 		: user_id,
				"course_id"		: course_id,
				"liberation" : (el.checked ? 1 : 0)
			},
			function() {
				
			}
		);
	}
}
function doAjaxDeleteScheduleContent(schedule_id, course_id, content_id, caller) {
	var container = jQuery(caller).parents("li"); 
	xContentAPI.deleteScheduleContentAction(
		null, 
		{"schedule_id" : schedule_id, "course_id" : course_id, "content_id" : content_id},
		function(data) {
			if (data.success) {
				container.remove();
			} 
		}, 'json'
	);
}
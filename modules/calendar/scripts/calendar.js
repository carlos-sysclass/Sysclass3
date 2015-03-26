$SC.module("portlet.calendar", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {

		this.collection = new Backbone.Collection;
		this.collection.url = "/module/calendar/data";

	  	// VIEWS
	  	var viewClass = Backbone.View.extend({
		    el: $('#calendar'),
		    portlet: $('#calendar-widget'),
		    calendarDialog : $('#calendar-dialog'),
		    calendarCreateDialog : $('#calendar-create-dialog'),
		    calOptions : {},

		    initialize: function() {
				if (!$.fn.fullCalendar) {
		        	return;
				}
				var h = {};

	            if (App.isRTL()) {
	                if (this.portlet.width() <= 720) {
	                    h = {
	                        right: 'title, prev, next',
	                        center: '',
	                        right: 'agendaDay, agendaWeek, month, today'
	                    };
	                } else {
	                    h = {
	                        right: 'title',
	                        center: '',
	                        left: 'agendaDay, agendaWeek, month, today, prev,next'
	                    };
	                }                
	            } else {
	                 if (this.portlet.width() <= 720) {
	                    h = {
	                        left: 'title, prev, next',
	                        center: '',
	                        right: 'today,month,agendaWeek'
	                    };
	                } else {
	                    h = {
	                        left: 'title',
	                        center: '',
	                        right: 'prev,next,today,month,agendaWeek'
	                    };
	                }
	            }
	            
	            this.calOptions =
	            { //re-initialize the calendar
	                header: h,
	                slotMinutes: 15,
	                selectable: false,
	                editable: false,
	                droppable: false,
	                 eventSources:
	                 [
						 '/module/calendar/data'
	                 ],
	                eventClick : function(event, jsEvent, view)
	                {
	                	mod.view.calendarDialog.find(".event-description").html(event.description);
	                	mod.view.calendarDialog.modal('show');
	                },
	                dayClick: function(date, jsEvent, view)
	                {
	                	mod.view.calendarCreateDialog.find("#date").val(date.toISOString().slice(0, 10));
	                	mod.view.calendarCreateDialog.modal('show');
	                }
	            };
	            
		        this.render();
		    },
		    render: function() {
				this.$el.fullCalendar('destroy'); // destroy the calendar
                if (this.portlet.width() <= 720) {
                    this.$el.addClass("mobile");
                } else {
                    this.$el.removeClass("mobile");
                }
		        this.$el.fullCalendar(this.calOptions);
		    }
	  	});

		this.view = new viewClass();
		this.searchBy = "title";
	});
});

jQuery("#event-to-filter").change
(
	function()
	{
		$('#calendar').fullCalendar('removeEvents');

		$('#calendar').fullCalendar('addEventSource', '/module/events/data/' + $("#event-to-filter").val());
    	//$('#calendar').fullCalendar('refetchEvents');
	}
);

jQuery("#form-calendar-event-creation").submit
(
	function()
	{
		var name 		= document.getElementById("name-modal").value;
        var description = document.getElementById("description").value;
        var date 		= document.getElementById("date").value;
        var type_id 	= document.getElementById("type_id").value;
        
		$.ajax
		(
            {
                url: "/module/calendar/item/me/",
                type: "POST",
                data: { name: name, description: description, date: date, type_id: type_id },
                success: function(data)
                {
                	alert("success");
                },
                error: function( XMLHttpRequest, textStatus, errorThrown)
                {
                	alert("XMLHttpRequest: " + XMLHttpRequest + "\n" + "textStatus: " +textStatus + "\n" + "errorThrown: " + errorThrown);
                }
            }
        );
	}
);
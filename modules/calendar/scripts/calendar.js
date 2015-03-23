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
	                	mod.view.calendarCreateDialog.find("#event-date").val(date.toISOString().slice(0, 10));
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

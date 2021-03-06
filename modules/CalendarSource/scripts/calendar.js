$SC.module("portlet.calendar", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {

		//this.collection = new Backbone.Collection;
		//this.collection.url = "/module/calendar/data";

		var parent = app.module("portlet");

	  	
	  	var viewClass = parent.widgetViewClass.extend({
		    //portlet: $('#calendar-widget'),
		    calendarDialog : $('#calendar-dialog'),
		    calendarCreateDialog : $('#calendar-create-dialog'),
		    calOptions : {},

		    initialize: function() {
				if (!$.fn.fullCalendar) {
		        	return;
				}
				var h = {};

	            if (App.isRTL()) {
	                if (this.$el.width() <= 720) {
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
	                 if (this.$el.width() <= 720) {
	                    h = {
	                        left: 'title, prev, next',
	                        center: '',
	                        right: 'today,month,agendaWeek'
	                    };
	                } else {
	                    h = {
	                        left: 'title',
	                        center: '',
	                        right: 'prev,next,today,month,agendaWeek,timelineView'
	                    };
	                }
	            }

	            this.calOptions =
	            { //re-initialize the calendar
	                header: h,
	                slotMinutes: 15,
	                editable: false,
                    eventSources: [
                        '/module/calendar/datasource/calendar'
	                ],
                    eventDataTransform : function( eventData ) {
                        if (/^-?[\d.]+(?:e-?\d+)?$/.test(eventData.id)) {
                            eventData.start = moment.unix(eventData.start);
                            eventData.end = moment.unix(eventData.end);
                        }
                        return eventData;
                    },
	                eventClick : function(event, jsEvent, view)
	                {
	                	mod.view.calendarDialog.find(".event-title").html(event.title);
	                	mod.view.calendarDialog.find(".event-description").html(event.description);
	                	mod.view.calendarDialog.modal('show');
	                },
	                /*
	                dayClick: function(date, jsEvent, view)
	                {
	                	mod.view.calendarCreateDialog.find("#date").val(date.toISOString().slice(0, 10));
	                	mod.view.calendarCreateDialog.modal('show');
	                }
                    */
	            };


	            this.$(":input[name='event_type']").change(function(e,a,b,c,d) {
	            	var data = e.added;
	            	this.$(".fc-event").show();
	            	if (!_.isEmpty(data.id)) {
	            		var class_name = data.id;

						this.$(".fc-event").not("." + class_name).hide();
	            	}

	            }.bind(this));

		        this.render();
		    },
		    render: function() {
				this.$("#calendar").fullCalendar('destroy'); // destroy the calendar
                if (this.$el.width() <= 720) {
                    this.$("#calendar").addClass("mobile");
                } else {
                    this.$("#calendar").removeClass("mobile");
                }
		        this.$("#calendar").fullCalendar(this.calOptions);
		    }
	  	});

		this.view = new viewClass({
			el: '#calendar-widget'
		});

		mod.view.$(".portlet-sidebar .close").on("click", function() {
			mod.view.$(".portlet-sidebar").hide();
		});

		mod.onFilter = function() {
			mod.view.$(".portlet-sidebar").toggle();
		}

		this.searchBy = "title";
        $('.fc-prev-button, .fc-next-button, .fc-today-button').click(function() {
			this.$(":input[name='event_type']").val();
		});
        /*
        jQuery("#event-to-filter").change
        (
            function()
            {
            	$('#calendar').fullCalendar('removeEventSource');
            	$('#calendar').fullCalendar('removeEventSource', '/module/events/data/0');
                $('#calendar').fullCalendar('removeEvents');

                $('#calendar').fullCalendar('addEventSource', '/module/events/data/' + $("#event-to-filter").val());
            }
        );

        $("#form-calendar-event-creation").validate({
            ignore: null,
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class

            errorPlacement: function (error, element) { // render error placement for each input type

                //if (element.attr("name") == "membership") { // for uniform radio buttons, insert the after the given container
                //    error.insertAfter("#form_2_membership_error");
                if (element.hasClass("wysihtml5")) { // for wysiwyg editors
                    //console.log(element.data('wysihtml5').editor.composer.iframe);
                    error.insertAfter(element.data('wysihtml5').editor.composer.iframe);
                //} else if (element.attr("name") == "service") { // for uniform checkboxes, insert the after the given container
                //    error.insertAfter("#form_2_service_error");
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            highlight: function (element) { // hightlight error inputs
               $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                    .addClass('valid').addClass('help-block') // mark the current input as valid and display OK icon
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler : function(f) {
                //f.submit();

                $.ajax({
                    url: "/module/calendar/item/me/",
                    type: "POST",
                    data: $(f).serialize(),
                    success: function(data)
                    {
                        $('#calendar').fullCalendar('removeEvents');

                        $('#calendar').fullCalendar('addEventSource', '/module/events/data/0');
                    },
                    error: function( XMLHttpRequest, textStatus, errorThrown)
                    {
                        alert("Não foi possível completar a sua requisição.");
                    }
                });
            }
        });
        */
	});
});


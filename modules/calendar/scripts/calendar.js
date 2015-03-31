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
$('.fc-button-prev').click
        (
        	function()
        	{
        		$('#calendar').fullCalendar('removeEventSource');
            	//$('#calendar').fullCalendar('removeEventSource', '/module/events/data/0');
        		$('#calendar').fullCalendar('removeEvents');

	         	var listOptions;
		        var i;
		        
		        listOptions = document.getElementById("event-to-filter").options;

			    $(".select2-chosen").html("All");
	     		//$("#event-to-filter").val("0");

	     		for(i = 0; i < listOptions.length; i++)
        		{
        			listOptions[i].selected = false;

				    $('#calendar').fullCalendar('removeEventSource', '/module/events/data/' + i);
					$('#calendar').fullCalendar('removeEvents');
				}
        		
        		listOptions[0].selected = true;
			}
		);

		$('.fc-button-next').click
		(
			function()
			{
				$('#calendar').fullCalendar('removeEventSource');
            	//$('#calendar').fullCalendar('removeEventSource', '/module/events/data/0');
        		$('#calendar').fullCalendar('removeEvents');
        		
				var listOptions;
		        var i;
		        
		        listOptions = document.getElementById("event-to-filter").options;

			    $(".select2-chosen").html("All");
	     		//$("#event-to-filter").val("0");

	     		for(i = 0; i < listOptions.length; i++)
        		{
        			listOptions[i].selected = false;
        			
				    $('#calendar').fullCalendar('removeEventSource', '/module/events/data/' + i);
					$('#calendar').fullCalendar('removeEvents');
				}
        		
        		listOptions[0].selected = true;
			}
		);

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
	});
});


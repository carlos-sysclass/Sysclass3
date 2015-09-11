$SC.module("views.config", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    mod.addInitializer(function() {

        //this.collection = new Backbone.Collection;
        //this.collection.url = "/module/calendar/data";

        // VIEWS
        var viewClass = Backbone.View.extend({
            el: $('#calendar-container'),
            //portlet: $('#calendar-widget'),
            //calendarDialog : $('#calendar-dialog'),
            //calendarCreateDialog : $('#calendar-create-dialog'),
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
                            right: 'prev,next,today,month,agendaWeek'
                        };
                    }
                }

                this.calOptions =
                { //re-initialize the calendar
                    header: h,
                    slotMinutes: 15,
                    selectable: false,
                    editable: true,
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    drop: function(date, allDay) { // this function is called when something is dropped

                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');
                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.className = $(this).attr("data-class");

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                        // is the "remove after drop" checkbox checked?
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }
                    },
                    eventSources: [
                        {
                            url : '/module/calendar/data',
                            color: '#005999',   // a non-ajax option
                            borderColor: "#aaaaaa",
                            textColor: 'white', // a non-ajax option
                        }
                    ],

                    eventClick : function(event, jsEvent, view)
                    {
                        //mod.view.calendarDialog.find(".event-description").html(event.description);
                        //mod.view.calendarDialog.modal('show');
                    },
                    dayClick: function(date, jsEvent, view)
                    {
                        //mod.view.calendarCreateDialog.find("#date").val(date.toISOString().slice(0, 10));
                        //mod.view.calendarCreateDialog.modal('show');
                    }
                };

                this.$('#event-add-action').unbind('click').click(function() {
                    var title = this.$('#event_title').val();
                    this.createEvent(title);
                }.bind(this));

                //predefined events
                //
                /*
                this.$('#event_box').html("");
                this.createEvent("My Event 1");
                this.createEvent("My Event 2");
                this.createEvent("My Event 3");
                this.createEvent("My Event 4");
                this.createEvent("My Event 5");
                this.createEvent("My Event 6");
                */
                this.render();

                /*
                $('#external-events div.external-event').each(function() {
                    initDrag($(this));
                });
                */

            },
            createDraggable : function(el) {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim(el.text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                el.data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                el.draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            },
            createEvent : function(title) {
                title = title.length === 0 ? "Untitled Event" : title;
                var html = $('<div class="external-event label label-default">' + title + '</div>');
                this.$('#event_box').append(html);
                this.createDraggable(html);
            },
            render: function() {
                this.$el.fullCalendar('destroy'); // destroy the calendar
                if (this.$el.width() <= 720) {
                    this.$el.addClass("mobile");
                } else {
                    this.$el.removeClass("mobile");
                }
                this.$("#calendar").fullCalendar(this.calOptions);
            }
        });

        this.view = new viewClass();
        /*
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
        */
    });
});


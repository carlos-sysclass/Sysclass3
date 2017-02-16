$SC.module("views.manage", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    mod.addInitializer(function() {

        //this.collection = new Backbone.Collection;
        //this.collection.url = "/module/calendar/data";

        // VIEWS
        //
        var baseFormClass = app.module("views").baseFormClass;
        var eventCreationDialogViewClass = baseFormClass.extend({

            initialize: function() {
                console.info('views.views.manage/eventCreationDialogViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                var self = this;
                /*
                this.$el.modal({
                    autoShow : false
                });
                */
                /*
                this.on("after:save", function(model) {
                    self.model = new mod.models.tutoria({
                        title : ""
                    });
                    self.render();
                });
                */
            },
            open : function() {
                this.$el.modal("show");
            },
            close : function() {
                this.$el.modal("hide");
            }
            /*
            handleValidation: function() {
                console.info('views/baseFormClass::handleValidation');
                var self = this;

                this.oForm.validate({
                    ignore: null,
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block', // default input error message class
                    errorPlacement: function(error, element) {
                        error.appendTo( element.closest(".chat-form") );
                    },
                    highlight: function (element) { // hightlight error inputs
                        // set error class to the control group
                        $(element).closest('.form-group').addClass('has-error')
                            .find(".input-group-btn button").removeClass("blue").addClass("red");
                    },
                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element).closest('.form-group').removeClass('has-error')
                            .find(".input-group-btn button").removeClass("red").addClass("blue");
                    },
                    success: function (label) {
                        label.closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },
                    submitHandler : function(f) {
                        self.save();
                    }
                });
            }
            */
        });



        var calendarManagerViewClass = Backbone.View.extend({
            //comboTemplate : _.template($("#calendar-event-source-combo-template").html(), null, {variable: "model"}),
            //portlet: $('#calendar-widget'),
            //calendarDialog : $('#calendar-dialog'),
            //calendarCreateDialog : $('#calendar-create-dialog'),
            calOptions : {},

            initialize: function(opt) {
                if (!$.fn.fullCalendar) {
                    return;
                }
                var h = {};

                this.listenTo(this.collection, "sync", this.createCalendar.bind(this));
                this.listenTo(this.collection, "sync", this.createSourceCombo.bind(this));
                /*
                $('#external-events div.external-event').each(function() {
                    initDrag($(this));
                });
                */
            },
            createSourceCombo : function() {
                /*
                this.$("#calendar-event-source-combo").html(
                    this.comboTemplate(this.collection.toJSON())
                );
                */
                /*
                this.$("#calendar-event-source-combo").select2({
                    templateResult: formatResult,
                    templateSelection: formatResult,
                    data : this.collection.toJSON()
                });
                */

            },
            createCalendar : function() {
                var self = this;
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
                    timezone : 'UTC',
                    //droppable: true, // this allows things to be dropped onto the calendar !!!
                    /*
                    drop: function(date, allDay) { // this function is called when something is dropped

                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');
                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.end = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.className = $(this).attr("data-class");

                        // CREATE THE EVENT AND AFTER THAT, RENDER INTO CALENDAR

                        var model = new mod.models.event(copiedEventObject);
                        model.save();
                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                        // is the "remove after drop" checkbox checked?
                        //if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        //}
                    },
                    */
                    eventDataTransform : function( eventData ) {
                        if (/^-?[\d.]+(?:e-?\d+)?$/.test(eventData.id)) {
                            eventData.start = moment.unix(eventData.start).utc();
                            eventData.end = moment.unix(eventData.end).utc();
                        }

                        return eventData;
                    },
                    eventRender : function( event, element, view ) {
                        if (/^-?[\d.]+(?:e-?\d+)?$/.test(event.id)) {
                            if (element.find(".fc-content .remove-event").size() == 0) {
                                element.find(".fc-content").append(
                                    '<span href="javascript:void(0);" aria-hidden="true" ' +
                                    'class="pull-right no-border remove-event-container ' + event.className + '">' +
                                        '<i class="remove-event fa fa-close"></i>' +
                                    '</span>'
                                );
                            }
                        }

                    },
                    //eventSources: this.collection.toJSON(),
                    googleCalendarApiKey: 'AIzaSyAFwmTQ7O_yp6ZsFTWkejI9S7l0RqYQkTo',
                    eventSources : [
                        {
                            url: "/module/calendar/datasource/calendar",
                        },
                        /*
                        {
                            googleCalendarId: 'en.usa#holiday@group.v.calendar.google.com',
                            className: 'calendar-holidays-item'
                        }
                        */
                        {
                            googleCalendarId: 'pt.brazilian#holiday@group.v.calendar.google.com',
                            className: 'calendar-holidays-item'
                        },
                        {
                            googleCalendarId: 'pt.py#holiday@group.v.calendar.google.com',
                            className: 'calendar-holidays2-item'
                        }
                    ],
                    /*[
                        {
                            url : '/module/calendar/data',
                            color: '#005999',   // a non-ajax option
                            borderColor: "#aaaaaa",
                            textColor: 'white', // a non-ajax option
                        }
                    ],
                    */
                    eventClick : function(event, jsEvent, view)
                    {
                        if ($(jsEvent.target).hasClass("remove-event")) {
                            // CREATE THE MODEL AND REMOVE
                            // OPEN DELETE CONFIRMATON DIALOG
                            var model = self.createEventModel(event);

                            model.destroy({
                                success : function() {
                                    this.$('#calendar').fullCalendar('removeEvents', event.id);
                                }
                            });
                            jsEvent.preventDefault();
                        } else {
                            var model = self.createEventModel(event);

                            self.stopListening(model);

                            self.listenTo(model, "sync", function(model, b, c, d) {

                                this.$('#calendar').fullCalendar('refetchEvents');

                                mod.eventCreationDialogView.close();

                            }.bind(self));

                            mod.eventCreationDialogView.setModel(model);
                            mod.eventCreationDialogView.open();
                        }
                        //mod.view.calendarDialog.find(".event-description").html(event.description);
                        //mod.view.calendarDialog.modal('show');
                    },
                    eventResize: function(event, delta, revertFunc) {
                        var model = self.createEventModel(event);
                        model.save();

                        //self.$('#calendar').fullCalendar('refetchEvents');
                    },
                    eventDrop: function(event, delta, revertFunc) {
                        var model = self.createEventModel(event);
                        model.save();

                        //self.$('#calendar').fullCalendar('refetchEvents');
                    },
                    dayClick: function(date, jsEvent, view)
                    {
                        //mod.view.calendarCreateDialog.find("#date").val(date.toISOString().slice(0, 10));
                        //mod.view.calendarCreateDialog.modal('show');

                        // retrieve the dropped element's stored Event Object
                        //var originalEventObject = $(this).data('eventObject');
                        // we need to copy it, so that multiple events don't have a reference to the same object
                        //var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        //copiedEventObject.start = date;
                        //copiedEventObject.end = date;
                        //copiedEventObject.allDay = allDay;
                        //copiedEventObject.className = $(this).attr("data-class");

                        var eventObject = {
                            source_id : "",
                            title: "",
                            description : "",
                            //source_id : sourceModel.get("id"),
                            start : date,
                            end : date,
                        };


                        // CREATE THE EVENT AND AFTER THAT, RENDER INTO CALENDAR
                        var model = self.createEventModel(eventObject);

                        self.stopListening(model);

                        self.listenTo(model, "sync", function(model, b, c, d) {
                            this.$('#calendar').fullCalendar('refetchEvents');
                            mod.eventCreationDialogView.close();

                        }.bind(self));

                        mod.eventCreationDialogView.setModel(model);
                        mod.eventCreationDialogView.open();

                        //this.eventCreationDialogView.open();


                    }
                };
                /*
                this.$('#event-add-action').unbind('click').click(function() {
                    var title = this.$('form').val();
                    var values = this.$("form.inline-form").serializeArray();
                    var EventObject = {};
                    for(key in values) {
                        EventObject[values[key].name] = values[key].value;
                    }
                    this.createEvent(EventObject);
                }.bind(this));
                */
                this.render();
            },
            createEventModel : function (event) {
                var eventObject = jQuery.extend(true, {}, event);

                eventObject.start = moment.utc(event.start).unix();
                if (_.isNull(eventObject.end)) {
                    eventObject.end = moment.utc(event.start).unix();
                } else {
                    eventObject.end = moment.utc(event.end).unix();
                }

                var model = new mod.models.event(eventObject);
                return model;
            },
            /*
            createDraggable : function(el) {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                // make the event draggable using jQuery UI
                el.draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            },
            */
            /*
            createEvent : function(EventObject) {
                var title = EventObject.title.length === 0 ? "Untitled Event" : EventObject.title;
                var type_id = EventObject.type_id;

                var sourceModel = this.collection.get(type_id);

                var html = $('<div class="external-event label ' + sourceModel.get("className") + '" ' +
                    'data-class="' + sourceModel.get("className") + '"' +
                    '">' +
                    title +
                '</div>');

                var eventObject = {
                    title: title,
                    source_id : sourceModel.get("id")
                };



                // store the Event Object in the DOM element so we can get to it later

                var el = $(html)
                    .appendTo(this.$('#event_box'))
                    .data('eventObject', eventObject);

                //this.$('#event_box').append(html);
                this.createDraggable(el);
            },
            */
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

        this.collections = {
            event_source : Backbone.Collection.extend({
                url : "/module/calendar/items/event-sources"
            })
        };

        var baseModelClass = $SC.module("models").getBaseModel();
        this.models = {
            event : baseModelClass.extend({
                response_type : "object",
                urlRoot : "/module/calendar/item/me"
            })
        };

        this.eventSourceCollection = new mod.collections.event_source();

        this.calendarManagerViewClass = new calendarManagerViewClass({
            el : '#calendar-container',
            collection : this.eventSourceCollection
        });

        this.eventCreationDialogView = new eventCreationDialogViewClass({
            el : "#calendar-create-dialog"
        });

        this.eventSourceCollection.fetch();


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


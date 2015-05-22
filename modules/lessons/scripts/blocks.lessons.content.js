$SC.module("blocks.lessons.content", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        this.config = $SC.module("crud.config").getConfig();
        var entity_id = mod.config.entity_id;


        var textContentTimelineViewClass = Backbone.View.extend({
            template : _.template($("#text-timeline-item").html()),
            className : "timeline-item",
            tagName : "div",
            editMode : null,
            events : {
                "dblclick .timeline-body"          : "edit",
                "click .edit-text-content"      : "edit",
                "click .save-text-content"      : "save",
                "click .delete-text-content"    : "delete"
            },
            /*
            initialize: function(opt) {
                console.info('blocks.lessons.content/textContentTimelineViewClass::initialize');
            },
            */
            render : function() {
                this.$el.html(this.template(this.model.toJSON()));

                return this;
            },
            initEditor : function() {
                var self = this;
                console.warn(this.$el);
                app.module("ui").refresh(this.$el);

                this.wysihtml5 = this.$(".wysihtml5").data('wysihtml5');
                this.editor = this.wysihtml5.editor;

                this.editMode = true;

                this.editor.on("change", function(e) {
                    self.$(".preview").html($(self.wysihtml5.el).val());
                });
            },
            edit : function(e) {
                e.stopPropagation();
                if (!this.editMode) {
                    this.$(".edit-text-content").addClass("hidden");
                    this.$(".save-text-content").removeClass("hidden");
                    this.$(".preview").addClass("hidden");

                    //this.editor = this.$(".wysihtml5").data('wysihtml5').editor;
                    this.$(".wysihtml5").parents(".wysihtml5-container").removeClass("hidden");
                    //this.editor.toolbar.show();
                    //this.editor.composer.show();
                    this.editMode = true;
                }
            },
            save : function(e) {
                e.stopPropagation();
                this.$(".text-loading").removeClass("hidden");
                // SEND MODEL TO SERVER
                // this.model.save();
                this.$(".preview").html($(this.wysihtml5.el).val());
                // UPDATE UI (destroy editor, show only text)
                this.$(".wysihtml5").parents(".wysihtml5-container").addClass("hidden");
                //this.editor.toolbar.hide();
                //this.editor.composer.hide();
                this.$(".preview").removeClass("hidden");
                // Put content on a DIV
                // Hide save button and show edit button
                this.$(".save-text-content").addClass("hidden");
                this.$(".edit-text-content").removeClass("hidden");


                //$('#editorId').data('wysihtml5').editor.composer.enable();
                // DESTROY WI
                // INFORM PARENT
                this.editMode = false;
                this.trigger("timeline-text-content:save", this.model);
                this.$(".text-loading").addClass("hidden");
            },
            delete : function() {
                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.$el.remove();

                this.trigger("timeline-text-content:delete", this.model);
            }
        });

        var exercisesContentTimelineViewClass = Backbone.View.extend({
            template : _.template($("#exercise-timeline-item").html()),
            className : "timeline-item",
            tagName : "div",
            editMode : null,
            events : {
                "dblclick .timeline-body"          : "edit",
                "click .edit-text-content"      : "edit",
                "click .save-text-content"      : "save",
                "click .delete-text-content"    : "delete"
            },
            /*
            initialize: function(opt) {
                console.info('blocks.lessons.content/textContentTimelineViewClass::initialize');
            },
            */
            render : function() {
                this.$el.html(this.template(this.model.toJSON()));

                return this;
            },
            initEditor : function() {
                var self = this;
                console.warn(this.$el);
                app.module("ui").refresh(this.$el);

                this.wysihtml5 = this.$(".wysihtml5").data('wysihtml5');
                this.editor = this.wysihtml5.editor;

                this.editMode = true;

                this.editor.on("change", function(e) {
                    self.$(".preview").html($(self.wysihtml5.el).val());
                });
            },
            edit : function(e) {
                e.stopPropagation();
                if (!this.editMode) {
                    this.$(".edit-text-content").addClass("hidden");
                    this.$(".save-text-content").removeClass("hidden");
                    this.$(".preview").addClass("hidden");

                    //this.editor = this.$(".wysihtml5").data('wysihtml5').editor;
                    this.$(".wysihtml5").parents(".wysihtml5-container").removeClass("hidden");
                    //this.editor.toolbar.show();
                    //this.editor.composer.show();
                    this.editMode = true;
                }
            },
            save : function(e) {
                e.stopPropagation();
                this.$(".text-loading").removeClass("hidden");
                // SEND MODEL TO SERVER
                // this.model.save();
                this.$(".preview").html($(this.wysihtml5.el).val());
                // UPDATE UI (destroy editor, show only text)
                this.$(".wysihtml5").parents(".wysihtml5-container").addClass("hidden");
                //this.editor.toolbar.hide();
                //this.editor.composer.hide();
                this.$(".preview").removeClass("hidden");
                // Put content on a DIV
                // Hide save button and show edit button
                this.$(".save-text-content").addClass("hidden");
                this.$(".edit-text-content").removeClass("hidden");


                //$('#editorId').data('wysihtml5').editor.composer.enable();
                // DESTROY WI
                // INFORM PARENT
                this.editMode = false;
                this.trigger("timeline-text-content:save", this.model);
                this.$(".text-loading").addClass("hidden");
            },
            delete : function() {
                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.$el.remove();

                this.trigger("timeline-text-content:delete", this.model);
            }
        });
        var contentTimelineViewClass = Backbone.View.extend({
            events : {
                "click .timeline-addtext" : "addTextContent",
                "click .timeline-addexercise" : "addExercisesContent"
            },
            uploadTemplate : _.template($("#fileupload-upload-timeline-item").html()),
            downloadTemplate : _.template($("#fileupload-download-timeline-item").html()),
            initialize: function(opt) {
                console.info('blocks.lessons.content/contentTimelineViewClass::initialize');

                this.initializeSortable();
                this.initializeFileUpload();


            },
            initializeSortable : function() {
                this.$el.sortable({
                    //connectWith: ".list-group",
                    items: "div.timeline-item",
                    opacity: 0.8,
                    /* axis : "y", */
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    /* helper : 'original',  */
                    receive : function( event, ui ) {
                        /*
                        $(this).removeClass("empty-list-group");
                        //self.refreshCounters();
                        // HANDLE COLLECTIONS
                        //
                        var classe_id = ui.item.data('classId');

                        var item = mod.classesCollection.findWhere({id : classe_id.toString()});

                        var classes = self.model.get('classes');

                        classes.push(item.toJSON());
                        self.model.set('classes', classes);
                        */
                    },
                    remove : function( event, ui ) {
                        /*
                        if ($(this).children().size() == 0) {
                            $(this).addClass("empty-list-group");
                        }
                        self.refreshCounters();

                        // HANDLE COLLECTIONS
                        var classe_id = ui.item.data('classId');

                        var classes = self.model.get('classes');

                        remainingClasses = _.filter(classes, function(model, i) {
                            if (model.id == classe_id) {
                                return false;
                            }
                            return true;
                        });

                        self.model.set('classes', remainingClasses);
                        */
                    },
                    over : function( event, ui ) {
                        //$(this).addClass("ui-sortable-hover");
                    },
                    out  : function( event, ui ) {
                        //$(this).removeClass("ui-sortable-hover");
                    },
                });
            },
            initializeFileUpload : function() {
                // CREATE FILEUPLOAD WIDGET
                var url = this.$el.data("fileuploadUrl");

                var self = this;

                var opt = {
                    url: url,
                    //paramName : fileInput.attr("name"),
                    dataType: 'json',
                    singleFileUploads: false,
                    autoUpload : true,
                    //imageMaxWidth: 800,
                    //imageMaxHeight: 800,
                    //imageCrop : true,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$("div.content-timeline-items"),
                    uploadTemplateId: null,
                    downloadTemplateId: null,
                    uploadTemplate: function (o) {
                        var rows = $();
                        // REMOVE OLD FILES
                        if (o.singleFileUploads) {
                          this.$("div.content-timeline-items .fileupload-item").remove();
                        }
                        $.each(o.files, function (index, file) {
                          rows = rows.add($(self.uploadTemplate({
                            file: file,
                            opt : o,
                            index : index
                          })));
                        });
                        return rows;
                    },
                    downloadTemplate: function (o) {
                        var rows = $();
                        $.each(o.files, function (index, file) {
                          rows = rows.add($(self.downloadTemplate({
                            file: file,
                            opt : o,
                            index : index
                          })));
                        });
                        return rows;
                    }
                };

                this.$el.fileupload(opt).bind('fileuploadalways', function (e, data) {
                    // var fieldName = $(self).data("fileuploadField");
                    // $(":input[name='" + fieldName + "']").change();
                });
            },
            addTextContent : function(e) {
                var self = this;
                var model = new Backbone.Model();

                var textContentTimelineView = new textContentTimelineViewClass({
                    model : model
                });

                this.$(".content-timeline-items").append(textContentTimelineView.render().el);


                this.listenTo(textContentTimelineView, "timeline-text-content:save", function(e, model) {
                    console.warn(e, model);
                    self.collection.add(model);
                });
                this.listenTo(textContentTimelineView, "timeline-text-content:delete", function(e, model) {
                    console.warn(e, model);
                    self.collection.add(model);
                });

                textContentTimelineView.initEditor();

                e.preventDefault();
                //this.textContentTimelineView.addOne();
            },
            addExercisesContent : function(e) {
                var self = this;
                var model = new Backbone.Model();

                var exercisesContentTimelineView = new exercisesContentTimelineViewClass({
                    model : model
                });

                this.$(".content-timeline-items").append(exercisesContentTimelineView.render().el);


                this.listenTo(exercisesContentTimelineView, "timeline-exercise-content:save", function(e, model) {
                    console.warn(e, model);
                    self.collection.add(model);
                });
                this.listenTo(exercisesContentTimelineView, "timeline-exercise-content:delete", function(e, model) {
                    console.warn(e, model);
                    self.collection.add(model);
                });

                //exercisesContentTimelineView.initEditor();

                e.preventDefault();
                //this.textContentTimelineView.addOne();
            }
        });
        /*
        mod.materialFileUploadWidgetView = new fileuploadContentTimelineViewClass({
            el : "#video-file-upload-widget",
            //url : "/module/lessons/upload/" + mod.config.entity_id + "/video?name=files_videos",
            //singleUpload : false,
            //type: "video",
            //acceptFileTypes: /(\.|\/)(mp4|webm)$/i,
            model : this.itemModel
        });
        */

        mod.contentTimelineView = new contentTimelineViewClass({
            el : "#content-timeline",
            collection : new Backbone.Collection()
        });

    });
    $SC.module("crud.views.edit").on("start", function() {
        mod.start();
    });
});

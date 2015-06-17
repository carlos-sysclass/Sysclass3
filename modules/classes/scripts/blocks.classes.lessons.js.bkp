$SC.module("blocks.classes.lessons", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        this.config = $SC.module("crud.config").getConfig();
        var entity_id = mod.config.entity_id;

        var lessonModel = app.module("crud.views.edit").itemModel;

        var baseLessonContentModelClass = Backbone.Model.extend({
            defaults : function() {
                return {
                    id              : null,
                    lesson_id       : null,
                    content_type    : null,
                    title           : '',
                    info            : '',
                    position        : -1,
                    active          : 1
                };
            },
            urlRoot: "/module/lessons/item/lesson-content/"
        });

        var lessonFileContentModelClass = baseLessonContentModelClass.extend({
            defaults : function() {
                var defaults = baseLessonContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'file';
                return defaults;
            },
            mergeWithinFileObject : function(file) {
                this.set("name", file.name);
                this.set("info", JSON.stringify(file));
                this.set("file", file);
            }
        });

        var lessonTextContentModelClass = baseLessonContentModelClass.extend({
            defaults : function() {
                var defaults = baseLessonContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'text';
                return defaults;
            }
        });

        var lessonContentCollectionClass = Backbone.Collection.extend({
            initialize: function(opt) {
                this.lesson_id = opt.lesson_id;
                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("lesson_id", this.lesson_id);
                    // SET POSITION

                });
            },
            url: function() {
                return "/module/lessons/items/lesson-content/default/" + this.lesson_id;
            },
            model: function(attrs, options) {
                if (options.add) {
                    if (attrs.content_type == "file") {
                        return new lessonFileContentModelClass(attrs, _.extend(options, {
                            collection: this,
                        }));
                    } else if (attrs.content_type == "text") {
                        return new lessonTextContentModelClass(attrs, _.extend(options, {
                            collection: this,
                        }));
                    } else {
                        return new baseLessonContentModelClass(attrs, _.extend(options, {
                            collection: this,
                        }));
                    }
                }
            },
            setContentOrder : function(order) {
                $.ajax(
                    "/module/lessons/items/lesson-content/set-order/" + this.lesson_id,
                    {
                        data: {
                            position: order
                        },
                        method : "PUT"
                    }
                );
            }
        });


        var lessonFileContentTimelineViewClass = Backbone.View.extend({
            uploadTemplate : _.template($("#fileupload-upload-timeline-item").html()),
            downloadTemplate : _.template($("#fileupload-download-timeline-item").html()),
            className : "timeline-item fileupload-item",
            tagName : "div",
            upload : true,
            fuploadFile : null,
            fuploadOptions : null,
            events : {
                "confirmed.bs.confirmation .delete-file-content"    : "delete"
            },

            initialize: function(opt) {
                console.info('blocks.lessons.content/lessonTextContentTimelineViewClass::initialize');
                this.setOptions(opt);

                this.listenTo(this.model, "sync", function(a,b,c) {
                    this.$el.attr("data-content-id", this.model.get("id"));
                });
            },
            setOptions : function(opt) {
                if(!_.isUndefined(opt.upload)) {
                    this.upload = opt.upload;
                }
                this.fuploadFile = opt.file;
                this.fuploadOptions = _.extend(
                    opt.opt,
                    { formatFileSize : this.formatFileSize }
                );

                return this;
            },
            render : function() {
                if (this.upload) {
                    this.$el.removeClass("template-download");

                    this.$el.html(this.uploadTemplate({
                        model: this.model.toJSON(),
                        file : this.fuploadFile,
                        opt  : this.fuploadOptions
                    }));

                    this.$el.addClass("template-upload");
                } else {
                    this.$el.removeClass("template-upload");
                    this.$el.html(this.downloadTemplate({
                        model: this.model.toJSON(),
                        //file : this.fuploadFile,
                        opt  : { formatFileSize : this.formatFileSize }
                    }));
                    this.$el.addClass("template-download");
                }

                app.module("ui").refresh(this.$el);

                this.$el.attr("data-content-id", this.model.get("id"));
                this.$el.data("viewObject", this);
                return this;
            },
            formatFileSize: function (bytes) {
                if (typeof bytes !== 'number') {
                    return '';
                }
                if (bytes >= 1000000000) {
                    return (bytes / 1000000000).toFixed(2) + ' GB';
                }
                if (bytes >= 1000000) {
                    return (bytes / 1000000).toFixed(2) + ' MB';
                }
                return (bytes / 1000).toFixed(2) + ' KB';
            },
            completeEvents : function() {
                this.trigger("timeline-file-content:save", this.model);
            },
            delete : function() {
                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.trigger("timeline-file-content:delete", this.model);
            }
        });

        var lessonTextContentTimelineViewClass = Backbone.View.extend({
            template : _.template($("#text-timeline-item").html()),
            className : "timeline-item",
            tagName : "div",
            editMode : true,
            events : {
                //"dblclick .timeline-body"                           : "edit",
                "click .edit-text-content"                          : "edit",
                "click .save-text-content"                          : "save",
                "confirmed.bs.confirmation .delete-text-content"    : "delete"
            },

            initialize: function(opt) {
                console.info('blocks.lessons.content/lessonTextContentTimelineViewClass::initialize');
                if(!_.isUndefined(opt.editMode)) {
                    this.editMode = opt.editMode;
                }
                this.listenTo(this.model, "sync", function(a,b,c) {
                    this.$el.attr("data-content-id", this.model.get("id"));
                });
            },

            render : function() {

                this.$el.html(this.template(this.model.toJSON()));
                this.$el.attr("data-content-id", this.model.get("id"));
                this.$el.data("viewObject", this);

                return this;
            },
            initEditor : function() {
                var self = this;
                app.module("ui").refresh(this.$el);

                this.wysihtml5 = this.$(".wysihtml5").data('wysihtml5');
                this.editor = this.wysihtml5.editor;

                this.editor.on("change", function(e) {
                    self.$(".preview").html($(self.wysihtml5.el).val());
                    self.model.set("info", $(self.wysihtml5.el).val());
                });

                if (this.editMode) {
                    this._enableEditMode();
                } else {
                    this._disableEditMode();
                }
            },
            _enableEditMode : function() {
                this.$(".edit-text-content").addClass("hidden");
                this.$(".save-text-content").removeClass("hidden");
                this.$(".preview").addClass("hidden");

                //this.editor = this.$(".wysihtml5").data('wysihtml5').editor;
                this.$(".wysihtml5").parents(".wysihtml5-container").removeClass("hidden");
                //this.editor.toolbar.show();
                //this.editor.composer.show();

            },
            _disableEditMode : function() {
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
            },
            edit : function(e) {
                e.stopPropagation();
                if (!this.editMode) {
                    this._enableEditMode();
                    this.editMode = true;
                }
            },
            save : function(e) {
                e.stopPropagation();
                if (this.editMode) {
                    this._disableEditMode();

                    this.$(".text-loading").removeClass("hidden");

                    this.editMode = false;
                    this.trigger("timeline-text-content:save", this.model);

                    this.$(".text-loading").addClass("hidden");
                }
            },
            delete : function() {
                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.$el.remove();

                this.trigger("timeline-text-content:delete", this.model);
            }
        });

        var lessonExercisesContentTimelineViewClass = Backbone.View.extend({
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
                console.info('blocks.lessons.content/lessonTextContentTimelineViewClass::initialize');
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
                "click .timeline-addexercise" : "addExercisesContent",
                "click .timeline-expand" : "expandAll",
                "click .timeline-collapse" : "collapseAll"
            },
            uploadTemplate : _.template($("#fileupload-upload-timeline-item").html()),
            downloadTemplate : _.template($("#fileupload-download-timeline-item").html()),
            jqXHR : null,
            initialize: function(opt) {
                console.info('blocks.lessons.content/contentTimelineViewClass::initialize');

                this.initializeSortable();
                this.initializeFileUpload();

                this.listenToOnce(this.collection, "sync", this.render.bind(this));

            },
            initializeSortable : function() {
                var self = this;
                this.$("div.content-timeline-items").sortable({
                    //connectWith: ".list-group",
                    handle : ".drag-handler",
                    items: "div.timeline-item",
                    opacity: 0.8,
                    /* axis : "y", */
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    /* helper : 'original',  */
                    update : function( event, ui ) {
                        var contentOrder = $(this).sortable("toArray", {attribute : "data-content-id"});

                        self.collection.setContentOrder(contentOrder);
                    }
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
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$("div.content-timeline-items"),
                    done: function() {

                    },
                };

                this.$el.fileupload(opt)
                    .bind('fileuploadadd', function (e, o) {
                        var rows = $();

                        $.each(o.files, function (index, file) {
                            rows = rows.add($(self.addFileContent({
                                upload : true,
                                file: file,
                                opt : o
                                //index : index
                            })));
                        });
                        o.context = rows;
                        self.jqXHR = o.submit();
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var files = data.getFilesFromResponse(data);
                        var viewObject = data.context.data("viewObject");

                        $.each(files, function (index, file) {
                            viewObject.model.mergeWithinFileObject(file);
                            viewObject.setOptions({
                                upload : false,
                                file: file,
                                opt : data
                            }).render();

                            viewObject.completeEvents();
                        });
                        self.jqXHR = null;

                    })
                    .bind('fileuploadfail', function (e, data) {
                        //console.warn("fileuploadfail");
                    })
                    .bind('fileuploadalways', function (e, data) {
                        //console.warn("fileuploadalways");
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        console.warn(progress);
                        data.context.find(".load-percent").html(progress);
                    });
            },
            render : function(collection, models, options) {
                collection.each(function(model, i) {
                    var view_type = model.get("content_type");

                    if (view_type == "file") {
                        return this.renderFileContent(model, {upload : false});
                    } else if (view_type == "text") {
                        return this.renderTextContent(model, {editMode : false});
                    }
                }, this);
            },
            expandAll : function() {
                this.$(".content-timeline-items .timeline-body-content-wrapper").removeClass("hidden");
            },
            collapseAll : function() {
                this.$(".content-timeline-items .timeline-body-content-wrapper").addClass("hidden");
            },
            addFileContent : function(options) {
                var self = this;
                // TODO: INJECT FILES DATA ON MODEL
                var model = new lessonFileContentModelClass(null, {
                    collection: this.collection,
                });

                model.mergeWithinFileObject(options.file);

                return this.renderFileContent(model, options);
            },
            renderFileContent : function(model, options) {
                var self = this;

                if (!_.isObject(options)) {
                    options = {};
                }

                var fileContentTimelineView = new lessonFileContentTimelineViewClass(_.extend(options, {
                    model : model
                }));

                this.$(".content-timeline-items").append(fileContentTimelineView.render().el);


                this.listenTo(fileContentTimelineView, "timeline-file-content:save", function(model) {
                    //console.warn(e, model);
                    self.collection.add(model);
                    model.save();
                });

                this.listenTo(fileContentTimelineView, "timeline-file-content:delete", function(model) {
                    if (!_.isNull(self.jqXHR)) {
                        self.jqXHR.abort();
                    }
                    model.destroy();
                    self.collection.remove(model, options);
                    fileContentTimelineView.remove();
                    //self.collection.add(model, "text");
                });

                return fileContentTimelineView.el;
            },

            addTextContent : function(e) {
                var self = this;
                var model = new lessonTextContentModelClass(null, {
                    collection: this.collection,
                });

                this.renderTextContent(model);

                e.preventDefault();
                //this.textContentTimelineView.addOne();
            },
            renderTextContent : function(model, options) {
                var self = this;

                if (!_.isObject(options)) {
                    options = {};
                }

                var textContentTimelineView = new lessonTextContentTimelineViewClass(_.extend(options, {
                    model : model
                }));

                this.$(".content-timeline-items").append(textContentTimelineView.render().el);


                this.listenTo(textContentTimelineView, "timeline-text-content:save", function(model) {
                    //console.warn(e, model);
                    self.collection.add(model);
                    model.save();
                });

                this.listenTo(textContentTimelineView, "timeline-text-content:delete", function(model) {
                    model.destroy();
                    self.collection.remove(model, options);
                    textContentTimelineView.remove();
                });

                textContentTimelineView.initEditor();

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

        mod.lessonContentCollection = new lessonContentCollectionClass({
            lesson_id : entity_id
        });

        this.listenTo(lessonModel, "sync", function(a,b,c,d,e) {
            mod.lessonContentCollection.fetch();
        });

        mod.contentTimelineView = new contentTimelineViewClass({
            el : "#content-timeline",
            collection : mod.lessonContentCollection
        });

    });
    $SC.module("crud.views.edit").on("start", function() {
        mod.start();


    });
});

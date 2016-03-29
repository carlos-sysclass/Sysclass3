$SC.module("blocks.lessons.content", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        this.config = $SC.module("crud.config").getConfig();
        mod.entity_id = mod.config.entity_id;

        var lessonModel = app.module("crud.views.edit").itemModel;

        var baseLessonContentModelClass = Backbone.Model.extend({
            defaults : function() {
                return {
                    id              : null,
                    lesson_id       : null,
                    content_type    : null,
                    title           : '',
                    info            : '',
                    content         : '',
                    position        : null,
                    active          : 1,
                    parent_id       : null,
                    childs          : []
                };
            },
            urlRoot: "/module/lessons/item/lesson_content"
        });

        var lessonUrlContentModelClass = baseLessonContentModelClass.extend({
            defaults : function() {
                var defaults = baseLessonContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'url';
                return defaults;
            }
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

        var lessonFileSubtitleContentModelClass = lessonFileContentModelClass.extend({
            defaults : function() {
                var defaults = lessonFileContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'subtitle';
                return defaults;
            },
            //urlRoot: "/module/lessons/datasource/lesson_content/",
            translate : function(from, to) {
                $.ajax(
                    this.url() + "/translate",
                    {
                        data: {
                            from: from,
                            to: to
                        },
                        method : "PUT",
                        success : function(data, textStatus, jqXHR ) {
                            mod.lessonContentCollection.add(data);
                        }
                    }
                );


            }
        });

        var lessonFilePosterContentModelClass = lessonFileContentModelClass.extend({
            defaults : function() {
                var defaults = lessonFileContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'poster';
                return defaults;
            },
            //urlRoot: "/module/lessons/item/lesson_content/"
        });

        var lessonTextContentModelClass = baseLessonContentModelClass.extend({
            defaults : function() {
                var defaults = baseLessonContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'text';
                return defaults;
            }
        });

        var lessonExerciseContentQuestionCollectionClass = Backbone.Collection.extend({
            initialize: function(data, opt) {
                console.warn(data, opt);
                this.content_id = opt.content_id;
            },
            /*
            url: function() {
                return "/module/lessons/items/lesson-content/default/" + this.lesson_id;
            },
            */
            initialize: function(data, opt) {
                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("content_id", this.content_id);
                    model.set("question_id", model.get("id"));
                    // SET POSITION
                });
                this.listenTo(this, "remove", function(model, collection, opt) {
                    /*
                    var self = this;

                    var subfiles = collection.where({parent_id : model.get("id")});

                    console.warn(model, collection, opt, this, subfiles);

                    _.each(subfiles, function(item) {
                        self.remove(item.id);
                    });

                    model.destroy();
                    */
                });
            }
        });

        var lessonExerciseContentModelClass = baseLessonContentModelClass.extend({
            defaults : function() {
                var defaults = baseLessonContentModelClass.prototype.defaults.apply(this);
                defaults['content_type'] = 'exercise';
                return defaults;
            },
            initialize: function(data, opt) {
                var exerciseCollection = new lessonExerciseContentQuestionCollectionClass(
                    this.get("exercise"), {lesson_id : this.get("lesson_id")}
                );

                this.set("exercise", exerciseCollection);
                this.listenTo(this, "sync", function() {
                    exerciseCollection.reset(this.get("exercise"));

                    this.set("exercise", exerciseCollection);
                });
                this.listenTo(exerciseCollection, "add", function() {
                    this.save();
                });
                this.listenTo(exerciseCollection, "remove", function() {
                    this.save();
                });

            }/*,
            addQuestion : function(questionModel) {
                $.ajax(
                    "/module/lessons/item/question-content",
                    {
                        data: {
                            content_id : this.get("id"),
                            question_id: questionModel.get("id")
                        },
                        method : "POST"
                    }
                );
            }
            */
        });

        var lessonContentCollectionClass = Backbone.Collection.extend({
            initialize: function(data, opt) {
                this.lesson_id = opt.lesson_id;
                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("lesson_id", this.lesson_id);
                    // SET POSITION

                });
                this.listenTo(this, "remove", function(model, collection, opt) {


                    var self = this;

                    var subfiles = collection.where({parent_id : model.get("id")});

                    _.each(subfiles, function(item) {
                        self.remove(item.id);
                    });

                    model.destroy();
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
                    } else if (attrs.content_type == "subtitle") {

                        return new lessonFileSubtitleContentModelClass(attrs, _.extend(options, {
                            collection: this,
                        }));
                    } else if (attrs.content_type == "text") {
                        return new lessonTextContentModelClass(attrs, _.extend(options, {
                            collection: this,
                        }));
                    } else if (attrs.content_type == "exercise") {
                        return new lessonExerciseContentModelClass(attrs, _.extend(options, {
                            collection: this,
                        }));
                    } else if (attrs.content_type == "poster") {
                        return new lessonFilePosterContentModelClass(attrs, _.extend(options, {
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

        /**
         * SUB VIEW BASE CLASS
         *
         * @todo  MOVE TO A MORE GENERIC MODULE
         */
        var baseLessonChildContentTimelineViewClass = Backbone.View.extend({
            events : function() {
                return {
                    "confirmed.bs.confirmation .delete-content"    : "delete"
                };
            },
            render : function() {
                this.$el.html(this.template(this.model.toJSON()));

                return this;
            },
            delete : function() {
                mod.lessonContentCollection.remove(this.model.get("id"));
            }
        });

        var lessonUrlContentTimelineViewClass = Backbone.View.extend({
            template : _.template($("#url-timeline-item").html(), null, {variable : 'model'}),
            className : "timeline-item",
            tagName : "div",
            editMode : true,
            events : {
                //"dblclick .timeline-body"                           : "edit",
                "click .save-url-content"                          : "save",
                "confirmed.bs.confirmation .delete-url-content"    : "delete"
            },

            initialize: function(opt) {
                console.info('blocks.lessons.content/lessonUrlContentTimelineViewClass::initialize');
                if(!_.isUndefined(opt.editMode)) {
                    this.editMode = opt.editMode;
                }
                this.listenTo(this.model, "sync", function(a,b,c) {
                    this.$el.attr("data-content-id", this.model.get("id"));
                });
            },
            render : function() {
                var self = this;

                console.warn(this.model.toJSON());

                this.$el.html(this.template(this.model.toJSON()));
                this.$el.attr("data-content-id", this.model.get("id"));
                this.$el.data("viewObject", this);

                this.editor = this.$(":input[type='text']");

                console.warn(this.editor);

                this.editor.on("change", function(e) {
                    self.model.set("content", self.editor.val());
                });

                return this;
            },
            save : function(e) {
                e.stopPropagation();
                this.$(".text-loading").removeClass("hidden");

                //this.editMode = false;
                this.trigger("timeline-url-content:save", this.model);

                this.$(".text-loading").addClass("hidden");
            },
            delete : function() {
                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.$el.remove();

                this.trigger("timeline-url-content:delete", this.model);
            }
        });

        var lessonFileContentTimelineViewClass = Backbone.View.extend({
            uploadTemplate : _.template($("#fileupload-upload-timeline-item").html()),
            downloadTemplate : _.template($("#fileupload-download-timeline-item").html()),
            className : "timeline-item fileupload-item",
            tagName : "div",
            upload : true,
            uploadClass : [ "template-upload" ],
            downloadClass : [ "template-download" ],
            fuploadFile : null,
            fuploadOptions : null,
            jqXHR : null,
            events : {
                "confirmed.bs.confirmation .delete-file-content"    : "delete"
            },
            initialize: function(opt) {
                console.info('blocks.lessons.content/lessonFileContentTimelineViewClass::initialize');
                this.setOptions(opt);

                this.listenTo(this.model, "sync", function(a,b,c) {
                    this.$el.attr("data-content-id", this.model.get("id"));
                    this.render();
                });
                this.listenTo(mod.lessonContentCollection, "add", this.renderOne.bind(this));
            },
            setOptions : function(opt) {
                console.info('blocks.lessons.content/lessonFileContentTimelineViewClass::setOptions');
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
                console.info('blocks.lessons.content/lessonFileContentTimelineViewClass::render');
                var self = this;
                if (this.upload) {

                    _.each(this.downloadClass, function(item) {
                        self.$el.removeClass(item);
                    });


                   this.$el.html(this.uploadTemplate({
                        model: this.model.toJSON(),
                        file : this.fuploadFile,
                        opt  : this.fuploadOptions
                    }));

                    _.each(this.uploadClass, function(item) {
                        self.$el.addClass(item);
                    });

                } else {
                    _.each(this.uploadClass, function(item) {
                        self.$el.removeClass(item);
                    });

                    this.$el.html(this.downloadTemplate({
                        model: this.model.toJSON(),
                        //file : this.fuploadFile,
                        opt  : { formatFileSize : this.formatFileSize }
                    }));

                    _.each(this.downloadClass, function(item) {
                        self.$el.addClass(item);
                    });

                    // IF IS A VIDEO, RENDER
                    if (/^video\/.*$/.test(this.model.get("file").type)) {
                        this.initializeFileSubtitleUpload();
                        this.initializeFileposterUpload();
                    }

                    // RENDER SUBFILES VIEW
                    /*
                    var subfiles = mod.lessonContentCollection.where({parent_id : this.model.get("id")});



                    var collection = new lessonContentCollectionClass(subfiles, {
                        lesson_id : mod.entity_id
                    });

                    console.warn(subfiles, this.model.toJSON(), collection.toJSON());

                    collection.each(function(model, i) {
                        console.log(i);
                        var view_type = model.get("content_type");

                        if (view_type == "subtitle") {
                            this.renderRelatedFileContent(model, {upload : false});
                        }
                    }, this);
                    */
                }

                app.module("ui").refresh(this.$el);

                this.$el.attr("data-content-id", this.model.get("id"));
                this.$el.data("viewObject", this);
                return this;
            },
            initializeFileSubtitleUpload : function() {
                var self = this;
                var fileUploadItem = this.$(".fileupload-subtitle");
                var url = fileUploadItem.data("fileuploadUrl");

                var opt = {
                    url: url,
                    paramName : "subtitles",
                    dataType: 'json',
                    singleFileUploads: true,
                    autoUpload : true,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$("ul.content-subtitles-items"),
                    downloadTemplateId: null,
                    uploadTemplate: function (o) {
                        return fileUploadItem.data("upload-contexts");
                    },
                    /*
                    downloadTemplate: function (o) {
                        return fileUploadItem.data("download-contexts");
                    },
                    */
                    done: function() {

                    }
                };

                fileUploadItem.fileupload(opt)
                    .bind('fileuploadadd', function (e, o) {
                        var rows = $();

                        $.each(o.files, function (index, file) {
                            //rows = rows.add("<p>FILE: " + file.name + "</p>");
                            rows = rows.add($(self.addRelatedFileContent({
                                upload : true,
                                file: file,
                                opt : o,
                                type : "subtitle"
                            })));
                        });

                        $(this).data("upload-contexts", rows);

                        o.context = rows;
                        //e.stopPropagation();
                        self.jqXHR = o.submit();
                        return rows;
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var files = data.getFilesFromResponse(data);
                        var rows = $();
                        var viewObject = data.context.data("viewObject");

                        if (files.length == 0) {
                            window.setTimeout(function() {
                                viewObject.delete();
                            }, 500);

                        } else {
                            $.each(files, function (index, file) {
                                viewObject.model.mergeWithinFileObject(file);
                                viewObject.setOptions({
                                    upload : false,
                                    file: file,
                                    opt : data
                                })//.render();

                                viewObject.completeEvents();
                            });
                            $(this).data("download-contexts", rows);
                        }
                        self.jqXHR = null;
                        return rows;
                    })
                    .bind('fileuploadfail', function (e, data) {
                        console.warn("fileuploadfail");
                    })
                    .bind('fileuploadalways', function (e, data) {
                        //console.warn("fileuploadalways");
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        data.context.find(".load-percent").html(progress);
                    });
            },
            initializeFileposterUpload : function() {
                var self = this;
                var fileUploadItem = this.$(".fileupload-poster");
                var url = fileUploadItem.data("fileuploadUrl");

                var opt = {
                    url: url,
                    paramName : "subtitles",
                    dataType: 'json',
                    singleFileUploads: true,
                    autoUpload : true,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$("ul.content-subtitles-items"),
                    downloadTemplateId: null,
                    uploadTemplate: function (o) {
                        return fileUploadItem.data("upload-contexts");
                    },
                    /*
                    downloadTemplate: function (o) {
                        return fileUploadItem.data("download-contexts");
                    },
                    */
                    done: function() {

                    }
                };

                fileUploadItem.fileupload(opt)
                    .bind('fileuploadadd', function (e, o) {
                        var rows = $();

                        $.each(o.files, function (index, file) {
                            //rows = rows.add("<p>FILE: " + file.name + "</p>");
                            rows = rows.add($(self.addRelatedFileContent({
                                upload : true,
                                file: file,
                                opt : o,
                                type : "poster"
                            })));
                        });

                        $(this).data("upload-contexts", rows);

                        o.context = rows;
                        //e.stopPropagation();
                        self.jqXHR = o.submit();
                        return rows;
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var files = data.getFilesFromResponse(data);
                        var rows = $();
                        var viewObject = data.context.data("viewObject");

                        if (files.length == 0) {
                            window.setTimeout(function() {
                                viewObject.delete();
                            }, 500);

                        } else {
                            $.each(files, function (index, file) {
                                viewObject.model.mergeWithinFileObject(file);
                                viewObject.setOptions({
                                    upload : false,
                                    file: file,
                                    opt : data
                                })//.render();

                                viewObject.completeEvents();
                            });
                            $(this).data("download-contexts", rows);
                        }
                        self.jqXHR = null;
                        return rows;
                    })
                    .bind('fileuploadfail', function (e, data) {
                        console.warn("fileuploadfail");
                    })
                    .bind('fileuploadalways', function (e, data) {
                        //console.warn("fileuploadalways");
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        data.context.find(".load-percent").html(progress);
                    });
            },

            renderOne : function(model, collection, options) {
                if (model.get("id")) {
                    if (!_.isNull(model.get("parent_id")) && model.get("parent_id") == this.model.get("id")) {
                        this.renderRelatedFileContent(model, {upload : false});
                    }
                }
            },
            addRelatedFileContent : function(options) {
                console.warn(options);
                var self = this;
                
                var model = null;

                if (options.type == 'poster') {
                    var model = new lessonFilePosterContentModelClass({
                        parent_id : this.model.get("id"),
                        lesson_id : this.model.get("lesson_id")
                    });
                } else {
                    var model = new lessonFileSubtitleContentModelClass({
                        parent_id : this.model.get("id"),
                        lesson_id : this.model.get("lesson_id")
                    });
                }

                model.mergeWithinFileObject(options.file);

                mod.lessonContentCollection.add(model);

                return this.renderRelatedFileContent(model, options);
            },
            renderRelatedFileContent : function(model, options) {
                console.info('blocks.lessons.content/lessonFileContentTimelineViewClass::renderRelatedFileContent');
                var self = this;

                if (!_.isObject(options)) {
                    options = {};
                }

                var viewClass = null;
                if (model.get("content_type") == "poster") {
                    viewClass = lessonFileRelatedPosterContentTimelineViewClass;
                } else {
                    viewClass = lessonFileRelatedContentTimelineViewClass;
                }
                
                var fileContentTimelineView = new viewClass(_.extend(options, {
                    model : model
                }));

                this.$("ul.content-subtitles-items").append(fileContentTimelineView.render().el);

                this.listenTo(fileContentTimelineView, "timeline-file-content:save", function(model) {
                    var self = this;

                    model.save(null, {
                        success : function() {
                            var childs = self.model.get("childs");
                            childs.push(model.get("id"));
                            self.model.set("childs", childs);
                        }
                    });
                });

                this.listenTo(fileContentTimelineView, "timeline-file-content:delete", function(model) {
                    if (!_.isNull(self.jqXHR)) {
                        self.jqXHR.abort();
                    }
                    mod.lessonContentCollection.remove(model.get("id"));
                    //model.destroy();
                    //self.collection.remove(model, options);
                    fileContentTimelineView.remove();
                    //self.collection.add(model, "text");

                });

                return fileContentTimelineView.el;
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
                console.info('blocks.lessons.content/lessonFileContentTimelineViewClass::delete');
                if (!_.isNull(this.jqXHR)) {
                    this.jqXHR.abort();
                }

                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.trigger("timeline-file-content:delete", this.model);
                //console.warn(this.$el, this.$(".fileupload-subtitle"));
                //this.$(".fileupload-subtitle").removeClass("disabled").fileupload("enable");
            }
        });

        var lessonFileRelatedContentTimelineViewClass = lessonFileContentTimelineViewClass.extend({
            uploadTemplate : _.template($("#fileupload-upload-related-item").html()),
            downloadTemplate : _.template($("#fileupload-download-related-item").html()),
            translationTemplate : _.template($("#fileupload-translation-related-item").html()),
            className : "list-file-item",
            tagName : "li",
            uploadClass : [ "template-upload red-stripe" ],
            downloadClass : [ "template-download green-stripe" ],
            events : {
                "confirmed.bs.confirmation .delete-file-content" : "delete",
                "click .save-file-content"                       : "save_contents",
                "click .translate-file-content"                  : "translate_contents",
                "click .delete-translation-content"              : "delete_translation"
            },
            renderOne : function(model, collection, options) {
                if (model.get("id")) {
                    if (!_.isNull(model.get("parent_id")) && model.get("parent_id") == this.model.get("id")) {
                        // "content_type": "subtitle-translation",
                        this.renderTranslationContent(model);
                    }
                }
            },
            renderTranslationContent : function(model) {
                var html = this.translationTemplate({
                    model: model.toJSON()
                });
                app.module("ui").refresh(
                    $(html).appendTo(
                        this.$(".translation-container")
                    )
                );
            },
            delete_translation : function(e) {
                var item = $(e.currentTarget);
                var modelId = item.data("contentId");
                mod.lessonContentCollection.remove(modelId);
                item.parents("li.translation-item").remove();
            },
            save_contents : function() {
                this.model.set("language_code", this.$("[name='related[lang_from]']").val());
                this.model.save();
            },
            translate_contents : function() {
                // REQUEST FILE TRANSLATION SERVICE
                // GET FROM AND TO VALUES, CHECK FOR EQUALITY AND REQUEST TRANSLATION
                //var translateServiceModelClass = app.module("models.translate").translateServiceModelClass;
                this.model.translate(
                    this.$("[name='related[lang_from]']").val(),
                    this.$("[name='related[lang_to]']").val()
                );
            }
        });

        var lessonFileRelatedPosterContentTimelineViewClass = lessonFileContentTimelineViewClass.extend({
            uploadTemplate : _.template($("#fileupload-upload-related-item").html()),
            downloadTemplate : _.template($("#fileupload-download-related-poster-item").html()),
            //translationTemplate : _.template($("#fileupload-translation-related-item").html()),
            className : "list-file-item",
            tagName : "li",
            uploadClass : [ "template-upload red-stripe" ],
            downloadClass : [ "template-download green-stripe" ],
            events : {
                "confirmed.bs.confirmation .delete-file-content" : "delete",
                "click .save-file-content"                       : "save_contents",
                "click .translate-file-content"                  : "translate_contents",
                "click .delete-translation-content"              : "delete_translation"
            },
            /*
            renderOne : function(model, collection, options) {
                if (model.get("id")) {
                    if (!_.isNull(model.get("parent_id")) && model.get("parent_id") == this.model.get("id")) {
                        // "content_type": "subtitle-translation",
                        this.renderTranslationContent(model);
                    }
                }
            },
            renderTranslationContent : function(model) {
                var html = this.translationTemplate({
                    model: model.toJSON()
                });
                app.module("ui").refresh(
                    $(html).appendTo(
                        this.$(".translation-container")
                    )
                );
            },
            delete_translation : function(e) {
                var item = $(e.currentTarget);
                var modelId = item.data("contentId");
                mod.lessonContentCollection.remove(modelId);
                item.parents("li.translation-item").remove();
            },
            save_contents : function() {
                this.model.set("language_code", this.$("[name='related[lang_from]']").val());
                this.model.save();
            },

            translate_contents : function() {
                // REQUEST FILE TRANSLATION SERVICE
                // GET FROM AND TO VALUES, CHECK FOR EQUALITY AND REQUEST TRANSLATION
                //var translateServiceModelClass = app.module("models.translate").translateServiceModelClass;
                this.model.translate(
                    this.$("[name='related[lang_from]']").val(),
                    this.$("[name='related[lang_to]']").val()
                );
            }
            */
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

        var lessonExercisesContentSubviewTimelineViewClass = Backbone.View.extend({
            template : _.template($("#exercise-question-timeline-item").html(), null, {variable : "model"}),
            events : {
                "confirmed.bs.confirmation .delete-question-item" : "onRemove"
            },
            initialize: function(opt) {
                this.listenTo(this.collection, "add", this.addOne.bind(this));
                this.listenTo(this.collection, "remove", this.removeOne.bind(this));
                this.listenTo(this.collection, "reset", this.render.bind(this));
            },
            render : function() {
                var self = this;
                this.$el.empty();
                this.collection.each(function(model) {
                    self.addOne(model);
                });

                return this;
            },
            addOne : function(model) {
                var html = this.template(model.toJSON());

                app.module("ui").refresh(
                    $(html).appendTo(this.$el)
                );
            },
            onRemove : function(e) {
                var id = $(e.currentTarget).data("questionId");
                this.collection.remove(id);

            },
            removeOne: function(model) {
                this.$("[data-question-id='" + model.get("id") +"']").parents("li.list-question-item").remove();
            }
        });

        var lessonExercisesContentTimelineViewClass = baseLessonChildContentTimelineViewClass.extend({
            questionSelectDialog : app.module("dialogs.questions.select"),
            template : _.template($("#exercise-timeline-item").html()),
            className : "timeline-item",
            tagName : "div",
            initialize: function(opt) {
                if (!$SC.module("dialogs.questions.select").started) {
                    $SC.module("dialogs.questions.select").start();
                }

                //this.questionModule = $SC.module("dialogs.questions.select");

                //this.listenTo(this.questionModule, "select:item", this.selectQuestion.bind(this));

                if (!this.questionSelectDialog.started) {
                    this.questionSelectDialog.start({
                        modelClass : Backbone.Model
                    });
                }
            },
            events : function() {
                var events = baseLessonChildContentTimelineViewClass.prototype.events.apply(this);
                events["click .select-question"] = "openSelectDialog";
                events["click .create-question"] = "openCreateDialog";
                return events;
            },
            render : function() {
                baseLessonChildContentTimelineViewClass.prototype.render.apply(this);

                this.questionView = new lessonExercisesContentSubviewTimelineViewClass({
                    collection : this.model.get("exercise"),
                    el: this.$(".questions-container")
                });

                this.questionView.render();

                return this;
            },
            openCreateDialog : function() {
                if (app.module("dialogs.questions.create").started) {
                    app.module("dialogs.questions.create").stop();
                }
                app.module("dialogs.questions.create").start();

                //this.questionCreateModule = $SC.module("dialogs.questions.create");
                this.listenToOnce(app.module("dialogs.questions.create"), "created.question", this.createQuestion.bind(this));

                app.module("dialogs.questions.create").open();
            },
            createQuestion : function(model) {
                console.warn(model);

                //app.module("dialogs.questions.select").close();
                this.model.get("exercise").add(model);
                //this.model.addQuestion(model);
            },
            openSelectDialog : function() {
                console.warn(this.questionSelectDialog);
                this.questionSelectDialog.setFilter({
                    content_id : this.model.get("id")
                }).getValue(function(result) {
                    this.model.get("exercise").add(result);
                }.bind(this));
            },
            /*
            selectQuestion : function(e, model) {
                app.module("dialogs.questions.select").close();
                this.model.get("exercise").add(model);
                //this.model.addQuestion(model);
            },
            renderQuestionsContent : function() {

            },
            */
            delete : function() {
                baseLessonChildContentTimelineViewClass.prototype.delete.apply(this);

                this.remove();
            }
        });

        var contentTimelineViewClass = Backbone.View.extend({
            events : {
                "click .timeline-addurl" : "addUrlContent",
                "click .timeline-addtext" : "addTextContent",
                "click .timeline-addexercise" : "addExercisesContent",
                "click .timeline-expand" : "expandAll",
                "click .timeline-collapse" : "collapseAll"
            },
            uploadTemplate : _.template($("#fileupload-upload-timeline-item").html()),
            downloadTemplate : _.template($("#fileupload-download-timeline-item").html()),
            collectionFilter : null,
            jqXHR : null,
            subviews : {},
            initialize: function(opt) {
                console.info('blocks.lessons.content/contentTimelineViewClass::initialize');

                this.collectionFilter = opt.collectionFilter ? opt.collectionFilter : null;

                this.initializeSortable();
                this.initializeFileUpload();

                //this.listenToOnce(this.collection, "sync", this.render.bind(this));

                this.listenTo(this.collection, "add", this.renderOne.bind(this));
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
                var fileUploadItem = this.$(".fileupload");
                var url = fileUploadItem.data("fileuploadUrl");

                var self = this;

                var opt = {
                    url: url,
                    //paramName : fileInput.attr("name"),
                    dataType: 'json',
                    singleFileUploads: false,
                    autoUpload : true,
                    maxChunkSize: 2*1024*1024,
                    bitrateInterval : 1000,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$("div.content-timeline-items"),
                    uploadTemplate: function (o) {
                        return fileUploadItem.data("upload-contexts");
                    },
                    done: function() {

                    }
                };

                fileUploadItem.fileupload(opt)
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
                        $(this).data("upload-contexts", rows);

                        o.context = rows;
                        //self.jqXHR = o.submit();
                    })
                    .bind('fileuploaddone', function (e, data) {
                        
                        var files = data.getFilesFromResponse(data);
                        var viewObject = data.context.data("viewObject");

                        if (_.size(files) == 0) {
                            window.setTimeout(function() { viewObject.delete() }, 1500);
                        } else {

                            $.each(files, function (index, file) {
                                viewObject.model.mergeWithinFileObject(file);
                                viewObject.setOptions({
                                    upload : false,
                                    file: file,
                                    opt : data
                                });//.render();

                                viewObject.completeEvents();
                            });
                        }
                        self.jqXHR = null;
                    })
                    .bind('fileuploadfail', function (e, data) {
                        console.warn("fileuploadfail");
                    })
                    .bind('fileuploadalways', function (e, data) {
                        //console.warn("fileuploadalways");
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        data.context.find(".load-total").html(self.formatFileSize(data.loaded));
                        data.context.find(".load-bitrate").html(self.formatFileSize(data._progress.bitrate / 8));
                        data.context.find(".load-percent").html(progress);
                    });
            },
            renderOne : function(model, collection, options) {
                if (model.get("id")) {
                    if (_.isNull(model.get("parent_id"))) {
                        var view_type = model.get("content_type");

                        if (view_type == "file") {
                            this.subviews[model.get("id")] = this.renderFileContent(model, {upload : false});
                            return this.subviews[model.get("id")];
                        } else if (view_type == "text") {
                            this.subviews[model.get("id")] = this.renderTextContent(model, {editMode : false});
                            return this.subviews[model.get("id")];
                        } else if (view_type == "url") {
                            this.subviews[model.get("id")] = this.renderUrlContent(model, {editMode : false});
                            return this.subviews[model.get("id")];
                        } else if (view_type == "exercise") {
                            this.subviews[model.get("id")] = this.renderExercisesContent(model, {editMode : false});
                            return this.subviews[model.get("id")];
                        }
                    } else {
                        // GET parent_id SUBVIEW AND DELEGATE RENDERING
                        //console.warn(this.subviews);
                        //this.subviews[model.get("id")]
                    }
                }
            },
            render : function(collection, models, options) {
                if (this.collectionFilter) {
                    var data = this.collection.where(this.collectionFilter);
                } else {
                    var data = this.collection.toJSON();
                }

                var collection = new lessonContentCollectionClass(data, {
                    lesson_id : this.collection.lesson_id
                });

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
            addUrlContent : function(options) {
                var self = this;
                // TODO: INJECT FILES DATA ON MODEL
                var model = new lessonUrlContentModelClass(null, {
                    collection: this.collection,
                });

                this.collection.add(model);

                return this.renderUrlContent(model, options).el;
            },
            renderUrlContent : function(model, options) {
                var self = this;

                if (!_.isObject(options)) {
                    options = {};
                }

                var urlContentTimelineView = new lessonUrlContentTimelineViewClass(_.extend(options, {
                    model : model
                }));

                this.$(".content-timeline-items").append(urlContentTimelineView.render().el);

                this.listenTo(urlContentTimelineView, "timeline-url-content:save", function(model) {
                    model.save();
                });

                this.listenTo(urlContentTimelineView, "timeline-url-content:delete", function(model) {
                    mode.destroy();
                });

                return urlContentTimelineView;
            },
            addFileContent : function(options) {
                var self = this;
                // TODO: INJECT FILES DATA ON MODEL
                var model = new lessonFileContentModelClass(null, {
                    collection: this.collection,
                });

                model.mergeWithinFileObject(options.file);

                this.collection.add(model);

                return this.renderFileContent(model, options).el;
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

                this.listenTo(fileContentTimelineView, "timeline-file-content:translate", function(model) {
                    //console.warn(e, model);
                    self.collection.add(model);
                    model.save();
                });

                this.listenTo(fileContentTimelineView, "timeline-file-content:delete", function(model) {
                    if (!_.isNull(self.jqXHR)) {
                        self.jqXHR.abort();
                    }
                    //model.destroy();
                    self.collection.remove(model);
                    fileContentTimelineView.remove();
                    //self.collection.add(model, "text");
                });

                return fileContentTimelineView;
            },
            addTextContent : function(e) {
                var self = this;
                var model = new lessonTextContentModelClass(null, {
                    collection: this.collection,
                });

                this.renderTextContent(model);

                this.collection.add(model);

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
                    //self.collection.add(model);
                    //model.save();
                });

                this.listenTo(textContentTimelineView, "timeline-text-content:delete", function(model) {
                    //model.destroy();
                    //self.collection.remove(model, options);
                    //textContentTimelineView.remove();
                });

                textContentTimelineView.initEditor();
            },
            addExercisesContent : function(e) {
                var self = this;
                var model = new lessonExerciseContentModelClass(null, {
                    collection: this.collection,
                });

                this.renderExercisesContent(model);

                this.collection.add(model);

                model.save();

                e.preventDefault();
            },
            renderExercisesContent : function(model, options) {
                var self = this;

                if (!_.isObject(options)) {
                    options = {};
                }

                var exerciseContentTimelineView = new lessonExercisesContentTimelineViewClass(_.extend(options, {
                    model : model
                }));

                this.$(".content-timeline-items").append(exerciseContentTimelineView.render().el);

                app.module("ui").refresh(exerciseContentTimelineView.render().el);

                mod.exerciseView = exerciseContentTimelineView;

                /*
                this.listenTo(exerciseContentTimelineView, "timeline-exercise-content:save", function(model) {
                    //console.warn(e, model);
                    //self.collection.add(model);
                    model.save();
                });

                this.listenTo(exerciseContentTimelineView, "timeline-exercise-content:delete", function(model) {
                    //model.destroy();
                    self.collection.remove(model, options);
                    exerciseContentTimelineView.remove();
                });
                */

                //exerciseContentTimelineView.initEditor();
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
        });

        mod.lessonContentCollection = new lessonContentCollectionClass([], {
            lesson_id :  mod.entity_id
        });

        this.listenToOnce(lessonModel, "sync", function() {
            mod.lessonContentCollection.fetch();
        });

        mod.contentTimelineView = new contentTimelineViewClass({
            el : "#content-timeline",
            collection : mod.lessonContentCollection,
            collectionFilter : {parent_id: null}
        });
    });

    $SC.module("crud.views.edit").on("start", function() {
        mod.start();


    });
});

$SC.module("blocks.dropbox.upload", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    mod.addInitializer(function() {
        var dropboxItemModelClass = Backbone.Model.extend({
            urlRoot: "/module/dropbox/item/me/"
        });
        var fileContentViewClass = Backbone.View.extend({
            uploadTemplate : _.template($("#block-dropbox-upload-upload").html()),
            downloadTemplate : _.template($("#block-dropbox-upload-download").html()),
            emptyTemplate : _.template($("#block-dropbox-upload-empty").html()),
            className : "list-file-item draggable",
            tagName : "li",
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
                    this.$el.attr("data-file-id", this.model.get("id"));
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
            renderEmpty : function() {
                this.$el.removeClass("template-upload red-stripe");
                this.$el.removeClass("template-download green-stripe");

                this.$el.addClass("template-empty placeholder dropzone");

                this.$el.html(this.emptyTemplate());
            },
            render : function() {
                if (this.model.get("name")) {
                    if (this.upload) {
                        this.$el.removeClass("template-download green-stripe");
                        this.$el.removeClass("template-empty placeholder dropzone");

                        this.$el.addClass("template-upload red-stripe");

                        this.$el.html(this.uploadTemplate({
                            model: this.model.toJSON(),
                            file : this.fuploadFile,
                            opt  : this.fuploadOptions
                        }));

                        this.$el.addClass("template-upload");
                    } else {
                        this.$el.removeClass("template-upload red-stripe");
                        this.$el.removeClass("template-empty placeholder dropzone");

                        this.$el.addClass("template-download green-stripe");

                        this.$el.html(this.downloadTemplate({
                            model: this.model.toJSON(),
                            //file : this.fuploadFile,
                            opt  : { formatFileSize : this.formatFileSize }
                        }));
                        this.$el.addClass("template-download");
                    }

                    app.module("ui").refresh(this.$el);

                    this.$el.attr("data-file-id", this.model.get("id"));
                    this.$el.data("viewObject", this);
                } else {
                    this.renderEmpty();
                }
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
                this.trigger("file-content:save", this.model);
            },
            delete : function() {
                // IF MODEL IS SAVED, SO DELETE FROM SERVER
                this.trigger("file-content:delete", this.model);
            }
        });
        var fileUploadItemViewClass = Backbone.View.extend({
            uploadTemplate : _.template($("#block-dropbox-upload-upload").html()),
            downloadTemplate : _.template($("#block-dropbox-upload-download").html()),
            jqXHR : null,
            initialize: function(opt) {
                console.info('blocks.dropbox.upload/fileUploadItemViewClass::initialize');

                console.warn(this.$el);

                this.initializeFileUpload();

                this.listenToOnce(this.model, "sync", this.render.bind(this));

                this.render();

            },
            initializeFileUpload : function() {
                // CREATE FILEUPLOAD WIDGET
                var url = this.$el.data("fileuploadUrl");

                var self = this;

                var opt = {
                    url: "/module/dropbox/upload/image",
                    //paramName : fileInput.attr("name"),
                    dataType: 'json',
                    singleFileUploads: true,
                    autoUpload : true,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$("content-timeline-items"),
                    uploadTemplate: function (o) {
                        return self.$el.data("upload-contexts");
                    },
                    done: function() {

                    }
                };

                this.$el.fileupload(opt)
                    .bind('fileuploadadd', function (e, o) {
                        var rows = $();
                        var that = this;

                        $.each(o.files, function (index, file) {
                            var context = $(self.addFileContent({
                                upload : true,
                                file: file,
                                opt : o
                                //index : index
                            }))
                            rows = rows.add(context);
                            $(that).data("upload-contexts", rows);
                        });
                        o.context = rows;
                        self.jqXHR = o.submit();
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var files = data.getFilesFromResponse(data);
                        var viewObject = data.context.data("viewObject");

                        $.each(files, function (index, file) {
                            //viewObject.model.mergeWithinFileObject(file);
                            viewObject.model.set(file);
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
                        data.context.find(".load-percent").html(progress);
                    });
            },
            render : function(model, data, options) {
                //collection.each(function(model, i) {
                var view_type = this.model.get("content_type");

                return this.renderFileContent(this.model, {upload : false});
            },
            addFileContent : function(options) {
                var self = this;
                // TODO: INJECT FILES DATA ON MODEL
                var model = new dropboxItemModelClass(options.file);

                //model.mergeWithinFileObject(options.file);

                return this.renderFileContent(model, options);
            },
            renderFileContent : function(model, options) {
                var self = this;

                if (!_.isObject(options)) {
                    options = {};
                }

                var fileContentView = new fileContentViewClass(_.extend(options, {
                    model : model
                }));

                this.$(".content-timeline-items").html(fileContentView.render().el);


                this.listenTo(fileContentView, "file-content:save", function(model) {
                    model.save(null, {
                        success : function() {
                            self.trigger("file-upload:change", model.toJSON());
                        }
                    });

                });

                this.listenTo(fileContentView, "file-content:delete", function(model) {
                    if (!_.isNull(self.jqXHR)) {
                        self.jqXHR.abort();
                    }
                    model.destroy();
                    fileContentView.renderEmpty();
                });

                return fileContentView.el;
            }
        });

        $(".fileupload-me").each(function() {
            var self = this;

            var dropboxItemModel = new dropboxItemModelClass();
            var fileId = $(this).find(":input[type='hidden']").val();
            var updateField = true;
            if (_.isEmpty(fileId)) {
                fileId = $(this).data("fileId");
                updateField = false;
            }
            dropboxItemModel.set("id", fileId);

            var fileUploadItemView = new fileUploadItemViewClass({
                model : dropboxItemModel,
                el : $(this)
            });

            fileUploadItemView.on("file-upload:change", function(data) {
                if (updateField) {
                    $(self).find(":input[type='hidden']").val(data.id);
                    $(self).find(":input[type='hidden']").change();
                } else {
                    $(self).data("fileId", data.id);
                }
            });

            dropboxItemModel.fetch();
        });
    });
/*
    this.loadFileContent = function(file_id) {
        // GET REMOTE FILE FROM ID

        var dropboxItemModel = new dropboxItemModelClass({
            id : file_id
        });




    }
*/


    $SC.module("crud.views.edit").on("start", function() {
        mod.listenTo(this.getForm(), "form:rendered", function() {
            mod.start();
        });
    });
});

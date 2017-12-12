$SC.module("blocks.dropbox.upload", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    var imageCropDialogViewClass = Backbone.View.extend({
        el : "#dropbox-image-crop",
        jCropApi : null,
        events : {
            "shown.bs.modal" : "startJCrop",
            "click .save-action" : "saveCrop",
            "click .close-action" : "cancelCrop",
            "change [name='default_sizes']" : "changeAspectRatio"
        },
        aspectRatio : 1,
        cropSizes : null,
        initialize : function(opt) {

            if (_.has(opt, 'sizes')) {
                this.cropSizes = opt.sizes;

                this.$("[name='default_sizes']").empty();

                for (var i in this.cropSizes) {
                    $option = jQuery("<option value=" + i + ">"+ this.cropSizes[i][2] + "</option>");
                    this.$("[name='default_sizes']").append($option);
                }
                //this.$("[name='default_sizes']").
                this.$(".size-container").show();

                this.$("[name='default_sizes']").select2();
                this.$("[name='default_sizes']").select2('val', 0);
            } else {
                this.$(".size-container").hide();
            }

            this.$el.modal({
                show : false
            });

            //"file-crop:save"
            //"file-crop:cancel"
        },
        setAspectRatio : function(width, height) {
            this.aspectRatio = width / height;
            if (!_.isNull(this.jCropApi)) {
                this.jCropApi.setOptions(
                {
                    aspectRatio: width / height
                });
            }
        },
        changeAspectRatio : function(e) {
            var id = e.added.id;
            this.setAspectRatio(this.cropSizes[id][0], this.cropSizes[id][1]);
        },
        saveCrop : function() {
            this.trigger("file-crop:save", this.model);
        },
        cancelCrop : function() {
            this.trigger("file-crop:cancel", this.model);
        },
        setModel : function(model) {
            this.model = model;
        },
        startJCrop : function(e) {
            this.$(".crop-container").attr("src", this.model.get("url"));
            //
            if (!_.isNull(this.jCropApi)) {
                this.jCropApi.destroy();
            }

            var self = this;
            this.$(".crop-container").Jcrop({
                aspectRatio: this.aspectRatio,
                setSelect:   [ 0, 0, 4096, 4096 ],
                boxWidth: this.$(".modal-dialog .modal-body").width(),
                boxHeight: 900,
                onSelect: function (c) {
                    this.model.set("crop", c);
                    /*
                    $('#crop_x').val(c.x);
                    $('#crop_y').val(c.y);
                    $('#crop_w').val(c.w);
                    $('#crop_h').val(c.h);
                    */
                }.bind(this)
            }, function() {
                self.jCropApi = this;
            });
        },
        open : function() {
            this.$el.modal("show");
        }
    });

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

                this.initializeFileUpload();

                if (this.$el.data("image-crop")) {
                    this.initializeImageCropDialog();
                }

                this.listenToOnce(this.model, "sync", this.render.bind(this));

                this.render();
            },
            initializeImageCropDialog : function() {
                this.imageCropDialog = new imageCropDialogViewClass();
            },
            initializeFileUpload : function() {
                // CREATE FILEUPLOAD WIDGET
                var url = this.$el.data("fileuploadUrl");
                
                var self = this;

                var opt = {
                    url: url,
                    //paramName : fileInput.attr("name"),
                    dataType: 'json',
                    singleFileUploads: true,
                    autoUpload : false,
                    disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                    filesContainer: this.$(".content-timeline-items"),
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
                            }));
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
                            
                            if (self.$el.data("image-crop")) {
                                self.imageCropDialog.setModel(
                                    viewObject.model
                                );
                                self.imageCropDialog.open();

                                self.listenTo(self.imageCropDialog, "file-crop:save", function(model) {
                                    viewObject.completeEvents();
                                });

                                self.listenTo(self.imageCropDialog, "file-crop:cancel", function(model) {
                                    
                                    viewObject.completeEvents();
                                });

                            } else {
                                viewObject.completeEvents();
                                
                            }

                        });
                        self.jqXHR = null;
                    })
                    .bind('fileuploadfail', function (e, data) {
                    })
                    .bind('fileuploadalways', function (e, data) {
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        data.context.find(".load-percent").html(progress);
                    });
            },
            render : function(model, data, options) {
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
                            self.trigger("file-upload:change", model);
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
            
            $(this).find(":input[type='hidden']").each(function(i) { // ##codigo novo
            	
            	var fileId = $(this).val(); // ##codigo novo
           	 	var updateField = $(this).size() > 0; // ##codigo novo
           	 	 //var fileId = $(this).find(":input[type='hidden']").val();
                 //var updateField = $(this).find(":input[type='hidden']").size() > 0;
                 if (_.isEmpty(fileId)) {
                     fileId = $(this).data("fileId");
                     //updateField = false;
                 }
                 dropboxItemModel.set("id", fileId);

                 var fileUploadItemView = new fileUploadItemViewClass({
                     model : dropboxItemModel,
                     el : $(self)
                 });

                 fileUploadItemView.on("file-upload:change", function(model) {
                     var data = model.toJSON();
                     if (updateField) {
                         $(self).find(":input[type='hidden']").val(data.id);
                         $(self).find(":input[type='hidden']").change();
                         $(self).data("fileId", data.id);

                         mod.trigger("uploadComplete.dropbox", model);
                     } else {
                         //$(self).data("fileId", data.id);
                     }
                 });

                 if (!_.isUndefined(dropboxItemModel.get("id"))) {
                     dropboxItemModel.fetch();
                 }
            	 
            });
            
        });



    });

    $SC.module("crud.views.edit").on("start", function() {
        mod.listenTo(this.getForm(), "form:rendered", function() {
            mod.start();
        });
    });

    // EXPORTS
    mod.imageCropDialogViewClass = imageCropDialogViewClass;
});

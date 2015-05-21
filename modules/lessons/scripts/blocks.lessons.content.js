$SC.module("blocks.lessons.content", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        alert('init');
        this.config = $SC.module("crud.config").getConfig();
        var entity_id = mod.config.entity_id;

        var fileUploadWidgetViewClass = Backbone.View.extend({
            uploadTemplate : _.template($("#file-upload-widget-upload").html()),
            downloadTemplate : _.template($("#file-upload-widget-download").html()),
            /*
            events : {
                "click .remove-file-action" : "remove"
            },
            */
            initialize: function(opt) {
                console.info('blocks.lessons.content/fileUploadWidgetViewClass::initialize');

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
                    filesContainer: this.$("div.file-list"),
                    uploadTemplateId: null,
                    downloadTemplateId: null,
                    uploadTemplate: function (o) {
                        var rows = $();
                        // REMOVE OLD FILES
                        if (o.singleFileUploads) {
                          this.$("div.file-list").empty();
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
            }/*,
            addOne : function(data) {
                console.info('views.lessons.edit/fileListView::addOne');

                var html = this.template(data);

                $SC.module("ui").refresh( $(html).appendTo( this.$("ul") ) );

            },
            render: function() {
                console.info('views.lessons.edit/fileListView::render');

                var files = this.model.get("files");
                var data = files[this.type];
                //this.$el.empty();
                for (i in data) {
                    this.addOne(data[i]);
                }
            },
            remove : function(e) {
                var fileId = $(e.currentTarget).data("fileId");
                var fileObject = new mod.lessonFileModelClass();
                fileObject.set("id", fileId);
                fileObject.destroy();
                $(e.currentTarget).parents("li").remove();
            }
            */
        });
        mod.materialFileUploadWidgetView = new fileUploadWidgetViewClass({
            el : "#video-file-upload-widget",
            //url : "/module/lessons/upload/" + mod.config.entity_id + "/video?name=files_videos",
            //singleUpload : false,
            //type: "video",
            //acceptFileTypes: /(\.|\/)(mp4|webm)$/i,
            model : this.itemModel
        });

    });
    $SC.module("crud.views.edit").on("start", function() {
        mod.start();
    });
});

$SC.module("dialogs.storage.library", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;

    /*    
    var baseModel = app.module("models").getBaseModel();
    mod.models = {
        message : baseModel.extend({
            response_type : "object",
            urlRoot : "/module/storage/item/me"
        })
    };

    mod.collections = {
        files : {

        }
    };
    */

    this.getValue = function(callback) {
        //this.dialogView.setModel(new mod.modelClass());
        this.dialogView.open();

        this.stopListening(this.dialogView);
        this.listenTo(this.dialogView, "selected.dialog", function(data) {
            this.stopListening(this.dialogView);
            callback(data);
            this.dialogView.close();
        }.bind(this));
    };

    this.setPath = function(path) {
        this.dialogView.setPath(path);
    };


    mod.on("start", function(opt) {
        var tableViewClass = app.module("utils.datatables").tableViewClass;

        var storageLibraryTableViewClass = tableViewClass.extend({
            /*
            getTableItemModel : function(data) {
                if (data['type'] == 'user') {
                    return itemModelClass = new mod.models.enroll.user({
                        'id'        : data['id'],
                        //'enroll_id' : this.getVar("enroll_id"),
                        //'course_id' : this.getVar("course_id"),
                        'user_id'   : data['user_id']
                    });
                } else {
                    return itemModelClass = new mod.models.enroll.group({
                        'role_id' : this.getVar("role_id"),
                        'group_id' : data['id']
                    });
                }
            }
            */
        });

        var dialogViewClass = app.module("views").dialogViewClass;
        var storageLibraryDialogViewClass = dialogViewClass.extend({
            events: {
                "click .select-action" : "selectAction"
            },
            initialize: function() {
                console.info('dialogs.storage.library/storageLibraryDialogViewClass::initialize');
                dialogViewClass.prototype.initialize.apply(this);

                var self = this;

                var context = app.getResource("storage-library-table_context");


                this.$("#library_tree").jstree({
                    "core" : {
                        /*
                        "themes" : {
                            "responsive": false
                        },
                        */ 
                        // so that create works
                        "check_callback" : true,
                        /*
                        'data' : {
                            'url' : function (node) {
                              return '/module/storage/items/library';
                            }
                        }
                        */
                    },
                    "types" : {
                        "dir" : {
                            "icon" : "fa fa-folder icon-state-warning icon-lg"
                        },
                        "file" : {
                            "icon" : "fa fa-file icon-state-warning icon-lg"
                        }
                    },
                    //"state" : { "key" : "demo3" },
                    "plugins" : [ "types" ]
                });

                this.$("#library_tree").on("dblclick.jstree", function (e, data) {
                    //console.warn($(this).jstree(true).get_node(e.target));
                    var values = this.getValues();
                    if (_.size(values) > 0) {
                        this.trigger("selected.dialog", values);
                    }
                    //var node = $(event.target).closest("li");
                    //var data = node.data("jstree");
                    // Do some action
                }.bind(this));

                this.initializeFileUpload();

                /*
                this.$("#library_tree").on("changed.jstree", function (e, data) {
                    console.log(data.selected);
                    //console.warn(this.get_node(data.selected))
                });
                */

                /*
                this.tableView = new storageLibraryTableViewClass({
                    el : "#view-storage-library-table",
                    datatable : {
                        //"sAjaxSource": "{$T_MODULE_CONTEXT.ajax_source}",
                        "aoColumns": context.datatable_fields
                    }
                });
                */

                //console.warn(context);

                /*
                this.tableView
                    .putVar('enroll_id', this.model.get("enroll_id"))
                    .putVar('course_id', this.model.get("course_id"))
                    .setUrl("/module/enroll/datasource/users/datatable/" + JSON.stringify({
                        enroll_id : this.model.get("enroll_id"),
                        course_id : this.model.get("course_id"),
                    }) + "?block");
                */
                /*

                this.on("complete:save", this.close.bind(this));
                this.on("complete:save", function() {
                    app.trigger("sent.messages", [
                        this.model
                    ]);
                }.bind(this));
                */
            },
            initializeFileTree : function() {

            },
            initializeFileUpload : function() {
                console.info('dialogs.storage.library/storageLibraryDialogViewClass::initializeFileUpload');
                // CREATE FILEUPLOAD WIDGET

                console.warn(this.$el);

                var fileInput = this.$(".fileupload-widget");

                var baseUrl = fileInput.data("fileuploadUrl");

                console.warn(baseUrl);

                var self = this;

                var opt = {
                    url: baseUrl,
                    //paramName : fileInput.attr("name"),
                    dataType: 'json',
                    singleFileUploads: true,
                    autoUpload : true,
                    maxChunkSize: 1*1024*1024,
                    //maxChunkSize: 100*1024,
                    bitrateInterval : 1000,
                    disableImageResize: true,
                    //filesContainer: this.$("div.content-timeline-items"),
                    /*
                    uploadTemplate: function (o) {
                        return fileUploadItem.data("upload-contexts");
                    },
                    
                    done: function() {
                        console.warn('done', arguments);
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress .progress-bar').css(
                            'width',
                            progress + '%'
                        );
                    }
                    */
                };

                fileInput.fileupload(opt)
                    /*
                    .bind('fileuploadsubmit', function(e, data) {

                    })
                    */
                    .bind('fileuploadadd', function (e, data) {
                        console.warn('fileuploadadd', this, arguments);

                        var directories = self.getDirectories();

                        if (_.size(directories) > 0) {
                            data.formData = _.first(directories);
                            return true;
                        }
                        return false;
                    })
                    .bind('fileuploadfail', function (e, data) {
                        console.warn('fileuploadfail', arguments);
                        // CALL AJAX TO REMOVE THE FILE, IF EXISTS

                    })
                    .bind('fileuploaddone', function (e, data) {
                        console.warn('fileuploaddone', arguments);  
                        // CALL AJAX TO MOVE TO REAL STORAGE
                        var files = data.result.files

                        console.warn(files);

                        for(var i in files) {
                            var file = files[i];
                            $.ajax({
                                url : "/module/storage/move",
                                data : file,
                                method : "POST",
                                success : function() {
                                    console.warn(arguments);

                                    self.$("#library_tree").jstree(true).refresh();
                                }

                            });
                        }
                        /*
                        
                        */
                    })
                    .bind('fileuploadalways', function (e, data) {
                        //console.warn("fileuploadalways");
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        console.warn('fileuploadprogress', arguments);
                        /*
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        data.context.find(".load-total").html(self.formatFileSize(data.loaded));
                        data.context.find(".load-bitrate").html(self.formatFileSize(data._progress.bitrate / 8));
                        data.context.find(".load-percent").html(progress);
                        */
                    });
                
            },
            setPath : function(path) {
                this.$("#library_tree").jstree(true).settings.core.data = { 
                    "url": "/module/storage/items/library/default/" + JSON.stringify({
                        path : path
                    })
                };

                this.$("#library_tree").jstree(true).refresh();

                /*
                this.tableView
                    .putVar('path', path)
                    .setUrl("/module/storage/items/library/datatable/" + ;
                */
            },
            getDirectories : function() {
                var selected = this.$("#library_tree").jstree(true).get_selected(true);
                var modelClass = app.module("models").dropbox().item;
                var result = [];

                for (var i in selected) {
                  var node = selected[i];

                  console.warn(node.original.type);
                  
                  if (node.original.type != 'dir') {
                    continue;
                  }




                  result.push({
                    filename : node.original.url,
                    storage : node.original.storage
                  });
                }
                return result;
            },
            getValues : function() {
                var selected = this.$("#library_tree").jstree(true).get_selected(true);
                var modelClass = app.module("models").dropbox().item;
                var result = [];

                for (var i in selected) {
                  var node = selected[i];
                  
                  if (node.original.type != 'file') {
                    continue;
                  }


                  result.push(new modelClass({
                    //upload_type : "lesson"
                    name : node.original.text,
                    filename : node.original.url,
                    //url : node.original.full_url,
                    type : node.original.mime_type,
                    size : node.original.size,
                    etag : node.original.etag,
                    storage : node.original.storage
                  }));
                }
                return result;
            },
            selectAction : function() {
                this.trigger("selected.dialog", this.getValues());
            }

            /*
            open : function() {
                this.$el.modal("show");
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            }
            */
        });



        this.dialogView = new storageLibraryDialogViewClass({
            el : "#dialogs-storage-library"/*,
            model : new mod.models.message()*/
        });

        // BIND TO DEFAULT CALLER
        $("body").delegate(".open-library-action", "click", function(e) {

            var path = $(e.currentTarget).data("library-path");

            e.preventDefault();
            //var model = new mod.models.message();
            
            this.dialogView.setPath(path);
            
            //this.dialogView.setModel(model);
            
            this.dialogView.open();
        }.bind(this));
    });
});

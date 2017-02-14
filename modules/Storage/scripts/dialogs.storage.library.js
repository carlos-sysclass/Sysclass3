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
                "click .select-action" : "selectAction",
                "click .deletefile-action" : "deleteAction"
            },
            initialize: function() {
                console.info('dialogs.storage.library/storageLibraryDialogViewClass::initialize');
                dialogViewClass.prototype.initialize.apply(this);

                var self = this;

                var context = app.getResource("storage-library-table_context");

                this.initializeFileTree();
                this.initializeFileUpload();
                this.initializeEditable();
            },
            initializeFileTree : function() {
                var self = this;
                this.$("#library_tree").jstree({
                    "core" : {
                        /*
                        "themes" : {
                            "responsive": false
                        },
                        */ 
                        // so that create works
                        "check_callback" : function(operation, node, parent, position) {
                            switch (operation) {
                                case "create_node" : {
                                    var directories = self.getDirectories();

                                    if (_.size(directories) == 0) {
                                        return false;
                                    }

                                    var new_folder = node.text;

                                    for(var i in parent.children) {
                                        var childId = parent.children[i];

                                        var child = this.get_node(childId);

                                        if (child.text.toLowerCase() == new_folder.toLowerCase()) {
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                                case "move_node" : {
                                    return $.inArray(this.get_type(node), this.get_rules(parent).valid_children) != -1;
                                }
                            }
                            return false;
                        },
                    },
                    "types" : {
                        //"valid_children" : [ "root" ],
                        "root" : {
                            "valid_children" : [ "dir", "file" ],
                            "icon" : "fa fa-home icon-state-primary icon-lg"
                        },
                        "dir" : {
                            "valid_children" : ["dir", "file"],
                            "icon" : "fa fa-folder icon-state-warning icon-lg"
                        },
                        "file" : {
                            "valid_children" : "none",
                            "max_children" : 0,
                            "max_depth" : 0,
                            "icon" : "fa fa-file icon-state-default icon-lg"
                        }
                    },
                    //"state" : { "key" : "demo3" },
                    "plugins" : [ "types", "dnd" ]
                });

                this.$("#library_tree").on("dblclick.jstree", function (e, data) {
                    var values = this.getValues();
                    if (_.size(values) > 0) {
                        this.trigger("selected.dialog", values);
                    }
                }.bind(this));


                this.$("#library_tree").on("create_node.jstree", function (e, data) {
                    // MOVE FROM data.old_parent TO data.parent
                    var folder = data.node;

                    var dest = $(this).jstree(true).get_node(data.parent);
                   
                    var data = {
                        name : folder.original.text,
                        parent : dest.original.url,
                        storage : dest.original.storage
                    };

                    $.ajax({
                        url : "/module/storage/folder",
                        data : data,
                        method : "POST",
                        success : function() {
                            self.$("#library_tree").jstree(true).refresh();
                        }

                    });
                });

                this.$("#library_tree").on("move_node.jstree", function (e, data) {
                    // MOVE FROM data.old_parent TO data.parent
                    var filename = data.node;

                    var from = $(this).jstree(true).get_node(data.old_parent);
                    var dest = $(this).jstree(true).get_node(data.parent);

                    var data = {
                        name : filename.original.text,
                        from : from.original.url,
                        dest : dest.original.url,
                        storage : filename.original.storage
                    };

                    $.ajax({
                        url : "/module/storage/move",
                        data : data,
                        method : "POST",
                        success : function() {
                            self.$("#library_tree").jstree(true).refresh();
                        }
                    });
                });

                this.$("#library_tree").on("refresh.jstree", this.toggleActions.bind(this));
                this.$("#library_tree").on("select_node.jstree", this.toggleActions.bind(this));
                this.$("#library_tree").on("deselect_node.jstree", this.toggleActions.bind(this));
            },
            toggleActions : function() {
                var api = this.$("#library_tree").jstree(true);
                var selected = api.get_selected(true);


                if (_.size(selected) == 0) {
                    this.$(".editable-me").addClass("disabled").editable("disable")
                    this.$(".fileupload-widget").addClass("disabled").fileupload('disable');  
                    this.$(".deletefile-action").addClass("disabled").attr("disabled", "disabled");
                } else if (_.size(selected) > 1) {
                    this.$(".editable-me").addClass("disabled").editable("disable")
                    this.$(".fileupload-widget").addClass("disabled").fileupload('disable');
                    this.$(".deletefile-action").removeClass("disabled").removeAttr("disabled", "disabled");
                } else {
                    this.$(".editable-me").removeClass("disabled").editable("enable")
                    this.$(".fileupload-widget").removeClass("disabled").fileupload('enable');  
                    this.$(".deletefile-action").removeClass("disabled").removeAttr("disabled");
                }

                for (var i in selected) {
                    var item = selected[i];

                    if (item.type == "root") {
                        this.$(".deletefile-action").attr("disabled", "disabled");
                        break;
                    }
                }
            },
            initializeFileUpload : function() {
                console.info('dialogs.storage.library/storageLibraryDialogViewClass::initializeFileUpload');
                // CREATE FILEUPLOAD WIDGET

                var fileInput = this.$(".fileupload-widget");

                var baseUrl = fileInput.data("fileuploadUrl");

                var self = this;

                var opt = {
                    url: baseUrl,
                    dataType: 'json',
                    singleFileUploads: true,
                    autoUpload : true,
                    maxChunkSize: 1*1024*1024,
                    bitrateInterval : 1000,
                    disableImageResize: true,
                };

                fileInput.fileupload(opt)
                    .bind('fileuploadadd', function (e, data) {
                        var api = self.$("#library_tree").jstree(true);
                        var selected = api.get_selected(true);

                        if (_.size(selected) == 1) {
                            if ($.inArray(selected[0].type, ['dir', 'root'] !== -1)) {
                                item = _.first(selected);

                                data.formData = {
                                    filename : item.original.url,
                                    storage : item.original.storage
                                };

                                self.$(".file-total").html(self.formatFileSize(data.files[0].size));

                                self.startLoader();
                                

                                return true;
                            }
                        }
                        return false;
                    })
                    .bind('fileuploadfail', function (e, data) {
                        // CALL AJAX TO REMOVE THE FILE, IF EXISTS
                    })
                    .bind('fileuploaddone', function (e, data) {
                        // CALL AJAX TO MOVE TO REAL STORAGE
                        var files = data.result.files

                        for(var i in files) {
                            var file = files[i];
                            $.ajax({
                                url : "/module/storage/send",
                                data : file,
                                method : "POST",
                                success : function() {
                                    self.finishLoader();

                                    self.$("#library_tree").jstree(true).refresh();

                                }

                            });
                        }
                        /*
                        
                        */
                    })
                    .bind('fileuploadalways', function (e, data) {
                    })
                    .bind('fileuploadprogress', function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        self.$(".load-total").html(self.formatFileSize(data.loaded));
                        self.$(".load-bitrate").html(self.formatFileSize(data._progress.bitrate / 8));
                        self.$(".load-percent").html(progress);

                        self.$(".file-loader-progress .progress-bar").width(progress + "%");
                    });
            },
            startLoader : function() {
               this.$(".loader-spinner")
                    .removeClass("fa-check font-green")
                    .addClass("fa-spinner fa-spin");
                this.$(".file-loader-progress .progress-bar").width("0%");
                this.$(".file-loader").show();
            },
            finishLoader : function() {
                this.$(".loader-spinner")
                    .removeClass("fa-spinner fa-spin")
                    .addClass("fa-check font-green");
                self.$(".file-loader").hide();
            },

            formatFileSize: function (bytes) {
                if (typeof bytes !== 'number') {
                    return '';
                }
                if (bytes >= (1024*1024*1024)) {
                    return (bytes / (1024*1024*1024)).toFixed(2) + ' GB';
                }
                if (bytes >= (1024*1024)) {
                    return (bytes / (1024*1024)).toFixed(2) + ' MB';
                }
                return (bytes / 1024).toFixed(2) + ' KB';
            },
            initializeEditable : function() {
                var editableItem = this.$(".editable-me");

                //if (this.opened) {
                    /*
                    window.setTimeout(function() {
                        editableItem.editable('show');
                    }, 350);
                    */
                //}

                editableItem.editable("option", {
                    display : false,
                    autotext : "never",
                    value: ""
                });


                

                var self = this;
                editableItem.on('shown', function(e, params) {
                    $(this).editable("setValue", "");
                });

                editableItem.on('save', function(e, params) {

                    var directories = self.getDirectories();

                    if (_.size(directories) > 0) {
                        var parent = _.first(directories);

                        self.$("#library_tree").jstree(true).create_node(parent.node, {
                            text: params.newValue,
                            type: "dir"
                        }, 'last');

                        return true;
                    }
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

                  if (node.original.type != 'dir') {
                    continue;
                  }

                  result.push({
                    node : node,
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
            },
            deleteAction : function() {
                var jstree = this.$("#library_tree").jstree(true);
                var selected = jstree.get_selected(true);

                for (var i in selected) {
                    var node = selected[i];
                    if ($.inArray(jstree.get_type(node), ["file", "dir"]) !== -1) {

                        var data = {
                            name : node.original.text,
                            url : node.original.url,
                            storage : node.original.storage
                        };

                        $.ajax({
                            url : "/module/storage/delete",
                            data : data,
                            method : "POST",
                            success : function() {
                                self.$("#library_tree").jstree(true).refresh();
                            }

                        });
                    }
                }
            }
        });

        this.dialogView = new storageLibraryDialogViewClass({
            el : "#dialogs-storage-library"
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

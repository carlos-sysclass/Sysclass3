$SC.module("models", function(mod, app, Backbone, Marionette, $, _) {
    var baseModelClass = Backbone.DeepModel.extend({
        response_type : "object",
        save: function(key, val, options) {
            this.trigger("before:save", this);
            //this.trigger('change:' + changes[i], this, current[changes[i]], options);
            Backbone.DeepModel.prototype.save.apply(this, arguments);

            this.trigger("after:save", this);
        },
        setResponseType : function(mode) {
            // MODE CAN BE // 
            // redirect => will return a respose for redirection
            // object => will return onlyh the object (with status response) (default)
            // status => will retuirn only the status response
            this.response_type = mode;
        },
        sync : function(method, model, options) {
            if (method == "update" || method == "create") {
                //if (method == "update") {
                    //options.url = _.result(model, 'urlRoot') + "/" + model.get("id");
                //} else {
                //}

                var base = _.result(model, 'urlRoot');

                if (method == "update") {
                    var id = this.get(this.idAttribute);
                    options.url = base.replace(/[^\/]$/, '$&/') + encodeURIComponent(id);
                } else {
                    options.url = base;
                }

                var params = [];

                if (!_.isNull(this.response_type)) {
                    if (this.response_type == "redirect") {
                        params.push("redirect=" + "1");
                    } else if (this.response_type == "reload") {
                        params.push("reload=" + "1");
                    } else if (this.response_type == "object") {
                        params.push("object=" + "1");
                    } else if (this.response_type == "status") {
                        params.push("status=" + "1");
                    } else if (this.response_type == "silent") {
                        params.push("silent=" + "1");
                    }
                    options.url = options.url + "?" + params.join("&");
                }

                //options.data = JSON.stringify(model._asNameValue());

                return Backbone.sync(method, model, options);
            }

            return Backbone.sync(method, model, options);

            if (method == "update" && this.mode) {

            }
            return Backbone.sync(method, model, options);
        }
    });

    var baseContentModelClass = baseModelClass.extend({
        files : null,
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
        addFile : function(info, saveInServer) {
            var files = this.getFiles();

            var self = this;

            saveInServer = _.isUndefined(saveInServer) ? true : saveInServer;
            if (saveInServer) {
                var model = new models.content.item.content_file({
                    content_id : this.get("id"),
                    file_id : info.id
                });
                model.save(null, {
                    success : function() {
                        files.add(info);
                    }
                });
            } else {
                files.add(info);
            }
        },
        getFiles : function() {
            if (_.isNull(this.files)) {
                var files = this.get("files");
                this.files = new models.dropbox.collection(files)
            }
            return this.files;
        },
        urlRoot: "/module/content/item/me",
        isVideo : function() {
            return /^video\/.*$/.test(this.get("type"));
        },
        //isRemoteVideo : function() {
        //    return /\.mp4$/.test(this.get("content"));
        //},
        isAudio : function() {
            return /^audio\/.*$/.test(this.get("type"));
        },
        isPdf : function() {
            return /.*\/pdf$/.test(this.get("type"));
        },
        isImage : function() {
            return /^image\/.*$/.test(this.get("type"));
        },
        /*
        isSubtitle : function() {
            return this.get("content_type") == "subtitle" || this.get("content_type") == "subtitle-translation";
        },
        isExercise : function() {
            return this.get("content_type") == "exercise";
        },
        */
        isMaterial : function() {
            return !this.isVideo() /*&& !this.isSubtitle() */ && !this.isAudio() && !this.isImage() /* && !this.isExercise()*/;
        }
    });

    var models = {
        base : {
            default: baseModelClass
        },
        users : {
            user : baseModelClass.extend({
                urlRoot : "/module/users/item/me"
            })
        },
        dropbox : {
            item : baseModelClass.extend({
                urlRoot : "/module/dropbox/item/me", // CHANGE ALL TO STORAGE MODULE
                isVideo : function() {
                    return /^video\/.*$/.test(this.get("type"));
                },
                //isRemoteVideo : function() {
                //    return /\.mp4$/.test(this.get("content"));
                //},
                isAudio : function() {
                    return /^audio\/.*$/.test(this.get("type"));
                },
                isPdf : function() {
                    return /.*\/pdf$/.test(this.get("type"));
                },
                isImage : function() {
                    return /^image\/.*$/.test(this.get("type"));
                },
                isSubtitle : function() {
                    return this.get("upload_type") == "subtitle" || this.get("upload_type") == "subtitle-translation";
                },
                /*
                isExercise : function() {
                    return this.get("content_type") == "exercise";
                },
                */
                isMaterial : function() {
                    return !this.isVideo() && !this.isSubtitle() && !this.isAudio() && !this.isImage() /* && !this.isExercise()*/;
                }
            }),
            collection : Backbone.Collection.extend({
                model : function(attrs, options) {
                    if (options.add) {
                        return new models.dropbox.item(attrs, _.extend(options, {
                            collection: this,
                        }));
                    }
                }
            })
        },
        content : {
            item : {
                base : baseContentModelClass,
                
                file : baseContentModelClass.extend({
                    files : null,
                    defaults : function() {
                        var defaults = baseContentModelClass.prototype.defaults.apply(this);
                        defaults['content_type'] = 'file';
                        return defaults;
                    },
                    mergeWithinFileObject : function(file) {
                        this.set("name", file.name);
                        this.set("info", JSON.stringify(file));
                        this.set("file", file);
                    }
                }),
                video : baseContentModelClass.extend({
                    files : null,
                    defaults : function() {
                        var defaults = baseContentModelClass.prototype.defaults.apply(this);
                        defaults['content_type'] = 'video';
                        return defaults;
                    }
                }),
                content_file : baseModelClass.extend({
                    urlRoot : "/module/content/item/file"
                })
            },
            collection : Backbone.Collection.extend({
                initialize: function(data, opt) {
                    this.lesson_id = opt.lesson_id;
                    this.listenTo(this, "add", function(model, collection, opt) {
                        model.set("lesson_id", this.lesson_id);
                        // SET POSITION
                    });
                    this.listenTo(this, "remove", function(model, collection, opt) {
                        /*
                        var self = this;

                        var subfiles = collection.where({parent_id : model.get("id")});

                        _.each(subfiles, function(item) {
                            self.remove(item.id);
                        });

                        model.destroy();
                        */
                    });
                },
                url: function() {
                    return "/module/lessons/items/lesson-content/default/" + JSON.stringify({
                        'lesson_id' : this.lesson_id
                    });
                },
                model: function(attrs, options) {
                    if (options.add) {
                        attrs.file = _.first(attrs.files);
                        if (attrs.content_type == "video") {
                            return new models.content.item.video(attrs, _.extend(options, {
                                collection: this,
                            }));
                        } else if (attrs.content_type == "file") {
                            return new models.content.item.file(attrs, _.extend(options, {
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
                            return new models.content.item.base(attrs, _.extend(options, {
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
                },
                getVideos : function() {
                    var videos = this.where({content_type : "video"});
                    /*
                    var videos = _.filter(files, function(model, index, collection) {
                        return model.isVideo();
                    });
                    */

                    return videos;
                },
                getMaterials : function() {
                    var materials = this.where({content_type : "file"});
                    /*
                    var videos = _.filter(files, function(model, index, collection) {
                        return model.isVideo();
                    });
                    */

                    return materials;
                }
            }),
        },
        storage : {
            info : baseModelClass.extend({
                urlRoot : "/module/storage/item/info" // CHANGE ALL TO STORAGE MODULE
            }),
            source : baseModelClass.extend({
                urlRoot : "/module/storage/item/source" // CHANGE ALL TO STORAGE MODULE
            })
        }

    };

    this.getBaseModel = function() {
       return models.base.default; 
    };

    this.users = function() {
        return models.users;
    };

    this.dropbox = function() {
        return models.dropbox;
    };

    this.storage = function() {
        return models.storage;
    };

    this.content = function() {
        return models.content;
    };


});

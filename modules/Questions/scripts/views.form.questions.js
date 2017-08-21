$SC.module("views.form.questions", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS

    this.startWithParent = false;

    this.setInfo = function(opt) {
        if (_.has(mod, "questionDetailView")) {
            mod.questionDetailView.disableAll();
            mod.questionDetailView.stopListening();
        }
        
        var formView = opt.module.getForm();

        mod.questionDetailView = new mod.questionDetailViewClass({
            el : "#question-type-container",
            model : formView.model,
            form : formView
        });

        mod.questionFilesView = new mod.questionFilesViewClass({
            el : "#question-files-container",
            model : formView.model,
            form : formView
        });
    }

    this.on("start", function(opt) {

        this.started = true;

        var questionChoiceModelClass = Backbone.Model.extend({});

        var questionChoicesCollectionClass = Backbone.Collection.extend({
            comparator : 'index',
            multiple : false,
            model : questionChoiceModelClass,
            initialize: function(data, opt) {
                if (_.isBoolean(opt.multiple)) {
                    this.multiple = opt.multiple;
                }
                this.listenTo(this, "change:answer", function(model,value,c,d) {
                    var correct = this.where({answer : true});

                    if (value) {
                        // CHANGE ALL TO FALSE
                        if (!this.multiple) {
                            this.each(function(item, index) {
                                if (item === model || !item.get("answer")) {
                                    return;
                                }
                                item.set("answer", false);
                            });//
                        } else if (_.size(correct) === this.size() && this.size() > 1) {
                            model.set("answer", false);
                        }
                    } else {
                        // AT LEAST ONE ANSWER MUST BE SELECTED
                        if (_.size(correct) === 0) {
                            // REVERT CHANGE
                            model.set("answer", true);
                        }
                   }
                });

                this.listenTo(this, "add", function(model,collection, c) {
                    if (collection.size() === 1) {
                        model.set("answer", true);
                    }
                });

                this.listenTo(this, "remove", function(model,collection) {
                    // AT LEAST ONE ANSWER MUST BE SELECTED
                    var correct = collection.where({answer : true});

                    if (_.size(correct) === 0 && collection.size() > 0) {
                        collection.at(0).set("answer", true);
                    }
                });
            }

        });

        var baseSubviewViewClass = Backbone.View.extend({
            disabled : true,
            /*
            initialize : function() {
                this.parentView = opt.form;
            },
            */
            disable : function() {
                console.info('views.form.questions/baseSubviewViewClass::disable');
                this.disabled = true;
                this.$el.hide();
            },
            enable : function() {
                console.info('views.form.questions/baseSubviewViewClass::enable');
                this.disabled = false;
                this.$el.show();
            }
        });

        var subviewsClass = {};
        subviewsClass.combine = baseSubviewViewClass.extend({});
        subviewsClass.fill_blanks = baseSubviewViewClass.extend({});
        subviewsClass.free_text = baseSubviewViewClass.extend({});

        subviewsClass.true_or_false = baseSubviewViewClass.extend({
            disabled : true,
            initialize: function(opt) {
                baseSubviewViewClass.prototype.initialize.apply(this);

                console.info('views.form.questions/subviewsClass[\'true_or_false\']::initialize');
                this.listenTo(this.model, "change:answer", this.updateModel.bind(this));
            },
            updateModel : function() {
                if (!this.disabled) {
                    console.info('views.form.questions/subviewsClass[\'true_or_false\']::updateModel');
                    this.model.set("true_or_false.answer", this.model.get("answer"));
                }
            }
        });

        var questionChoiceViewClass = Backbone.View.extend({
            template : _.template($("#question-simple_choice-item").html(), null, {variable: 'model'}),
            tagName : "li",
            className : "draggable margin-bottom-5",
            events : {
                "click .add-choice-action"      : "addChoice",
                "click .remove-choice-action"   : "removeChoice",
                "change :input"                 : "update",
                "click .select-choice-correct-action"   : "setCorrect",
                "click .select-choice-incorrect-action"   : "setIncorrect"
            },
            parent: null,
            initialize: function(opt) {
                this.listenTo(this.model, "change", this.render.bind(this));
                this.parent = opt.parent;

            },
            render : function() {
                this.$el.html(this.template(this.model.toJSON()));
                if (this.model.get("answer")) {
                    this.$el.addClass("has-success");
                    this.$el.removeClass("has-error");
                } else {
                    this.$el.removeClass("has-success");
                    this.$el.addClass("has-error");
                }
                this.$el.attr("data-order", this.model.get("index"));
                return this;
            },
            bindValues : function(model) {

            },
            update : function(e) {
                this.model.set("choice", $(e.currentTarget).val());
                this.parent.updateModel();
            },
            setCorrect : function() {
                this.model.set("answer", true);
                
                this.parent.updateModel();

            },
            setIncorrect : function() {
                this.model.set("answer", false);

                this.parent.updateModel();
            },
            removeChoice : function(e) {
                console.info('views.form.questions/questionChoiceViewClass::removeChoice');

                this.parent.collection.remove(this.model);
                this.model.destroy();

                this.remove();
            }
        });

        var baseSubviewChoicesViewClass = baseSubviewViewClass.extend({
            events : {
                "click .add-choice-action" : "addChoice"
            },
            sub_type : null,
            initialize: function(opt) {
                console.info('views.form.questions/baseSubviewChoicesViewClass::initialize');
                if (!this.model.has(this.sub_type + ".options")) {
                    if (_.isNull(this.model.get("options"))) {
                        this.model.set("options", []);
                    }
                    this.model.set(this.sub_type + ".options", this.model.get("options"));
                } else if (_.isNull(this.model.get("simple_choice.options"))) {
                    this.model.set(this.sub_type + ".options", []);
                }

                var data = this.model.get(this.sub_type + ".options");

                this.collection = new questionChoicesCollectionClass(data, {multiple : (this.sub_type == "multiple_choice")});
                this.listenTo(this.collection, "add", this.addOne.bind(this));
                this.listenTo(this.collection, "reset", this.render.bind(this));
                this.listenTo(this.collection, "update", this.updateModel.bind(this));
                this.listenTo(this.collection, "sort", this.updateModel.bind(this));

                //this.listenTo(this.model, "before:save", this.updateModel.bind(this));
                //mod.testol = this.collection;

                //this.collection.reset(data);

                this.initializeSortable();
            },
            render : function() {
                console.info('views.form.questions/baseSubviewChoicesViewClass::render');
                var self = this;
                this.collection.each(function(model) {
                    self.addOne(model);
                });
            },
            initializeSortable : function() {
                var self = this;
                this.$(".list-group").sortable({
                    //connectWith: ".list-group",
                    handle : ".drag-handler",
                    items: "li.draggable",
                    opacity: 0.8,
                    /* axis : "y", */
                    placeholder: 'list-file-item placeholder no-padding',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    /* helper : 'original',  */
                    update : function( event, ui ) {
                        var order = $(this).sortable("toArray", {attribute : "data-order"});

                        console.warn(order);

                        for(var i = 0; i < self.collection.size(); i++) {
                            self.collection.at(order[i]).set("index", i);
                        }
                        self.collection.sort();

                        //self.collection.setContentOrder(contentOrder);
                    }
                });
            },
            updateModel : function() {
                console.info('views.form.questions/baseSubviewChoicesViewClass::updateModel');
                if (!this.disabled) {
                    this.model.unset(this.sub_type + ".options", {silent: true});
                    this.model.set(this.sub_type + ".options", this.collection.toJSON());
                }
            },
            addChoice : function(e) {
                console.info('views.form.questions/baseSubviewChoicesViewClass::addChoice');

                var model = new questionChoiceModelClass({
                    index: this.collection.size()
                },{
                    collection : this.collection
                });

                this.collection.add(model);
            },
            addOne : function(model) {
                console.info('views.form.questions/baseSubviewChoicesViewClass::addOne');

                var questionChoiceView = new questionChoiceViewClass({
                    model : model,
                    parent: this
                });

                var html = questionChoiceView.render().el;

                app.module("ui").refresh(
                    $(html).appendTo(this.$(".list-group"))
                );
            }
        });

        subviewsClass.simple_choice = baseSubviewChoicesViewClass.extend({
            initialize: function(opt) {
                console.info('views.form.questions/subviewsClass[\'simple_choice\']::initialize');
                this.sub_type = "simple_choice";
                baseSubviewChoicesViewClass.prototype.initialize.apply(this);
            }
        });
        subviewsClass.multiple_choice = baseSubviewChoicesViewClass.extend({
            initialize: function(opt) {
                console.info('views.form.questions/subviewsClass[\'multiple_choice\']::initialize');
                this.sub_type = "multiple_choice";
                baseSubviewChoicesViewClass.prototype.initialize.apply(this);
            }
        });

        mod.questionDetailViewClass = Backbone.View.extend({
            subviews : {},
            initialize: function(opt) {
                console.info('views.form.questions/questionDetailViewClass::initialize');

                this.parentView = opt.form;

                this.listenTo(this.model, "sync", this.updateChild.bind(this));
                this.listenTo(this.model, "change:type_id", this.render.bind(this));
           },
            /*
            request : function(a,b,c,d,e) {
                a.set("a", true);
            },
            */
            updateChild : function(model) {
                console.info('views.form.questions/questionDetailViewClass::updateChild');
                this.parentView.render();
            },
            render : function(model) {
                console.info('views.form.questions/questionDetailViewClass::render');

                var type_id = model.get("type_id");
                var values = model.pick("options", "answer", "explanation", "answers_explanation", "settings");
                model.set(type_id, values);

                if (!_.has(this.subviews, type_id)) {
                    if (_.has(subviewsClass, type_id)) {
                        var className = subviewsClass[type_id];
                        this.subviews[type_id] = new className({
                            el: this.$(".question-type-" + type_id),
                            model: this.model
                        });
                        this.subviews[type_id].render();
                    }
                } else {
                    this.subviews[type_id].setElement(this.$(".question-type-" + type_id));
                }
                _.each(this.subviews, function(subview, index) {
                    if (type_id != index) {
                        subview.disable();
                    } else {
                        subview.enable();
                    }
                });
            },
            updateChildModel : function(model) {
                var type_id = model.get("type_id");
                if (_.has(this.subviews, type_id)) {
                    //var values = this.subviews[type_id].getChildModelData();
                    //model.set(values);
                }
            },
            disableAll : function() {
                _.each(this.subviews, function(subview, index) {
                    subview.disable();
                });
                this.subviews = {};
            }
        });

        mod.questionFileItemViewClass = Backbone.View.extend({
            events : {
                "confirmed.bs.confirmation .delete-item"    : "delete",
                "change :input[name='locale_code']" : "updateLocale"
            }, 
            template: _.template($("#content-file-item").html(), null, {variable: 'model'}),
            tagName : "li",
            className : "list-item",
            render : function() {
                if (this.model) {
                    this.$el.html(this.template(this.model.toJSON()));
                    /*

                    console.warn(this.model.toJSON());
                    console.warn(this.model.get("locale_code"));

                    if (_.isEmpty(this.model.get("locale_code"))) {

                        this.$(":input[name='locale_code']").select2("val", "");
                    }
                    */
                }
                return this;
            },
            updateLocale : function(e, data) {
                console.info('views.form.questions/questionFileItemViewClass::updateLocale');
                console.warn(e, data);
                if (e.added) {
                    this.model.set("locale_code", e.added.id);
                    //this.model.save();
                }
            },
            delete : function() {
                this.model.collection.remove(this.model);
                this.remove();
            }
        });

        mod.questionFilesViewClass = Backbone.View.extend({
            subviews : {},
            childViews : [],
            libraryModule : app.module("dialogs.storage.library"),
            events : {
                "click .content-addfile" : "openStorageLibrary"
            },
            initialize: function(opt) {
                console.info('views.form.questions/questionFilesViewClass::initialize');
                // CREATE FILE COLLECTION FORM "files"
                this.parentView = opt.form;

                this.listenTo(this.model, "sync", this.updateCollection.bind(this));

                /*
                this.listenTo(this.parentView, "before:save", function(model) {
                    alert(1);
                    return true;
                });
                */

                //app.module("ui").refresh(this.$el);
                //this.listenTo(this.model, "before:save", this.updateChildModel.bind(this));
            },
            /*
            request : function(a,b,c,d,e) {
                a.set("a", true);
            },
            */
            openStorageLibrary : function(e) {
                var self = this;
                if (!this.libraryModule.started) { 
                    this.libraryModule.start();
                }
                var path = $(e.currentTarget).data("library-path");
                var type = $(e.currentTarget).data("library-type");

                this.libraryModule.setPath(path);

                this.libraryModule.getValue(function(files) {

                    // SAVE THE FILE REFERENCE IN DATABASE
                    var modelClass = app.module("models").dropbox().item;

                    for(var i in files) {
                        var model = files[i];
                        model.set("upload_type", _.isEmpty(type) ? "lesson" : type);
                        model.save(null, {
                            success : function(model, data, options) {
                                console.warn(model, data, options);

                                this.collection.add(data);
                                /*
                                // SAVE THE FILE UNDER THE VIEW MODEL
                                var questionFileModelClass = app.module("models").question().item.file;

                                var questionFileModel = new questionFileModelClass({
                                    title : data.name,
                                    info : JSON.stringify(data),
                                    files: [data]
                                });

                                this.collection.add(contentFileModel);

                                contentFileModel.save(null, {
                                    success : function() {
                                        //contentFileModel.addFile(data)
                                    }
                                });
                                */
                                //this.model.addFile(data);
                            }.bind(self)
                        });
                    }

                });
            },
            updateCollection : function() {
                var files = this.model.get("files");
                if (!this.collection) {
                    var questionFileCollection = app.module("models").question().collection.file;
                    this.collection = new questionFileCollection(files);

                    this.listenTo(this.collection, "add change remove", this.updateModel.bind(this))
                    this.listenTo(this.collection, "add", this.addOne.bind(this));
                } else {
                    this.collection.reset(files);
                }

                this.render();
            },
            updateChild : function(model) {
                console.info('views.form.questions/questionFilesViewClass::updateChild');
                this.parentView.render();
            },
            updateModel : function() {
                console.info('views.form.questions/questionFilesViewClass::updateModel');
                console.warn(this.collection.toJSON());
                console.warn(arguments);
                this.model.unset("files");
                this.model.set("files", this.collection.toJSON());
            },
            render : function(model) {
                console.info('views.form.questions/questionFilesViewClass::render');

                for(var i in this.childViews) {
                    this.childViews[i].remove();
                }

                this.collection.each(this.addOne.bind(this));
            },
            addOne : function(model) {
                var childView = new mod.questionFileItemViewClass({
                    model : model
                });

                this.$(".files-container").append(childView.render().el);
                app.module("ui").refresh(childView.el);
                this.childViews.push(childView);
            }
        });


        if (_.has(opt, "module")) {
            this.setInfo(opt);
        }
        /*
        var formView = opt.module.getForm();

        mod.questionDetailView = new mod.questionDetailViewClass({
            el : "#question-type-container",
            model : formView.model,
            form : formView
        });
        */
    });


    /*
    $SC.module("crud.views.add").on("start", function() {
        if (!mod._isInitialized) {
            mod.start({
                module: this
            });
        }
    });
    
    $SC.module("crud.views.edit").on("start", function() {
        if (!mod._isInitialized) {
            mod.start({
                module: this
            });
        }
    });
    */
});

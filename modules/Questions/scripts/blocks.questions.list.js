$SC.module("blocks.questions.list", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    mod.on("start", function(opt){
        //var module_id = "questions";
        //var model_id = "me";


        /*
        mod.classesCollectionClass = Backbone.Collection.extend({
            course_id : 0,
            datatable : false,

            url : function() {
                var url = "/module/roadmap/items/classes/:type/:courses/";
                url = url.replace(":courses", JSON.stringify(this.course_id));
                if (this.datatable) {
                    url = url.replace(":type", 'datatable');
                } else {
                    url = url.replace(":type", 'default');
                }
                return url;
            }
        });
        */
        // TODO: EXTEND FROM BASE CLASS, TO GET VIEW/MODEL SYNC
        var viewsBaseClass = app.module("views").baseClass;

        mod.blockItemViewClass = viewsBaseClass.extend({
            events : function() {
                var baseEvents = viewsBaseClass.prototype.events.apply(this);
                var events = {
                    //"click .edit-item-detail" : "editItem",
                    "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
                    //"click .view-item-detail": "toogleDetail",
                    "confirmed.bs.confirmation .delete-item-action" : "delete"
                };
                return _.extend(baseEvents, events);
            },
            /*
            events : {
                "click .edit-item-detail" : "editItem",
                "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
                "click .view-item-detail": "toogleDetail",
                "confirmed.bs.confirmation .delete-item-action" : "delete"
            },
            */
            template : _.template($("#question-item").html(), null, {variable: 'model'}),
            tagName : "li",
            className : "list-file-item blue-stripe",
            initialize: function(opt) {
                console.info('blocks.questions.list/blockItemViewClass::initialize');

                viewsBaseClass.prototype.initialize.apply(this);

                this.opened = opt.opened ? opt.opened : false;

                this.listenTo(this.model, 'sync', this.render.bind(this));
            },
            render : function() {
                console.info('blocks.questions.list/blockItemViewClass::render');
                
                this.$el.html(this.template(this.model.toJSON()));
                if (this.model.get("id")) {
                    if (this.model.get("active") == "0") {
                        this.$el.removeClass("green-stripe");
                        this.$el.removeClass("blue-stripe");
                        this.$el.addClass("red-stripe");
                    } else {
                        this.$el.removeClass("red-stripe");
                        this.$el.removeClass("blue-stripe");
                        this.$el.addClass("green-stripe");
                    }
                    this.stopListening(this.model, 'sync');
                    this.listenTo(this.model, 'sync', this.render.bind(this));
                }
                //this.$el.data("lessonId", this.model.get("id"));
                this.$el.attr("data-roadmap-grouping-id", this.model.get("id"));

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }

                this.trigger("grouping:updated", this.model);

                viewsBaseClass.prototype.render.apply(this);

                return this;
            },
            start : function() {
                /*
                var editableItem = this.$(".editable-me");

                if (this.opened) {
                    window.setTimeout(function() {
                        editableItem.editable('show');
                    }, 350);
                }

                var self = this;
                editableItem.on('save', function(e, params, b,c) {
                    self.model.set($(this).data("name"), params.newValue);
                    self.model.save();
                });
                */
                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }

                this.listenTo(this.model, 'change:points change:weight', this.save.bind(this));

                this.$("[name='points[]']").focus();
            },
            save : function() {
                // SHOW INNER LOADER
                // TODO: MAKE A WAY TO INHIBIT GLOBAL LOADER
                this.model.save();
                mod.blockView.refreshScore();
            },
            editItem : function(e) {
                var self = this;

                if (_.isNull(mod.groupingAddDialog)) {
                    mod.groupingAddDialog = new mod.groupingAddDialogClass({
                        el : "#roadmap-grouping-dialog-modal",
                        model : this.model
                    });
                } else {
                    mod.groupingAddDialog.setModel(this.model);

                }
                mod.groupingAddDialog.render();
                mod.groupingAddDialog.open();
            },
            /*
            toogleDetail : function(e) {
                e.preventDefault();
                this.$(".detail-container").toggle(500);
            },
            */
            toogleActive : function(e, state) {
                this.model.set("active", state ? 1 : 0);
                this.model.save();
                mod.blockView.refreshScore();
            //    this.render();
            },
            delete: function() {
                this.model.destroy();
                this.trigger("grouping:removed", this.model);
                this.remove();
            }
        });

        mod.blockViewClass = Backbone.View.extend({
            questionModule : null,
            questionSelectDialog : app.module("dialogs.questions.select"),
            events : {
                "click .add-item-action" : "addItem",
                "click .create-question" : "openCreateDialog",
                "click .select-question" : "openSelectDialog",
                "click .show-tips" : "showTips"
            },
            initialize : function() {
                //this.listenToOnce(this.collection, 'sync', this.render.bind(this));
                this.listenTo(this.collection, 'add', this.addOne.bind(this));
                this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
                this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));

                this.initializeSortable();
                /*
                if (!$SC.module("dialogs.questions.create").started) {
                    $SC.module("dialogs.questions.create").start();
                }
                */
                if (!this.questionSelectDialog.started) {
                    this.questionSelectDialog.start();
                }
                /*
                this.questionCreateModule = $SC.module("dialogs.questions.create");
                this.listenTo(this.questionCreateModule, "created.question", this.createQuestion.bind(this));
                */

                //this.questionSelectModule = $SC.module("dialogs.questions.select");
                //this.listenTo(this.questionSelectModule, "select:item", this.selectQuestion.bind(this));

            },
            initializeSortable : function() {
                var self = this;

                this.$(".list-group").sortable({
                    items: "li.list-file-item",
                    handle : ".drag-handler",
                    opacity: 0.8,
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    update : function( event, ui ) {
                        var order = $(this).sortable("toArray", {attribute : "data-roadmap-grouping-id"});

                        self.collection.setOrder(order);

                        self.refreshCounters();
                    },
                    over : function( event, ui ) {
                        $(this).addClass("ui-sortable-hover");
                    },
                    out  : function( event, ui ) {
                        $(this).removeClass("ui-sortable-hover");
                    }
                });
            },
            addItem : function() {
                var self = this;
                var itemModel = new mod.groupingModelClass(null);

                self.collection.add(itemModel);

                this.listenToOnce(itemModel, "sync", function(model) {
                    self.addOne(model);
                    self.refreshCounters();
                });

                if (_.isNull(mod.groupingAddDialog)) {
                    mod.groupingAddDialog = new mod.groupingAddDialogClass({
                        el : "#roadmap-grouping-dialog-modal",
                        model : itemModel
                    });
                } else {
                    mod.groupingAddDialog.setModel(itemModel);

                }
                mod.groupingAddDialog.render();

                mod.groupingAddDialog.open();
            },
            addOne : function(model) {
                console.info('blocks.roadmap/classUnitsView::addOne');

                var self = this;

                var roadmapBlockItemView = new mod.blockItemViewClass({
                    model : model
                });

                $(roadmapBlockItemView.render().el).appendTo(this.$("ul.items-container"));
                roadmapBlockItemView.start();

                this.listenTo(roadmapBlockItemView, "grouping:updated", function(model) {
                    self.refreshCounters();
                });

                this.refreshScore();
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

                var testQuestionModel = new mod.models.question({
                    question_id : model.get("id"),
                    question : model.toJSON(),
                    active : 1
                });

                var exists = this.collection.where({
                    question_id : model.get("id"),
                    lesson_id : this.collection.lesson_id
                });

                if (_.size(exists) === 0) {
                    this.collection.add(testQuestionModel);
                    testQuestionModel.save(null, {silent: true});
                } else {
                    // already exists. Show message??
                }
            },
            openSelectDialog : function() {
                this.questionSelectDialog.setFilter({
                    content_id : this.model.get("id")
                }).getValue(function(questionData) {

                    var testQuestionModel = new mod.models.question({
                        question_id : questionData.id,
                        question : questionData,
                        active : 1
                    });

                    var exists = this.collection.where({
                        question_id : questionData.id,
                        lesson_id : this.collection.lesson_id
                    });

                    if (_.size(exists) === 0) {
                        this.collection.add(testQuestionModel);
                        //testQuestionModel.save();
                    } else {
                        // already exists. Show message??
                    }
                }.bind(this));
            },
            refreshCounters : function() {
                console.info('blocks.questions.list/blockViewClass::refreshCounters');
                var total = this.collection.size();
                this.$("ul.items-container > li.list-file-item .total").html(total);

                this.$("ul.items-container > li.list-file-item").each(function(i, item) {
                    $(this).find(".counter").html(i+1);
                });

            },
            refreshScore : function() {
                console.info('blocks.questions.list/blockViewClass::refreshScore');
                this.$(".total_score").empty();

                var score = this.collection.reduce(function(context, model) {
                    if (model.get("active") == 1) {
                        return context + (model.get("points") * model.get("weight"));
                    } else {
                        return context;
                    }
                }, 0);

                this.$(".total_score").html((score).toFixed(2));

                if (score > 0) {
                    /*
                    var weights = _.reduce(this.collection.pluck("weight"), function(context, weight) {
                        return context + parseInt(weight);
                    }, 0);

                    if (weights > 0) {
                    */
                        
                    /*
                    }
                    */
                }
            },
            render: function() {
                console.info('blocks.roadmap.grouping/classUnitsView::render');

                var self = this;

                this.collection.each(function(model, i) {
                    self.addOne(model, i);
                });
                //this.refreshCounters();
                app.module("ui").refresh( this.$("ul.items-container ") );

                this.refreshCounters();
            },
            remove : function(e) {
                var fileId = $(e.currentTarget).data("fileId");
                var fileObject = new mod.lessonFileModelClass();
                fileObject.set("id", fileId);
                fileObject.destroy();
                $(e.currentTarget).parents("li").remove();
            },
            showTips : function(e) {
                e.preventDefault();
                $(e.currentTarget).hide();
                this.$(".tips-container").show(500);
            }
        });

        $("[data-widget-id='questions-list-widget']").each(function() {
            var test_id = null;
            if (_.isEmpty($(this).data("testId"))) {
                // LOAD COURSE MODEL TO BY-PASS
                test_id = opt.testModel.get("id");
            } else {
                test_id = $(this).data("testId");
            }

            if (!_.isNull(test_id)) {
                mod.createBlock(this, {
                    test_id : test_id,
                    testModel : opt.testModel
                });
            }
        });

    });

    mod.createBlock = function(el, data) {
        var questionCollection = new mod.collections.questions({
            lesson_id : data.test_id
        });

        var blockView = new mod.blockViewClass({
            el : el,
            collection : questionCollection,
            model : data.testModel
        });

        questionCollection.fetch();

        mod.collectionTest = questionCollection;
        mod.blockView = blockView;
    };

    var module_id = "tests";
    var model_id = "question";

    this.models = {
        question : Backbone.DeepModel.extend({
            urlRoot : function() {
                if (this.get("id")) {
                    return "/module/" + module_id + "/item/" + model_id;
                } else {
                    return "/module/" + module_id + "/item/" + model_id + "?object=1";
                }
            }
        })
    };

    this.collections = {
        questions : Backbone.Collection.extend({
            initialize: function(opt) {
                this.lesson_id = opt.lesson_id;
                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("lesson_id", this.lesson_id);
                });
                this.listenTo(this, "remove", function(model, collection, opt) {
                });
            },
            model : mod.models.question,
            url: function() {
                return "/module/" + module_id + "/items/" + model_id + "/default/" + JSON.stringify({ lesson_id : this.lesson_id });
            },
            setOrder : function(order) {
                $.ajax(
                    "/module/" + module_id + "/items/" + model_id + "/set-order/" + this.lesson_id,
                    {
                        data: {
                            position: order
                        },
                        method : "PUT"
                    }
                );
            }
        })
    };


    app.module("crud.views.edit").on("start", function() {
        var self = this;

        mod.listenToOnce(this.getForm(), "form:rendered", function() {
            mod.start({
                testModel : self.itemModel
            });
        });
    });

});

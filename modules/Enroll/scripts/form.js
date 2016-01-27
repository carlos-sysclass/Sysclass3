$SC.module("views.enroll.form", function(mod, app, Backbone, Marionette, $, _) {

	this.startWithParent = false;

    mod.on("start", function(formView) {

        this.models = {
            fixed_grouping : Backbone.Model.extend({
                defaults : {
                    name: "",
                    start_date : "",
                    end_date : ""
                },
                urlRoot : function() {
                    if (this.get("id")) {
                        return "/module/roadmap/item/grouping";
                    } else {
                        return "/module/roadmap/item/grouping?redirect=0";
                    }
                }
            }),
            fields : Backbone.DeepModel.extend({
                urlRoot : function() {
                    if (this.get("id")) {
                        return "/module/enroll/item/fields";
                    } else {
                        return "/module/enroll/item/fields?object";
                    }
                }
            }),
        };

        this.collections = {
            fixed_grouping : Backbone.Collection.extend({}),
            fields : Backbone.Collection.extend({
                enroll_id : null,
                model : mod.models.fields,
                initialize : function() {
                    this.listenTo(this, "add", function(model) {
                        model.set("enroll_id", this.enroll_id);
                    }.bind(this));
                },
                setOrder : function(order) {
                    console.warn(order);

                    for(var i in order) {
                        var item = this.get(order[i]);
                        item.set("position", parseInt(i) + 1);
                        item.save();
                    }
                    this.sort();


                },
                comparator : function(first, last) {
                    if (first.get("position") < last.get("position")) {
                        return -1;
                    }
                    return 1;
                }
                /*
                url : function() {
                    if (_.isNull(this.enroll_id)) {
                        throw "enroll_id not defined";
                    } else {
                        return '/module/enroll/items/fields/default/' + JSON.stringify({enroll_id : this.enroll_id})
                    }
                }
                */
            }),
            model : this.models.fixed_grouping
        };

    	// TODO: MOVE THIS CLASS TO VIEW, ABSTRACTING IT
        this.roadmapBlockItemViewClass = Backbone.View.extend({
            dialogModule : app.module("dialogs.fixed_grouping.form"),
            events : {
                "click .edit-item-detail" : "editItem",
                "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
                "click .view-item-detail": "toogleDetail",
                "confirmed.bs.confirmation .delete-item-action" : "delete"
            },
            template : _.template($("#grouping-edit-item").html(), null, {variable: 'data'}),
            tagName : "li",
            className : "list-file-item draggable blue-stripe",
            initialize: function(opt) {
                console.info('blocks.roadmap.grouping/classLessonsView::initialize');

                this.opened = opt.opened ? opt.opened : false;

                this.listenTo(this.model, 'sync', this.render.bind(this));
            },
            render : function() {
                this.$el.html(this.template(this.model.toJSON()));
                if (this.model.get("id")) {
                    if (this.model.get("active") == 0) {
                        this.$el.removeClass("green-stripe");
                        this.$el.removeClass("blue-stripe");
                        this.$el.addClass("red-stripe");
                    } else {
                        this.$el.removeClass("red-stripe");
                        this.$el.removeClass("blue-stripe");
                        this.$el.addClass("green-stripe");
                    }
                }
                //this.$el.data("lessonId", this.model.get("id"));
                this.$el.attr("data-roadmap-grouping-id", this.model.get("id"));

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }

                this.trigger("grouping:updated", this.model);

                return this;
            },
            start : function() {

                var editableItem = this.$(".editable-me");

                if (this.opened) {
                    window.setTimeout(function() {
                        editableItem.editable('show');
                    }, 350);
                }
                /*
                editableItem.editable("option", "ajaxOptions", {
                    type: editableItem.data("method")
                });
                */
                var self = this;
                editableItem.on('save', function(e, params, b,c) {
                    self.model.set($(this).data("name"), params.newValue);
                    self.model.save();
                });

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }
            },
            editItem : function(e) {
                var self = this;

                if (!this.dialogModule.started) {
                    app.module("dialogs.fixed_grouping.form").start({
                        modelClass : mod.models.fixed_grouping
                    });
                }

                this.dialogModule.setModel(this.model).getValue(function(item, model) {
                    console.warn(item);
                    //self.addOne(model);
                    //self.refreshCounters();
                });
                /*
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
                */
            },
            toogleDetail : function(e) {
                e.preventDefault();
                this.$(".detail-container").toggle(500);
            },
            toogleActive : function(e, state) {
                this.model.set("active", state);
                this.model.save();
                this.render();
            },
            /*
            enable: function() {
                this.model.set("active", 1);
                this.model.save();
                this.render();
            },
            disable: function() {
                this.model.set("active", 0);
                this.model.save();
                this.render();
            },
            */
            delete: function() {
                this.model.destroy();
                this.trigger("grouping:removed", this.model);
                this.remove();
            }
        });

        // TODO: MOVE THIS CLASS TO VIEW, ABSTRACTING IT
        var listManagerCreatorItemViewClass = Backbone.View.extend({
            dialogModule : null,
            modelClass : Backbone.Model,
            template : null,
            events : {
                "click .edit-item-detail" : "editItem",
                "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
                "click .view-item-detail": "toogleDetail",
                "confirmed.bs.confirmation .delete-item-action" : "delete"
            },
            tagName : "li",
            className : "list-file-item draggable blue-stripe",
            initialize: function(opt) {
                console.info('views.enroll.form/fieldsItemBlockViewClass::initialize');

                this.opened = opt.opened ? opt.opened : false;

                this.listenTo(this.model, 'sync', this.render.bind(this));
            },
            editItem : function(e) {
                var self = this;

                if (!this.dialogModule.started) {
                    this.dialogModule.start({
                        modelClass : this.modelClass
                    });
                }
                console.warn(this.dialogModule)
                this.dialogModule.setModel(this.model).getValue(function(item, model) {
                    console.warn(item);
                    //self.addOne(model);
                    //self.refreshCounters();
                });
                /*
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
                */
            },
  
            toogleActive : function(e, state) {
                this.model.set("active", state);
                this.model.save();
                this.render();
            },
            /*
            enable: function() {
                this.model.set("active", 1);
                this.model.save();
                this.render();
            },
            disable: function() {
                this.model.set("active", 0);
                this.model.save();
                this.render();
            },
            */
            delete: function() {
                this.model.destroy();
                this.trigger("grouping:removed", this.model);
                this.remove();
            }
        });



        var baseFormClass = app.module("views").baseFormClass;

        this.fieldsItemBlockViewClass = baseFormClass.extend({
            dialogModule : app.module("dialogs.fields.form"),
            modelClass : mod.models.fields,
            template : _.template($("#enroll-fields-item").html(), null, {variable: 'model'}),
            events : function() {
                var events = baseFormClass.prototype.events.apply(this);
                events["click .view-item-detail"] = "toogleDetail";
                events["confirmed.bs.confirmation .delete-item-action"] = "deleteItem";

                return events;
            },
            tagName : "li",
            className : "list-file-item draggable blue-stripe",

            initialize: function(opt) {
                baseFormClass.prototype.initialize.apply(this, opt);
                this.listenTo(this.model, 'sync', function(model) {
                    
                }.bind(this));
                /*
                this.$el.delegate("[name='field_id']", "change", function(data) {
                    if (data.added) {
                        this.model.set("field", data.added, {silent : true});
                    }
                }.bind(this));
                */
            },
            start : function() {
                if (!this.model.get("id")) {
                    this.toogleDetail();
                }
            },
            toogleDetail : function(e) {
                if (e) {
                    e.preventDefault();
                }
                this.$(".view-item-detail i.fa").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
                this.$(".detail-container").toggle(500);
            },
            onBeforeRender : function(model) {
                console.info('views/fieldsItemBlockViewClass::onBeforeRender');
                this.oForm = null;
                this.$el.html(this.template(this.model.toJSON()));
                if (this.model.get("id")) {
                    if (this.model.get("active") == 0) {
                        this.$el.removeClass("green-stripe");
                        this.$el.removeClass("blue-stripe");
                        this.$el.addClass("red-stripe");
                    } else {
                        this.$el.removeClass("red-stripe");
                        this.$el.removeClass("blue-stripe");
                        this.$el.addClass("green-stripe");
                    }
                }
                //this.$el.data("lessonId", this.model.get("id"));
                this.$el.attr("data-roadmap-grouping-id", this.model.get("id"));

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }

                return this;
            },
            deleteItem : function() {
                this.model.destroy();
                this.remove();
            }
        });


    /*
        app.module("dialogs.fixed_grouping.form").start({
            modelClass : mod.models.fixed_grouping
        });
    */
        var listManagerCreatorViewClass = app.module("views").listManagerCreatorViewClass;

    	this.groupingFixedBlockViewClass = listManagerCreatorViewClass.extend({
            dialogModule : app.module("dialogs.fixed_grouping.form"),
            modelClass : mod.models.fixed_grouping,
            itemViewClass : this.roadmapBlockItemViewClass
        });

        this.fieldsBlockViewClass = listManagerCreatorViewClass.extend({
            dialogModule : app.module("fields.form"),
            modelClass : mod.models.fields,
            itemViewClass : this.fieldsItemBlockViewClass/*,
            initialize : function() {
                listManagerCreatorViewClass.prototype.initialize.apply(this);

                this.listenTo(this.collection, "add remove change", function() {
                    console.warn("changed");
                });

            }*/
        });

        this.enrollFormViewClass = Backbone.View.extend({
            initialize : function() {

                this.model.on("change:admittance_type", function(model, value) {
                    this.$(".admittance-type-item").addClass("hidden");
                    this.$(".admittance-type-item.admittance-type-" + value).removeClass("hidden");
                }.bind(this));

                this.model.on("change:interval_definition", function(model, value) {
                    this.$(".interval-definition-item").addClass("hidden");
                    this.$(".interval-definition-item.interval-definition-" + value).removeClass("hidden");
                }.bind(this));

                this.model.on("change:interval_rule_type", function(model, value) {
                    this.$(".interval-rule-type-item").addClass("hidden");
                    this.$(".interval-rule-type-item.interval-rule-type-" + value).removeClass("hidden");
                }.bind(this));

                this.groupingFixedBlockView = new mod.groupingFixedBlockViewClass({
                    el : "#fixed_grouping-create-container",
                    collection : new mod.collections.fixed_grouping()
                });

                if (formView.model.get("id")) {

                    this.listenToOnce(formView.model, "sync", function() {
                        this.fieldsCollection = new mod.collections.fields();

                        this.fieldsCollection.enroll_id = formView.model.get("id");

                        this.fieldsBlockView = new mod.fieldsBlockViewClass({
                            el : "#fields-create-container",
                            collection : this.fieldsCollection
                        });

                        console.warn(formView.model.get("enrollfields"));

                        this.fieldsCollection.reset(formView.model.get("enrollfields"));
                        console.warn(this.fieldsCollection.toJSON());
                    }.bind(this));
                }
            }
        });

        mod.enrollView = new this.enrollFormViewClass({
            el: "#form-enroll",
            model: formView.model
        })
    });

    $SC.module("crud.views.edit").on("start", function() {
    	if (!mod._isInitialized && this.getForm) {
           	mod.start(this.getForm());
        }
    });

    $SC.module("crud.views.add").on("start", function() {
    	if (!mod._isInitialized && this.getForm) {
    		mod.start(this.getForm());
    	}
    });
});

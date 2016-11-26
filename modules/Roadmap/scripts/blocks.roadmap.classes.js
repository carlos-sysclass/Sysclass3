$SC.module("blocks.roadmap.courses", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    mod.on("start", function(opt) {

        var baseModel = app.module("models").getBaseModel();

        mod.classModelClass = baseModel.extend({
            urlRoot : "/module/roadmap/item/course"
            /*function() {
                if (this.get("id")) {
                    ;
                } else {
                    return "/module/roadmap/item/course";
                }
            }
            */
        });
        mod.coursesCollectionClass = Backbone.Collection.extend({
            initialize: function(data, opt) {
                this.course_id = opt.course_id;
                this.period_id = opt.period_id;

                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("course_id", this.course_id);
                    model.set("period_id", this.period_id);
                });
                this.listenTo(this, "remove", function(model, collection, opt) {
                });
            },
            model : mod.classModelClass,
            url: function() {
                return "/module/roadmap/datasources/classes/default/" + JSON.stringify({ 'course_id' : this.course_id, 'period_id' : this.period_id });
            },
            setContentOrder : function(order) {
                $.ajax(
                    "/module/roadmap/datasources/classes/set-order/" + this.course_id,
                    {
                        data: {
                            position: order,
                            period_id : this.period_id
                        },
                        method : "PUT"
                    }
                );
            }
        });

        mod.periodModelClass = Backbone.Model.extend({
            defaults : {
                name: "",
                max_classes : -1,
                active: true
            },
            urlRoot : function() {
                if (this.get("id")) {
                    return "/module/roadmap/item/periods";
                } else {
                    return "/module/roadmap/item/periods?object=1";
                }
            }
        });
        mod.periodsCollectionClass = Backbone.Collection.extend({
            initialize: function(opt) {
                this.course_id = opt.course_id;
                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("course_id", this.course_id);
                });
                this.listenTo(this, "remove", function(model, collection, opt) {
                    //console.warn(model);
                });
            },
            model : mod.periodModelClass,
            url: function() {
                return "/module/roadmap/items/periods/default/" + JSON.stringify({ course_id : this.course_id });
            },
            setContentOrder : function(order) {
                $.ajax(
                    "/module/roadmap/datasources/periods/set-order/" + this.course_id,
                    {
                        data: {
                            position: order
                        },
                        method : "PUT"
                    }
                );
            }
        });

        var baseFormClass = app.module("views").baseFormClass;
        mod.periodAddDialogClass = baseFormClass.extend({
            initialize : function(opt) {
                baseFormClass.prototype.initialize.apply(this);

                this.$el.modal({
                    show : false
                });
            },
            open : function() {
                this.$el.modal('show');
            },
            close : function() {
                this.$el.modal('hide');
            }
        });

        mod.periodAddDialog = null;

        mod.roadmapBlockClassItemViewClass = Backbone.View.extend({
            events : {
                "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
                "click .view-item-detail": "toogleDetail",
                "confirmed.bs.confirmation .delete-item-action" : "delete"
            },
            template : _.template($("#classes-edit-item").html(), null, {variable: 'data'}),
            tagName : "li",
            className : "list-file-item draggable blue-stripe",
            invalidated : true,
            initialize: function(opt) {
                console.info('blocks.roadmap.courses/roadmapBlockClassItemViewClass::initialize');
                this.opened = opt.opened ? opt.opened : false;
                //this.listenTo(this.model, 'sync', this.render.bind(this));
            },
            render : function() {
                //if (this.invalidated || !this.model.get("id")) {
                    this.$el.html(this.template(this.model.toJSON()));




                    if (this.model.get("id")) {
                        if (this.model.get("active") === 0) {
                            this.$el.removeClass("green-stripe");
                            this.$el.removeClass("blue-stripe");
                            this.$el.addClass("red-stripe");
                        } else {
                            this.$el.removeClass("red-stripe");
                            this.$el.removeClass("blue-stripe");
                            this.$el.addClass("green-stripe");
                        }
                    }
                    this.$el.attr("data-roadmap-class-id", this.model.get("id"));

                    if (this.$el.length) {
                        app.module("ui").refresh(this.$el);
                    }

                    mod.trigger("lesson:updated", this.model);

                    this.$el.data("view", this);
                    /*                    
                    if (this.invalidated && this.$el.closest(document.documentElement)) {
                        //this.start();
                    }
                    */
                    this.invalidated = false;
                //

                return this;
            },
            start : function() {

                var editableItem = this.$(".editable-me");

                if (this.opened) {
                    window.setTimeout(function() {
                        editableItem.editable('show');
                    }, 350);
                }

                var self = this;
                editableItem.on('save', function(e, params, b,c) {

                    self.model.set($(this).data("name"), params.newValue);
                    if (!self.model.get("id")) {
                        self.invalidated = true;
                    }
                    self.model.save(null, {
                        success : function() {
                            if (self.invalidated) {
                                self.invalidated = false;
                                self.render();
                                self.opened = false;
                                self.start();
                            }
                        }
                    });

                });

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }
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
            delete: function() {
                this.model.destroy();
                this.trigger("lesson:removed", this.model);
                this.remove();
            }
        });

        mod.roadmapBlockCoursesViewClass = Backbone.View.extend({
            events : {
                "click .add-item-action" : "addItem"
            },
            initialize : function(opt) {
                //this.listenToOnce(this.collection, 'sync', this.render.bind(this));
                this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
                this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));

                this.initializeSortable();
            },
            initializeSortable : function() {
                var self = this;

                this.$(".list-group").sortable({
                    items: "li.list-file-item.draggable",
                    connectWith: ".list-group",
                    opacity: 0.8,
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    receive : function( event, ui ) {
                        console.info('blocks.roadmap.courses/roadmapBlockCoursesViewClass::sortable->receive');

                        var view = $(ui.item).data("view");

                        self.collection.add(view.model);
                        view.model.save();
                        $(this).removeClass("empty-list-group");
                    },
                    remove : function( event, ui ) {
                        console.info('blocks.roadmap.courses/roadmapBlockCoursesViewClass::sortable->remove');
                        var id = $(ui.item).data("roadmapClassId");
                        self.collection.remove(id);
                    },

                    update : function( event, ui ) {
                        var contentOrder = $(this).sortable("toArray", {attribute : "data-roadmap-class-id"});

                        self.collection.setContentOrder(contentOrder);

                        self.refreshCounters();
                    }
                });
            },
            addItem : function(e) {
                console.info('blocks.roadmap.courses/roadmapBlockCoursesViewClass::addItem');
                var self = this;

                var itemModel = new mod.classModelClass(null);

                this.collection.add(itemModel);

                var roadmapBlockClassItemView = new mod.roadmapBlockClassItemViewClass({
                    model : itemModel,
                    opened : true
                });

                this.listenTo(mod, "lesson:updated", function(model) {
                    self.refreshCounters();
                });

                $(roadmapBlockClassItemView.render().el).appendTo( this.$("ul.list-group") );
                roadmapBlockClassItemView.start();
            },
            addOne : function(model, i) {
                console.info('blocks.roadmap.courses/roadmapBlockCoursesViewClass::addOne');

                var self = this;


                var roadmapBlockClassItemView = new mod.roadmapBlockClassItemViewClass({
                    model : model
                });

                $(roadmapBlockClassItemView.render().el).appendTo(this.$("ul.list-group"));
                roadmapBlockClassItemView.start();

                this.listenTo(mod, "lesson:updated", function(model) {
                    self.refreshCounters();
                });

            },
            refreshCounters : function() {
                console.info('blocks.roadmap.courses/roadmapBlockCoursesViewClass::refreshCounters');
                var total = this.collection.size();
                //alert(total);
                this.$("ul > li.list-file-item .total").html(total);

                this.$("ul > li.list-file-item").each(function(i, item) {
                    $(this).find(".counter").html(i+1);
                });
            },
            render: function() {
                console.info('blocks.roadmap.courses/roadmapBlockCoursesViewClass::render');

                var self = this;

                this.collection.each(function(model, i) {
                    self.addOne(model, i);
                });
                if (this.collection.size() === 0) {
                    this.$("ul.list-group").addClass("empty-list-group");
                } else {
                    this.$("ul.list-group").removeClass("empty-list-group");
                }

                this.refreshCounters();
            },
            remove : function(e) {
                var fileId = $(e.currentTarget).data("fileId");
                var fileObject = new mod.lessonFileModelClass();
                fileObject.set("id", fileId);
                fileObject.destroy();
                $(e.currentTarget).parents("li").remove();
            }
        });

        mod.roadmapBlockPeriodItemViewClass = Backbone.View.extend({
            events : {
                "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toggleActive",
                "click .toogle-visible-item" : "toggleVisible",
                "confirmed.bs.confirmation .delete-item-action" : "delete"
            },
            template : _.template($("#period-edit-item").html(), null, {variable: 'data'}),
            tagName : "div",
            className : "panel panel-default period-item draggable",
            initialize: function(opt) {
                var self = this;
                console.info('blocks.roadmap.courses/roadmapBlockPeriodItemViewClass::initialize');

                this.opened = opt.opened ? opt.opened : false;

                this.listenTo(this.model, 'sync', this.render.bind(this));

                console.warn('roadmapBlockPeriodItemViewClass.model', this.model.toJSON());

                // CREATE A COLLECTION BASED ON MODEL courses attribute
                this.collection = new mod.coursesCollectionClass(
                    this.model.get("courses"),
                    {
                        course_id : this.model.get("course_id"),
                        period_id : _.isUndefined(this.model.get("id")) ? null : this.model.get("id")
                    }
                );
                this.listenTo(this.collection, "add", function(model, collection, options) {
                    self.model.set("courses", collection.toJSON());
                });
                this.listenTo(this.collection, "remove", function(model, collection, options) {
                    self.model.set("courses", collection.toJSON());
                });
            },
            render : function() {
                console.info('blocks.roadmap.courses/roadmapBlockPeriodItemViewClass::render');
                this.$el.html(this.template(this.model.toJSON()));

                // RENDER SUBVIEWS
                var roadmapBlockCoursesView = new mod.roadmapBlockCoursesViewClass({
                    el: this.$(".subitems-container"),
                    collection : this.collection
                });

                roadmapBlockCoursesView.render();

                this.$el.attr("data-roadmap-period-id", this.model.get("id"));

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }

                this.trigger("period:updated", this.model);

                return this;
            },
            start : function() {
                var editableItem = this.$(".panel-heading > .editable-me");

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

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }
            },
            toggleVisible : function(e) {
                e.preventDefault();
                this.$(".subitems-container").toggle(500);
                $(e.currentTarget).find("i.fa").toggleClass("fa-angle-up").toggleClass("fa-angle-down");
            },
            toggleActive : function(e, state) {
                this.model.set("active", state);
                this.model.save();
                this.render();
            },
            delete: function() {
                this.model.destroy();
                mod.trigger("period:removed", this.model);
                this.remove();
            }
        });

        mod.roadmapBlockViewClass = Backbone.View.extend({
            fakePeriodModel : null,
            fakePeriodView : null,
            events : {
                "click .add-block-action" : "addItem"/*,
                "click .add-item-action" : "addItem"*/
            },
            initialize : function(opt) {

                this.listenToOnce(this.collection, 'sync', this.render.bind(this));
                //this.listenTo(this.collection, 'add', this.addOne.bind(this));
                this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
                this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));

                this.listenTo(opt.coursesCollection, 'sync', this.renderNoPeriod.bind(this));

                this.listenTo(mod, 'period:removed', function() {
                    opt.coursesCollection.fetch();
                });

                this.initializeSortable();
            },
            initializeSortable : function() {
                var self = this;
                this.$(".items-container").sortable({
                    items: ".period-item.draggable",
                    //connectWith: ".list-group",
                    handle: ".drag-handler",
                    opacity: 0.8,
                    placeholder: 'draggable placeholder placeholder-minimal',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    //forcePlaceholderSize: true,
                    tolerance: "intersect",

                    update : function( event, ui ) {
                        var contentOrder = $(this).sortable("toArray", {attribute : "data-roadmap-period-id"});

                        self.collection.setContentOrder(contentOrder);

                        self.refreshCounters();
                    }
                });
            },
            addItem : function(e) {
                console.info('blocks.roadmap.courses/roadmapBlockViewClass::addItem');

                var self = this;
                var itemModel = new mod.periodModelClass({
                    course_id : this.model.get("id")
                });

                this.listenToOnce(itemModel, "sync", function(model) {
                    self.collection.add(itemModel);
                    self.addOne(model);
                    //self.refreshCounters();
                });

                if (_.isNull(mod.periodAddDialog)) {
                    mod.periodAddDialog = new mod.periodAddDialogClass({
                        el : "#roadmap-period-dialog-modal",
                        model : itemModel
                    });
                } else {
                    mod.periodAddDialog.setModel(itemModel);

                }
                mod.periodAddDialog.render();

                mod.periodAddDialog.open();
            },
            addOne : function(model, prefixSelector) {
                console.info('blocks.roadmap.courses/roadmapBlockViewClass::addOne');

                var self = this;
                // GET ONLY THIS PERIOD COURSES

                var roadmapBlockClassItemView = new mod.roadmapBlockPeriodItemViewClass({
                    model : model
                });

                var container = this.$(".items-container");
                if (!_.isUndefined(prefixSelector)) {
                    container = this.$(prefixSelector);
                }

                $(roadmapBlockClassItemView.render().el).appendTo(container);
                roadmapBlockClassItemView.start();

                self.refreshCounters();

                this.listenTo(roadmapBlockClassItemView, "period:updated", function(model) {
                    self.refreshCounters();
                });

                return roadmapBlockClassItemView;
            },
            refreshCounters : function() {
                console.info('blocks.roadmap.courses/roadmapBlockViewClass::refreshCounters');
                var total = this.collection.size();
                this.$(".items-container > div.period-item .period-total").html(total);

                this.$(".items-container > div.period-item").each(function(i, item) {
                    $(this).find(".period-counter").html(i+1);
                });
            },
            renderNoPeriod : function(collection) {
                this.fakePeriodModel = new mod.periodModelClass({
                    name : "Other Courses",
                    course_id : this.model.get("id"),
                    courses : collection.toJSON()
                });
                if (!_.isNull(this.fakePeriodView)) {
                    this.fakePeriodView.remove();
                }
                this.fakePeriodView = this.addOne(this.fakePeriodModel, ".no-period-container");
            },
            render: function() {
                console.info('blocks.roadmap.courses/roadmapBlockViewClass::render');

                var self = this;

                this.collection.each(function(model, i) {
                    self.addOne(model);
                });

                app.module("ui").refresh( this.$(".items-container ") );

                this.refreshCounters();
            },
            remove : function(e) {
                var fileId = $(e.currentTarget).data("fileId");
                var fileObject = new mod.lessonFileModelClass();
                fileObject.set("id", fileId);
                fileObject.destroy();
                $(e.currentTarget).parents("li").remove();
            }
        });

        $("[data-widget-id='roadmap-classes-widget']").each(function() {
            var course_id = null;
            if (_.isEmpty($(this).data("courseId"))) {
                // LOAD COURSE MODEL TO BY-PASS
                course_id = opt.courseModel.get("id");
            } else {
                course_id = $(this).data("courseId");
            }

            if (!_.isNull(course_id)) {
                mod.createBlock(this, {
                    course_id : course_id,
                    courseModel : opt.courseModel
                });
            }
        });


    });

    mod.createBlock = function(el, data) {
        var coursesCollection = new mod.coursesCollectionClass([], {
            course_id : data.course_id,
            period_id : null
        });

        var periodsCollection = new mod.periodsCollectionClass({
            course_id : data.course_id
        });

        var roadmapBlockView = new mod.roadmapBlockViewClass({
            el : el,
            collection : periodsCollection,
            coursesCollection : coursesCollection,
            model : data.courseModel
        });



        coursesCollection.fetch();
        periodsCollection.fetch();

        mod.periodsCollection = periodsCollection;
        mod.coursesCollection = coursesCollection;
    };

    app.module("crud.views.edit").on("start", function() {
        var self = this;
        //console.warn(this.getForm());

        mod.listenToOnce(this.getForm(), "form:rendered", function() {
            mod.start({
                courseModel : self.itemModel
            });
        });
    });
});

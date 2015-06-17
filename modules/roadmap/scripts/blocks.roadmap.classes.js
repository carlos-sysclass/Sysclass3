$SC.module("blocks.roadmap.classes", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    mod.on("start", function(opt){

        mod.classModelClass = Backbone.DeepModel.extend({
            urlRoot : function() {
                if (this.get("id")) {
                    return "/module/roadmap/item/classes";
                } else {
                    return "/module/roadmap/item/classes?redirect=0";
                }
            }
        });

        mod.classesCollectionClass = Backbone.Collection.extend({
            initialize: function(opt) {
                this.course_id = opt.course_id;
                this.listenTo(this, "add", function(model, collection, opt) {
                    model.set("course_id", this.course_id);
                });
                this.listenTo(this, "remove", function(model, collection, opt) {
                    //console.warn(model);
                });
            },
            model : mod.classModelClass,
            url: function() {
                return "/module/roadmap/items/classes/default/" + JSON.stringify({ course_id : this.course_id });
            },
            setContentOrder : function(order) {
                $.ajax(
                    "/module/roadmap/items/classes/set-order/" + this.course_id,
                    {
                        data: {
                            position: order
                        },
                        method : "PUT"
                    }
                );
            }
        });

        mod.roadmapBlockClassItemViewClass = Backbone.View.extend({
            events : {
                "switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
                "click .view-item-detail": "toogleDetail",
                "confirmed.bs.confirmation .delete-item-action" : "delete"
            },
            template : _.template($("#classes-edit-item").html(), {variable: 'data'}),
            tagName : "li",
            className : "list-file-item draggable blue-stripe",
            initialize: function(opt) {
                console.info('blocks.classes.lessons.edit/classLessonsView::initialize');

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
                this.$el.attr("data-roadmap-class-id", this.model.get("id"));

                if (this.$el.length) {
                    app.module("ui").refresh(this.$el);
                }

                this.trigger("lesson:updated", this.model);

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
                    self.model.save();
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


        mod.roadmapBlockViewClass = Backbone.View.extend({
            events : {
                "click .add-item-action" : "addItem"
            },
            initialize : function() {
                this.listenToOnce(this.collection, 'sync', this.render.bind(this));
                this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
                this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));

                this.initializeSortable();
            },
            initializeSortable : function() {
                var self = this;

                this.$(".list-group").sortable({
                    items: "li.list-file-item.draggable",
                    opacity: 0.8,
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    update : function( event, ui ) {
                        var contentOrder = $(this).sortable("toArray", {attribute : "data-roadmap-class-id"});

                        self.collection.setContentOrder(contentOrder);

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
            addItem : function(e) {
                var self = this;

                var itemModel = new mod.classModelClass(null);

                self.collection.add(itemModel);

                var roadmapBlockClassItemView = new mod.roadmapBlockClassItemViewClass({
                    model : itemModel,
                    opened : true
                });

                this.listenTo(roadmapBlockClassItemView, "lesson:updated", function(model) {
                    self.refreshCounters();
                });

                $(roadmapBlockClassItemView.render().el).appendTo( this.$("ul.items-container") );
                roadmapBlockClassItemView.start();
            },
            addOne : function(model, i) {
                console.info('blocks.roadmap/classLessonsView::addOne');

                var self = this;


                var roadmapBlockClassItemView = new mod.roadmapBlockClassItemViewClass({
                    model : model
                });

                this.listenTo(roadmapBlockClassItemView, "lesson:updated", function(model) {
                    self.refreshCounters();
                });


                $(roadmapBlockClassItemView.render().el).appendTo(this.$("ul.items-container"));
                roadmapBlockClassItemView.start();
            },
            refreshCounters : function() {
                console.info('blocks.roadmap/classLessonsView::refreshCounters');
                var total = this.collection.size();
                this.$("ul.items-container > li.list-file-item .total").html(total);

                this.$("ul.items-container > li.list-file-item").each(function(i, item) {
                    $(this).find(".counter").html(i+1);
                });
            },
            render: function() {
                console.info('blocks.roadmap/classLessonsView::render');

                var self = this;

                this.collection.each(function(model, i) {
                    self.addOne(model, i);
                });
                //this.refreshCounters();
                app.module("ui").refresh( this.$("ul.items-container ") );
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
        var classesCollection = new mod.classesCollectionClass({
            course_id : data.course_id
        });

        // CREATE A VIEW CLASS TO:
        // 1. SHOW CURRENT CLASSES FROM COURSE, WITH ADD, EDIT, AND REMOVE FROM COURSE
        // 2. IF IT HAS model->has_periods == TRUE,
        // 2.1 ENABLE periods VIEW (sortable for put classes inside periods)
        // 2.2 IF NOT, JUST A SINGLE SORTABLE CLASS LIST
        //
        // FOR EACH CLASS IT'S MUST BE POSSIBLE TO:
        // 1. CHANGE ORDER
        // 2. SET AS REQUIRED OR OPCIONAL
        // 3. SET PRE-REQUISITES (MAYBE ON CLASS MODULE ITSELF)
        //
        // 2. IF IT HAS model->has_grouping == TRUE, (WILL BE MOVED T ANOTHER PAGE, FOR SIMPLICITY)
        // 2.1 ENABLE grouping period SWITCH (multiple roadmaps, grouping selector AND grouping creator)
        // 2.2 IF NOT, JUST A SINGLE SORTABLE CLASS LIST

        // var classLessonsView = new mod.classLessonsViewClass({
        //     collection : lessonsCollection,
        //     el : el
        // });

        var roadmapBlockView = new mod.roadmapBlockViewClass({
            el : el,
            collection : classesCollection,
            model : data.courseModel
        });

        classesCollection.fetch();
    };

    app.module("crud.views.edit").on("start", function() {
        var self = this;

        mod.listenToOnce(this.getForm(), "form:rendered", function() {
            mod.start({
                courseModel : self.itemModel
            });
        });
    });
});

$SC.module("blocks.roadmap", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    mod.on("start", function(opt){

    });
    /*
    mod.on("start", function(opt){
        // do stuff after the module has been started
        this.course_id = opt.course_id;

        var classesCollectionClass = mod.classesCollectionClass; // @todo think about move out from here
        this.classesCollection = new classesCollectionClass();

        var seasonsCollectionClass = mod.seasonsCollectionClass; // @todo think about move out from here
        this.seasonsCollection = new seasonsCollectionClass();

        this.courseRoadmapTabView = new mod.courseRoadmapTabViewClass({
            el : "#block_roadmap",
            //collection : this.seasonsCollection,
            collections : {
                seasons     : this.seasonsCollection,
                classes     : this.classesCollection
            }
        });

        //this.courseRoadmapTabView.setCourseID();
        this.seasonsCollection.course_id = this.course_id;
        this.seasonsCollection.fetch();

        this.classesCollection.course_id = this.course_id;
        this.classesCollection.fetch();
    });

    app.module("crud.config").on("start", function() {
        var config = this.getConfig();

        mod.start({
            course_id : parseInt(config.entity_id)
        });
    });

    // PRIVATE MODELS
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

    mod.seasonsModelClass = Backbone.NestedAttributesModel.extend({
        defaults : {
            "id"        : "all",
            "active"    : 1,
            "course_id" : null,
            "name"      : "All other classes",
            "classes"   : []
        }
    });


    mod.seasonsCollectionClass = Backbone.Collection.extend({
        course_id : 0,
        datatable : false,
        model : mod.seasonsModelClass,

        url : function() {
            var url = "/module/roadmap/items/seasons/:type/:courses/";
            url = url.replace(":courses", JSON.stringify(this.course_id));
            if (this.datatable) {
                url = url.replace(":type", 'datatable');
            } else {
                url = url.replace(":type", 'default');
            }
            return url;
        }
    });


    // PRIVATE VIEWS
    mod.groupingAddDialogClass = Backbone.View.extend({
        initialize : function(opt) {
            this.$el.modal({
                show : false
            });
            this.$("form[data-validate='true']").on("validate:submit", this.save.bind(this));
        },
        save : function(e) {
        },
        open : function() {
            this.$el.modal('show');

        },
        close : function() {
            this.$el.modal('hide');
        }
    });


    mod.seasonAddDialogClass = Backbone.View.extend({
        initialize : function(opt) {
            this.$el.modal({
                show : false
            });
            this.$("form[data-validate='true']").on("validate:submit", this.save.bind(this));
        },
        save : function(e) {
            e.preventDefault();

            var data = {};

            this.$("form[data-validate='true'] :input").each(function() {
                data[$(this).attr("name")] = $(this).val();
            });


            var modelClass = Backbone.Model.extend({
              urlRoot : "/module/roadmap/item/season"
            });

            var model = new modelClass();

            model.set("course_id", mod.course_id);
            model.set("name", data.name);
            model.set("active", data.active);

            var self = this;

            model.save(null, {
                success : function() {
                    self.collection.add(model);
                    self.close();
                }
            });

        },
        open : function() {
            this.$el.modal('show');

        },
        close : function() {
            this.$el.modal('hide');
        }
    });
    mod.classAddDialogClass = Backbone.View.extend({
        initialize : function(opt) {
            this.$el.modal({
                show : false
            });
            this.$("form[data-validate='true']").on("validate:submit", this.save.bind(this));
        },
        save : function(e) {
            e.preventDefault();
            var data = $(":input[name='class']").select2('data');

            var modelClass = Backbone.Model.extend({
              urlRoot : "/module/roadmap/item/class/" + mod.course_id
            });

            var model = new modelClass();

            model.set("course_id", mod.course_id);
            model.set("name", data['name']);
            model.set("lesson_id", data['id']);

            var self = this;

            model.save(null, {
                success : function() {
                    self.collection.add(model);
                    self.close();
                }
            });
        },
        open : function() {
            this.$el.modal('show');

        },
        close : function() {
            this.$el.modal('hide');
        }
    });

    mod.courseRoadmapTabSeasonViewClass = Backbone.View.extend({
        template : _.template($("#tab_roadmap-season-template").html()),
        className : "panel panel-default",
        initialize : function() {
            if (_.isUndefined(this.model)) {
                this.model = mod.seasonsModelClass();
                if (!_.isUndefined(this.collection)) {
                    this.model.set("classes", this.collection);
                }
            }
        },
        render : function() {
            console.info('blocks.roadmap/courseRoadmapTabSeasonViewClass::render');

            var modelData = this.model.toJSON();

            this.$el.empty().append(this.template(
                modelData
            ));

            var self = this;

            this.$(".list-group").sortable({
                connectWith: ".list-group",
                items: "li.list-group-item",
                opacity: 0.8,
                // axis : "y",
                placeholder: 'list-group-item list-group-item btn btn-block btn-default',
                dropOnEmpty : true,
                forceHelperSize : true,
                forcePlaceholderSize: true,
                tolerance: "intersect",
                // helper : 'original',
                receive : function( event, ui ) {
                    $(this).removeClass("empty-list-group");
                    self.refreshCounters();
                    // HANDLE COLLECTIONS
                    var classe_id = ui.item.data('classId');

                    var item = mod.classesCollection.findWhere({id : classe_id.toString()});

                    var classes = self.model.get('classes');

                    classes.push(item.toJSON());
                    self.model.set('classes', classes);
                },
                remove : function( event, ui ) {
                    if ($(this).children().size() == 0) {
                        $(this).addClass("empty-list-group");
                    }
                    self.refreshCounters();

                    // HANDLE COLLECTIONS
                    var classe_id = ui.item.data('classId');

                    var classes = self.model.get('classes');

                    remainingClasses = _.filter(classes, function(model, i) {
                        if (model.id == classe_id) {
                            return false;
                        }
                        return true;
                    });

                    self.model.set('classes', remainingClasses);
                },
                over : function( event, ui ) {
                    $(this).addClass("ui-sortable-hover");
                },
                out  : function( event, ui ) {
                    $(this).removeClass("ui-sortable-hover");
                },
            });

            return this;
        },
        refreshCounters : function() {
            this.$(".size-counter").html(
                this.$(".list-group").children().size()
            );
        }
    });

    mod.courseRoadmapTabViewClass = Backbone.View.extend({
        //portlet: $('#courses-widget'),
        events : {
            "click .roadmap-add-grouping" : "openNewGroupingDialog",
            "click .roadmap-add-season" : "openNewSeasonDialog",
            "click .roadmap-add-class" : "openNewClassDialog"
        },
        seasonsSynced   : false,
        classesSynced   : false,
        groupingAddDialog : null,
        seasonAddDialog : null,
        classesAddDialog : null,
        noSeasonModel : null,
        className : "teste",

        initialize: function(opt) {
            console.info('blocks.roadmap/courseRoadmapTabViewClass::initialize');

            this.collections = opt.collections;

            var self = this;

            this.$('#block_roadmap-accordion').on('shown.bs.collapse', function (e) {
                self.$("a[href='#" + e.target.id + "']").find("i").removeClass("fa fa-plus-square").addClass("icon-minus");
            });
            this.$('#block_roadmap-accordion').on('hidden.bs.collapse', function (e) {
                self.$("a[href='#" + e.target.id + "']").find("i").removeClass("icon-minus").addClass("fa fa-plus-square");
            });

            this.listenTo(this.collections.seasons, 'request', (function() {
                this.seasonsSynced = false;
            }).bind(this));

            this.listenTo(this.collections.classes, 'request', (function() {
                this.classesSynced = false;
            }).bind(this));

            this.listenTo(this.collections.seasons, 'sync', this.renderSeasons.bind(this));
            this.listenTo(this.collections.classes, 'sync', this.renderClasses.bind(this));

            this.initializeSortable();

            //this.$el.nestable();
        },
        initializeSortable : function() {
            this.$(".list-group").sortable({
                connectWith: ".list-group",
                items: "li.list-group-item",
                opacity: 0.8,
                // axis : "y",
                placeholder: 'list-group-item list-group-item btn btn-block btn-default',
                dropOnEmpty : true,
                forceHelperSize : true,
                forcePlaceholderSize: true,
                tolerance: "intersect",
                // helper : 'original',
                receive : function( event, ui ) {
                    $(this).removeClass("empty-list-group");
                    self.refreshCounters();
                    // HANDLE COLLECTIONS
                    var classe_id = ui.item.data('classId');

                    var item = mod.classesCollection.findWhere({id : classe_id.toString()});

                    var classes = self.model.get('classes');

                    classes.push(item.toJSON());
                    self.model.set('classes', classes);
                },
                remove : function( event, ui ) {
                    if ($(this).children().size() == 0) {
                        $(this).addClass("empty-list-group");
                    }
                    self.refreshCounters();

                    // HANDLE COLLECTIONS
                    var classe_id = ui.item.data('classId');

                    var classes = self.model.get('classes');

                    remainingClasses = _.filter(classes, function(model, i) {
                        if (model.id == classe_id) {
                            return false;
                        }
                        return true;
                    });

                    self.model.set('classes', remainingClasses);
                },
                over : function( event, ui ) {
                    $(this).addClass("ui-sortable-hover");
                },
                out  : function( event, ui ) {
                    $(this).removeClass("ui-sortable-hover");
                },
            });

        },
        openNewGroupingDialog : function() {
            if (_.isNull(this.groupingAddDialog)) {
                this.groupingAddDialog = new mod.groupingAddDialogClass({
                    el : "#roadmap-grouping-dialog-modal"
                });
            }
            this.groupingAddDialog.open();
        },
        openNewSeasonDialog : function() {
            if (_.isNull(this.seasonAddDialog)) {
                this.seasonAddDialog = new mod.seasonAddDialogClass({
                    collection : this.collections.seasons,
                    el : "#roadmap-season-dialog-modal"
                });
            }
            this.seasonAddDialog.open();
        },
        openNewClassDialog : function() {
            if (_.isNull(this.classesAddDialog)) {
                this.classesAddDialog = new mod.classAddDialogClass({
                    collection : this.collections.classes,
                    el : "#roadmap-class-dialog-modal"
                });
            }
            this.classesAddDialog.open();
        },
        renderSeasons : function(e) {
            this.seasonsSynced = true;
            this.render();

            this.listenTo(this.collections.seasons, 'add', this.addSeason.bind(this));
        },

        renderClasses : function(e) {
            this.classesSynced = true;
            this.render();

            this.listenTo(this.collections.classes, 'add', this.addClass.bind(this));
        },
        addSeason : function(seasonModel) {
            // FILTER CLASSES TO RETURN ONLY CLASSES IN THAT SEASON

            //var courseRoadmapTabSeasonView = new mod.courseRoadmapTabSeasonViewClass({model : seasonModel, collection : classesArray});
            var courseRoadmapTabSeasonView = new mod.courseRoadmapTabSeasonViewClass({model : seasonModel });
            self.$("#block_roadmap-accordion").append(courseRoadmapTabSeasonView.render().el);
        },
        addClass : function(classModel) {
            //self.$("#block_roadmap-all_lessons-accordion").empty().append(this.courseRoadmapTabNoSeasonView.render().el);
        },
        render : function() {
            console.info('blocks.roadmap/courseRoadmapTabViewClass::render');
            if (this.seasonsSynced && this.classesSynced) {
                this.$("#block_roadmap-accordion").empty();
                // ORDER LESSONS BY SEMESTER
                //this.$el.empty();
                var self = this;

                if (this.collections.seasons.size() > 0) {
                    this.collections.seasons.each(this.addSeason.bind(this));
                } else {

                }
                // SHOW THE CLASSES BACKLOGS
                // GET ALL CLASSES WITH SEASONS AND SHOW IN THE "BACKLOG"

                if (_.isNull(this.noSeasonModel)) {
                    this.noSeasonModel = new mod.seasonsModelClass();
                    this.courseRoadmapTabNoSeasonView = new mod.courseRoadmapTabSeasonViewClass({model : this.noSeasonModel});
                }

                var allSeasonClassesIds = this.collections.seasons.pluck("classes");
                allSeasonClassesIds = _.pluck(_.flatten(allSeasonClassesIds), "id");

                var noSeasonClasses = [];

                this.collections.classes.each(function(classModel, i) {
                    if (!_.contains(allSeasonClassesIds, classModel.get("id"))) {
                        noSeasonClasses.push(classModel.toJSON());
                    }
                });

                this.noSeasonModel.set("classes", noSeasonClasses);

                self.$("#block_roadmap-all_lessons-accordion").empty().append(this.courseRoadmapTabNoSeasonView.render().el);

            }
        }
    });
    */
    app.module("crud.views.edit").on("start", function() {
        var self = this;
        mod.listenToOnce(this.getForm(), "form:rendered", function() {
            mod.start();
        });
    });
});

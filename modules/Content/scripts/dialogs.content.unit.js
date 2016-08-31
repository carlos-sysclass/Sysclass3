$SC.module("dialogs.content.unit", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;
    /*
    this.getValue = function(callback) {
        //this.dialogView.setModel(new mod.modelClass());
        this.dialogView.open();

        this.stopListening(this.tableView);
        this.listenTo(this.tableView, "action.datatables", function(el, data, action, evt) {
            if (evt) {
                evt.preventDefault();
            }
            
            if (action == "select") {
                this.stopListening(this.tableView);
                callback(data);
                this.dialogView.close();
            }
        }.bind(this));
    };

    this.setFilter = function(filter) {
        // FILTER DATATABLE
        this.filter = filter;
        //var url = "/module/questions/items/lesson-content/datatable/" + JSON.stringify(this.filter);
        var url = "/module/questions/items/lesson-content/datatable";
        this.tableView.setUrl(url);

        return this;
    };
    */

    mod.open = function() {
        mod.dialogView.open();
    };
    mod.close = function() {
        mod.dialogView.close();
    };

    mod.setInfo = function(info) {
        console.warn(info.model);
        this.dialogView.setModel(info.model);
        this.dialogView.setCollection(info.collection);

        this.dialogView.render();

        return this; // for chaining
    };

    var baseChangeModelViewClass = app.module("views").baseChangeModelViewClass;
    var unitVideoTabViewClass = baseChangeModelViewClass.extend({
        videoJS : null,
        nofoundTemplate : _.template($("#tab_unit_video-nofound-template").html()),
        template : _.template($("#tab_unit_video-item-template").html(), null, {variable: "model"}).bind(this),
        initialize: function(opt) {
            console.info('portlet.content/unitVideosTabViewClass::initialize');

            //this.listenTo(mod.programsCollection, "unit.changed", this.setModel.bind(this));
        },
        render : function(e) {
            console.info('portlet.content/unitVideosTabViewClass::render');
            var self = this;

            if (!this.model.get("video")) {
                // THERE'S NO VIDEO LESSON... DISABLE THE VIEW
                this.disableView();
            } else {
                this.enableView();

                this.videoModel = this.model.get("video");

                if (!_.isNull(this.videoJS)) {
                    this.videoJS.dispose();
                }

                if (this.videoModel) {
                    var videoDomID = "unit-video-" + this.videoModel.get("id");

                    if (this.$("#" + videoDomID).size() === 0) {
                        this.$el.empty().append(
                            this.template(this.videoModel.toJSON())
                        );

                        //var videoData = _.pick(entityData["data"], "controls", "preload", "autoplay", "poster", "techOrder", "width", "height", "ytcontrols");
                        videojs(videoDomID, {
                            "controls": true,
                            "autoplay": false,
                            "preload": "auto",
                            "width" : "auto",
                            "height" : "auto",
                            "techOrder" : [
                                'html5', 'flash'
                            ]
                        }, function() {
                            //this.play();
                        });
                    }

                    this.videoJS = videojs(videoDomID);

                    this.videoJS.ready(this.bindStartVideoEvents.bind(this));

                    mod.videoJS = this.videoJS;
                }

                app.module("ui").refresh(this.$el);
            }
        },
        bindStartVideoEvents : function() {
            var self = this;
            //this.videoJS.play();
            this.currentProgress = parseFloat(this.videoModel.get("progress.factor"));

            if (_.isNaN(this.currentProgress)) {
                this.currentProgress = 0;
            }

            // @todo CALCULATE THE CURRENT VIDEO TIMELINE, IF PREVIOUSLY STARTED

            if (this.currentProgress >= 1) {
                this.trigger("video:viewed");
            } else {

                this.videoJS.on("timeupdate", function() {
                    // CALCULATE CURRENT PROGRESS
                    var currentProgress = this.videoJS.currentTime() / this.videoJS.duration();

                    if (currentProgress > this.currentProgress) {
                        var progressDiff =  currentProgress - this.currentProgress;
                        if (progressDiff > 0.03 ) {
                            this.currentProgress = currentProgress;
                            //this.videoModel.set("progress", this.currentProgress);
                            var progressModel = new mod.models.content_progress(this.videoModel.get("progress"));
                            progressModel.setAsViewed(this.videoModel, this.currentProgress);
                        }
                    }

                }.bind(this));

                this.videoJS.on("ended", function() {
                    this.currentProgress = 1;
                    var progressModel = new mod.models.content_progress(this.videoModel.get("progress"));
                    progressModel.setAsViewed(this.videoModel, this.currentProgress);

                    this.trigger("video:viewed");
                }.bind(this));
            }
        },
        disableView : function() {
            //$("[href='#tab_unit_video'").hide();
            //$("[href='#tab_unit_materials']").tab('show');
            this.$el.hide();
        },
        enableView : function() {
            //$("[href='#tab_unit_video'").show().tab('show');
            this.$el.show();
        }
    });

    var baseChildTabViewItemClass = app.module("views").baseChildTabViewItemClass;
    var unitMaterialsTabViewItemClass = baseChildTabViewItemClass.extend({
        events : {
            "click .view-content-action" : "viewContentAction"
        },
        template : _.template($("#tab_unit_materials-item-template").html(), null, {variable: "model"}),
        viewContentAction : function(e) {
            // TRACK PROGRESS
            var progressModel = new mod.models.content_progress();
            progressModel.setAsViewed(this.model);

            this.model.set("progress", progressModel.toJSON());

            this.render();
        },
        checkProgress : function(model) {
            var progress = _.findWhere(model.get("contents"), {content_id : this.model.get("id")});
            if (!_.isUndefined(progress)) {
                this.model.set("progress", progress);
                this.render();
            }

        }
    });

    var baseChildTabViewClass = app.module("views").baseChildTabViewClass;
    var unitMaterialsTabViewClass = baseChildTabViewClass.extend({
        nofoundTemplate : _.template($("#tab_unit_materials-nofound-template").html()),
        childViewClass : unitMaterialsTabViewItemClass,
        makeCollection: function() {
            // GET THE MATERIALS
            return this.model.get("materials");
        },
        disableView : function() {
            $("[href='#tab_unit_materials'],#tab_unit_materials").addClass("hidden");
        },
        enableView : function() {
            $("[href='#tab_unit_materials'],#tab_unit_materials").removeClass("hidden");
        }
    });

    var dialogViewClass = app.module("views").dialogViewClass;

    var questionDialogViewClass = dialogViewClass.extend({
        initialize : function() {
            dialogViewClass.prototype.initialize.apply(this, arguments);

            // CREATE SUB-VIEWS
            // MENU DROPDOW
            // VIDEO
            this.unitVideoTabView   = new unitVideoTabViewClass({
                el : this.$("#unit-video-container"),
                model : this.model,
                //collection: this.collection,
                portlet : this.$el
            });
            // MATERIALS
            this.unitMaterialsTabView   = new unitMaterialsTabViewClass({
                el : this.$("#unit-material-container table tbody"),
                model : this.model,
                //collection: this.collection,
                portlet : this.$el
            });
        },
        setModel : function(model) {
            //dialogViewClass.prototype.setModel.apply(this, arguments);
            this.model = model;
            // APPLYING TO CHILDS
            this.unitVideoTabView.setModel(model);
            this.unitMaterialsTabView.setModel(model);
        },
        setCollection : function(collection) {
            //dialogViewClass.prototype.setCollection.apply(this, arguments);
            this.collection = collection;
            // APPLYING TO CHILDS
            //this.unitVideoTabView.setCollection(collection);
            //this.unitMaterialsTabView.setCollection(collection);
        },
        render : function() {
            dialogViewClass.prototype.render.apply(this, arguments);

            //this.navigationView.render();
            this.unitVideoTabView.render();
            this.unitMaterialsTabView.render();
            //this.unitTestTabView.render();

            this.$("[data-update='course.name']").html(this.model.get("course").get("name"));
        }
    });

    mod.on("start", function(opt) {
        console.warn(opt);
        this.started = true;

        this.dialogView = null;

        if (_.has(opt, 'modelClass')) {
            this.modelClass = opt.modelClass;
        } else {
            this.modelClass = Backbone.Model;
        }



        this.dialogView = new questionDialogViewClass({
            el : "#content-unit-modal",
            model : new mod.modelClass(),
            collection : new Backbone.Collection()
        });
        //this.dialogView.render();

        // CREATE TABLE SUB-VIEW
        //var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
        //var sAjaxSource = "/module/questions/items/lesson-content/datatable/";

        //var config = app.getResource("questions-select_context");
        //console.warn(config);
        /*
        var tableViewClass = app.module("utils.datatables").tableViewClass;
        this.tableView = new tableViewClass({
            el : "#questions-select-table",
            datatable : config
        });
        */

    });
});

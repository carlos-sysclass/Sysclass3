$SC.module("tests.execute", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS

    mod.on("start", function() {
        var baseFormClass = app.module("views").baseFormClass;

        var testExecutionViewClass = baseFormClass.extend({
            renderType : "byView",
            readonly : false,
            initialize: function() {
                console.info('tests.execute/testExecutionViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                // THIS WILL STARTED OR RESUME THE TEST PROGRESS (AND UPDATE INTERFACE AS WELL)
                var self = this;

                this.listenTo(this.model, "change:answers", this.saveProgress.bind(this));
                this.listenTo(this.model, "change:pending", this.checkPending.bind(this));
                //this.model.save();
            },
            saveProgress : function() {
                console.info('tests.execute/testExecutionViewClass::saveProgress');

                this.model.save();
            },
            onRender : function() {
                if (this.readonly) {
                    this.setReadonly(true);
                }
            },
            checkPending : function() {
                if (this.model.get("pending") == "0") {
                    // SET TEST VIEW TO READONLY
                    this.stopListening(this.model, "change:pending change:answers");
                    this.readonly = true;
                    /// SET INPUT AS READ_ONLY

                } else {
                    //console.warn("write");
                }
            }
        });

        var testExecutionStatsViewClass = Backbone.View.extend({
            secondInterval : null,
            minuteInterval : null,
            readonly : false,
            initialize: function() {
                console.info('tests.execute/testExecutionStatsViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                // THIS WILL STARTED OR RESUME THE TEST PROGRESS (AND UPDATE INTERFACE AS WELL)
                var self = this;

                this.listenTo(this.model, "change:try_index", this.renderTryIndex.bind(this));
                this.listenTo(this.model, "change:pending", this.checkPending.bind(this));
                this.listenTo(this.model, "change:progress", this.renderProgress.bind(this));

                this.minuteInterval = window.setInterval(this.renderProgress.bind(this), 1000 * 30); // A CADA 30 SEGUNDOS
            },
            renderTryIndex : function() {
                console.info('tests.execute/testExecutionStatsViewClass::renderTryIndex');
                this.$(".text-try-index-text").html(this.model.get("try_index"));
            },
            renderProgress : function() {
                console.info('tests.execute/testExecutionStatsViewClass::renderProgress');

                var progress = this.model.get("progress");
                var time_elapsed = progress.time_elapsed;

                if (this.readonly) {
                    var duration = moment.duration(progress.time_elapsed, 'seconds');
                    this.$(".test-time-limit-text");
                    if (progress.time_elapsed < 60) {
                        this.$(".test-time-limit-text").html(duration.as("seconds") + "s");
                    } else {
                        this.$(".test-time-limit-text").html(duration.humanize(false));
                    }
                } else {

                    var expires = progress.expire_in;
                    expiresDate = moment.unix(expires);

                    if (moment().diff(expiresDate, 'minutes') >= -5 && _.isNull(this.secondInterval)) {
                        window.clearInterval(this.minuteInterval);
                        this.secondInterval = window.setInterval(this.renderProgress.bind(this), 1000);
                    }

                    if (moment().diff(expiresDate, 'seconds') >= 0) {
                        this.trigger("test:time_expired");
                        //window.clearInterval(this.secondInterval);
                    }

                    time_elapsed = moment().diff(moment.unix(progress.started), 'seconds');

                    this.$(".test-time-limit-text").html(expiresDate.fromNow());
                }

                // CHANGE TEXT COLORS BASED ON TIME PROGRESS
                var progressIndicator = (time_elapsed / (progress.expire_in - progress.started)) * 100;

                var progressClass = "default";

                if (progressIndicator >= 90) {
                    progressClass = "danger";
                } else if (progressIndicator >= 70) {
                    progressClass = "warning";
                } else if (progressIndicator >= 40) {
                    progressClass = "primary";
                }

                this.$(".test-time-limit-text")
                    .removeClass("progress-bar-primary")
                    .removeClass("progress-bar-default")
                    .removeClass("progress-bar-warning")
                    .removeClass("progress-bar-danger")
                    .addClass("text-" + progressClass);

                this.$(".test-time-limit .progress-bar")
                    .css("width", progressIndicator+"%")
                    .removeClass("progress-bar-primary")
                    .removeClass("progress-bar-default")
                    .removeClass("progress-bar-warning")
                    .removeClass("progress-bar-danger")
                    .addClass("progress-bar-" + progressClass)
                    .find(".progress-text")
                    .html(Math.round(progressIndicator) + "%");
            },
            checkPending : function() {
                if (this.model.get("pending") == 0) {
                    // SET TEST VIEW TO READONLY
                    this.readonly = true;
                    this.stopListening(this.model, "change:progress");
                    if (!_.isNull(this.minuteInterval)) {
                        window.clearInterval(this.minuteInterval);
                    }
                    if (!_.isNull(this.secondInterval)) {
                        window.clearInterval(this.secondInterval);
                    }

                    this.renderProgress();
                    console.warn("readonly");
                    /// SET INPUT AS READ_ONLY
                } else {
                    console.warn("write");
                }
            }
        });

        this.testViewClass = Backbone.View.extend({
            events : {
                "click .finish-test-action" : "finishTest"
            },
            initialize : function() {
                var testExecutionView = new testExecutionViewClass({
                    el :this.$("form"),
                    model : this.model
                });

                var testExecutionStatsView = new testExecutionStatsViewClass({
                    el :this.$(".test-sidebar-info"),
                    model : this.model
                });

                this.listenTo(testExecutionStatsView, "test:time_expired", this.finishTest.bind(this));

                //mod.testExecutionModel = this.model;
            },
            finishTest : function() {
                console.info('tests.execute/testViewClass::finishTest');
                // SAVE THE EXECUTION, BLOCK THE UI, SHOW A MESSAGE AND REDIRECT TO TEST RESULTS
                this.model.set("complete", 1);
                this.model.save();



            }
        });
    });
    this.models = {
        test : Backbone.DeepModel.extend({
            urlRoot : "/module/tests/item/me"
        }),
        test_execution : Backbone.DeepModel.extend({
            urlRoot : "/module/tests/item/execution",
        })
    };

    mod.on("start", function() {
        this.listenToOnce(app.userSettings, "sync", function(model, data, options) {

            /*
            var testModel = new this.models.test({
                id : app.userSettings.get("test_id")
            });
            */

            var testExecutionModel = new mod.models.test_execution({
                id : app.userSettings.get("test_execution_id")
            });

            this.testView = new this.testViewClass({
                model : testExecutionModel,
                el: '#tests-execute-block'
            });

            testExecutionModel.fetch();

            mod.testExecutionModel = testExecutionModel;

        }.bind(this));
    });

});

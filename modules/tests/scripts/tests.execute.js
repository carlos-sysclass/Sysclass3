$SC.module("tests.execute", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS

    mod.on("start", function() {
        var baseFormClass = app.module("views").baseFormClass;

        var testExecutionViewClass = baseFormClass.extend({
            initialize: function() {
                console.info('tests.execute/testExecutionViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                // THIS WILL STARTED OR RESUME THE TEST PROGRESS (AND UPDATE INTERFACE AS WELL)
                var self = this;

                this.listenTo(this.model, "change:answer", this.saveProgress.bind(this));

                this.model.save();

            },
            saveProgress : function() {
                console.info('tests.execute/testExecutionViewClass::saveProgress');

                this.model.save();
            }
        });

        this.testViewClass = Backbone.View.extend({
            initialize : function() {
                this.listenTo(this.model, "sync", this.render.bind(this));

                var testExecutionModel = new mod.models.test_execution({
                    test_id : this.model.get("id")
                });

                var testExecutionView = new testExecutionViewClass({
                    el :this.$("form"),
                    model : testExecutionModel
                });

                mod.testExecutionModel = testExecutionModel;

            },
            render :function() {

            }
        });
    });
    this.models = {
        test : Backbone.DeepModel.extend({
            urlRoot : "/module/tests/item/me"
        }),
        test_execution : Backbone.DeepModel.extend({
            urlRoot : "/module/tests/item/execution"
        })
    };

    mod.on("start", function() {
        this.listenToOnce(app.userSettings, "sync", function(model, data, options) {

            var testModel = new this.models.test({
                id : app.userSettings.get("teste_execution_id")
            });

            this.testView = new this.testViewClass({
                model : testModel,
                el: '#tests-execute-block'
            });

            testModel.fetch();

            mod.testModel = testModel;

        }.bind(this));
    });

});

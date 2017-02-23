$SC.module("dialogs.tests.info", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;

    mod.on("start", function(opt){
        // MOVE THIS MODEL TO ANOTHER MODULE
        var testsInfoDialogViewClass = Backbone.View.extend({
            autoStart : false,
            events : {
                "click [data-action-trigger]" : "triggerAction"
            },
            //template : _.template($("#tests_info_modal-template").html(), null, {variable : "model"}),
            initialize : function() {
                // CREATE DIALOG
                this.$el.modal({
                    show : false,

                });
                mod.started = true;
            },
            setModel : function(model) {
                /*
                if (this.model) {
                    this.stopListening(this.model);
                }
                */
                this.model = model;
                this.render();
            },
            render : function() {
                //this.$(".modal-content").html(this.template(this.model.toJSON()));
                //
                this.$(".modal-content").load("/module/tests/open/" + this.model.get("id") + "?dialog", function() {
                    if (this.autoStart) {
                        this.$("form").submit();
                    }
                }.bind(this));

                app.module("ui").refresh(this.$(".modal-content"));
            },
            triggerAction : function(e) {
                var trigger = $(e.currentTarget).data("actionTrigger");

                if (!_.isEmpty(trigger)) {
                    mod.trigger("action:do-test", this.model);
                }
            }
        });

        

        this.setInfo = function(info) {
            // FILTER DATATABLE
            //this.filter = filter;
            //this.model = info.model;
            // LOAD TEST MODEL FROM
            if (info.autoStart) {
                this.dialogView.autoStart = true;
            }

            this.dialogView.setModel(info.model);


            //var url = "/module/questions/items/lesson-content/datatable/" + JSON.stringify(this.filter);

            //this.dialogView
        };

        this.dialogView = new testsInfoDialogViewClass({
            el : "#tests-info-modal"
        });

        mod.open = function() {
            if (!mod.dialogView.autoStart) {
                mod.dialogView.$el.modal('show');
            }
        };
        mod.close = function() {
            mod.dialogView.$el.modal('hide');
        };
    });
});

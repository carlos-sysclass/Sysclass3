$SC.module("dialogs.exercises", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;

    mod.on("start", function(opt){
        // MOVE THIS MODEL TO ANOTHER MODULE
 
        this.models = {
            questions : Backbone.DeepModel.extend({})
        };

        var exercisesDialogViewClass = Backbone.View.extend({
            events : {
                "click [data-action-trigger]" : "triggerAction"
            },
            exerciseTemplate : _.template($("#tab_lesson_exercises-details-template").html(), null, {variable: "model"}),

            //template : _.template($("#tests_info_modal-template").html(), null, {variable : "model"}),
            initialize : function() {
                // CREATE DIALOG
                this.$el.modal({
                    show : false,
                    backdrop : "static",
                    keyboard : false
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
                var self = this;

                this.$(".question-container").empty();

                _.each(this.model.get("exercise"), function(data, index) {
                    var innermodel = new mod.models.questions(data);

                    var questionView = new lessonExercisesQuestionItemClass({
                        model : innermodel,
                        model_index : index
                    });
                    this.$(".question-container").append(questionView.render().el);
                }.bind(this));

                app.module("ui").refresh(this.$(".exercises-container"));

                //app.module("ui").refresh(this.$(".modal-content"));
            },
            triggerAction : function(e) {
                var trigger = $(e.currentTarget).data("actionTrigger");

                if (!_.isEmpty(trigger)) {
                    mod.trigger("action:do-test", this.model);
                }
            }
        });

        /* TESTS AND EXERCISES UTILITY VIEWS */
        var lessonExercisesQuestionItemClass = Backbone.View.extend({
            tagName : "li",
            templates : {
                "combine" : _.template($("#tab_lesson_exercises-question-combine-template").html(), null, {variable: "model"}),
                "true_or_false" : _.template($("#tab_lesson_exercises-question-true_or_false-template").html(), null, {variable: "model"}),
                "simple_choice" : _.template($("#tab_lesson_exercises-question-simple_choice-template").html(), null, {variable: "model"}),
                "multiple_choice" : _.template($("#tab_lesson_exercises-question-multiple_choice-template").html(), null, {variable: "model"}),
                "fill_blanks" : _.template($("#tab_lesson_exercises-question-fill_blanks-template").html(), null, {variable: "model"}),
                "free_text" : _.template($("#tab_lesson_exercises-question-free_text-template").html(), null, {variable: "model"})
            },
            initialize: function(opt) {
                this.model_index = opt.model_index;
            },
            render : function() {
                if (_.has(this.templates, this.model.get("type_id"))) {
                    var template = this.templates[this.model.get("type_id")];
                    this.$el.html(
                        template(_.extend(this.model.toJSON(), {
                            model_index : this.model_index
                        }))
                    );

                }
                return this;
            }
        });

        mod.setInfo = function(info) {
            // FILTER DATATABLE
            //this.filter = filter;
            //this.model = info.model;
            // LOAD TEST MODEL FROM
            //
            this.dialogView.setModel(info.model);

            //var url = "/module/questions/items/lesson-content/datatable/" + JSON.stringify(this.filter);

            //this.dialogView
        };

        this.dialogView = new exercisesDialogViewClass({
            el : "#lesson-exercises-dialog"
        });

        mod.open = function() {
            mod.dialogView.$el.modal('show');
            mod.dialogView.$el.modal('handleUpdate');
        };

        mod.close = function() {
            mod.dialogView.$el.modal('hide');
        };
    });
});

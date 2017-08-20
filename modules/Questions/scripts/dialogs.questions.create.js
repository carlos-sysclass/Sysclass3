$SC.module("dialogs.questions.create", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    this.started = false;

    mod.getForm = function() {
        return mod.dialogView;
    };
    mod.open = function() {
        mod.dialogView.open();
    };
    mod.close = function() {
        mod.dialogView.close();
    };

    /*
    mod.setModel = function(model) {
        if (!_.isUndefined(mod.dialogView)) {
            this.stopListening(mod.dialogView.model);
            mod.dialogView.setModel(model);

            mod.dialogView.listenTo(mod.dialogView.model, "sync", function() {
                mod.trigger("created.question", this.model);
            });
        }
    };
    */

    mod.on("start", function(opt) {
        var baseFormClass = app.module("views").baseFormClass;
        var questionCreationDialogViewClass = baseFormClass.extend({
            renderType : "byView",
            initialize: function() {
                console.info('dialogs.roles.create/questionCreationDialogViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                var self = this;

                this.on("complete:save", this.close.bind(this));
            },
            open : function() {
                if (this.model) {
                    this.model.unset("id");
                    this.model.destroy();
                    this.model = new mod.models.question();
                
                    this.listenTo(this.model, "sync", function() {
                        mod.trigger("created.question", this.model);
                    });
                }
                console.warn('open', this.model.toJSON());
                this.model.unset("id");
                this.model.unset("name");
                this.model.unset("question");
                this.model.unset("simple_choice");
                this.model.unset("multiple_choice");

                this.oForm.get(0).reset();
                this.oForm.find(".select2-me").select2("val", "");

                this.unbindViewEvents();

                this.$(".modal-body").load("/module/questions/form/create", function() {

                    if (!app.module("views.form.questions").started) {
                        app.module("views.form.questions").start({
                            module: mod
                        });
                    } else {
                        app.module("views.form.questions").setInfo({
                            module: mod
                        });
                    }

                    this.$(".wysihtml5-toolbar").remove();
                    app.module("ui").refresh(this.$el);

                    this.bindViewEvents();
                    this.$el.modal("show");
                }.bind(this));

                console.warn('open2', this.model.toJSON());
               
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            }
        });

        this.models = {
            response_type : "object",
            question : $SC.module("crud.models").baseItemModelClass.extend({
                /*
                defaults : {
                    name : "",
                    active : 1,
                    in_course : 0,
                    in_class : 0
                },
                */
                urlRoot : "/module/questions/item/me"
            })
        };

        this.dialogView = new questionCreationDialogViewClass({
            el : "#dialogs-questions-create",
            model : new mod.models.question()
        });

        // BIND TO DEFAULT CALLER
        /*
        $(".dialog-create-role-open-action").on("click", function(e) {
            e.preventDefault();
            this.dialogView.setModel(new mod.models.roles());
            this.dialogView.open();
        }.bind(this));
        */
        /*
        mod.open = function() {
            mod.dialogView.$el.modal('show');
        };
        mod.close = function() {
            mod.dialogView.$el.modal('hide');
        };
        */

    });
});

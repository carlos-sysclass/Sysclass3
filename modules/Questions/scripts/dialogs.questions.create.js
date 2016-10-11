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

                //mod.setModel(this.model);
                
                this.listenTo(this.model, "sync", function() {
                    mod.trigger("created.question", this.model);
                });
                
                this.on("complete:save", this.close.bind(this));
            },
            open : function() {
                this.model.unset("id");
                this.model.unset("name");
                this.model.unset("question");

                this.oForm.get(0).reset();
                this.oForm.find(".select2-me").select2("val", "");

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

$SC.module("dialogs.institution.social", function(mod, app, Backbone, Marionette, $, _) {
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

    mod.setModel = function(model) {
        console.warn(model);
        mod.dialogView.setModel(model);
    };


    mod.on("start", function(opt) {
        this.started = true;
        var dialogViewClass = app.module("views").dialogViewClass;
        var socialInfoDialog = dialogViewClass.extend({
            renderType : "byView",
            initialize: function() {
                console.info('dialogs.institution.social/socialInfoDialog::initialize');
                dialogViewClass.prototype.initialize.apply(this);

                var self = this;

                //mod.setModel(this.model);
                
                this.listenTo(this.model, "sync", function() {
                    mod.trigger("created.question", this.model);
                });
                
                this.on("complete:save", this.close.bind(this));
            },
            setModel : function(model) {
                console.warn(model);
                this.model.set("id", model.get("id"));
            },

            open : function() {
                console.info('dialogs.institution.social/socialInfoDialog::open');
                dialogViewClass.prototype.open.apply(this);
                /*
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
                */
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            }
        });

        this.models = {
            details : $SC.module("crud.models").baseItemModelClass.extend({
                response_type : "object",
                urlRoot : "/module/institution/item/details"
            })
        };

        this.dialogView = new socialInfoDialog({
            el : "#dialogs-organization-social",
            model : new mod.models.details()
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

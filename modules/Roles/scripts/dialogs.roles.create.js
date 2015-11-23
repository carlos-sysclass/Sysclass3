$SC.module("dialogs.roles.create", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
//    this.startWithParent = false;

    //this.config = dialogs_questions_select;

//    this.started = false;

    mod.on("start", function(opt) {
        var baseFormClass = app.module("views").baseFormClass;
        var roleCreationDialogViewClass = baseFormClass.extend({
            renderType : "byView",
            initialize: function() {
                console.info('dialogs.roles.create/roleCreationDialogViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                var self = this;

                this.on("complete:save", this.close.bind(this));
            },
            open : function() {
                this.$el.modal("show");
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            }
        });

        this.models = {
            roles : Backbone.Model.extend({
                defaults : {
                    name : "",
                    active : 1,
                    in_course : 0,
                    in_class : 0
                },
                urlRoot : "/module/roles/item/me"
            })
        };

        this.dialogView = new roleCreationDialogViewClass({
            el : "#dialogs-roles-create",
            model : new mod.models.roles()
        });

        // BIND TO DEFAULT CALLER
        $(".dialog-create-role-open-action").on("click", function(e) {
            e.preventDefault();
            this.dialogView.setModel(new mod.models.roles());
            this.dialogView.open();
        }.bind(this));
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

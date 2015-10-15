$SC.module("dialogs.messages.send", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
//    this.startWithParent = false;

    //this.config = dialogs_questions_select;

//    this.started = false;

    mod.on("start", function(opt) {
        var baseFormClass = app.module("views").baseFormClass;
        var messageSendDialogViewClass = baseFormClass.extend({
            renderType : "byView",
            initialize: function() {
                console.info('dialogs.roles.create/messageSendDialogViewClass::initialize');
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
            message : Backbone.DeepModel.extend({
                defaults : {
                    subject : "",
                    body : ""
                },
                urlRoot : "/module/messages/item/me"
            })
        };

        this.dialogView = new messageSendDialogViewClass({
            el : "#dialogs-messages-send",
            model : new mod.models.message()
        });

        // BIND TO DEFAULT CALLER
        $(".dialogs-messages-send-action").on("click", function(e) {
            e.preventDefault();
            var group_id = $(e.currentTarget).data("groupId");
            //console.warn(this, group_id, $(e.currentTarget).data());
            var model = new mod.models.message();
            model.set("group_id.0.id", group_id);

            this.dialogView.setModel(model);
            
            this.dialogView.open();
        }.bind(this));
    });
});

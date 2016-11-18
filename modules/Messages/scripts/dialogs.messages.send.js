$SC.module("dialogs.messages.send", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
//    this.startWithParent = false;

    //this.config = dialogs_questions_select;

//    this.started = false;

    var baseModel = app.module("models").getBaseModel();

    mod.models = {
        message : baseModel.extend({
            response_type : "object",
            defaults : {
                subject : "",
                body : ""
            },
            urlRoot : "/module/messages/item/me"
        })
    };

    mod.on("start", function(opt) {
        var baseFormClass = app.module("views").baseFormClass;
        var messageSendDialogViewClass = baseFormClass.extend({
            renderType : "byView",
            mode : "group",
            initialize: function() {
                console.info('dialogs.roles.create/messageSendDialogViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                var self = this;

                this.on("complete:save", this.close.bind(this));
            },
            setMode : function(mode) {
                this.mode = mode;

                if (this.mode == "user") {
                    this.$(":input[name='group_id']").select2("container").hide();
                    this.$(":input[name='user_id']").show();
                } else {
                    this.$(":input[name='user_id']").hide();
                    this.$(":input[name='group_id']").show();
                }

            },
            open : function() {
                this.$el.modal("show");
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            }
        });



        this.dialogView = new messageSendDialogViewClass({
            el : "#dialogs-messages-send",
            model : new mod.models.message()
        });

        // BIND TO DEFAULT CALLER
        $(".dialogs-messages-send-action").on("click", function(e) {
            e.preventDefault();
            var model = new mod.models.message();

            if ($(e.currentTarget).data("mode") == "user") {
                var user_id = $(e.currentTarget).data("userId");
                model.set("user_id.0.id", user_id);
                this.dialogView.setMode("user");
            } else {
                var group_id = $(e.currentTarget).data("groupId");
                model.set("group_id.0.id", group_id);
                this.dialogView.setMode("group");
            }

            this.dialogView.setModel(model);
            
            this.dialogView.open();
        }.bind(this));
    });
});

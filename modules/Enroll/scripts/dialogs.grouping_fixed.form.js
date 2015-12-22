$SC.module("dialogs.grouping_fixed.form", function(mod, app, Backbone, Marionette, $, _) {
.js
    mod.on("start", function(opt) {
        this.dialogView = null;

        this.getValue = function(callback) {
            if (_.isNull(this.dialogView)) {
                var dialogViewClass = app.module("views").dialogViewClass;

                var userSelectDialogViewClass = dialogViewClass.extend({
                    save : function() {
                        this.trigger("selected.dialogsUsersSelect", this.model.toJSON());
                        this.close();

                        // DESTROY ???
                        
                        if (_.isFunction(callback)) {
                            callback(this.model.toJSON());
                        }
                    }
                });

                this.dialogView = new userSelectDialogViewClass({
                    el : "#dialogs-users-select",
                    model : new Backbone.Model({
                        user_id : null
                    })
                });
                this.dialogView.render();
            } else {
                this.dialogView.setModel(new Backbone.Model);
            }

            this.dialogView.open();
        };
    });
});

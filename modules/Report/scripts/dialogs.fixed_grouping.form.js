$SC.module("dialogs.fixed_grouping.form", function(mod, app, Backbone, Marionette, $, _) {

    this.startWithParent = false;
    this.started = false;

    mod.on("start", function(opt) {
        this.started = true;

        this.dialogView = null;

        this.modelClass = opt.modelClass;

        this.getValue = function(callback) {
            if (_.isNull(this.dialogView)) {
                var dialogViewClass = app.module("views").dialogViewClass;

                var groupingEditDialogViewClass = dialogViewClass.extend({
                    save : function() {
                        console.info('dialogs.fixed_grouping.form/groupingEditDialogViewClass::save');
                        //this.trigger("selected.dialogsUsersSelect", this.model.toJSON());
                        this.close();

                        // DESTROY ???
                        //console.warn(_.isFunction(callback));
                        if (_.isFunction(callback)) {
                            callback(this.model.toJSON(), this.model);
                        }
                    }
                });

                this.dialogView = new groupingEditDialogViewClass({
                    el : "#fixed-grouping-dialog",
                    model : new mod.modelClass()
                });
                this.dialogView.render();
            } else {
                this.dialogView.setModel(new mod.modelClass());
            }

            this.dialogView.open();
        };
    });
});

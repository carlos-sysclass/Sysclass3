$SC.module("dialogs.auto_translate", function(mod, app, Backbone, Marionette, $, _) {

    this.startWithParent = false;
    this.started = false;

    this.modelClass = Backbone.Model;


    mod.on("start", function(opt) {
        this.started = true;

        this.dialogView = null;

        var dialogViewClass = app.module("views").dialogViewClass;

        //this.modelClass = opt.modelClass;

        this.getValue = function(callback) {
            var selectCountryDialogViewClass = dialogViewClass.extend({
                save : function() {
                    console.info('dialogs.auto_translate/selectCountryDialogViewClass::save');
                    //this.trigger("selected.dialogsUsersSelect", this.model.toJSON());
                    this.close();

                    // DESTROY ???
                    //console.warn(_.isFunction(callback));
                    if (_.isFunction(callback)) {
                        callback(this.model.toJSON(), this.model);
                    }
                }
            });

            if (_.isNull(this.dialogView)) {
                this.dialogView = new selectCountryDialogViewClass({
                    el : "#lessons-dialog-auto_translate",
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

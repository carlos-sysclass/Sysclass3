$SC.module("fields.form", function(mod, app, Backbone, Marionette, $, _) {

    this.startWithParent = false;
    this.started = false;

    mod.on("start", function(opt) {
        this.started = true;
        this.modelClass = opt.modelClass;

        console.warn(this.modelClass);

        this.getValue = function(callback) {
            var model = new mod.modelClass({});

            console.warn(model);

            callback(model.toJSON(), model);
        };
    });
});

$SC.module("views.crud.add", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;

    mod.addInitializer(function() {
        var itemModelClass = $SC.module("crud.models").itemModelClass;
        var itemModel = new itemModelClass();

        var baseFormClass = app.module("views").baseFormClass;
        mod.formView = new baseFormClass({el : "#form-" + this.module_id, model: itemModel});

        // EXPORTS
        //this.itemModel = itemModel;
    });
});

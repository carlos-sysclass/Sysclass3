$SC.module("crud.views.add", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;

    this.startWithParent = false;
    mod.addInitializer(function() {
        var itemModelClass = $SC.module("crud.models").itemModelClass;
        var itemModel = new itemModelClass();
        itemModel.response_type = "redirect";

        var baseFormClass = app.module("views").baseFormClass;
        mod.formView = new baseFormClass({el : "#form-" + this.module_id, model: itemModel});

        // EXPORTS
        //this.itemModel = itemModel;
    });
    mod.getForm = function() {
        return this.formView;
    };

    $SC.module("crud.models").on("start", function() {
        mod.start();
    });
});

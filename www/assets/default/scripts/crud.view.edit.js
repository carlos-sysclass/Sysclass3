$SC.module("crud.views.edit", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.entity_id = this.config.entity_id;

    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        var itemModelClass = $SC.module("crud.models").itemModelClass;
        this.itemModel = new itemModelClass();

        this.itemModel.set("id", this.entity_id);

        var baseFormClass = app.module("views").baseFormClass;
        mod.formView = new baseFormClass({el : "#form-" + this.module_id, model: this.itemModel});

        this.itemModel.fetch();
    });
    mod.getForm = function() {
        return this.formView;
    };

    mod.getModel = function() {
        return this.itemModel;
    };


    $SC.module("crud.models").on("start", function() {
        mod.start();
    });
});

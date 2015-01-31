$SC.module("crud.views.edit", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.entity_id = this.config.entity_id;

    // MODELS
    mod.addInitializer(function() {
        var itemModelClass = $SC.module("crud.models").itemModelClass;
        var itemModel = new itemModelClass();

        itemModel.set("id", this.entity_id);

        var baseFormClass = app.module("views").baseFormClass;
        var newsFormView = new baseFormClass({el : "#form-" + this.module_id, model: itemModel});


        itemModel.fetch();
    });
});

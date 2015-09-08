$SC.module("views.profile", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.entity_id = this.config.entity_id;

    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        //var itemModelClass = $SC.module("crud.view.edit").itemModel;
        this.itemModel = $SC.module("crud.views.edit").itemModel;

        //this.itemModel.set("id", this.entity_id);

        var baseFormClass = app.module("views").baseFormClass;
        mod.formView = new baseFormClass({el : "#form-users-avatar", model: this.itemModel});

        //this.itemModel.fetch();
    });

    mod.getForm = function() {
        return this.formView;
    };

    $SC.module("crud.views.edit").on("start", function() {
        mod.start();
    });
});

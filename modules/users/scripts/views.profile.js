$SC.module("views.profile", function(mod, app, Backbone, Marionette, $, _) {
    /*
        this.config = $SC.module("crud.config").getConfig();
        this.module_id = this.config.module_id;
        this.entity_id = this.config.entity_id;
    */
    // MODELS
    this.startWithParent = false;
    mod.addInitializer(function() {
        //var itemModelClass = $SC.module("crud.view.edit").itemModel;
        this.formView = $SC.module("crud.views.edit").getForm();

        console.warn(this.formView.$el);

        this.formView.$(".profile-edit").on("click", function() {
            this.formView.$("[href='#tab_1-2']").click();
            //this.formView.$("[href='#tab_1-2']").click();
        }.bind(this));

        this.listenTo(this.formView, "before:save", function(model) {
            model.setResponseType("redirect");
        });
    });
    $SC.module("crud.views.edit").on("start", function() {
        mod.start();
    });
});

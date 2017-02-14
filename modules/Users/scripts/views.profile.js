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

        this.itemModel = $SC.module("crud.views.edit").getModel();

        this.formView.$(".profile-edit").on("click", function() {
            this.formView.$("[href='#tab_1-2']").click();
            //this.formView.$("[href='#tab_1-2']").click();
        }.bind(this));


        // store the currently selected tab in the hash value
        $(".profile-tabs > li > a").on("shown.bs.tab", function (e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
        });        

        this.listenTo(this.formView, "before:save", function(model) {
            model.setResponseType("redirect");
        });

        this.listenTo(app.module("blocks.dropbox.upload"), "uploadComplete.dropbox", function(model) {
            /**
             * TODO: MAKE A WAY TO RELOAD THE PROFILE IMAGES
             */

            //this.formView.update();
            this.formView.save();
        }.bind(this));

        
    });
    $SC.module("crud.views.edit").on("start", function() {
        mod.start();
    });
});

$SC.module("crud.models", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;

    //mod.addInitializer(function() {
        this.itemModelClass = Backbone.Model.extend({
            urlRoot : "/module/" + this.module_id + "/item/me"
        });

        console.warn(this.config);
    //});
});

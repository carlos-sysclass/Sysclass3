$SC.module("crud.models", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.route = this.config['route'];
    this.modelPrefix = this.config['model-prefix'];

    //mod.addInitializer(function() {
        if (typeof this.modelPrefix == "undefined") {
            this.itemModelClass = Backbone.Model.extend({
                urlRoot : "/module/" + this.module_id + "/item/me"
            });
        } else {
            this.itemModelClass = Backbone.Model.extend({
                urlRoot : "/module/" + this.module_id + "/" + this.modelPrefix + "/item/me"
            });
        }
    //});
});

$SC.module("crud.models", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.route = this.config['route'];
    this.modelPrefix = this.config['model-prefix'];

    var baseItemModelClass = Backbone.DeepModel.extend({
        save: function(key, val, options) {
            this.trigger("before:save", this);
            console.warn(this.toJSON());
            //this.trigger('change:' + changes[i], this, current[changes[i]], options);
            Backbone.DeepModel.prototype.save.apply(this);
        }
    });

    //mod.addInitializer(function() {
        if (typeof this.modelPrefix == "undefined") {
            this.itemModelClass = baseItemModelClass.extend({
                urlRoot : "/module/" + this.module_id + "/item/me"
            });
        } else {
            this.itemModelClass = baseItemModelClass.extend({
                urlRoot : "/module/" + this.module_id + "/" + this.modelPrefix + "/item/me"
            });
        }
    //});
});

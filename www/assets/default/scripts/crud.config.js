$SC.module("crud.config", function(mod, app, Backbone, Marionette, $, _) {
    if (typeof crud_config != 'undefined') {
        this.config = crud_config;
        this.module_id = this.config.module_id;

        this.getConfig = function() {
            return this.config;
        }
    }
});

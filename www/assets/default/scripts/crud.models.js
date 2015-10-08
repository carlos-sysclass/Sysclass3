$SC.module("crud.models", function(mod, app, Backbone, Marionette, $, _) {
    this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.route = this.config['route'];
    this.modelPrefix = this.config['model-prefix'];

    var baseItemModelClass = Backbone.DeepModel.extend({
        response_type : "object",
        save: function(key, val, options) {
            this.trigger("before:save", this);
            //this.trigger('change:' + changes[i], this, current[changes[i]], options);
            Backbone.DeepModel.prototype.save.apply(this);
        },
        setResponseType : function(mode) {
            // MODE CAN BE // 
            // redirect => will return a respose for redirection
            // object => will return onlyh the object (with status response) (default)
            // status => will retuirn only the status response
            this.response_type = mode;
        },
        sync : function(method, model, options) {
            console.info('models.scores/scoreModelClass::sync');

            if (method == "update") {
                options.url = model.urlRoot + "/" + model.get("id");

                var params = [];

                if (!_.isNull(this.response_type)) {
                    if (this.response_type == "redirect") {
                        params.push("redirect=" + "1");
                    } else if (this.response_type == "object") {
                        params.push("object=" + "1");
                    } else if (this.response_type == "status") {
                        params.push("status=" + "1");
                    }
                    options.url = options.url + "?" + params.join("&");
                }

                

                //options.data = JSON.stringify(model._asNameValue());

                return Backbone.sync(method, model, options);
            }

            return Backbone.sync(method, model, options);

            if (method == "update" && this.mode) {

            }
            console.warn(method, model, options);
            return Backbone.sync(method, model, options);
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

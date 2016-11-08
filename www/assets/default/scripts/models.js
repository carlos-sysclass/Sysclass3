$SC.module("models", function(mod, app, Backbone, Marionette, $, _) {
    var baseModelClass = Backbone.DeepModel.extend({
        response_type : "object",
        save: function(key, val, options) {
            this.trigger("before:save", this);
            //this.trigger('change:' + changes[i], this, current[changes[i]], options);
            Backbone.DeepModel.prototype.save.apply(this);

            this.trigger("after:save", this);
        },
        setResponseType : function(mode) {
            // MODE CAN BE // 
            // redirect => will return a respose for redirection
            // object => will return onlyh the object (with status response) (default)
            // status => will retuirn only the status response
            this.response_type = mode;
        },
        sync : function(method, model, options) {
            if (method == "update" || method == "create") {
                if (method == "update") {
                    options.url = model.urlRoot + "/" + model.get("id");
                } else {
                    options.url = model.urlRoot;
                }

                var params = [];

                if (!_.isNull(this.response_type)) {
                    if (this.response_type == "redirect") {
                        params.push("redirect=" + "1");
                    } else if (this.response_type == "reload") {
                        params.push("reload=" + "1");
                    } else if (this.response_type == "object") {
                        params.push("object=" + "1");
                    } else if (this.response_type == "status") {
                        params.push("status=" + "1");
                    } else if (this.response_type == "silent") {
                        params.push("silent=" + "1");
                    }
                    options.url = options.url + "?" + params.join("&");
                }

                //options.data = JSON.stringify(model._asNameValue());

                return Backbone.sync(method, model, options);
            }

            return Backbone.sync(method, model, options);

            if (method == "update" && this.mode) {

            }
            return Backbone.sync(method, model, options);
        }
    });

    var models = {
        base : {
            default: baseModelClass
        },
        users : {
            user : baseModelClass.extend({
                urlRoot : "/module/users/item/me"
            })
        }
    };

    this.getBaseModel = function() {
       return models.base.default; 
    }

    this.users = function() {
        return models.users;
    }

});

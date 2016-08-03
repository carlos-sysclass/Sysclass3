var $SC = new Backbone.Marionette.Application();

jQuery(document).ready(function() {
    $SC.hasSettings = false;
    $SC.on("before:start", function(options){
        options.theme_app.init(options.theme_path);

        var userSettingsModelClass = Backbone.Model.extend({
            url : "/module/settings"
        });

        this.userSettings = new userSettingsModelClass();
        this.userSettings.fetch({
          success: function(model,data, xhR) {
            // SETTINGS DEPLOYED
            $SC.trigger("settings.sysclass", model,data, xhR);
            $SC.hasSettings = true;
          }
        });

        this.userSettings.on("change", function(a,b,c,d,e) {
            this.save(null, {silent : true});
        });

        this._tables = {};
        this._resources = {};

        if (typeof _before_init_functions != "undefined") {
            for(i in _before_init_functions) {
                if (typeof _before_init_functions[i] == "function") {
                    _before_init_functions[i].bind(this).apply();
                }
            }
        }
    });

    $SC.on("start", function(options){
        $("[data-publish]").click(function (e) {
            $SC.request(jQuery(this).data("publish"), jQuery(this).data());
        });
    });

    $SC.addTable = function(name, obj) {
        this._tables[name] = obj;
        this.trigger("added.table", name, obj);
        return obj;
    };

    $SC.getTable = function(name) {
        return this._tables[name];
    };

    $SC.addResource = function(name, obj) {
        this._resources[name] = obj;
        return obj;
    };

    $SC.getResource = function(name) {
        return this._resources[name];
    };

    if (typeof _lazy_init_functions != "undefined") {

        for(i in _lazy_init_functions) {
            if (typeof _lazy_init_functions[i] == "function") {
                $SC.addInitializer(_lazy_init_functions[i]);
            }
        }
    }
});

var $SC = new Backbone.Marionette.Application();

jQuery(document).ready(function() {
    $SC.addInitializer(function(options){
        options.theme_app.init(options.theme_path);

        $("[data-publish]").click(function (e) {
        	$SC.request(jQuery(this).data("publish"), jQuery(this).data());
        })
    });


    if (typeof _lazy_init_functions != "undefined") {

        for(i in _lazy_init_functions) {
            if (typeof _lazy_init_functions[i] == "function") {
                $SC.addInitializer(_lazy_init_functions[i]);
            }
        }
    }
});

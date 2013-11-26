var $SC = new Backbone.Marionette.Application();

jQuery(document).ready(function() {   
    $SC.addInitializer(function(options){
        options.theme_app.init(options.theme_path);

        $("[data-publish]").click(function (e) {
        	$SC.request(jQuery(this).data("publish"), jQuery(this).data());
        })
    });
});
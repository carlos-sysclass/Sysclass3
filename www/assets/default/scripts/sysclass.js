var $SC = new Backbone.Marionette.Application();

jQuery(document).ready(function() {   
    $SC.addInitializer(function(options){
        //console.log(options);
        options.theme_app.init(options.theme_path);
    });
});
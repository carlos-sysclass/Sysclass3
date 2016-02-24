$SC.module("menu.settings", function(mod, app, Backbone, Marionette, $, _) {

	mod.on("start", function() {
        // pageguide init
        if (!_.usUndefined(tl)) {
	        tl.pg.init({
	            /* pg_caption : "" */
	            custom_open_button : "#open-pageguide-action"
	        });
        }
	});
});
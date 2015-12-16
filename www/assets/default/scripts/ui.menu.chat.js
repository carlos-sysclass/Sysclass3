$SC.module("menu.chat", function(mod, app, Backbone, Marionette, $, _) {
	mod.on("start", function() {
        $('.dropdown-quick-sidebar-toggler a, .page-quick-sidebar-toggler, .quick-sidebar-toggler').click(function (e) {
            $('body').toggleClass('page-quick-sidebar-open'); 
        });
	});
});
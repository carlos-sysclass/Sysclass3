// THINK ABOUT BREAKING THIS MODULE IN TWO.
$SC.module("panel.institution", function(mod, app, Backbone, Marionette, $, _) {

	var InstitutionWidgetViewClass = Backbone.View.extend({
	    initialize: function() {
	    	/*
			App.blockUI({
			    target: '#institution-chat-list',
			    overlayColor: 'none',
				iconOnly : true,
			    boxed: true
			});
			*/
			this.started = false;
			
            this.$('.carroussel').bxSlider({
				minSlides: 2,
				maxSlides: 2,
				slideWidth: 420,
				slideMargin: 10,
				adaptiveHeight : false,
				responsive : true,
				infiniteLoop : true,
				controls : false,
				auto : true
            });
	    }
	});


	this.on("start", function() {
		//$("#institution-chat-list").blockUI();
		mod.view = new InstitutionWidgetViewClass({el : '#institution-widget'});
	});
});
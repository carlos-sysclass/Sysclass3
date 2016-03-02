$SC.module("panel.notification", function(mod, app, Backbone, Marionette, $, _) {
	var notificationWidgetViewClass = Backbone.View.extend({
		timer : null,
		events : {
			//"mouseenter .alert" : "stop",
			//"mouseleave .alert" : "resume"
		},
	    initialize: function() {
	    	//current_course
	    	console.info('panel.notification/notificationWidgetViewClass::initialize');

	    	// CREATE THE PSEUDO-CAROUSELL
	    	this.$(".alert:first").fadeIn(400);
	    },
	    resume : function() {
	    	console.info('panel.notification/notificationWidgetViewClass::start');
	    	if (_.isNull(this.timer) && this.$(".alert").size() > 1) {
	    		this.timer = window.setInterval(this.rotate.bind(this), 5000);
	    	}
	    },
	    stop : function() {
	    	console.info('panel.notification/notificationWidgetViewClass::stop');
	    	if (!_.isNull(this.timer)) {
	    		window.clearInterval(this.timer);
	    		this.timer = null;
	    	}
	    },
	    rotate : function() {
	    	var current = this.$(".alert:visible");
	    	var next = this.$(".alert:visible").next();
	    	if (next.size() == 0) {
	    		next = this.$(".alert:first");
	    	}

	    	current.fadeOut(750, function() {
				next.fadeIn(1000);
	    	});

	    	console.warn(current, next);
	    }
  	});

	mod.on("start", function() {
		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.notificationWidgetView = new notificationWidgetViewClass({
				el: '#notification-panel',
				model : app.userSettings,
			});
		}.bind(this));
	});
});
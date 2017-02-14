$SC.module("panel.notification", function(mod, app, Backbone, Marionette, $, _) {

    var baseModel = app.module("models").getBaseModel();

    mod.models = {
        notification : {
            user : baseModel.extend({
                //response_type : "object",
                //idAttribute : "user_id",
                urlRoot : "/module/notification/item/me"
                /*
                urlRoot : function() {
                    return "/module/enroll/item/users/" + this.get("role_id")
                } 
                */
            })
        }
    };

	var notificationWidgetViewClass = Backbone.View.extend({
		_slider : null,
		events : {
			"click .dismiss-action" : "removeAlert"
		},
		emptyTemplate : _.template($("#notification-lastest-empty").html(), null, {variable: "model"}),
	    initialize: function() {
	    	//current_course
	    	console.info('panel.notification/notificationWidgetViewClass::initialize');

	    	// CREATE THE PSEUDO-CAROUSELL
            this._slider = this.$('.carroussel').bxSlider({
            	pager: false,
            	controls : false,
            	auto: true,
            	autoHover : true,
            	autoDelay : 7500,
            	pause : 7500
            });
	    },
		removeAlert : function(e) {
			var notification_id = $(e.currentTarget).parents("li").data("entityId");

			if (notification_id) {

				var notificationModel = new mod.models.notification.user({
					id : notification_id,
					viewed : 1
				});

				notificationModel.save();
			}

			$(e.currentTarget).parents("li").remove();

			if (this._slider) {
				this._slider.reloadSlider();
			}
			// Check For notifications
			if (this._slider.getSlideCount() == 0) {
				this.$(".carroussel").append(
					this.emptyTemplate()
				);
			}
		},
		/*
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

	    }
	    */
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
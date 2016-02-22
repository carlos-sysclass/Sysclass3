$SC.module("panel.users", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	// 
	mod.models = {
		course_stats : Backbone.DeepModel.extend({
			urlRoot : "/module/courses/stats/me"
		})
	}
	var usersWidgetViewClass = Backbone.View.extend({
	    
	    events: {
	    	"change :input[name='current_course']" : 'setCourse',
	    },
	    initialize: function() {
	    	//current_course
	    	console.info('panel.users/usersWidgetViewClass::initialize');

	    	//this.statsModel = new mod.models.course_stats();

	    	this.listenTo(this.model, "change:course_id", this.loadCourseDetails.bind(this));

	    	this.listenTo(this.model, "sync", this.injectCourseDetails.bind(this));

	    	//this.$(":input[name='current_course']").select2('val', this.model.get("course_id"));
	    	//this.statsModel.set("id", this.model.get("course_id"));

	    	//this.statsModel.fetch();
	    },
	    render: function(collection) {
	    	console.info('panel.users/usersWidgetViewClass::render');
	    },
	    setCourse : function(e,a,b,c,d) {
	    	console.info('panel.users/usersWidgetViewClass::setCourse');

	    	

	    	this.model.set("course_id", $(e.currentTarget).val());

	    },
	    loadCourseDetails : function(model) {
	    	console.warn(model);
			console.info('panel.users/usersWidgetViewClass::loadCourseDetails');

			var html = '<div class="loading-message loading-message-boxed">' +
				'<a href="javascript:void(0)" class="btn">' +
					'<i class="fa fa-circle-o-notch fa-spin"></i> Loading' +
				'</a>' +
			'</div>';

			$(".user-course-details").block({
				message: html,
				centerY: true,
				css: {
					top: '10%',
					width: '50%',
					left : '50%',
					right : '50%',
					border: '0',
					padding: '0',
					backgroundColor: 'none'
				},
				overlayCSS: {
					backgroundColor: '#555',
					opacity: 0.1,
					cursor: 'wait'
				}
			});

			//this.statsModel.set("id", this.model.get("course_id"));
			//this.statsModel.fetch();

			// LOAD COURSE DETAILS AND INJECT

			//window.setTimeout(1500, this.$(".user-course-details").unblock());
	    },
	    injectCourseDetails : function() {
	    	this.$(".course_name").html(this.model.get("course_name"));
	    	//this.$(".enroll_token").html(this.model.get("enroll_token"));

	    	//this.$(".total_classes").html(this.statsModel.get("total_classes"));
	    	//this.$(".total_lessons").html(this.statsModel.get("total_lessons"));
	    	/*
	    	this.$(".progress-text").html(
    			app.module("views").formatValue(
					this.statsModel.get("progress.course"),
					'decimal-custom',
					'0.[00]%'
    			)
	    	);
	    	*/

	    	//this.$(".user-course-details").unblock();
	    }
  	});

	mod.on("start", function() {
		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.usersWidgetView = new usersWidgetViewClass({
				el: '#users-panel',
				model : app.userSettings,
			});
		}.bind(this));
	});
});
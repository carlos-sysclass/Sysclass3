$SC.module("panel.users", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	// 
	mod.models = {
		course_stats : Backbone.DeepModel.extend({
			urlRoot : "/module/courses/stats/me"
		})
	}
	var usersWidgetViewClass = Backbone.View.extend({
	    
	    initialize: function() {
	    	//current_course
	    	console.info('panel.users/usersWidgetViewClass::initialize');

	    	//this.statsModel = new mod.models.course_stats();

	    	//this.listenTo(this.model, "change:course_id", this.loadCourseDetails.bind(this));

	    	//this.listenTo(this.statsModel, "sync", this.injectCourseDetails.bind(this));

	    	//this.$(":input[name='current_course']").select2('val', this.model.get("course_id"));

	    	//var user_pointer = app.getResource("user_pointer");

	    	//this.statsModel.set("id", user_pointer.program_id);

	    	//this.statsModel.fetch();
	    	this.render();

	    	this.listenTo(this.collection, "program.changed", this.render.bind(this));

	    	
	    },
	    render: function(collection) {
	    	this.$(".course_name").html(this.collection.getCurrentProgram().get("name"));
	    },
		/*
	    loadCourseDetails : function(model) {
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
	    	this.$(".course_name").html(this.statsModel.get("name"));

	    	//this.$(".enroll_token").html(this.model.get("enroll_token"));

	    	//this.$(".total_classes").html(this.statsModel.get("total_classes"));
	    	//this.$(".total_lessons").html(this.statsModel.get("total_lessons"));

	    	//this.$(".user-course-details").unblock();
	    }
	    */
  	});


	var overallProgressViewClass = Backbone.View.extend({
		el: $('#progress-content'),
		portlet: $('#courses-widget'),
		initialize: function() {
			this.listenTo(this.collection, 'sync', this.render.bind(this));
			this.initializeElements();

			this.render();

		},
		initializeElements : function() {

			if (jQuery.fn.easyPieChart) {

				this.$(".unit").easyPieChart({
					animate: 1000,
					size: 150,
					lineWidth: 20,
					lineCap : 'butt',
					barColor: App.getLayoutColorCode('red'),
					scaleColor : false
				});
				/*
				this.$(".lesson").easyPieChart({
					animate: 1000,
					size: 75,
					lineWidth: 9,
					lineCap : 'square',
					barColor: App.getLayoutColorCode('green'),
					scaleColor : false
				});
				this.$(".class").easyPieChart({
					animate: 1000,
					size: 75,
					lineWidth: 9,
					lineCap : 'square',
					barColor: App.getLayoutColorCode('yellow'),
					scaleColor : false
				});
				this.$(".course").easyPieChart({
					animate: 1000,
					size: 75,
					lineWidth: 9,
					lineCap : 'square',
					barColor: App.getLayoutColorCode('red'),
					scaleColor : false
				});
				*/
			}
		},
		render : function() {
			/*
			this.renderCourse(
				this.model.get('current_days'), this.model.get('total_days')
			);
			this.renderClass(
				this.model.get('classes.completed'), this.model.get('classes.total')
			);
			this.renderLesson(
				this.model.get('lessons.completed'), this.model.get('lessons.total')
			);
			*/
			this.renderUnit(
				this.collection.getTotalCompleteUnits(), this.collection.getTotalUnits()
			);
		},
		/*
		renderCourse : function(completed, total) {
			// INJECT HERE PARTIAL PROGRESS FROM LESSONS
			var factor = 0;
			if (total > 0) {
				factor = completed / total;
			}
			this.$(".course span").html(
				app.module("views").formatValue(
					factor,
					'decimal-custom',
					'0%'
				)
			);

			this.$(".course-counter").html(completed + "/" + total);

			if (jQuery.fn.easyPieChart) {
				var percent = factor * 100;

				if (_.isObject(this.$(".course").data('easyPieChart'))) {
					this.$(".course").data('easyPieChart').update(percent);
				}
			}
		},
		renderClass : function(completed, total) {
			// INJECT HERE PARTIAL PROGRESS FROM LESSONS
			var factor = 0;
			if (total > 0) {
				factor = completed / total;
			}

			this.$(".class span").html(
				app.module("views").formatValue(
					factor,
					'decimal-custom',
					'0%'
				)
			);

			this.$(".class-counter").html(completed + "/" + total);

			if (jQuery.fn.easyPieChart) {
				var percent = factor * 100;

				if (_.isObject(this.$(".class").data('easyPieChart'))) {
					this.$(".class").data('easyPieChart').update(percent);
				}
			}
		},
		renderLesson : function(completed, total) {
			// INJECT HERE PARTIAL PROGRESS FROM LESSONS
			var factor = 0;
			if (total > 0) {
				factor = completed / total;
			}

			this.$(".lesson span").html(
				app.module("views").formatValue(
					factor,
					'decimal-custom',
					'0%'
				)
			);

			this.$(".lesson-counter").html(completed + "/" + total);

			if (jQuery.fn.easyPieChart) {
				var percent = factor * 100;

				if (_.isObject(this.$(".lesson").data('easyPieChart'))) {
					this.$(".lesson").data('easyPieChart').update(percent);
				}
			}
		},
		*/
		renderUnit : function(completed, total) {
			// INJECT HERE PARTIAL PROGRESS FROM LESSONS
			var factor = 0;
			if (total > 0) {
				factor = completed / total;
			}

			this.$(".unit span").html(
				app.module("views").formatValue(
					factor,
					'decimal-custom',
					'0%'
				)
			);

			//this.$(".unit-counter").html(completed + "/" + total);

			if (jQuery.fn.easyPieChart) {
				var percent = factor * 100;

				if (_.isObject(this.$(".unit").data('easyPieChart'))) {
					this.$(".unit").data('easyPieChart').update(percent);
				}
			}
		}
	});

	mod.on("start", function() {
		/*
		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.usersWidgetView = new usersWidgetViewClass({
				el: '#users-panel',
				model : app.userSettings,
			});
		}.bind(this));
		*/
	});

	this.listenTo(app, "progress.started", function() {
		// LISTEN TO MODULE EVENTS TO UPDATE THE UI AS WELL
		this.usersWidgetView = new usersWidgetViewClass({
			el: '#users-panel',
			//model : app.userSettings,
			collection : app.module("portlet.content").programsCollection
		});

		this.overallProgressView = new overallProgressViewClass({
	    	el : $("#progress-user"),
	    	collection : app.module("portlet.content").progressCollection
	    	//model : this.statsModel
	    });

	});
});
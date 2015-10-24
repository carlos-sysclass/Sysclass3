$SC.module("portlet.tutoria", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
	  	var baseFormClass = app.module("views").baseFormClass;
	  	var tutoriaFormViewClass = baseFormClass.extend({

			initialize: function() {
		    	console.info('portlet.tutoria/tutoriaFormViewClass::initialize');
		    	baseFormClass.prototype.initialize.apply(this);

		    	var self = this;
				this.on("after:save", function(model) {
					self.model = new mod.models.tutoria({
						title : ""
					});
					self.render();
				});
			},
		    handleValidation: function() {
		    	console.info('views/baseFormClass::handleValidation');
		    	var self = this;
				this.oForm.validate({
					ignore: null,
	                errorElement: 'span', //default input error message container
	                errorClass: 'help-block', // default input error message class
					errorPlacement: function(error, element) {
						error.appendTo( element.closest(".chat-form") );
					},
					highlight: function (element) { // hightlight error inputs
						// set error class to the control group
						$(element).closest('.form-group').addClass('has-error')
							.find(".input-group-btn button").removeClass("blue").addClass("red");
					},
					unhighlight: function (element) { // revert the change done by hightlight
						$(element).closest('.form-group').removeClass('has-error')
							.find(".input-group-btn button").removeClass("red").addClass("blue");
					},
					success: function (label) {
						label.closest('.form-group').removeClass('has-error'); // set success class to the control group
					},
					submitHandler : function(f) {
						self.save();
					}
				});
		    }
	  	});

		var parent = app.module("portlet");

		var tutoriaBlockViewItemClass = parent.blockViewItemClass.extend({
			tagName : "div",
			template : _.template($("#tutoria-item-template").html(), null, {variable: "model"})
		});

		var tutoriaBlockViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#news-nofound-template").html()),
			childViewClass : tutoriaBlockViewItemClass
		});

		this.tutoriaWidgetViewClass = parent.widgetViewClass.extend({
			collectionClass : mod.collections.tutoria,
			blockViewClass : tutoriaBlockViewClass,
			onStart : function() {

				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS
				this.tutoriaFormView = new tutoriaFormViewClass({
					el: '#tutoria-widget-form',
					model: new mod.models.tutoria()
				});

				this.listenTo(this.tutoriaFormView, "after:save", function(model) {
					this.collection.add(model, {at: 0});
				});
			},
			onFullScreen : function() {
				this.$(".scroller").slimScroll({destroy: true});
				this.$(".scroller").css("height", "auto");
			},
			onRestoreScreen : function() {
				app.module("ui").handleScrollers(this.$el);
			}
		});
	});

	this.models = {
		tutoria : Backbone.DeepModel.extend({
			urlRoot : "/module/tutoria/item/question"
		})
	};
	this.collections = {
		tutoria : Backbone.Collection.extend({
			url : "/module/tutoria/items/question",
			model : this.models.tutoria
		})
	};

	mod.on("start", function() {

		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			mod.tutoriaWidgetView = new this.tutoriaWidgetViewClass({
				model : app.userSettings,
				el: '#tutoria-widget'
			});

		}.bind(this));
	});
});

// THINK ABOUT BREAKING THIS MODULE IN TWO.


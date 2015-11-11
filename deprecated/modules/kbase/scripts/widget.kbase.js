$SC.module("portlet.kbase", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
	  	var baseFormClass = app.module("views").baseFormClass;
	  	var kbaseFormViewClass = baseFormClass.extend({
			initialize: function() {
		    	console.info('portlet.kbase/kbaseFormViewClass::initialize');
		    	baseFormClass.prototype.initialize.apply(this);

		    	var self = this;
				this.on("after:save", function(model) {
					self.model = new mod.models.kbase({
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

		var kbaseBlockViewItemClass = parent.blockViewItemClass.extend({
			tagName : "div",
			template : _.template($("#kbase-item-template").html(), null, {variable: "model"})
		});

		var kbaseBlockViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#kbase-nofound-template").html()),
			childViewClass : kbaseBlockViewItemClass
		});

		this.kbaseWidgetViewClass = parent.widgetViewClass.extend({
			collectionClass : mod.collections.kbase,
			blockViewClass : kbaseBlockViewClass,
			onStart : function() {

				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS
				this.kbaseFormView = new kbaseFormViewClass({
					el: '#kbase-widget-form',
					model: new mod.models.kbase()
				});

				this.listenTo(this.kbaseFormView, "after:save", function(model) {
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
		kbase : Backbone.DeepModel.extend({
			urlRoot : "/module/kbase/item/question"
		})
	};
	this.collections = {
		kbase : Backbone.Collection.extend({
			url : "/module/kbase/items/question",
			model : this.models.kbase
		})
	};

	mod.on("start", function() {

		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			mod.kbaseWidgetView = new this.kbaseWidgetViewClass({
				model : app.userSettings,
				el: '#kbase-widget'
			});

		}.bind(this));
	});
});

$SC.module("portlet.tutoria", function(mod, MyApp, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	this.collection = new Backbone.Collection;
		this.collection.url = "/module/tutoria/data";
	  	
	  	// VIEWS
	  	var viewClass = Backbone.View.extend({
		    el: $('#tutoria-accordion'),
		    portlet: $('#tutoria-widget'),

		    itemTemplate: _.template($('#tutoria-item-template').html()),
		    noDataFoundTemplate: _.template($('#tutoria-nofound-template').html()),

		    initialize: function() {
				this.listenTo(mod.collection, 'sync', this.render);
				mod.collection.fetch();
		    },
		    render: function(collection) {
				this.$el.empty();

				if (collection.size() == 0) {
					this.$el.append(this.noDataFoundTemplate());
				} else {
					var self = this;

					collection.each(function(model,i) {
						self.$el.append(
							self.itemTemplate(model.toJSON())
						);
					});
				}
		    }
	  	});

		this.view = new viewClass();
		this.searchBy = "title";

		this.onFullscreen = function(e, portlet) {
			this.view.$el.css({
				'height': 720
			});
		};
		this.onRestorescreen = function(e, portlet) {
			this.view.$el.css({
				'height': 'auto'
			});
		};

	  	// VIEWS
	  	var formClass = Backbone.View.extend({
		    el: $('#tutoria-widget-form'),
		    initialize: function() {

				this.$el.validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					focusInvalid: false, // do not focus the last invalid input
					ignore: "",
					rules: {
						title: {
							minlength: 10,
							required: true
						}
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
					errorPlacement: function(error, element) {
						error.appendTo( element.closest(".chat-form") );
					},
					submitHandler: function (form) {
						jQuery.post(
						  $(form).attr("action"),
						  $(form).serialize(),
						  function(response, status) {
							//toastr[response.message_type](response.message);
							$(form).find(":input").val("");
							//mod.collection.fetch();
						  }
						);
					}
				});
		    }
	  	});

		this.formView = new formClass();
	});
});
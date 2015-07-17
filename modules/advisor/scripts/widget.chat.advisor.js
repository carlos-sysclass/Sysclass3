$SC.module("portlet.advisor.chat", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
		var parent = app.module("portlet");

		var advisorChatBlockViewItemClass = parent.blockViewItemClass.extend({
			tagName : "div",
			template : _.template($("#kbase-item-template").html(), null, {variable: "model"})
		});

		var advisorChatBlockViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#kbase-nofound-template").html()),
			childViewClass : advisorChatBlockViewItemClass
		});

		//this.advisorChatWidgetViewClass = parent.widgetViewClass.extend({
		this.advisorChatWidgetViewClass = Backbone.View.extend({
			chatModule : app.module("utils.strophe"),
			events : {
				"click .start-chat-action" : "startChat"
			},
			initialize : function() {
				// LISTEN TO chatModule to Execute interface
				// $SC.module("utils.strophe").startChat('support1@enterprise.sysclass.com');

				/*
				this.listenTo(this.chatModule, "xmpp:roster:sync")
		$SC.module("utils.strophe").on(, function(col) {
			mod.view.start(col);
		});
				*/
			},
			startChat : function() {
				this.chatModule.start();
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

			mod.advisorChatWidgetView = new this.advisorChatWidgetViewClass({
				model : app.userSettings,
				el: '#advisor-chat-widget'
			});

		}.bind(this));
	});
});

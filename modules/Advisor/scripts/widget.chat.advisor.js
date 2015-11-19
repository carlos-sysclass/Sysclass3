$SC.module("portlet.advisor.chat", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
		var parent = app.module("portlet");
/*
		var advisorChatBlockViewItemClass = parent.blockViewItemClass.extend({
			tagName : "div",
			template : _.template($("#kbase-item-template").html(), null, {variable: "model"})
		});

		var advisorChatBlockViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#kbase-nofound-template").html()),
			childViewClass : advisorChatBlockViewItemClass
		});
*/
		//this.advisorChatWidgetViewClass = parent.widgetViewClass.extend({
		this.advisorChatWidgetViewClass = Backbone.View.extend({
			collection : null,
			chatModule : app.module("utils.chat"),
			events : {
				"click .start-chat-action" : "startChart"
			},
			initialize : function() {
				// LISTEN TO chatModule to Execute interface
				// 'support1@enterprise.sysclass.com');

				this.listenToOnce(this.chatModule, "beforeConnection.chat", function(status) {
					this.$(".block-title").hide();
					this.$(".chat-loader").show();
				}.bind(this));

				this.listenToOnce(this.chatModule, "errorConnection.chat", function(status) {
					this.$(".chat-loader").fadeOut(1500, function() {
						this.$el.addClass("advisor-chat-error");
						this.$(".block-error").show();
						this.$(".start-chat-action").addClass("disabled").attr("disabled", "disabled");
					}.bind(this));
				}.bind(this));


				this.listenToOnce(this.chatModule, "afterConnection.chat", function(status) {
					//if (status == Strophe.Status.CONNECTED) {
						this.$(".chat-loader").fadeOut(1500, function() {
							this.$(".chat-loader").hide();
							this.$(".block-title").show();
						}.bind(this));


					//}
				}.bind(this));
				/*
				this.listenToOnce(this.chatModule, "xmpp:startchat", function(status) {
					this.$(".chat-loader").fadeOut(500, function() {
						this.$(".block-title").show();
						this.$("#chat-action-container").hide();
					}.bind(this));
				}.bind(this));

				this.listenToOnce(this.chatModule, "xmpp:roster:sync", function(collection) {
					this.collection = collection;
					this.startChart();
				}.bind(this));
				*/
				if (!this.chatModule.started) {
					this.chatModule.start();
				}
			},
			startChart : function() {
				if (!this.chatModule.started) {
					this.chatModule.start();
				}

				this.chatModule.createQueue("advisor", "Advisor");
			}
		});
	});
/*
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
*/
	mod.on("start", function() {

		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			mod.advisorChatWidgetView = new this.advisorChatWidgetViewClass({
				model : app.userSettings,
				el: '#advisor-chat-widget'
			});

		}.bind(this));
	});
});

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
			chatModule : app.module("utils.strophe"),
			events : {
				"click .start-chat-action" : "startChart"
			},
			initialize : function() {
				// LISTEN TO chatModule to Execute interface
				// 'support1@enterprise.sysclass.com');
				this.listenToOnce(this.chatModule, "xmpp:connect:before", function(status) {
					this.$(".block-title").hide();
					this.$(".chat-loader").show();
				}.bind(this));

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
			},
			startChart : function() {
				if (!this.chatModule.started) {
					this.chatModule.start();
				}
				if (!_.isNull(this.collection)) {
					if (this.collection.size() > 0) {

						// TODO: DO A LOOP TO CHECK NEXT online support USER.
						var modelToChat = this.collection.at(0);
						if (modelToChat.get("status") != "offline") {
							this.chatModule.startChat(modelToChat.get("id"));
						}
					} else {
						// Show Unavaliable Message
					}
				}
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

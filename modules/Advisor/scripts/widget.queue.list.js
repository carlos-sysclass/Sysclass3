$SC.module("portlet.advisor.queue.list", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.on("start", function() {
	  	// VIEWS
		//var parent = app.module("portlet");
		mod.chatModule = app.module("utils.chat");

		this.queueItemViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			tagName : 'li',
			className : "list-group-item",
			template : _.template($("#queue-list-item-template").html(), null, {variable : 'model'}),
			events : {
				"click .view-chat-action" : "viewChatAction",
				"hover" : "hoverAction"
			},
			hoverAction : function() {
				this.$el.toggleClass("hover-white");
			},
			render : function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			},
			viewChatAction : function() {
				this.chatModule.subscribeToChat(this.model.get("topic"), this.model);
				//this.chatModule.startQueueView(this.model);
			}
		});
		this.advisorChatWidgetViewClass = Backbone.View.extend({
			collection : null,
			chatModule : mod.chatModule,
			events : {
				"click .start-chat-action" : "startChart"
			},
			initialize : function() {
				this.listenTo(this.chatModule, "beforeConnection.chat", function(status) {
					this.$(".chat-loader").show();
					this.$(".block-error").hide();
					this.$(".block-title").hide();
				}.bind(this));

				this.listenTo(this.chatModule, "errorConnection.chat", function(status) {
					this.$(".chat-loader").hide();
					this.$(".block-error").show();
					this.$(".block-title").hide();
					this.$el.addClass("advisor-chat-error");
					this.$(".start-chat-action").addClass("disabled").attr("disabled", "disabled");
				}.bind(this));

				this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
					this.$(".chat-loader").hide();
					this.$(".block-error").hide();
					this.$(".block-title").show();
					this.$el.removeClass("advisor-chat-error");
					this.$(".start-chat-action").removeClass("disabled").removeAttr("disabled", "disabled");

				}.bind(this));

				this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
					this.chatModule.getUnassignedQueues(function(list) {
						//console.warn(list);
						this.collection.reset(list);
					}.bind(this));
				}.bind(this));

				this.listenTo(this.collection, "reset", this.render);

				if (!this.chatModule.started) {
					this.chatModule.start();
				}
			},
			render : function() {
				this.$(".queue-list").empty();
				this.collection.each(this.addOne);
			},
			addOne : function(model) {
				var itemView = new mod.queueItemViewClass({
					model: model
				});

				this.$(".queue-list").prepend(itemView.render().el);
			},
			startChart : function() {
				this.chatModule.createQueue("advisor", "Advisor");
			}
		});


		this.models = {
			queue : Backbone.DeepModel.extend()
		};

		this.collections = {
			queues : Backbone.Collection.extend({
				url : "/module/kbase/items/question",
				model : this.models.queue
			})
		};
		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			mod.advisorChatWidgetView = new this.advisorChatWidgetViewClass({
				model : app.userSettings,
				el: '#advisor-queue-list',
				collection : new this.collections.queues()
			});

		}.bind(this));
	});
});

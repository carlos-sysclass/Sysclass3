$SC.module("portlet.advisor.queue.list", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.on("start", function() {
	  	// VIEWS
		//var parent = app.module("portlet");
		mod.chatModule = app.module("utils.chat");
		/*
		this.queueItemViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			tagName : 'tr',
			//className : "list-group-item",
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
				e.preventDefault();
				this.chatModule.subscribeToChat(this.model.get("topic"), this.model);
			}
		});
		*/

		this.advisorQueueListWidgetViewClass = Backbone.View.extend({
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
				/*
				this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
					this.chatModule.getUnassignedQueues(function(list) {
						this.collection.reset(list);
					}.bind(this));
				}.bind(this));

				//this.listenTo(this.collection, "reset", this.render);
				*/
				if (!this.chatModule.started) {
					this.chatModule.start();
				}
			}
			/*
			render : function() {
				$SC.getTable("view-advisor_queue_list").destroy();

				this.$(".queue-list tbody").empty();
				this.collection.each(this.addOne);

				$SC.getTable("view-advisor_queue_list").recreate();
			},
			addOne : function(model) {
				var itemView = new mod.queueItemViewClass({
					model: model
				});

				this.$(".queue-list tbody").prepend(itemView.render().el);
			},
			*/
		});

		this.bindTableEvents = function(table) {
			
			this.listenTo(table, "action.datatables", function(el, data, action) {
				if (action == "view") {
					var queueModel = new this.models.queue(data);

					this.chatModule.subscribeToChat(queueModel.get("topic"), queueModel);

	        	} else if (action == "remove") {
					var queueModel = new this.models.queue(data);

					queueModel.destroy();
					//this.chatModule.subscribeToChat(queueModel.get("topic"), queueModel);

	        	}
			}.bind(this));
		};	

		app.on("added.table", function(name, table) {
			if (name == "view-advisor_queue_list") {
				this.bindTableEvents(table);
			}
		}.bind(this));

		this.models = {
			queue : Backbone.DeepModel.extend({
				urlRoot : "/module/chat/item/me"
			})
		};

		this.collections = {
			queues : Backbone.Collection.extend({
				model : this.models.queue
			})
		};
		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			mod.advisorChatWidgetView = new this.advisorQueueListWidgetViewClass({
				model : app.userSettings,
				el: '#advisor-queue-list',
				collection : new this.collections.queues()
			});

		}.bind(this));



	});
});

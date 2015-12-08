$SC.module("menu.chat", function(mod, app, Backbone, Marionette, $, _) {

	this.sidebarChatView = null;
	this.canStart = false;

	this.startChatSidebar = function() {
		if (this.canStart && _.isNull(this.sidebarChatView) && $('body').hasClass('page-quick-sidebar-open')) {
			this.started = true;
			this.sidebarChatView = new this.sidebarChatViewClass({
				model : app.userSettings,
				el: '#page-quick-sidebar',
				collection : new this.collections.queues()
			});
		}
	}

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

	this.on("start", function() {

	    $('.dropdown-quick-sidebar-toggler a, .page-quick-sidebar-toggler, .quick-sidebar-toggler').click(function (e) {
	        $('body').toggleClass('page-quick-sidebar-open'); 

	       	this.startChatSidebar();
	    }.bind(this));

		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.canStart = true;

			this.startChatSidebar();
		}.bind(this));

		mod.chatModule = app.module("utils.chat");


		this.sidebarChatQueueViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			tagName : 'li',
			className : "media",
			template : _.template($("#sidebar-chat-queue-template").html(), null, {variable : 'model'}),
			initialize : function() {
				this.listenTo(this.model, "change", this.render.bind(this));

				this.wrapperChat = this.$el.parents('.page-quick-sidebar-chat');

				//this.listenTo(this.chatModule, "receiveMessage.chat", this.refreshCounter.bind(this));
				this.chatModule.subscribeToChat(this.model.get("topic"), this.model, true);
			},
			events : {
				"click" : "startChatAction",
				"hover" : "hoverAction",
			},
			hoverAction : function() {
				this.$(".media-status button.show-hover").toggleClass("hidden");
			},
			render : function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			},
			startChatAction : function() {
				mod.trigger("startChat.sidebar", this.model);


			},
			refreshCounter : function(topic, data) {
				if (topic == this.model.get("topic")) {
					//this.model.set("")
				}
			}
		});


	    var messageItemViewClass = Backbone.View.extend({
	        //tagName: "li",
	        templates : {
	            info : _.template($('#sidebar-chat-item-info-template').html(), null, {variable : "model"}),
	            all : _.template($('#sidebar-chat-item-template').html(), null, {variable : "model"})
	        },
	        render: function() {
	            if (_.has(this.templates, this.model.get("type"))) {
	                var template = this.templates[this.model.get("type")];
	            } else {
	                var template = this.templates['all'];
	            }
	            this.$el.html(template(this.model.toJSON()));

	            return this;
	        }
	    });

		this.sidebarChatConversationViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			baseHeight:  0,
			template : _.template($("#sidebar-conversation-item-template").html(), null, {variable : 'model'}),
			events : {
				"keyup .page-quick-sidebar-chat-user-form input" : "keyenter",
				"click .page-quick-sidebar-chat-user-form button" : "send",
				"click .page-quick-sidebar-back-to-list" : "stop"
			},
			initialize : function(opt) {
				console.info("menu.chat/sidebarChatConversationViewClass::initialize", this);

				//this.chatModule.subscribeToChat(this.model.get("topic"), this.model);
				this.baseHeight = opt.height;

				this.start();
			},
			render : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::send", this);

				this.$el.html(this.template(this.model.toJSON()));

				this.$(".user-details .media-heading").html(this.model.get("requester.name") + ' ' + this.model.get("requester.surname"));
				this.$(".user-details .media-heading-sub").html("COURSE #1");

				this.messageContainer = this.$('.page-quick-sidebar-chat-user-messages');
			
				return this;
			},
			updateScrolls : function(height) {
	            var chatMessagesHeight = this.baseHeight - this.$('.page-quick-sidebar-chat-user-form').outerHeight(true);
	            chatMessagesHeight = chatMessagesHeight - this.$('.page-quick-sidebar-nav').outerHeight(true);

	            this.$(".page-quick-sidebar-chat-user-messages")
	            	.attr("data-height", chatMessagesHeight)
	            	.addClass("scroller");

	            app.module("ui").handleScrollers(this.$el);
			},
			send : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::send", this);
                if (this.$(".send-message-input").val() != "") {
                    var message = {
                        topic     : this.model.get("topic"),
                        //from        : "me",
                        message    : this.$(".send-message-input").val()
                    };

                    this.chatModule.sendMessage(
                        this.model.get("topic"), 
                        message
                    );
                    this.$(".send-message-input").val("");
                }
			},
	        keyenter: function(e) {
	            if ( e.which == 13 ) {
	                e.preventDefault();
	                this.send();
	            }
	        },
			addOne : function(topic, data) {
				console.info("menu.chat/sidebarChatConversationViewClass::addOne", this);
	            if (topic == this.model.get("topic")) {
	                var model = new this.chatModule.models.message(data);

	                var view = new messageItemViewClass({model: model});

	                this.messageContainer.append(view.render().el);


	                this.messageContainer.slimScroll({
    		            scrollTo: '1000000px'
					});
	            }
			},
			start : function() {
				this.$el.removeClass("hidden");
				this.listenTo(this.chatModule, "receiveMessage.chat", this.addOne.bind(this));
			},
			stop : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::stopChat", this);
				this.$el.addClass("hidden");
				this.stopListening(this.chatModule);

				mod.trigger("stopChat.sidebar", this.model);

				
			}
			/*
			stop : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::remove", this);
				this.messageContainer.empty();
				this.stopListening();
				this.undelegateEvents();
				//this.chatModule.unsubscribeToChat(this.model.get("topic"));
			}
			*/
		});

		this.sidebarChatViewClass = Backbone.View.extend({
			collection : null,
			chatModule : mod.chatModule,
			conversationViews : {},
			conversationHeight : 0,
			events : {
				//"click .default-queue-list .media" : "startChart"
			},
			blockingOptions : {
			    css: {
			        border: '0px',
			        padding: '5px 10px 10px 10px',
			        backgroundColor: 'transparent',
			        opacity: 1,
			        color: "#aaa",
			        width: 'auto'
			    },
			    overlayCSS: {
			        backgroundColor: '#888',
			        opacity: 0.9,
			        cursor: 'wait'
			    }
			},
			initializeScrolls : function() {
	        	var wrapper = $('.page-quick-sidebar-wrapper');
	        	var wrapperChat = this.$('.page-quick-sidebar-chat');

	            var chatUsers = this.$('.page-quick-sidebar-chat-users');
	            var chatUsersHeight;

	            chatUsersHeight = this.$el.height() - this.$('.nav-tabs').outerHeight(true);

	            // chat user list 
				chatUsers
	            	.attr("data-height", chatUsersHeight)
	            	.addClass("scroller");	            

				app.module("ui").handleScrollers(this.$el);

	            //var chatMessages = wrapperChat.find('.page-quick-sidebar-chat-user-messages');

	            this.conversationHeight = chatUsersHeight;

	            /*
	            chatMessages
	            	.attr("data-height", chatMessagesHeight)
	            	.addClass("scroller");

	            app.module("ui").handleScrollers(this.$el);	            

	            */
			},
			initialize : function() {
				this.initializeScrolls();

				this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
					console.warn(status);
					this.$el.unblock();
					this.chatModule.getQueues(this.renderChatQueues.bind(this));
					this.chatModule.subscribe("chat-events", this.receiveChatUpdates.bind(this));
				}.bind(this));

				//this.listenTo(this.collection, "reset", this.renderChatQueues.bind(this));

				this.listenTo(this.chatModule, "beforeConnection.chat", function(status) {
					var html = '<i class="fa">' +
						'<span class="fa fa-circle-o-notch fa-4x fa-spin"></span>' +
					'</i>';

					this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
				}.bind(this));

				this.listenTo(this.chatModule, "errorConnection.chat", function(status) {
					var html = '<div class="">' +
					        '<i class="fa fa-lg fa-close text-danger"></i><br /> ' + 
					      'No Connection' +
					'</div>';

					this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
					console.warn(this.$el.data('blockUI.isBlocked'));
				}.bind(this));

				this.listenTo(mod, "startChat.sidebar", this.startChat.bind(this));

				/*
				this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
					this.chatModule.getUnassignedQueues(function(list) {
						//console.warn(list);
						this.collection.reset(list);
					}.bind(this));
				}.bind(this));

				//this.listenTo(this.collection, "reset", this.render);
				*/
				if (!this.chatModule.started) {
					this.chatModule.start();
				}
			},
			renderChatQueues : function(result) {
				console.warn(result);
				this.collection.reset(result);

				this.$(".default-queue-list").empty();
				this.collection.each(this.addOneChatQueue);
				app.module("ui").refresh(this.$(".default-queue-list"));
			},
			addOneChatQueue : function(model) {
				var itemView = new mod.sidebarChatQueueViewClass({
					model: model
				});

				//if (model.get("sticky")) {
				//	this.$(".stick-queue-list").prepend(itemView.render().el);	
				//} else {
					this.$(".default-queue-list").prepend(itemView.render().el);
				//}
			},
			startChat : function(model) {
				console.warn(model.toJSON());
				var topic = model.get("topic");
				this.$('.page-quick-sidebar-chat').addClass("page-quick-sidebar-content-item-shown");

				if (
					_.has(this.conversationViews, topic) &&
					_.isObject(this.conversationViews[topic])
				) {
					//this.conversationViews[model.get("topic")].focus();
					this.conversationViews[topic].start();
				} else {
					this.conversationViews[topic] = new mod.sidebarChatConversationViewClass({
						model: model,
						height: this.conversationHeight
						//el: this.$('.page-quick-sidebar-chat-user')
					});

					this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));

					this.$('.page-quick-sidebar-chat-user').append(
						this.conversationViews[topic].render().el
					);
				}
				/*
				if (!_.isNull(this.conversationViews[model.get("topic")])) {
					//this.conversationView.remove();
				}
				*/
				//console.warn(this.conversationViews[topic].render());


				this.conversationViews[topic].updateScrolls();
				/*
				// LOAD ALL MESSAGES FROM QUEUE
				// SUBSCRIBE
				
				*/
			},
			stopChat : function(model) {
				//this.conversationViews[topic].
				/*
				if (!_.isNull(this.conversationView)) {
					this.conversationView.remove();
					this.conversationView = null;
				}
				*/
				this.$('.page-quick-sidebar-chat').removeClass("page-quick-sidebar-content-item-shown");

				this.updatesQueues();

				//this.chatModule.getQueues(this.renderChatQueues.bind(this));
			}
		});
		/*
		this.bindTableEvents = function(table) {
			
			this.listenTo(table, "action.datatables", function(el, data, action) {
				console.warn(el, data, action);
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
		*/





	});
});

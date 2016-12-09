$SC.module("sidebar.chat", function(mod, app, Backbone, Marionette, $, _) {

	this.startWithParent = false;
	this.started = false;
	this.sidebarChatView = null;
	this.canStart = false;

	this.startChatSidebar = function() {
		if (this.canStart && _.isNull(this.sidebarChatView) /* && $('body').hasClass('page-quick-sidebar-open') */) {
			this.started = true;
			this.sidebarChatView = new this.sidebarChatViewClass({
				model : app.userSettings,
				el: '#page-quick-sidebar',
				collection : mod.chatModule.getQueuesCollection()
			});
		}
	}

	/**
	 * @todo MOVE RELATED MODELS TO CHAT MODULE
	 */
	 /*
	this.models = {
		queue : Backbone.DeepModel.extend({
			urlRoot : "/module/chat/item/me"
		}),
		conversation : Backbone.DeepModel.extend({}),
	};

	*/
	this.on("start", function() {

	    mod.chatModule = app.module("utils.chat");

		this.listenTo(this.chatModule, "afterConnection.chat", function(topic, model) {
			this.canStart = true;

			if (!this.started) {
	    		this.canStart = true;
	    		this.startChatSidebar();
	    	} else {
				this.sidebarChatView.enableView();
	    	}
		}.bind(this));
		
	    $('#chat-topbar-menu a, .page-quick-sidebar-toggler').click(function (e) {
			if (!this.started) {
	    		this.canStart = true;
	    		this.startChatSidebar();
	    	}

	    	this.sidebarChatView.toggleWidget();

	    }.bind(this));

	    this.listenTo(this.chatModule, "receiveMessage.chat", function(topic, model) {
	    	if (!this.started) {
	    		this.canStart = true;
	    		this.startChatSidebar();
	    	}

			$("#chat-topbar-menu i.fa-comments").css("color", 'red');

	    }.bind(this));

		this.sidebarChatQueueViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			tagName : 'li',
			className : "media",
			template : _.template($("#sidebar-chat-queue-template").html(), null, {variable : 'model'}),
			_isOwner : false,
			events : {
				"click .assign-to-me-action" : "assignToMeAction",
				"click .assign-to-other-action" : "assignToUserAction",
				"click .resolve-action" : "resolveAction",
				"click .delete-action" : "deleteAction",
				"confirmed.bs.confirmation .delete-action" : "deleteAction",
				"click" : "startChatAction",
				"hover" : "hoverAction",
			},
			initialize : function() {
				this.listenTo(this.model, "change", this.render.bind(this));

				this.wrapperChat = this.$el.parents('.page-quick-sidebar-chat');


				this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));
				this.listenTo(mod, "assignToMe.sidebar", this.assignToMe.bind(this));
				this.listenTo(mod, "assignToUser.sidebar", this.assignToUser.bind(this));
				this.listenTo(mod, "resolve.sidebar", this.resolve.bind(this));
				this.listenTo(mod, "delete.sidebar", this.delete.bind(this));

			},
			isOwnership : function(switcher) {
				this._isOwner = switcher;
			},
			hoverAction : function(e) {
				if (e.type == "mouseenter") {
					this.$(".media-status button.show-hover").removeClass("hidden");
				} else if (e.type == "mouseleave") {
					this.$(".media-status button.show-hover").addClass("hidden");
				} else {
					this.$(".media-status button.show-hover").toggleClass("hidden");
				}
			},
			render : function() {
				this.$el.html(this.template(_.extend(
					this.model.toJSON(),
					{isOwner : this._isOwner}
				)));
				return this;
			},
			startChatAction : function() {
				mod.trigger("startChat.sidebar", this.model);
			},
			refreshCounter : function(topic, data) {
				if (topic == this.model.get("topic")) {
					//this.model.set("")
				}
			},
			assignToMeAction : function(e) {
				e.preventDefault();
				e.stopPropagation();
				mod.trigger("assignToMe.sidebar", this.model);
			},
			assignToUserAction : function(e) {
				e.preventDefault();
				e.stopPropagation();
				mod.trigger("assignToUser.sidebar", this.model);
			},
			resolveAction : function () {
				e.preventDefault();
				mod.trigger("reolve.sidebar", this.model);
			},
			deleteAction : function (e) {
				e.preventDefault();
				e.stopPropagation();

				mod.trigger("delete.sidebar", this.model);
				this.listenTo(this.model, "destroy", this.remove.bind(this));
			},

			// FUNCTIONS TO RECEIVE THE TRIGGER
			stopChat : function(model) {
				console.info("menu.chat/sidebarChatQueueViewClass::initialize::stopChat", this);
			},
			assignToMe : function(model) {
				console.info("menu.chat/sidebarChatQueueViewClass::initialize::assignToMe", this);
			},
			assignToUser : function(model) {
				console.info("menu.chat/sidebarChatQueueViewClass::assignToUser", this);
			},
			resolve : function(model) {
				console.info("menu.chat/sidebarChatQueueViewClass::resolve", this);
			},
			delete : function() {
				console.info("menu.chat/sidebarChatQueueViewClass::delete", this);
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
			started : false,
			template : _.template($("#sidebar-conversation-item-template").html(), null, {variable : 'model'}),
			events : {
				"keyup .page-quick-sidebar-chat-user-form input" : "keyenter",
				"click .page-quick-sidebar-chat-user-form button" : "send",
				"click .page-quick-sidebar-back-to-list" : "stopAction",
				"click .assign-to-me-action" : "assignToMeAction",
				"click .assign-to-other-action" : "assignToUserAction",
				"click .resolve-action" : "resolveAction"/*,
				"confirmed.bs.confirmation .delete-action" : "deleteAction"*/
			},
			initialize : function(opt) {
				console.info("menu.chat/sidebarChatConversationViewClass::initialize", this);

				//this.chatModule.subscribeToChat(this.model.get("topic"), this.model);
				this.baseHeight = opt.height;

				// LOAD PREVIOUS CONVERSATION
				this.collection = this.chatModule.getConversation(this.model.get("topic"));
				//this.collection.id = this.model.get("id");
				this.listenTo(this.collection, "request", this.showPreviousLoader.bind(this));
				this.listenTo(this.collection, "sync", this.renderPrevious.bind(this));
				this.listenTo(this.collection, "add", this.addOne.bind(this));


				this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));
				this.listenTo(mod, "assignToMe.sidebar", this.assignToMe.bind(this));
				this.listenTo(mod, "assignToUser.sidebar", this.assignToUser.bind(this));
				this.listenTo(mod, "resolve.sidebar", this.resolve.bind(this));
				this.listenTo(mod, "delete.sidebar", this.delete.bind(this));


				this.start();
			},
			showPreviousLoader : function() {
				this.loader.show();
			},
			renderPrevious : function(collection) {
				var showedDate = moment();
				this.loader.hide();
				this.previousMessageContainer.empty();
				this.currentMessageContainer.empty();
				this.collection.each(function(model, index) {
					var modelDate = moment.unix(model.get("sent"));
					if (!modelDate.isSame(showedDate, 'day')) {

						var infoModel = new this.chatModule.models.message({
							type : "info",
							message : modelDate.format("LL")
						})
						var view = new messageItemViewClass({model: infoModel});
	                	this.previousMessageContainer.append(view.render().el);

						showedDate = modelDate;
					}
					this.addPreviousOne(model, index);
				}.bind(this));

				this.messageContainer.slimScroll({
    		        scrollTo: '1000000px'
				});
				
			},
			render : function() {

				console.info("menu.chat/sidebarChatConversationViewClass::render", this);

				this.$el.html(this.template(this.model.toJSON()));

				this.messageContainer = this.$('.page-quick-sidebar-chat-user-messages');
				this.previousMessageContainer = this.$('.page-quick-sidebar-chat-user-messages-previous');
				this.currentMessageContainer = this.$('.page-quick-sidebar-chat-user-messages-current');
				this.loader = this.$('.chat-loader');

				this.collection.fetch();


				if (this.model.get('another')) {
               		var another = this.model.get('another');
               	} else {
               		var another = this.model.get('from');
               	}

				this.$(".user-details .media-heading").html(another.name + ' ' + another.surname);
				//this.$(".user-details .media-heading-sub").html("COURSE #1");



				app.module("ui").refresh(this.$el);
			
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
	        addPreviousOne : function(model) {
				console.info("menu.chat/sidebarChatConversationViewClass::addPreviousOne", model);

	            if (model.get("chat.topic") == this.model.get("topic")) {
	                //var model = new this.chatModule.models.message(data);

	                var view = new messageItemViewClass({model: model});

	                this.previousMessageContainer.append(view.render().el);
	            }
	        },
			addOne : function(model, collection, oper) {
				console.info("menu.chat/sidebarChatConversationViewClass::addOne", arguments);

	            if (model.get("topic") == this.model.get("topic")) {
	                //var model = new this.chatModule.models.message(data);

	                var view = new messageItemViewClass({model: model});

	                this.currentMessageContainer.append(view.render().el);

	                this.messageContainer.slimScroll({
    		            scrollTo: '1000000px'
					});
	            }
			},
			start : function() {
				if (!this.started) {
					this.started = true;
					this.$el.removeClass("hidden");
					
				}
			},

			// FUNCTIONS TO START THE TRIGGER
			stopAction : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::stopAction", this);
				this.$el.addClass("hidden");
				this.stopListening(this.chatModule);

				this.started = false;

				mod.trigger("stopChat.sidebar", this.model);
			},
			assignToMeAction : function(e) {
				e.preventDefault();
				mod.trigger("assignToMe.sidebar", this.model);
			},
			assignToUserAction : function(e) {
				e.preventDefault();
				mod.trigger("assignToUser.sidebar", this.model);
			},
			resolveAction : function () {
				e.preventDefault();
				mod.trigger("reolve.sidebar", this.model);
			},
			deleteAction : function () {
				mod.trigger("delete.sidebar", this.model);
				this.listenTo(this.model, "destroy", this.remove.bind(this));
			},
			// FUNCTIONS TO RECEIVE THE TRIGGER
			stopChat : function(model) {
				console.info("menu.chat/sidebarChatConversationViewClass::initialize::stopChat", this);
			},
			assignToMe : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::assignToMe", this);
			},
			assignToUser : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::assignToUser", this);
			},
			resolve : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::resolve", this);
			},
			delete : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::delete", this);
			}

		});

		this.sidebarChatViewClass = Backbone.View.extend({
			collection : null,
			chatModule : mod.chatModule,
			userSelectDialog : app.module("dialogs.users.select"),
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

	            this.conversationHeight = chatUsersHeight;
			},
			initialize : function() {
				this.initializeScrolls();

				this.listenTo(this.chatModule, "beforeConnection.chat", function(status) {
					var html = '<i class="fa">' +
						'<span class="fa fa-circle-o-notch fa-4x fa-spin"></span>' +
					'</i>';

					this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
				}.bind(this));

				this.listenTo(this.chatModule, "errorConnection.chat", this.disableView.bind(this));

				//this.listenTo(this.chatModule, "afterConnection.chat", this.enableView.bind(this));

				this.listenTo(mod, "startChat.sidebar", this.startChat.bind(this));

				this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));
				this.listenTo(mod, "assignToMe.sidebar", this.assignToMe.bind(this));
				this.listenTo(mod, "assignToUser.sidebar", this.assignToUser.bind(this));
				this.listenTo(mod, "resolve.sidebar", this.resolve.bind(this));
				this.listenTo(mod, "delete.sidebar", this.delete.bind(this));



				if (this.chatModule.isConnected()) {
					this.enableView();
					//this.$el.unblock();
					//this.chatModule.getQueues(this.renderChatQueues.bind(this));
					//this.chatModule.subscribe("chat-events", this.receiveChatUpdates.bind(this));
				}

				this.listenTo(this.collection, "reset", this.renderChatQueues.bind(this));

			},
			toggleWidget : function() {
				$('body').toggleClass('page-quick-sidebar-open'); 

				if ($('body').hasClass('page-quick-sidebar-open')) {
				}
			},
			enableView : function() {
				console.warn("sidebar->enableView");
				if (this.chatModule.isConnected()) {
					this.$el.unblock();
					//this.chatModule.getQueues(this.renderChatQueues.bind(this));
				}
			},
			disableView : function() {
				var html = '<div class="">' +
				        '<i class="fa fa-lg fa-close text-danger"></i><br /> ' + 
				      'No Connection' +
				'</div>';

				this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
			},
			renderChatQueues : function(result) {
				console.warn("sidebar->renderChatQueues");

				// UPDATE SIDEBAR ICON
				if ($("#chat-topbar-menu > a .badge").size() > 0) {
					$("#chat-topbar-menu > a .badge").html(_.size(result));
				} else {
					$("#chat-topbar-menu > a > i").after("<span class=\"badge badge-warning\">" + _.size(result) +"</span>")
				}

				this.$(".default-queue-list, .stick-queue-list").empty();
				this.collection.each(this.addOneChatQueue.bind(this));
				app.module("ui").refresh(this.$(".default-queue-list, .stick-queue-list"));
				//app.module("ui").refresh(this.$(".default-queue-list"));
			},
			addOneChatQueue : function(model) {
				console.warn("sidebar->addOneChatQueue");
				console.warn(model);
				var itemView = new mod.sidebarChatQueueViewClass({
					model: model
				});

				if (model.get("receiver_id") == this.model.get("user_id")) {
					itemView.isOwnership(true);
					this.$(".stick-queue-list").prepend(itemView.render().el);	
				} else {
					itemView.isOwnership(false);
					this.$(".default-queue-list").prepend(itemView.render().el);
				}
			},
			startChat : function(model) {
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

					this.$('.page-quick-sidebar-chat-user').append(
						this.conversationViews[topic].render().el
					);
				}

				this.conversationViews[topic].updateScrolls();
				/*
				// LOAD ALL MESSAGES FROM QUEUE
				// SUBSCRIBE
				
				*/
			},
			stopChat : function(model) {
				this.$('.page-quick-sidebar-chat').removeClass("page-quick-sidebar-content-item-shown");
			},
			assignToMe : function(model) {
				console.info("menu.chat/sidebarChatViewClass::assignToMe", this);

				model.set("receiver_id", this.model.get("user_id"));
				model.save({}, {
					success : function() {
						this.chatModule.getQueues(this.renderChatQueues.bind(this));
					}.bind(this)
				});
			},
			assignToUser : function(model) {
				console.info("menu.chat/sidebarChatViewClass::assignToUser", this);

				this.userSelectDialog.getValue(function(item) {
					// { user_id="1"}
					model.set("receiver_id", item.user_id);
					model.save({}, {
						success : function() {
							this.chatModule.getQueues(this.renderChatQueues.bind(this));
						}.bind(this)
					});
				}.bind(this));
			},
			resolve : function() {
				console.info("menu.chat/sidebarChatViewClass::resolve", this);
			},
			delete : function() {
				console.info("menu.chat/sidebarChatViewClass::delete", this);
			}
		});
	});

	app.module("utils.chat").on("start", this.start.bind(this));
});

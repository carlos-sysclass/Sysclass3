$SC.module("widget.chat", function(mod, app, Backbone, Marionette, $, _) {
	this.blockChatView = null;
	this.canStart = false;

	this.startChatBlock = function() {
		if (this.canStart && _.isNull(this.blockChatView)) {
			this.started = true;
			this.blockChatView = new this.blockChatViewClass({
				model : app.userSettings,
				el: '#advisor-chat-widget',
				collection : new this.collections.queues()
			});
		}
	}

	/**
	 * @todo MOVE RELATED MODELS TO CHAT MODULE
	 */
	this.models = {
		queue : Backbone.DeepModel.extend({
			urlRoot : "/module/chat/item/me"
		}),
		conversation : Backbone.DeepModel.extend({}),
	};

	this.collections = {
		queues : Backbone.Collection.extend({
			model : this.models.queue
		})
	};

	this.on("start", function() {

	    $('#chat-topbar-menu a, .page-quick-sidebar-toggler').click(function (e) {
	        $('body').toggleClass('page-quick-sidebar-open'); 

	       	this.startChatSidebar();
	    }.bind(this));

		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.canStart = true;

			this.startChatBlock();
		}.bind(this));

		mod.chatModule = app.module("utils.chat");

		this.blockChatQueueViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			template : _.template($("#widget-chat-queue-template").html(), null, {variable : 'model'}),
			events : {
				"click .start-chat-action" : "startChat"
			},
			/*
			events : {
				"click .assign-to-me-action" : "assignToMeAction",
				"click .assign-to-other-action" : "assignToUserAction",
				"click .resolve-action" : "resolveAction",
				"click .delete-action" : "deleteAction",
				"confirmed.bs.confirmation .delete-action" : "deleteAction",
				"click" : "startChatAction",
				"hover" : "hoverAction",
			},
			*/
			initialize : function() {
				this.listenTo(this.model, "change", this.render.bind(this));

				//this.wrapperChat = this.$el.parents('.page-quick-sidebar-chat');

				/*
				this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));
				this.listenTo(mod, "assignToMe.sidebar", this.assignToMe.bind(this));
				this.listenTo(mod, "assignToUser.sidebar", this.assignToUser.bind(this));
				this.listenTo(mod, "resolve.sidebar", this.resolve.bind(this));
				this.listenTo(mod, "delete.sidebar", this.delete.bind(this));
				*/

				//this.listenTo(this.chatModule, "receiveMessage.chat", this.refreshCounter.bind(this));
				//this.chatModule.subscribeToChat(this.model.get("topic"), this.model, true, false);
			},
			render : function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			},
			startChat : function() {
				var topic = this.model.get("topic");

				//console.warn(topic, this.model.get("name"), this.model.get("user"), this.model.get("user.id"));

				this.chatModule.createChat(this.model.get("user.id"));
			},
			/*
			hoverAction : function(e) {
				if (e.type == "mouseenter") {
					this.$(".media-status button.show-hover").removeClass("hidden");
				} else if (e.type == "mouseleave") {
					this.$(".media-status button.show-hover").addClass("hidden");
				} else {
					this.$(".media-status button.show-hover").toggleClass("hidden");
				}
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
			*/
		});
		/*

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
		*/
		/*
		this.blockChatConversationViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			baseHeight:  0,
			template : _.template($("#sidebar-conversation-item-template").html(), null, {variable : 'model'}),
			events : {
				"keyup .page-quick-sidebar-chat-user-form input" : "keyenter",
				"click .page-quick-sidebar-chat-user-form button" : "send",
				"click .page-quick-sidebar-back-to-list" : "stopAction",
				"click .assign-to-me-action" : "assignToMeAction",
				"click .assign-to-other-action" : "assignToUserAction",
				"click .resolve-action" : "resolveAction"
			},
			initialize : function(opt) {
				console.info("menu.chat/sidebarChatConversationViewClass::initialize", this);

				//this.chatModule.subscribeToChat(this.model.get("topic"), this.model);
				this.baseHeight = opt.height;

				// LOAD PREVIOUS CONVERSATION
				this.collection = new this.chatModule.collections.conversations();
				this.collection.id = this.model.get("id");
				this.listenTo(this.collection, "sync", this.renderPrevious.bind(this));


				this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));
				this.listenTo(mod, "assignToMe.sidebar", this.assignToMe.bind(this));
				this.listenTo(mod, "assignToUser.sidebar", this.assignToUser.bind(this));
				this.listenTo(mod, "resolve.sidebar", this.resolve.bind(this));
				this.listenTo(mod, "delete.sidebar", this.delete.bind(this));


				this.start();
			},
			renderPrevious : function(collection) {
				var showedDate = moment();
				this.collection.each(function(model, index) {
					var modelDate = moment.unix(model.get("sent"));
					if (!modelDate.isSame(showedDate, 'day')) {

						var infoModel = new this.chatModule.models.message({
							type : "info",
							message : modelDate.format("LL")
						})
						var view = new messageItemViewClass({model: infoModel});
	                	this.previousMessageContainer.append(view.render().el);
						//console.warn("print", );
						showedDate = modelDate;
					}
					this.addPreviousOne(model, index);
				}.bind(this));

				this.messageContainer.slimScroll({
    		        scrollTo: '1000000px'
				});
				
			},
			render : function() {
				this.collection.fetch();

				console.info("menu.chat/sidebarChatConversationViewClass::render", this);

				this.$el.html(this.template(this.model.toJSON()));

				this.$(".user-details .media-heading").html(this.model.get("requester.name") + ' ' + this.model.get("requester.surname"));
				this.$(".user-details .media-heading-sub").html("COURSE #1");

				this.messageContainer = this.$('.page-quick-sidebar-chat-user-messages');
				this.previousMessageContainer = this.$('.page-quick-sidebar-chat-user-messages-previous');
				this.currentMessageContainer = this.$('.page-quick-sidebar-chat-user-messages-current');


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
				console.info("menu.chat/sidebarChatConversationViewClass::addOne", this);
	            if (model.get("chat.topic") == this.model.get("topic")) {
	                //var model = new this.chatModule.models.message(data);

	                var view = new messageItemViewClass({model: model});

	                this.previousMessageContainer.append(view.render().el);
	            }
	        },
			addOne : function(topic, data) {
				console.info("menu.chat/sidebarChatConversationViewClass::addOne", this);
	            if (topic == this.model.get("topic")) {
	                var model = new this.chatModule.models.message(data);

	                var view = new messageItemViewClass({model: model});

	                this.currentMessageContainer.append(view.render().el);


	                this.messageContainer.slimScroll({
    		            scrollTo: '1000000px'
					});
	            }
			},
			start : function() {
				this.$el.removeClass("hidden");
				this.listenTo(this.chatModule, "receiveMessage.chat", this.addOne.bind(this));
			},

			// FUNCTIONS TO START THE TRIGGER
			stopAction : function() {
				console.info("menu.chat/sidebarChatConversationViewClass::stopAction", this);
				this.$el.addClass("hidden");
				this.stopListening(this.chatModule);

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
		*/
		this.blockChatViewClass = Backbone.View.extend({
			collection : null,
			chatModule : mod.chatModule,
			//userSelectDialog : app.module("dialogs.users.select"),
			//conversationViews : {},
			//conversationHeight : 0,
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
			initialize : function() {
				//this.initializeScrolls();

				this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
					this.$el.unblock();
					this.chatModule.getAvaliableQueues(this.renderAvaliableQueues.bind(this));

					//this.chatModule.getQueues(this.renderChatQueues.bind(this));
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

				//this.$(".start-chat-action").on("click", this.startChat.bind(this))

				/*
				this.listenTo(mod, "startChat.sidebar", this.startChat.bind(this));
				this.listenTo(mod, "stopChat.sidebar", this.stopChat.bind(this));
				this.listenTo(mod, "assignToMe.sidebar", this.assignToMe.bind(this));
				this.listenTo(mod, "assignToUser.sidebar", this.assignToUser.bind(this));
				this.listenTo(mod, "resolve.sidebar", this.resolve.bind(this));
				this.listenTo(mod, "delete.sidebar", this.delete.bind(this));
				*/
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
			renderAvaliableQueues : function(result) {
				this.collection.reset(result);

				this.$(".queue-container").empty();
				this.collection.each(this.addOneChatQueue.bind(this));
				app.module("ui").refresh(this.$(".queue-container"));
			},
			addOneChatQueue : function(model) {
				var itemView = new mod.blockChatQueueViewClass({
					model: model
				});
				this.$(".queue-container").append(itemView.render().el);
			},


			/*
			stopChat : function(model) {
				this.$('.page-quick-sidebar-chat').removeClass("page-quick-sidebar-content-item-shown");
			},
			assignToMe : function(model) {
				console.info("menu.chat/sidebarChatViewClass::assignToMe", this);

				//console.warn(this.model.get("user_id"));

				model.set("assign_id", this.model.get("user_id"));
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
					model.set("assign_id", item.user_id);
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
			*/
		});

		
		this._chatViews = [];

	    var messageViewClass = Backbone.View.extend({
	        tagName: "li",
	        templates : {
	            info : _.template($('#chat-item-info-template').html(), null, {variable : "model"}),
	            all : _.template($('#chat-item-template').html(), null, {variable : "model"})
	        },
	        render: function() {
	            if (_.has(this.templates, this.model.get("type"))) {
	                var template = this.templates[this.model.get("type")];
	            } else {
	                var template = this.templates['all'];
	            }
	            this.$el.append(template(this.model.toJSON()));
	            return this;
	        }
	    });
	    var statusViewClass = Backbone.View.extend({
	        tagName: "li",
	        itemTemplate: _.template($('#tutoria-chat-status-template').html()),
	        render: function() {
	            this.$el.append(this.itemTemplate(this.model.toJSON()));
	            return this;
	        }
	    });
	    var chatViewClass = Backbone.View.extend({
	        chatTemplate: _.template($('#chat-template').html(), null, {variable : "model"}),
	        collection  : null,
	        model       : null,
	        bbview      : null,
	        isStarted   : false,
	        events : {
	            "click .portlet" : "removeFocus",
	            "keyup .send-block input" : "keyenter",
	            "click .portlet-title" : "toggleWindow",
	            "click .tools a.remove" : "close",
	        },
	        initialize: function(opt) {
	            //console.log(this.model.toJSON());
	            //this.collection = this.model.get("messages");
	            //  this.bbview  = opt.bbview;
	            this.$el.addClass("chat-widget");

	            this.render();

	            this.listenTo(mod.chatModule, "receiveMessage.chat", this.addOne.bind(this));
	            this.listenTo(mod.chatModule, "errorConnection.chat", this.disable.bind(this));
	            this.listenTo(mod.chatModule, "afterConnection.chat", this.enable.bind(this));
	            // ADD FIRST MESSAGES
	            //this.updateStatus(this.model);

	            //this.collection.each(this.addOne.bind(this));

	            //this.listenTo(this.model, 'change:status', this.updateStatus);
	            //this.listenTo(this.collection, 'add', this.addOne);

	        },

	        keyenter: function(e) {
	            if ( e.which == 13 ) {
	                e.preventDefault();

	                if (jQuery(e.currentTarget).val() != "") {
	                    
	                    var message = {
	                        topic     : this.model.get("topic"),
	                        //from        : "me",
	                        message    : jQuery(e.currentTarget).val()
	                    };
	                    
	                    mod.chatModule.sendMessage(
	                        this.model.get("topic"), 
	                        message
	                    );
	                    //this.collection.add(message);
	                    jQuery(e.currentTarget).val("");

	                }
	            }
	        },
	        render : function() {
	            this.$el.empty();
	            this.$el.append(this.chatTemplate(this.model.toJSON()));

	            $("#off-windows").append(this.$el);
	            this.$(".portlet").data("portlet-type", "chat");

	            var scroller = this.$(".scroller");
	            var height;
	            if (scroller.attr("data-height")) {
	                height = scroller.attr("data-height");
	            } else {
	                height = scroller.css('height');
	            }

	            app.module("ui").handleScrollers(this.$el);

	            return this;
	        },
	        updateStatus : function(model) {
	            var view = new statusViewClass({model: model});
	            this.$(".chat-contents").append(view.render().el);
	        },
	        addOne: function(topic, model) {
	            if (topic == this.model.get("topic")) {
	                //var model = new mod.models.message(data);
	                var view = new messageViewClass({model: model});
	                this.$(".chat-contents").append(view.render().el);

	                if (!model.get("mine")) {
	                    this.focus();
	                } else {
	                    var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
	                    this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
	                }
	            }
	        },
	        focus : function() {
	            document.getElementById('ping').play();
	            
	            this.$(".portlet").addClass("yellow");

	            if (!this.$(".portlet").is(":visible")) {
	                this.$el.removeClass("hidden");
	            }

	            if (!this.$(".portlet > .portlet-body").hasClass("hidden")) {
	                var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
	                this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
	            }
	        },

	        enable : function() {
	            this.$(".portlet").removeClass("red");
	            this.$(".portlet-body").unblock();
	        },
	        disable : function() {
	            //this.collapse();
	            this.$(".portlet").addClass("red");

	            var html = '<div class="loading-message loading-message-boxed red">' +
	                '<a href="javascript:void(0)" class="btn btn-lg blockable-item">' +
	                    '<i class="fa fa-edit"></i> ' + 
	                '</a>' +
	            '</div>';

	            var html = '<div class="">' +
	                    '<i class="fa fa-lg fa-close text-danger"></i><br /> ' + 
	                  'No Connection' +
	            '</div>';

	            $(".portlet-body").block({
	                message: html,
	                baseZ: 20,
	                centerY: true,
	                css: {
	                    border: '1px dashed #ccc',
	                    padding: '5px 10px 10px 10px',
	                    //backgroundColor: '#fff',
	                    width: '50%'
	                },
	                overlayCSS: {
	                    backgroundColor: '#888',
	                    opacity: 0.1,
	                    cursor: 'wait'
	                }
	            });


	        },

	        toggleWindow : function() {
	            this.$(".portlet > .portlet-body").toggleClass("hidden");

	        },
	        close : function() {
	            //this.removeFocus();

	            this.$el.addClass("hidden");
	        },
	        removeFocus : function() {
	            this.$(".portlet").removeClass("yellow");
	        }
	    });


		this.listenTo(this.chatModule, "createChat.chat", function(topic, model) {
			mod.startChatView(topic, model);
		});
		/*
		this.listenTo(this.chatModule, "createQueue.chat", function(topic, model) {
			console.warn(topic, model);
			mod.startChatView(topic, model);
		});
		*/
		
		this.listenTo(this.chatModule, "receiveChat.chat", function(topic, model) {
			mod.startChatView(topic);
		});

	    this.startChatView = function(topic, model, closed) {
       
	        if (_.isUndefined(mod._chatViews[topic])) {

	            mod._chatViews[topic] = new chatViewClass({
	                model : model
	            });
	            if (closed === true) {
	            	mod._chatViews[topic].close();
	            }
	        } else {
	            mod._chatViews[topic].focus();
	        }
	    };

	});
});

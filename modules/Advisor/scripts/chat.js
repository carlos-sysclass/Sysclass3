$SC.module("utils.chat", function(mod, app, Backbone, Marionette, $, _) {
    /*
    this.ChatCollectionClass = Backbone.Collection.extend({
        initialize : function(models, options) {
            $SC.module("utils.strophe").start();

            app.reqres.setHandler("xmpp:connect:status", this.connectStatus);
            //app.reqres.setHandler("xmpp:message", this.receiveMessage);

            $SC.module("utils.strophe").connection.messaging.on("xmpp:message", this.receiveMessage);
            $SC.module("utils.strophe").connection.roster.on("xmpp:presence", this.receivePresence);
        },
        connectStatus : function(status) {
            //console.log('fdks');
        },
        receivePresence : function(presence) {
            var chat_index = presence.jid;
            var username = presence.jid.split("@");
            presence.from = username[0];
            if (mod.chatCollections[chat_index]) {
                mod.chatCollections[chat_index].add(presence);
            }
        },
        receiveMessage : function(message) {
            var chat_index = message.from.barejid;
            var username = message.from.node;
            console.log(chat_index);
            console.log(mod.chatCollections);
            if (mod.chatCollections[chat_index]) {
                mod.chatCollections[chat_index].add(message);
            }
        }
    });
    */



    var messageViewClass = Backbone.View.extend({
        tagName: "li",
        itemTemplate: _.template($('#chat-item-template').html(), null, {variable : "model"}),
        render: function() {
            this.$el.append(this.itemTemplate(this.model.toJSON()));
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
            "keyup .send-block input" : "keyenter"
        },
        initialize: function(opt) {
            //console.log(this.model.toJSON());
            //this.collection = this.model.get("messages");
            //  this.bbview  = opt.bbview;
            this.$el.addClass("chat-widget");

            this.render();

            this.listenTo(mod, "receiveMessage.chat", this.addOne.bind(this));
            // ADD FIRST MESSAGES
            //this.updateStatus(this.model);

            //this.collection.each(this.addOne.bind(this));

            //this.listenTo(this.model, 'change:status', this.updateStatus);
            //this.listenTo(this.collection, 'add', this.addOne);

        },
        keyenter: function(e,a,b,c) {
            if ( e.which == 13 ) {
                e.preventDefault();

                if (jQuery(e.currentTarget).val() != "") {
                    
                    var message = {
                        topic     : this.model.get("topic"),
                        //from        : "me",
                        message    : jQuery(e.currentTarget).val()
                    };
                    
                    mod.sendMessage(
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
        addOne: function(topic, data) {
            if (topic == this.model.get("topic")) {
                var model = new mod.models.message(data);
                var view = new messageViewClass({model: model});
                this.$(".chat-contents").append(view.render().el);

                var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
                this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
            }

            document.getElementById('ping').play();
        },
        focus : function(restoreIfHidden) {
            if (!this.$(".portlet").is(":visible") && restoreIfHidden != false) {
                //this.$(".portlet-title .tools a.expand").removeClass("expand").addClass("collapse");
                this.$el.slideDown(200);
            } else if (!this.$(".portlet > .portlet-body").is(":visible") && restoreIfHidden != false) {
                this.$(".portlet-title .tools a.expand").removeClass("expand").addClass("collapse");
                var self = this;
                this.$(".portlet > .portlet-body").slideDown(200, function() {
                    /*
                    self.$(".portlet-title").pulsate({
                        color: "#399bc3",
                        repeat: false
                    });
                    */
                });
            } else {
                /*
                this.$(".portlet-title").pulsate({
                    color: "#399bc3",
                    repeat: false
                });
                */
            }

            var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
            this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
        }
    });

    /*
    this.onRemove = function(e, portlet) {
        portlet.closest(".chat-widget").hide();
        return false;
    };

    this.chatViews = [];

    $SC.module("utils.strophe").on("xmpp:message xmpp:startchat", function(model) {

    this.on("start", function() {

        $SC.module("utils.strophe").on("xmpp:message xmpp:startchat", function(model) {
            var index = model.get("id");
            if (mod.chatViews[index] == undefined) {
                mod.chatViews[index] = new chatViewClass({
                    model       : model
                });
            } else {
                mod.chatViews[index].focus();
            }
        });
    });
    */
    this.startWithParent = false;

    this._chatViews = [];
    this._canStart = false;
    this._conn = null;
    this._token = null;

    this._wsUri = 'ws://' + window.location.hostname +':8080';

    this._options = {
        maxRetries : 10,
        tryCount : 0,
    }

    this.models = {
        chat : Backbone.Model.extend({}),
        message : Backbone.Model.extend({})
    };

    mod.initialize = function() {
        this.on("afterConnection.chat", this.onChatConnected.bind(this));
        this.on("startQueue.chat", this.startQueueView.bind(this));
        this.on("errorConnection.chat", this.startRetryMode.bind(this));
        this.trigger();
        this.startRetryMode();
    }

    mod.onChatConnected = function(result) {
        this._token = result.token;
    }

    mod.startConnection = function(force_close) {
        if (_.isNull(app.userSettings.get("websocket_key"))) {
            return false;
        } 
        if (!_.isNull(this._conn) && force_close === true) {
            this._conn.close();
            this._conn = null;
        }
        if (_.isNull(this._conn)) {
            this.trigger("beforeConnection.chat");
            /*
            ab.launch({
                wsuri: 'ws://localhost:8080',
                // authentication info
                appkey: null, // authenticate as anonymous
                appsecret: null,
                appextra: null,
                // additional session configuration
                sessionConfig: {maxRetries: 10, retryDelay : 1000}
            },
            // session open handler
            function (sess) {
                console.warn('WebSocket connection open');
                this._conn = sess;
                var websocket_key = app.userSettings.get("websocket_key");
                var session_key = $.cookie("SESSIONID");

                this._conn
                    .call("authentication", websocket_key, session_key)
                    .then(function (result) {
                        this.trigger("afterConnection.chat", result);
                    }.bind(this), function (error) {
                        this.trigger("errorConnection.chat", error);
                        console.warn("error", error);

                    }.bind(this));
              //main(sess);
            }.bind(this),
            // session close handler
            function (code, reason, detail) {
              sess = null;
              this.trigger("errorConnection.chat");
              console.warn(code, reason, detail);
            }.bind(this));
            */
            
            this._conn = new ab.Session(
                this._wsUri,
                function() {
                    console.warn('WebSocket connection open');
                    var websocket_key = app.userSettings.get("websocket_key");
                    var session_key = $.cookie("SESSIONID");

                    this._options.tryCount = 0;

                    this._conn
                        .call("authentication", websocket_key, session_key)
                        .then(function (result) {
                            this.trigger("afterConnection.chat", result);
                        }.bind(this), function (error) {
                            this.trigger("errorConnection.chat", error);
                            console.warn("error", error);

                        }.bind(this));

                }.bind(this),
                function(code, reason, detail) {
                    console.warn('WebSocket connection closed');
                    this._conn = null;
                    this.trigger("errorConnection.chat");
                }.bind(this),
                {'skipSubprotocolCheck': true, retryDelay : 1000}
            );
            
        }
        return this._conn;
    }

    mod.startRetryMode = function() {
        this._options.tryCount++;
        if (this._options.tryCount <= this._options.maxRetries) {
            console.warn('WebSocket connection Try #' + this._options.tryCount);
            mod.startConnection();
        }
    }

    this.createQueue = function(topic, title) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("startQueue", topic, title)
            .then(function (result) {
                //this.trigger("afterConnection.chat", result);
                console.warn("success", result);
                var model = new this.models.chat(result);

                var new_topic = model.get("topic");
                this._conn.subscribe(new_topic, function(topic, data) {
                    if (data.origin == this._token) {
                        data.mine = true;
                    } else {
                        data.mine = false;
                    }
                    
                    this.trigger("receiveMessage.chat", topic, data);
                }.bind(this));
                
                this.trigger("startQueue.chat", model);

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.sendMessage = function(topic, message) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        this._conn.publish(topic, message, false);
    }


    this.startQueueView = function(model) {
        var topic = model.get("topic");
        if (mod._chatViews[topic] == undefined) {
            mod._chatViews[topic] = new chatViewClass({
                model : model
            });
        } else {
            mod._chatViews[topic].focus();
        }

    }

    this.startChat = function(who) {
        // CHECK IF USER IS ALLOWED 

        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        return false;
    };


    mod.on("start", function() {
        if (!_.isUndefined(app.userSettings)) {
            this.initialize();
        } else {
            //var userSettingsModel = new userSettingsModelClass();
            this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
                mod.initialize();
            }.bind(this));
        }
    });
});

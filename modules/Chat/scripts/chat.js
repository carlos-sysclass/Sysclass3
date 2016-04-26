$SC.module("utils.chat", function(mod, app, Backbone, Marionette, $, _) {
    this.startWithParent = false;

    this._chatViews = [];
    this._canStart = false;
    this._conn = null;
    this._token = null;
    this._started = moment().unix();
    this._subscribedTopics = {};
    this._subscribedChats = {};

    this._wsUri = 'ws://' + window.location.hostname +':8080'; // DEFAULT

    this._options = {
        maxRetries : 10,
        tryCount : 0,
        delayTime: 100
    }

    this.models = {
        chat : Backbone.Model.extend({}),
        message : Backbone.DeepModel.extend({})
    };

    this.collections = {
        conversations : Backbone.Collection.extend({
            id: null,
            model : this.models.message,
            url : function() {
                return "/module/chat/items/conversation/default/" + JSON.stringify({chat_id: this.id});
            }
        })
    };

    mod.initialize = function() {

        this._wsUri = 'ws://' + window.location.hostname +':' + app.userSettings.get("websocket_port");

        this.on("errorConnection.chat", this.startRetryMode.bind(this));

        //this.on("errorConnection.chat", this.disableChatViews.bind(this));
        this.on("afterConnection.chat", this.restoreSession.bind(this));
        
        this.startRetryMode(true);
    }
    /*
    mod.onChatConnected = function(result) {
    }
    */
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
                            this._token = result.token;
                            this._started = result.started;

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

    mod.startRetryMode = function(now) {
        this._options.tryCount++;
        if (this._options.maxRetries > 0 && this._options.tryCount <= this._options.maxRetries) {
            

            if (_.isBoolean(now) && now) {
                console.warn('WebSocket connection Try #' + this._options.tryCount);
                mod.startConnection();
            } else {
                var delay = this._options.delayTime + this._options.tryCount * this._options.tryCount * 25;
                console.warn('WebSocket connection Try #' + this._options.tryCount + ", delaying for " + delay + "ms");
                setTimeout(mod.startConnection.bind(this), delay);
            }
        }
    }

    this.restoreSession = function() {
        for (var topic in this._subscribedTopics) {
            if (!_.isNull(this._subscribedTopics[topic])) {
                this._conn.subscribe(this._subscribedTopics[topic], this.parseReceivedTopic.bind(this));
            }
        }
    }

    this.createQueue = function(topic, title, exclusive, startChat) {
        //console.warn("createQueue");
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("startQueue", topic, title)
            .then(function (result) {
                //this.trigger("afterConnection.chat", result);
                var model = new this.models.chat(result);

                var new_topic = model.get("topic");

                this.trigger("createQueue.chat", new_topic, model);

                this.subscribeToChat(new_topic, model, exclusive, startChat);
                
                //this.trigger("startQueue.chat", new_topic, model);
                

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.createChat = function(user_id) {
        //console.warn("createQueue");
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("createChat", user_id)
            .then(function (result) {
                console.warn(result);
                //this.trigger("afterConnection.chat", result);
                var model = new this.models.chat(result);

                var new_topic = model.get("topic");

                this.trigger("createChat.chat", new_topic, model);

                

                this.subscribeToChat(new_topic, model, true);
                
                //this.trigger("startQueue.chat", new_topic, model);
                

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.receiveChat = function(topic, callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        if (!_.has(this._subscribedChats, topic) || _.isNull(this._subscribedChats[topic])) {
            // CALL A FUNCTION TO CREATE THE TOPIC
            this._conn
                .call("receiveChat", topic)
                .then(function (result) {
                    //console.warn(result);
                    var model = new this.models.chat(result);

                    this._subscribedChats[topic] = model;

                    this.trigger("receiveChat.chat", topic, model);

                    callback();
                }.bind(this), function (error) {
                    console.warn("error", error);
                }.bind(this));
        } else {
            console.warn("receiveChat-error");
            callback();
        }
    };

    this.subscribeToChat = function(topic, model, exclusive, startChat) {
        if (exclusive && _.has(this._subscribedTopics, topic)) {
            return false;
        }
        this._subscribedTopics[topic] = topic;
        this._subscribedChats[topic] = model ? model : null;

        this._conn.subscribe(topic, this.parseReceivedTopic.bind(this));

        //this.trigger("queueSubscribed.chat", topic, model);

        if (startChat !== false) {

            //this.startChatView(model);
        }
    }

    this.unsubscribeToChat = function(topic) {
        this._conn.unsubscribe(topic);
        this._subscribedTopics[topic] = null;
    }

    this.parseReceivedTopic = function(topic, data) {
        // CHECK IF IS A COMMAND OR A MESSAGE
        console.warn("RECEIVE", topic, data);
        
        this.receiveChat(topic, function() {
            if (data.origin == this._token) {
                data.mine = true;
            } else {
                data.mine = false;
            }

            var model = new this.models.message(data);
            this.trigger("receiveMessage.chat", topic, model);
        }.bind(this));

    }

    this.sendMessage = function(topic, message) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        console.warn("SEND", topic, message);
        this._conn.publish(topic, message, false);
    }
    /*
    this.getQueues = function(callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getUnassignedQueues")
            .then(function (result) {
                callback(result);

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };
    */

    this.disableChatViews = function() {
        for (var topic in this._chatViews) {
            this._chatViews[topic].disable();
        }
    };
    /*
    this.startChatView = function(model) {
        console.warn("startChatView");
        var topic = model.get("topic");
        if (mod._chatViews[topic] == undefined) {
            mod._chatViews[topic] = new chatViewClass({
                model : model
            });
        } else {
            mod._chatViews[topic].focus();
        }
    } 
    */
    // REMOTE FUNCTIONS
    this.getQueues = function(callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getQueues")
            .then(function (result) {
                console.warn("getQueues", result);
                callback(result);

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.getAvaliableQueues = function(callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }

        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getAvaliableQueues")
            .then(function (result) {
                console.warn("getAvaliableQueues", result);
                callback(result);

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    /*
    this.startChat = function(who) {
        // CHECK IF USER IS ALLOWED 

        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        return false;
    };
    */

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

$SC.module("utils.strophe", function(mod, app, Backbone, Marionette, $, _){

    this.startWithParent = false;

    console.log(this);

	mod.addInitializer(function(){
        var BOSH_SERVICE = 'http://layout.sysclass.com/chat-poll';
        this.connection = new Strophe.Connection(BOSH_SERVICE);
  	});

    this.on("start", function() {
        // SET REQUEST/RESPONSE HANDLERS
        var self = this;
        this.connection.connect('akucaniz@layout.sysclass.com', '123456', function(status) {
            self.trigger("xmpp:connect", status);

            if (status == Strophe.Status.CONNECTING) {
                console.log('Strophe is connecting.');
            } else if (status == Strophe.Status.CONNFAIL) {
                console.log('Strophe failed to connect.');
            } else if (status == Strophe.Status.DISCONNECTING) {
                console.log('Strophe is disconnecting.');
            } else if (status == Strophe.Status.DISCONNECTED) {
                console.log('Strophe is disconnected.');
            } else if (status == Strophe.Status.CONNECTED) {
                console.log('Strophe is connected.');
                //console.log(this);

                //connection.addHandler(this.onMessage, null, 'message', null, null,  null); 
                this.send($pres().tree());
                //var presence = $pres().c("show").t("away").up().c("status").t("Volto Logo");
                //this.send(presence);
                
                this.roster.on("xmpp:presence", function(data) {
                    self.trigger("xmpp:presence", data);
                });

                this.messaging.on("xmpp:message", function(data) {
                    self.trigger("xmpp:message", data);
                });
            }
        });
        app.reqres.setHandler("xmpp:message:send", this.sendMessage);
    });
    this.sendMessage = function(message) {
        mod.connection.messaging.send(message.jid, message.body);
    };
    this.getRosterList = function(callback) {
        defered = mod.connection.roster.get();
        defered.done(function(roster) {
            callback(roster);
        });
    };

    /* GETTING USER CHAT LIST */


});

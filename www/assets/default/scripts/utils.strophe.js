$SC.module("utils.strophe", function(mod, app, Backbone, Marionette, $, _){

    this.startWithParent = false;

	mod.addInitializer(function(){
        var BOSH_SERVICE = '/chat-poll';
        this.connection = new Strophe.Connection(BOSH_SERVICE);
  	});

    this.on("start", function() {
        // SET REQUEST/RESPONSE HANDLERS
        this.connection.connect('akucaniz@thesis.sysclass.com', '123456', function(status) {
            app.request("xmpp:connect:status", status);

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
                console.log(this);

                //connection.addHandler(this.onMessage, null, 'message', null, null,  null); 
                //this.send($pres().tree());
                var presence = $pres().c("show").t("away").up().c("status").t("Volto Logo");
                this.send(presence);

                this.roster.on("xmpp:presence", function(data) {
                    app.request("xmpp:presence", data);
                });
                this.messaging.on("xmpp:message", function(data) {
                    app.request("xmpp:message", data);
                })
            }
        });
        app.reqres.setHandler("xmpp:message:send", this.sendMessage);
    });
    this.sendMessage = function(message) {
        mod.connection.messaging.send(message.to, message.body);
    }

});

$SC.module("utils.toastr", function(mod, app, Backbone, Marionette, $, _){

	mod.addInitializer(function(){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        this.message = function(type, message) {
            console.log(type);
            return toastr[type](message);
        }
  	});

    this.on("start", function() {
        // SET REQUEST/RESPONSE HANDLERS
        app.reqres.setHandler("toastr:message", this.message);
    });


});

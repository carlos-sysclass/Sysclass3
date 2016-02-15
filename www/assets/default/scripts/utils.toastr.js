$SC.module("utils.toastr", function(mod, app, Backbone, Marionette, $, _){

	mod.addInitializer(function(){
        if (typeof toastr != 'undefined') {

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
                if (_.has(toastr, type)) {
                    return toastr[type](message);
                }
                return toastr['info'](message);
            }
        }
  	});

    this.on("start", function() {
        // SET REQUEST/RESPONSE HANDLERS
        app.reqres.setHandler("toastr:message", this.message);
    });


});

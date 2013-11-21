(function($){
  // VIEWS
  var MessagesView = Backbone.View.extend({

    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    el: $('.message-recipient-group'),
    _dialog : null,
    // Delegated events for creating new items, and clearing completed ones.
    
    events: {
      "click .message-recipient-item": "openDialog"
    },
    initialize: function() {
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

      // APPEND DIALOG
      this._dialog = jQuery("<div></div>")
        .attr("id", "message-contact-dialog")
        .attr("tabindex", "-1")
        .attr("role", "basic")
        .attr("aria-hidden", "true")
        .addClass('modal fade')
        .modal({
          show: false
        });

      jQuery("body").append(this._dialog);
      this._dialog = $("#message-contact-dialog");
    },
    openDialog: function(e) {
      var self = this;

      ajaxurl = $(e.currentTarget).attr("href");
      this._dialog.load(ajaxurl, function() {
        self._dialog.find('.wysihtml5').wysihtml5();

        self._dialog.find('.fileinput').fileupload();

        var error1 = $('.alert-danger', self._dialog.find('form'));

        self._dialog.find('form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                subject: {
                    minlength: 3,
                    required: true
                },
                body: {
                    minlength: 10,
                    required: true,
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                //success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (form) {
                error1.hide();
                jQuery.post(
                  $(form).attr("action"),
                  $(form).serialize(),
                  function(response, status) {
                    self._dialog.modal('hide');
                    toastr[response.message_type](response.message);

                  },
                  'json'
                );
            }
        });      
      }).modal("show");



      return false;

    },
    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {

    }
  });
  var messagesView = new MessagesView();

})(jQuery);

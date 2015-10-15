$SC.module("blocks.lessons.content", function(mod, app, Backbone, Marionette, $, _) {
	// VIEWS
	
    mod.on("start", function(opt) {
        var baseFormClass = app.module("views").baseFormClass;
        var messageSenderDialogViewClass = baseFormClass.extend({
            renderType : "byView",
            initialize: function() {
                console.info('dialogs.roles.create/roleCreationDialogViewClass::initialize');
                baseFormClass.prototype.initialize.apply(this);

                var self = this;

                this.on("complete:save", this.close.bind(this));
            },
            open : function() {
                this.$el.modal("show");
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            }
        });

        this.models = {
            roles : Backbone.Model.extend({
                defaults : {
                    name : "",
                    active : 1,
                    in_course : 0,
                    in_class : 0
                },
                urlRoot : "/module/roles/item/me"
            })
        };

        this.dialogView = new messageSenderDialogViewClass({
            el : $("#message-contact-dialog"),
            model : new mod.models.roles()
        });

        // BIND TO DEFAULT CALLER
        /*
        $(".dialog-create-role-open-action").on("click", function(e) {
            e.preventDefault();
            this.dialogView.setModel(new mod.models.roles());
            this.dialogView.open();
        }.bind(this));
		*/
        /*
        mod.open = function() {
            mod.dialogView.$el.modal('show');
        };
        mod.close = function() {
            mod.dialogView.$el.modal('hide');
        };
        */
    });

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

		var currentForm = self._dialog.find('form');
		var files = {};

		$('input[type=file]', currentForm).on('change', function(e) {
			var name = $(this).attr("name");
			files[name] = e.target.files;
		});

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
				var formData = {};
				var TotalFiles = _.size(files);
				if (files != null && TotalFiles > 0) {
					$.each(files, function(index, file) {

						var data = new FormData();
						$.each(file, function(key, value)
						{
							data.append(key, value);
						});

						$.ajax({
				            url: '/module/messages/attach_file',
				            type: 'POST',
				            data: data,
				            cache: false,
				            dataType: 'json',
				            processData: false, // Don't process the files
				            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
				            success: function(data, textStatus, jqXHR)
				            {
				            	if(typeof data.error === 'undefined')
				            	{
				            		// Success so call function to process the form
				            		TotalFiles--;
				            		formData[index] = data['file'];
				            		if (TotalFiles == 0) {
										// SEND THE FORM
										$(form).find(":input").each(function() {
											if ($(this).is("[type=file]")) {
												return true;
											}
											formData[$(this).attr("name")] = $(this).val();
										});

										jQuery.post(
										  $(form).attr("action"),
										  formData,
										  function(response, status) {
											self._dialog.modal('hide');
											$SC.request("toastr:message", response.message_type, response.message);

										  },
										  'json'
										);
				            		}
				            	}
				            	else
				            	{
				            		// Handle errors here
				            		console.log('ERRORS: ' + data.error);
				            	}
				            },
				            error: function(jqXHR, textStatus, errorThrown)
				            {
				            	// Handle errors here
				            	console.log('ERRORS: ' + textStatus);
				            	// STOP LOADING SPINNER
				            }
				        });
					});
				} else {
					$(form).find(":input").each(function() {
						if ($(this).is("[type=file]")) {
							return true;
						}
						formData[$(this).attr("name")] = $(this).val();
					});

					jQuery.post(
					  $(form).attr("action"),
					  formData,
					  function(response, status) {
						self._dialog.modal('hide');
						$SC.request("toastr:message", response.message_type, response.message);

					  },
					  'json'
					);
				}
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

});

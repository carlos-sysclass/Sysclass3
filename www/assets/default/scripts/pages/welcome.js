$SC.module("view.welcome", function(mod, app, Backbone, Marionette, $, _){
	//this.startWithParent = false;

	this.on("start", function(opt) {
		
		var paymentLoggerModel = Backbone.Model.extend({
			url: "/module/payment/items/logger"
		});
		var baseChangeModelViewClass = app.module("views").baseChangeModelViewClass;

		var wizardView = Backbone.View.extend({
			events: {
				"click .do-test-action" : "doTest",
				"click .do-payment-action" : "doPayment",
			},
			testInfoModule :  app.module("dialogs.tests.info"),
			initialize : function() {
				this.$el.bootstrapWizard({
		            'nextSelector': '.button-next',
		            'previousSelector': '.button-previous',
		            onTabClick: function (tab, navigation, index, clickedIndex) {
		                /*
		                
		                success.hide();
		                error.hide();
		                if (form.valid() == false) {
		                    return false;
		                }
		                
		                handleTitle(tab, navigation, clickedIndex);
		                */
		            },
		            onNext: function (tab, navigation, index) {
		            	/*
		                success.hide();
		                error.hide();

		                if (form.valid() == false) {
		                    return false;
		                }

		                handleTitle(tab, navigation, index);
		                */
		            },
		            onPrevious: function (tab, navigation, index) {
		            	/*
		                success.hide();
		                error.hide();

		                handleTitle(tab, navigation, index);
		                */
		            },
		            onTabShow: function (tab, navigation, index) {
		            	/*
		                var total = navigation.find('li').length;
		                var current = index + 1;
		                var $percent = (current / total) * 100;
		                */
		               	/*
		                this.$('.progress-bar').css({
		                    width: $percent + '%'
		                });
		                */
		            }.bind(this)
		        });

				var CREATE_PAYMENT_URL  = '/module/payment/create/' + $SC.getResource("T_ENROLL_ID");
    			var EXECUTE_PAYMENT_URL  = '/module/payment/execute/' + $SC.getResource("T_ENROLL_ID");


				paypal.Button.render({
			        env: 'sandbox', // Or 'sandbox'

			        client: {
			            sandbox:    'AVnYcJlI1BZMtTCb3c0_WItiOYT4BDu5GmD07Vs9YgexIZom6_vUgzDroLgUu9JlsSpbLE2zc9PdzEuz',
			            production: 'xxxxxxxxx'
			        },

			        commit: false, // Show a 'Pay Now' button

			        style: {
			            size: 'small',
			            color: 'blue',
			            shape: 'rect',
			            label: 'checkout'
			        },

			        payment: function(data, actions) {
            			return paypal.request.post(
            				CREATE_PAYMENT_URL
            			).then(function(data) {
            				console.warn(data);
                			return data.id;
            			});
            			/*
			            return actions.payment.create({
			                payment: {
			                    transactions: [
			                        {
			                            amount: { total: '1.00', currency: 'USD' }
			                        }
			                    ]
			                }
			            });
			            */
			        },
					onAuthorize: function(data) {
						console.warn(data);
            			return paypal.request.post(EXECUTE_PAYMENT_URL, {
                			paymentID: data.paymentID,
            			    payerID:   data.payerID
			            }).then(function() {

            			});
        			}
			    }, '#paypal-button');
			},
			/*
			doPayment: function(e) {
				var CREATE_PAYMENT_URL  = '/module/payment/create';
				var EXECUTE_PAYMENT_URL  = '/module/payment/execute';
    			paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
    				console.warn(data);

                	return data.id;
            	});
			},
			*/
			doTest : function(e) {
				if (!this.testInfoModule.started) {
                    this.testInfoModule.start();

                    //this.listenTo(this.testInfoModule, "action:do-test", this.doTest.bind(this));
                }

                var test_id = $(e.currentTarget).data("test-id");

                // TRY TO OPEN WIN A "FULL SCREEN" DIALOG
                this.testInfoModule.setInfo({
                	model : new Backbone.Model({id: test_id}),
                	autoStart : true
                });
			}
		});


		this.wizardView = new wizardView({
			el : '#form_wizard_1'
		});

		
        var handleTitle = function(tab, navigation, index) {
            var total = navigation.find('li').length;
            var current = index + 1;
            // set wizard title
            $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
            // set done steps
            jQuery('li', $('#form_wizard_1')).removeClass("done");
            var li_list = navigation.find('li');
            for (var i = 0; i < index; i++) {
                jQuery(li_list[i]).addClass("done");
            }

            if (current == 1) {
                $('#form_wizard_1').find('.button-previous').hide();
            } else {
                $('#form_wizard_1').find('.button-previous').show();
            }

            if (current >= total) {
                $('#form_wizard_1').find('.button-next').hide();
                $('#form_wizard_1').find('.button-submit').show();
                displayConfirm();
            } else {
                $('#form_wizard_1').find('.button-next').show();
                $('#form_wizard_1').find('.button-submit').hide();
            }
            App.scrollTo($('.page-title'));
        }

	});

});

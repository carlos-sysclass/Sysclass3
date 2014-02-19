$SC.module("ui", function(mod, app, Backbone, Marionette, $, _){
  	this.handleValidate = function(context) {
	  	// Validation
	  	/* 
		if($('.form-validate', context).length > 0) {
			$('.form-validate', context).each(function(){
				//var id = $(this).attr('id');
				$(this).validate({
					ignore: null,
	                errorElement: 'span', //default input error message container
	                errorClass: 'help-block', // default input error message class

	                errorPlacement: function (error, element) { // render error placement for each input type
	                	
	                    //if (element.attr("name") == "membership") { // for uniform radio buttons, insert the after the given container
	                    //    error.insertAfter("#form_2_membership_error");
	                    if (element.hasClass("wysihtml5")) { // for wysiwyg editors
	                    	//console.log(element.data('wysihtml5').editor.composer.iframe);
	                        error.insertAfter(element.data('wysihtml5').editor.composer.iframe); 
	                    //} else if (element.attr("name") == "service") { // for uniform checkboxes, insert the after the given container
	                    //    error.insertAfter("#form_2_service_error");
	                    } else {
	                    	error.insertAfter(element); // for other inputs, just perform default behavior
	                    }
	                },

	                highlight: function (element) { // hightlight error inputs
	                   $(element)
	                        .closest('.form-group').addClass('has-error'); // set error class to the control group
	                },
	                unhighlight: function (element) { // revert the change done by hightlight
	                    $(element)
	                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
	                },
	                success: function (label) {
                        label
                            .addClass('valid').addClass('help-block') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
	                },
					submitHandler : function(f) {
						f.submit();
					}
				});
			});
		}
		*/
	};
	this.handleiCheck = function(context) {
		if($(".icheck-me", context).length > 0){
			$(".icheck-me", context).each(function() {
				var $el = $(this);
				var skin = ($el.attr('data-skin') !== undefined) ? "_"+$el.attr('data-skin') : "",
				color = ($el.attr('data-color') !== undefined) ? "-"+$el.attr('data-color') : "";

				if (color == "") {
					//console.log($("body").attr("data-theme"));
					//console.log($CEPETI.module("system").context.get("layout_color"));
				}

				var opt = {
					checkboxClass: 'icheckbox' + skin + color,
					radioClass: 'iradio' + skin + color,
					increaseArea: "10%"
				}

				$el.iCheck(opt);
			});
		}
	};
	this.handleSelect2 = function(context) {
		if($(".select2-me", context).length > 0) {
			$(".select2-me", context).each(function(el, i) {
				var $el = jQuery(this);
				var opt = jQuery(this).data();

				if (jQuery(this).is('[data-url]')) {
					if (jQuery(this).is("[type='hidden']") || jQuery(this).data('select-search') == true) {
						opt.ajax = { // instead of writing the function to execute the request we use Select2's convenient helper
							url: jQuery(this).data('url'),
							dataType: 'json',
							data: function (term, page) {
								return {
									q: term
								};
							},
							results: function (data, page) { // parse the results into the format expected by Select2.
								// since we are using custom formatting functions we do not need to alter remote JSON data
								console.log(data);
								return {results: data};
							}
						};
						opt.formatResult = function (item, container, query, escapeMarkup) {
							console.log(item, container, query, escapeMarkup);
            				var markup=[];
            				var terms = query.term.split("%");
            				if (item.text) {
								return item.text;
            				} else {
	            				var text = item.name;

	            				for(q in terms) {
	            					term_markup = [];
	            					Select2.util.markMatch(text, terms[q], markup, escapeMarkup);
									text = markup.pop();
	            				}
            				}
					        return markup.join("") + text;
						}
						opt.formatSelection = function (item) { return item.name; }
						$el.select2(opt);
					} else {
						opt.minimumResultsForSearch = 10;
						console.log(opt);
						mod.loadDatasourceInto(this, jQuery(this).data('url'), function() {
							$el.select2(opt);
						}, jQuery(this).data('url-cache'), jQuery(this).data('url-clear'));
					}
				} else {
					opt.minimumResultsForSearch = 10;

					$el.select2(opt);	
				}
			});
		}
	};

	this.handleDatepickers = function(context) {
		// datepicker
		if($('.date-picker', context).length > 0){
			$('.date-picker', context).datepicker()
			.on('changeDate', function(e) {
				$(this).datepicker('hide');
			});

		}
	};

	this.handleTimepickers = function(context) {
        if (jQuery().timepicker) {
            $('.timepicker-default', context).timepicker({
                autoclose: true
            });
            $('.timepicker-24', context).timepicker({
                autoclose: true,
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            });
        }
	};

	this.handleWysihtml5 = function(context) {
		if($('.wysihtml5', context).length > 0) {
			$('.wysihtml5', context).wysihtml5();

			//console.log(a.data('wysihtml5'));
		}
	};

	this.handleTabs = function(context) {
		$('.tabs a[data-toggle="tab"][data-url]', context).on('click', function (e) {
			var target = $(e.currentTarget).attr("href") // activated tab
			//$(target).empty();
			if ($(target).is(':empty')) {
				var url = $(e.currentTarget).data("url");
				$.ajax({
					type: "GET",
					url: url,
					data : {
						block : true
					},
					error: function(data){
						alert("There was a problem");
					},
					success: function(data){
						$(target).html(data);
						// HANDLE ALL ELEMENTS ON THIS CONTENT
						mod.refresh($(target));
						

					}
				});
			}
		});
	};

	this.handleTooltips = function(context) {
		if(this.tooltipOnlyForDesktop) {
			if(!this.mobile) {
				$('.tooltips', context).tooltip();
			}
		}
	};
    this.handlePasswordStrengthChecker = function (context) {
    	if($('.password_strength', context).length > 0) {
    		//$(':password').pwstrength();
    		$(".password_strength", context).each(function(el, i) {
				$(this).pwstrength({
                    raisePower: 1.4,
                    minChar: 6,
                    verdicts: ["Weak", "Normal", "Medium", "Strong", "Very Strong"],
                    scores: [11, 21, 33, 40, 55],
                    bootstrap3: true,
	                container: "#pwd-container",
	                viewports: {
	                    progress: ".pwstrength_viewport_progress",
	                    verdict: ".pwstrength_viewport_verdict"
	                },
                });
    		});
			
    	};
    };

	this.refresh = function(context) {
		this.handleTooltips(context);
		this.handleValidate(context);
		this.handleiCheck(context);
		this.handleSelect2(context);
		this.handleDatepickers(context);
		this.handleTimepickers(context);
		this.handleWysihtml5(context);
		this.handleTabs(context);
		this.handlePasswordStrengthChecker(context);
	};

	this.handleAction = function(action) {
		if (typeof action != 'undefined') {
			if (action.intent == "redirect") {
				window.location.href = action.data;
			} else if (action.intent == "advise") {
				app.module("utils.toastr").message(action.type, action.message);
			} else {
				console.debug("@TODO: handleaction:", action);
			}
		}
	}

	this.on("start", function() {
		
		if ($.fn.modal && $.fn.modalmanager) {
			$.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
			'<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
				'<div class="progress progress-striped active">' +
					'<div class="progress-bar" style="width: 100%;"></div>' +
				'</div>' +
			'</div>';
		}
		
		$("div.img-vertical-middle").each(function() {
		    var $el = $(this);
		    var $img = $el.find("img")
		    var height = $el.parents("div").height()
		    $el.height(height);
		    
		    var img_height = $img.height();
		    
		    var margin = (height - img_height) / 2;
		    $img.css("margin-top", margin);
		});



		this.mobile = false,
		this.tooltipOnlyForDesktop = true,
		this.notifyActivatedSelector = 'button-active';

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			this.mobile = true;
		}
		/*
        $.extend(
        	$.jGrowl.defaults, {
        		closer : false,
        		glue : "after",
        		life : 3250,
        		sticky : false,
        		theme : "success",
        		themeState : false,
        		corners : false,
        		closeTemplate : '<i class="icon-remove">',
        		position : "bottom-right"
        	}
        );
		*/
		$( document ).ajaxStart(function( event, xhr, settings ) {
			$(".ajax-loader").stop(true, true).show();
		});
		$( document ).ajaxComplete(function( event, xhr, settings ) {
			if (xhr.responseJSON) {
				var json = xhr.responseJSON;
				app.module("ui").handleAction(json._response_);
			}
			$(".ajax-loader").fadeOut(1000);
		});

		this.refresh(document);
	});

	// UTILITY FUNCTIONS
	this.cachedDatasources = [];
    this.injectDatasourceInto = function(context, response) {
        for (var i in response) {
            $option = jQuery("<option value=" + response[i].id + ">"+ response[i].name + "</option>");
            if (response[i]['selected'] != null) {
                $option.attr("selected", "selected");
            }
           	jQuery(context).append($option);
        }
    };
    this.loadDatasourceInto = function(context, url, callback, cache, clear) {
    	var reload = true;
    	/*
    	if (cache) {
    		if (mod.cachedDatasources[url]) {
    			if (!clear || $(context).find("option").size() == 0) {
    				if (clear) {
    				//	$(context).find("option").remove();
    				}
   					mod.injectDatasourceInto(context, mod.cachedDatasources[url]);
    			}
    			reload = false;
    		}
    	}
    	*/
    	if (reload) {
			jQuery.ajax( 
	            url,
				{
	            	async : false,
	                type: "get",
	                success : function(response, status) {
	                    mod.injectDatasourceInto(context, response);
	                    if (cache) {
	                    	mod.cachedDatasources[url] = response;
	                    }
	                    if (typeof callback == 'function') {
	                    	callback();
	                    }
					},
					dataType : 'json'
				}
			);
		} else {
			if (typeof callback == 'function') {
	           	callback();
			}
		}
    };

  	

});

$SC.module("ui", function(mod, app, Backbone, Marionette, $, _){
  	this.handleValidate = function(context) {
	  	// Validation
		if($('.form-validate', context).length > 0) {
			$('.form-validate', context).each(function(){
				var id = $(this).attr('id');
				$("#"+id).validate({
					ignore: null,
					errorElement:'span',
					errorClass: 'help-block error',
					errorPlacement:function(error, element){
						element.parents('.controls').append(error);
					},
					highlight: function(label) {
						$(label).closest('.control-group').removeClass('error success').addClass('error');
					},
					success: function(label) {
						label.addClass('valid').closest('.control-group').removeClass('error success').addClass('success');
					},
					submitHandler : function(f) {
						f.submit();
					}
				});
			});
		}
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
					if (jQuery(this).data('select-search') == true) {
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
								return {results: data};
							}
						};
						opt.formatResult = function (item, container, query, escapeMarkup) {
            				var markup=[];
            				var terms = query.term.split("%");
            				var text = item.name;

            				for(q in terms) {
            					term_markup = [];
            					Select2.util.markMatch(text, terms[q], markup, escapeMarkup);
								text = markup.pop();
            				}
					        return markup.join("") + text;
						}
						opt.formatSelection = function (item) { return item.name; }
						$el.select2(opt);
					} else {
						opt.minimumResultsForSearch = 10;
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
		if($('.datepick', context).length > 0){
			$('.datepick', context).datepicker()
			.on('changeDate', function(e) {
				$(this).datepicker('hide');
			});

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
				$('[rel=tooltip]', context).tooltip();
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
		this.handleTabs(context);	
		this.handlePasswordStrengthChecker(context);
	};

	this.handleAction = function(action) {
		if (typeof action != 'undefined') {
			if (action.intent == "redirect") {
				window.location.href = action.data;
			} else if (action.intent == "info") {
				$.jGrowl(action.message, {
					theme : action.type
				});
			} else {
				console.debug("@TODO: handleaction:", action);
			}
		}
	}

	this.on("start", function() {
		/*
		this.mobile = false,
		this.tooltipOnlyForDesktop = true,
		this.notifyActivatedSelector = 'button-active';

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			this.mobile = true;
		}

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
		$( document ).ajaxStart(function( event, xhr, settings ) {
			$(".ajax-loader").stop(true, true).show();
		});
		$( document ).ajaxComplete(function( event, xhr, settings ) {
			if (xhr.responseJSON) {
				var json = xhr.responseJSON;
				$CEPETI.module("pages").handleAction(json._response_);
			}
			$(".ajax-loader").fadeOut(1000);
		});
		*/
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

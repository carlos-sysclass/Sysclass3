languageAPI = {
	
};

jQuery(function($) {
	

});

/* MODULE CREATING */
(function( $ ) {
	var methods = {
		setTranslationModeAction : function(sendData, callback, output) {
			var actionName = "set_translation_mode";
			this._doAction(actionName, sendData, callback, output);
		},
		setNormalModeAction : function(sendData, callback, output) {
			var actionName = "set_normal_mode";
			this._doAction(actionName, sendData, callback, output);
		},
		_doAction : function(actionName, sendData, callback, output) {
			var url = 
				window.location.protocol + "//" +
				window.location.hostname +
				window.location.pathname + 
				"?ctg=module&op=module_language" +
				"&action=" + actionName + "&output=json";
				
			if (typeof(output) === "undefined" || output === null || output === "") {
				output = "json";
			}

			jQuery.post(
				url,
				sendData,
				function(data, status) {
					if (output == "json") { 
						jQuery.messaging.show(data);
						console.log(data);
						if (data.reload) {
							window.location.reload();
						}
					}

					if (typeof(callback) == 'function') {
						callback(data, status);
					}
				},
				output);
		},
		startTranslationMode : function() {
			//jQuery("*").unbind("click");
			/*
			// CREATE TRANSLATING DIALOG
			$translationDialog = 
			'<div id="translation_tooltip">' +
			'	<table id="translation_table" class="style1">' +
			'		<thead>' +
			'			<tr>' +
			'				<th>Token</th>' +
			'				<th>Value</th>' +
			'			</tr>' +
			'		</thead>' +
			'		<tbody>' +
			'		</tbody>' +
			'	</table>' +
			'</div>';
			jQuery("body").append($translationDialog);
			// JQuery Tooltip
			*/

			jQuery("#translation_tooltip select").change(function(evt) {
				_sysclass("load", "language").setConfig("language", jQuery(this).val());

				var $terms = _sysclass("load", "language").config("terms");
				var $currentTerms = _sysclass("load", "language").config("currentTerms");
			    var $newLanguageTerms = $terms[jQuery(this).val()];

			    //_sysclass("load", "language").setConfig("currentTerms", usedTerms);

			    var usedTerms = {};
			    for(key in $currentTerms) {
			    	if ($newLanguageTerms[key] != undefined) {
			    		usedTerms[key] = $newLanguageTerms[key];
			    	}
			    }

				_sysclass("load", "language").injectTerms(usedTerms);
			});
			jQuery("a#language-close-tokens").click(function(evt) {
				jQuery("#translation_tooltip").css("visibility", "hidden");
			});

			jQuery("a#language-save-tokens").click(function(evt) {
				_sysclass("load", "language")._postAction(
					"save_terms&language=" + _sysclass("load", "language").config("language"),
					jQuery("#translation_tooltip > form").serialize(),
					function(response) {
						jQuery("#translation_tooltip").css("visibility", "hidden");
					},
					'json'
				);

				return false;
			});

			jQuery("*").click(function(evt) {
			    // SEARCH FOR TOKENS
			    if (jQuery(this).parents("#translation_tooltip").size() > 0) {
			    	evt.stopPropagation();
			    	return false;
			    }
			    if (jQuery(this).attr("id") == "language-save-updates") {
			    	_sysclass('load', 'language').setNormalModeAction();
			    	return;
			    }

			    var $terms = _sysclass("load", "language").config("terms");
			    var $language = _sysclass("load", "language").config("language");

			    var $currentTerms = $terms[$language];

			    // GET ALL TERMS IN $terms AND ;
			    var $subject = jQuery(this).html();
			    var patternNonWord = /\W/;
			    
			    var usedTerms = {};
			    var hasTerms = false;
			    
			    for(key in $currentTerms) {
			        var pattern = new RegExp(key + "(?![A-Z_])","g");
			        if (pattern.test($subject)) {
			            isOk = true;

			            var indexOf = $subject.indexOf(key);
			            if (indexOf != 0) {
			                if (!patternNonWord.test($subject.charAt(indexOf -1))) {
			                    isOk = false;
			                }
			            }
			            if (isOk) {
			                usedTerms[key] = $currentTerms[key];
			                hasTerms = true;
			            }
			        }

			    }

			    jQuery("#translation_tooltip select").val($language);

			    _sysclass("load", "language").injectTerms(usedTerms, this);

			    return false;
			});
		},
		injectTerms : function(usedTerms, ref) {
			_sysclass("load", "language").setConfig("currentTerms", usedTerms);
			jQuery("#translation_table tbody").empty();
		    hasTerms = false;
		    for(index in usedTerms) {
		        jQuery("#translation_table tbody").append(
		            '<tr>' +
		                '<td>' + index + '</td>' +
		                '<td><input type="text" name="token[' + index + ']" value="' + usedTerms[index] + '" /></td>' +
		            '</tr>'
		        );
		        hasTerms = true;
		    }

		    if (ref != undefined) {
					jQuery("#translation_tooltip").position({
						"my": "left top",
						"at": "right top",
						"of": jQuery(ref)
					});
		    	}

		    //if (hasTerms) {
		    	jQuery("#translation_tooltip").css("visibility", "visible");
		    	jQuery("#translation_tooltip").show();
		    //} else {
	//	    	jQuery("#translation_tooltip").css("visibility", "hidden");
		    //}
		},
		startUI : function() {
			// LOAD PAGE USED TERMS
			var self = this;
			this._getAction("get_used_terms", {language : 'default'}, function(response, status) {
				console.log(response, status);
				self.setConfig("terms", response.terms);
				self.setConfig("language", response.language);

				if (response.translation_mode) {
					_sysclass("load", "language").startTranslationMode();
				}
			}, "json");

			// DATATABLES WRAPPER
			dataTableDefaults = {
				"bJQueryUI": false,
				"bPaginate": true,
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": false,
				"bAutoWidth": true,
				"iDisplayLength"	: 10,
				"aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Tudo"]],
				"bDeferRender" : true,
				"sPaginationType": "full_numbers",
				"bScrollCollapse": true,
				"sDom": 't<"datatables-header-controls"ilrp>',
				"oLanguage": {
					"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
				}
			};
			
			/// CREATE DEFAULT DATATABLES
		    jQuery.datepicker.regional[""].dateFormat = 'dd/mm/yy';
		    jQuery.datepicker.setDefaults($.datepicker.regional['']);
			jQuery(".languageDataTable").dataTable( dataTableDefaults );
		}
	};

	_sysclass("register", "language", methods);
})( jQuery );

/* MODULE FLOW-LOGIC */
(function( $ ){
	_sysclass('load', 'language').startUI();
})( jQuery );

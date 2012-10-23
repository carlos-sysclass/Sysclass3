function JS_clearStringSymbols($subject) {

	return 	new String($subject)
			.replace(/[àáâãä]/g, "a")
			.replace(/[ÀÁÂÃÄÅ]/g, "A")
			.replace(/[ç]/g, "c")
			.replace(/[Ç]/g, "C")
			.replace(/[èéêë]/g, "e")
			.replace(/[ÈÉÊË]/g, "E")
			.replace(/[ìíîï]/g, "i")
			.replace(/[ÌÍÎÏ]/g, "I")
			.replace(/[ñ]/g, "n")
			.replace(/[Ñ]/g, "N")
			.replace(/[òóôõö]/g, "o")
			.replace(/[ÒÓÔÕÖ]/g, "O")
			.replace(/[ùüú]/g, "u")
			.replace(/[ÙÜÚ]/g, "U")
			.replace(/[ÿ]/g, "y")
			.replace(/[Ÿ]/g, "Y")
			.replace(/ /g, "_");
}

function getJqueryPeriodicData() {
	jQuery.get(
		'periodic_updater.php', 
		{},
		function(data, status) {
			// data may contain
			/*
			{
				"messages":"8",
				"online": [ {
					"login":"admin",
					"name":"Andr\u00e9",
					"surname":"Kucaniz",
					"formattedLogin":"Andr\u00e9 Kucaniz. (admin)",
					"user_type":"administrator",
					"timestamp_now":"1328190911",
					"time":{"hours":0,"minutes":9,"seconds":25}
			  	} ]
			}
			*/
			//console.log(data, status);
		}
	);
}

(function($) {
	// CREATE JAVASCRIPT TIMER

	if (typeof(updaterPeriod) != 'undefined') {
		intervalID = window.setInterval(getJqueryPeriodicData, updaterPeriod);
	} else {
		intervalID = window.setInterval(getJqueryPeriodicData, 100000);
	}

	// Content Box Toggle Config 
	 $("a.toggle").click(function(){
		$(this).toggleClass("toggle_closed").next().slideToggle("slow");
		return false; //Prevent the browser jump to the link anchor
	});
 	// Content Box Tabs Config
	$( ".tabs" ).tabs({ 
		fx: {opacity: 'toggle', duration: 0} 
	});
	$('ul.dropdown').parent().addClass('has_dropdown');
	
	jQuery(".menu-dropdown-subtitle").click(function() {
	    if (jQuery(this).hasClass("menu-dropdown-subtitle-selected")) {
	        jQuery(this).find("div").hide();
	        jQuery(this).removeClass("menu-dropdown-subtitle-selected");
	    } else {
	        jQuery(".menu-dropdown-subtitle").removeClass("menu-dropdown-subtitle-selected");
	        jQuery(".menu-dropdown-subtitle div").hide();

	        jQuery(this).addClass("menu-dropdown-subtitle-selected");
	        jQuery(this).find("div").show();
	    }
	});	
	jQuery(".menu-dropdown-subitem a").click(function (evt) {
		evt.stopPropagation();
	});
	
	jQuery.Topic( "xcourse_course_lesson_change" ).subscribe( function(course_id, lesson_id) {
		var url = window.location.pathname + "?ctg=lesson_information&popup=1&lessons_ID=" + lesson_id;
		jQuery("#xcourse_lesso_info_link").attr("href", url);
	});

	/*
	wysiwygEditors = [];
	jQuery(":input[alt='wysiwyg']").each(function(i, elem) {
		if (jQuery(this).attr('id') == "") {
			jQuery(this).attr('id', 'wysiwyg-' + i);
			var editorID = 'wysiwyg-' + i;
		} else {
			var editorID = jQuery(this).attr('id');
		}
		
		wysiwygEditors[editorID] = new TINY.editor.edit('editor',{
            id: editorID,
            height: 300,
            cssclass:'te',
            controlclass:'tecontrol',
            rowclass:'teheader',
            dividerclass:'tedivider',
            controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
                              'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
                              'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n','image','hr','link','unlink','|','cut','copy','paste','print','|','font','size','style'],
            footer:false,
            fonts:['Arial','Verdana','Georgia','Trebuchet MS'],
            xhtml:true,
            cssfile:'css/tinyeditor/style.css',
            bodyid:'editor',
            footerclass:'tefooter',
            toggle:{text:'source',activetext:'wysiwyg',cssclass:'toggler'},
            resize:{cssclass:'resize'}
        });
        
        jQuery(this).parents("form").bind('submit', function() {
        	wysiwygEditors[editorID].post();
        });
	});
	*/
	
    // DATATABLES CUSTOM ORDER PLUG-INS
    jQuery.fn.dataTableExt.aTypes.unshift(
   		function ( sData )
   		{
   			sData = new String(sData);
   			if (sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
   			{
   				/// @todo Incluir metodos para detectar se formato é
   				return 'd_m_Y';
   			}
   			return null;
   		} 
   	);
    
    
	jQuery.fn.dataTableExt.oSort['d_m_Y-asc']  = function(a,b) {
		var ukDatea = a.split('/');
		var ukDateb = b.split('/');

		var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;
		
		return ((x < y) ? -1 : ((x > y) ?  1 : 0));
	};

	jQuery.fn.dataTableExt.oSort['d_m_Y-desc'] = function(a,b) {
		var ukDatea = a.split('/');
		var ukDateb = b.split('/');
		
		var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;
		
		return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
	};
	
	jQuery.fn.dataTableExt.afnSortData['dom-checkbox'] = function  ( oSettings, iColumn )
	{
		var aData = [];
		jQuery( 'td:eq('+iColumn+') input[type=\'checkbox\']', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
			aData.push( this.checked==true ? "1" : "0" );
		} );
		return aData;
	};
	jQuery.fn.dataTableExt.afnSortData['dom-checkbox-reversed'] = function  ( oSettings, iColumn )
	{
		var aData = [];
		jQuery( 'td:eq('+iColumn+') input[type=\'checkbox\']', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
			aData.push( this.checked==true ? "0" : "1" );
		} );
		return aData;
	};
	
	/*
	jQuery.fn.dataTableExt.oSort['m/d/Y-asc']  = function(a,b) {
		var ukDatea = a.split('/');
		var ukDateb = b.split('/');
		
		var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
		var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;
		
		return ((x < y) ? -1 : ((x > y) ?  1 : 0));
	};

	jQuery.fn.dataTableExt.oSort['m/d/Y-desc'] = function(a,b) {
		var ukDatea = a.split('/');
		var ukDateb = b.split('/');
		
		var x = (ukDatea[2] + ukDatea[0] + ukDatea[1]) * 1;
		var y = (ukDateb[2] + ukDateb[0] + ukDateb[1]) * 1;
		
		return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
	};
	*/
	jQuery(".ui-progress-bar").each(function() {
		var currentValue = new Number(jQuery(this).html());
		
		jQuery(this).empty();
		
		jQuery(this).progressbar({
			value: currentValue.valueOf()
		});
	});
	$.metadata.setType('attr','metadata');
	
	jQuery('input:text').setMask({autoTab: false});
	
	// GET datepicker LANGUAGE
	//alert(window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datepicker&output=json");
	
	jQuery.getJSON(
		window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datepicker&output=json",
		{},
		function (data, status) {
			defaultDatepicker = {
				showButtonPanel		: true,
				changeMonth			: true,
				changeYear			: true,
				showOtherMonths		: true,
				selectOtherMonths	: true,
				dateFormat			: 'dd/mm/yy',
				firstDay			: 0,
				isRTL				: false,
				showMonthAfterYear	: false,
				yearSuffix			: '',
				yearRange			: "c-30:c+30",
				showOn				: "button",
				buttonImage: "/themes/sysclass/images/icons/small/others/calendar.gif",
				buttonImageOnly: true
			};
			
			/*
			jQuery( ":input[alt='date']" ).each(function() {
				alert(jQuery(this).val());
			});
			*/
			
			if (status == 'success') {
				datepickerData = jQuery.extend(true, jQuery.datepicker.regional[""], defaultDatepicker, data);
				jQuery( ":input[alt='date']" ).filter(":not(.no-button)").datepicker(datepickerData);
				
				datepickerData.showButtonPanel 	= false;
				datepickerData.buttonImageOnly 	= false;
				datepickerData.showOn			= "focus";
				
				jQuery( ":input[alt='date']" ).filter(".no-button").datepicker(datepickerData);
			} else {
				datepickerData = jQuery.extend(true, jQuery.datepicker.regional[""], defaultDatepicker);
				jQuery( ":input[alt='date']" ).filter(":not(.no-button)").datepicker(datepickerData);
				
				defaultDatepicker.showButtonPanel 	= false;
				defaultDatepicker.buttonImageOnly 	= false;
				defaultDatepicker.showOn			= "focus";

				
				jQuery( ":input[alt='date']" ).filter(".no-button").datepicker(datepickerData);
			}
			jQuery.datepicker.setDefaults(datepickerData);
			jQuery.datepicker.regional[""] = datepickerData;
			/*
			jQuery( ":input[alt='date']" ).each(function() {
				alert(jQuery(this).val());
			});
			*/
		}
	);
	
	// Requires jQuery!
	// Requires jQuery!
	/*
	$.ajax({
	    url: "https://jira.wiseflex.com/s/en_USag3i3o/772/4/1.1.1/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs.js?collectorId=927d80a0",
	    type: "get",
	    cache: true,
	    dataType: "script"
	});
	*/

	
})(jQuery);

/* Set the defaults for DataTables initialisation */
var dataTableOptionTemplates = {
	'default' : _.template($("#datatables-option-default-template").html(), null, {variable : 'item'}),
	'switch' : _.template($("#datatables-option-switch-template").html(), null, {variable : 'item'})
};
//console.log($("#datatables-options-template").html());
//
jQuery.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
    // DataTables 1.10 compatibility - if 1.10 then `versionCheck` exists.
    // 1.10's API has ajax reloading built in, so we use those abilities
    // directly.
    if ( jQuery.fn.dataTable.versionCheck ) {
        var api = new jQuery.fn.dataTable.Api( oSettings );

        if ( sNewSource ) {
            api.ajax.url( sNewSource ).load( fnCallback, !bStandingRedraw );
        }
        else {
            api.ajax.reload( fnCallback, !bStandingRedraw );
        }
        return;
    }

    if ( sNewSource !== undefined && sNewSource !== null ) {
        oSettings.sAjaxSource = sNewSource;
    }

    // Server-side processing should just call fnDraw
    if ( oSettings.oFeatures.bServerSide ) {
        this.fnDraw();
        return;
    }

    this.oApi._fnProcessingDisplay( oSettings, true );
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];

    this.oApi._fnServerParams( oSettings, aData );

    oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );

        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== "") ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

        for ( var i=0 ; i<aData.length ; i++ )
        {
            that.oApi._fnAddData( oSettings, aData[i] );
        }

        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

        that.fnDraw();

        if ( bStandingRedraw === true )
        {
            oSettings._iDisplayStart = iStart;
            that.oApi._fnCalculateEnd( oSettings );
            that.fnDraw( false );
        }

        that.oApi._fnProcessingDisplay( oSettings, false );

        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback == 'function' && fnCallback !== null )
        {
            fnCallback( oSettings );
        }
    }, oSettings );
};

$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row'<'col-lg-6 col-md-6 col-sm-12'l><'col-lg-6 col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
		"sLengthMenu": "_MENU_ records"
	},
	// DEFAULT COLUMN FORMATING
	"aoColumnDefs": [
		{
			"mRender": function ( data, type, row) {
				if (type == 'display' || type == 'filter') {
					return moment.unix(data).fromNow();
				} else {
					return parseFloat( data );
				}
				return data;
			},
			"sClass"		: "text-center",
			"aTargets": [ 'unix-moment-since' ]
		},
		{
			"mRender": function ( data, type, row) {
				if (type == 'display' || type == 'filter') {
					return moment(data).fromNow();
				} else {
					return moment(data).unix();
				}
				return data;
			},
			"sClass"		: "text-center",
			"aTargets": [ 'datetime-moment-since' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display' || type == 'filter') {
					if (data != 0) {
						return moment.unix(data).format("L");
					} else {
						return "";
					}
				} else {
					return parseFloat( data );
				}
				return data;
			},
			"sClass"		: "text-center",
			"aTargets": [ 'unix-moment-date' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display' || type == 'filter') {
					if (data != 0) {
						return moment.unix(data).format("L LT");
					} else {
						return "";
					}
				} else {
					return parseFloat( data );
				}
				return data;
			},
			"sClass"		: "text-center",
			"aTargets": [ 'unix-moment-datetime' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display' || type == 'filter') {
					var duration = moment.duration(data, "seconds");
					//if (duration.asSeconds() < 60) {
						//return duration.asSeconds() + "s";
					//} else {
						return duration.humanize(true);
					//}
				} else {
					return parseFloat( data );
				}
				return data;
			},
			"sClass"		: "text-center",
			"aTargets": [ 'unix-moment-duration' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (!_.isNull(data)) {
					var floatValue = parseFloat( data );
					if (type == 'display') {
						return (floatValue * 100) + "%";
					}
					return floatValue;
				}
				return data;
			},
			"sClass"		: "text-center",
			"aTargets": [ 'float-as-percentage' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display') {
					return '<i class="' + data + '"></i>';
				} else {
					return data;
				}
			},
			"sClass"		: "text-center",
			"aTargets": [ 'table-icon' ]
		},



		/**
		 * PLEASE REMOVE THIS FUNCTION FROM HERE, BECAUSE THE STRONG COUPLING
		 */
		{
			"mRender": function ( data, type, row ) {
				return row.user.name + " " + row.user.surname;
			},
			"aTargets": [ 'concatenate-user' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display' || type == 'filter') {
					// CHECK IF ROW HAS A CURRENCY FIELD AND UPDATE BY TYPE
					if (_.has(row, "currency")) {
						numeral.language(row['currency']);
					}
					return numeral(data).format('$0,0.00');
				} else {
					return parseFloat( data );
				}
				return data;
			},
			"bSearchable" 	: true,
			"bSortable"		: true,
			"sClass"		: "text-center",
			"aTargets": [ 'table-currency' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display') {
					result = [];
					return '<img src="' + data + '" />';
				}
				return data;
			},
			"bSearchable" 	: true,
			"bSortable"		: true,
			"sClass"		: "text-center",
			"aTargets": [ 'table-image' ]
		},
		{
			"mRender": function ( data, type, row ) {
				if (type == 'display') {
					result = [];
					for(i in data) {
						var type = 'default';

						if (!_.isUndefined(data[i].type) && _.has(dataTableOptionTemplates, data[i].type)) {
							type = data[i].type;
						}

						var template = dataTableOptionTemplates[type];

						result.push(template(_.extend(data[i], {key : i})));	
					}
					return result.join("");
				}
				return data;
			},
			"bSearchable" 	: false,
			"bSortable"		: false,
			"sClass"		: "text-center",
			"aTargets": [ 'table-options' ]
		},
		{
			"mRender": function ( data, type, row ) {
				/*
				if (type == 'display') {
					result = [];
					for(i in data) {
						result.push(dataTableOptionsTemplate({item: data[i], key : i}));
					}
					return result.join("");
				}
				*/
				return "1";
			},
			"bSearchable" 	: false,
			"bSortable"		: false,
			"sClass"		: "text-center",
			"aTargets"		: [ 'table-checkbox' ]
		},
		{
			"mRender": function ( data, type, row ) {
				// TODO GET THE MAP FROM TRANSLATION MODEL
				var map = {
					1 : "Yes",
					0 : "No"
				};
				if (data == 1) {
					return '<span class="label label-sm label-success">' + map[data] + '</span>';
				} else if (data == 0) {
					return '<span class="label label-sm label-danger">' + map[data] + '</span>';
				} else {
					return '<span class="label label-sm label-info">N/A</span>';
				}

				return ;
			},
			"bSearchable" 	: true,
			"bSortable"		: true,
			"sClass"		: "text-center",
			"aTargets": [ 'table-boolean' ]
		},
	],
	"aaSorting": [[0, 'asc']],
	/*
	"aLengthMenu": [
		[10, 15, 20, -1],
		[10, 15, 20, "All"] // change per page values here
	],
	*/
	// set the initial value
	"iDisplayLength": 10,
	"createdRow": function( row, data, dataIndex ) {
		$SC.module("ui").refresh(row);
	}
});


/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );

// SORTING FUNCTIONS
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "unix-moment-since-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "unix-moment-since-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
	return {
		"iStart":         oSettings._iDisplayStart,
		"iEnd":           oSettings.fnDisplayEnd(),
		"iLength":        oSettings._iDisplayLength,
		"iTotal":         oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
};


/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bootstrap": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			/**
			// pagination with prev, next link captions
			$(nPaging).append(
				'<ul class="pagination">'+
					'<li class="prev disabled"><a href="#"><i class="icon-angle-left"></i>'+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+'<i class="icon-angle-right"></i></a></li>'+
				'</ul>'
			);
			**/
			// pagination with prev, next link icons
			$(nPaging).append(
				'<ul class="pagination">'+
					'<li class="prev disabled"><a href="#" title="'+oLang.sPrevious+'"><i class="icon-angle-left"></i></a></li>'+
					'<li class="next disabled"><a href="#" title="'+oLang.sNext+'"><i class="icon-angle-right"></i></a></li>'+
				'</ul>'
			);

			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}

			for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
				// Remove the middle elements
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnDraw( oSettings );
						} );
				}

				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	}
} );


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( $.fn.DataTable.TableTools ) {
	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT btn-group",
		"buttons": {
			"normal": "btn default",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info modal"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );
}

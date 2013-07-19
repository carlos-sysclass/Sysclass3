<link rel = "stylesheet" type = "text/css" href = "themes/SysClass/css/jquery-ui.css">
<link rel='stylesheet' type='text/css' href='themes/SysClass/css/weekcalendar.css' />

<script type="text/javascript" src="/js/jquery/jquery.1.5.2.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.10.custom.min.js"></script>
<script type='text/javascript' src='/js/jquery/jquery.weekcalendar.js'></script>
<script type='text/javascript' src='/js/jquery/jquery.metadata.js'></script>


<script type="text/javascript">

var courseData = {Mag_Json_Encode data = $T_EDIT_COURSE->course};

{literal}
$j(document).ready(function(jQuery) {

	jQuery.metadata.setType('attr','metadata');

	var classData = null;
	

	var $about = jQuery("#calendar-dialog");

	jQuery(".open-calendar-link").live('click', function() {
		classData = jQuery(this).parents("tr").metadata();

		$calendar.weekCalendar('refresh');
		
		$about.dialog({
	    	title: "Horários da Turma " + classData.name + " | Curso: " + courseData.name,
			width: 1000,
	        height: 500,
	        draggable : true,
	        resizable : false,
	        buttons : {
				Salvar : function() {
					// SABE HOURS BY AJAX
					schedules = [];

					$calendar.find(".wc-cal-event").each(function() {

						scheduleData = jQuery(this).data("calEvent");

						scheduleData.week_day = scheduleData.start.getDay();
						scheduleData.start = scheduleData.start.getHours() + ":" + scheduleData.start.getMinutes();  
						scheduleData.end = scheduleData.end.getHours() + ":" + scheduleData.end.getMinutes();
						
			        	schedules.push(
			        		scheduleData
						);
					});

					url = window.location.toString() + 
						"&courseclass=" + classData.id + 
						"&ajax=1" +
						"&postAjaxRequest=classes_schedules";

					jQuery.post(
						url,
						{"schedules" : schedules},
						function(data, response) {
							sC_js_redrawPage('classesTable', true);
							
						}
					);
					classData = null;
					jQuery(this).dialog('close');
				},
				Cancelar : function() {
					classData = null;
					jQuery(this).dialog('close');
				}
			}
		}).show();
	});
	   	
	var $calendar = jQuery('#calendar');
	   var id = 10;
	   
	   $calendar.weekCalendar({
		   date: new Date(),
	       timeFormat : "h:ia",
	       dateFormat : "",
	       alwaysDisplayTimeMinutes: true,
	       use24Hour : true,
	       daysToShow : 5,
	       firstDayOfWeek : 1, // 0 = Sunday, 1 = Monday, 2 = Tuesday, ... , 6 = Saturday
	       useShortDayNames: false,
	       timeSeparator : " as ",
	       startParam : "start",
	       endParam : "end",
	       businessHours : {start: 14, end: 24, limitDisplay : true},
	       newEventText : "Horario Reservado",
	       timeslotHeight: 20,
	       defaultEventLength : 2,
	       timeslotsPerHour : 4,
	       buttons : false,
	       buttonText : {
	          today : "today",
	          lastWeek : "&nbsp;&lt;&nbsp;",
	          nextWeek : "&nbsp;&gt;&nbsp;"
	       },
	       scrollToHourMillis : 500,
	       allowCalEventOverlap : true,
	       overlapEventsSeparate: false,
	       readonly: false,
	       draggable : function(calEvent, element) {
	    	   return calEvent.readOnly != true;
	       },
	       resizable : function(calEvent, element) {
	    	   return calEvent.readOnly != true;
	       },
	       eventClick : function() {
	    	   // OPEN DIALOG TO EDIT
	       },
	       eventRender : function(calEvent, element) {
				if (element.find(".wc-time").find(".ui-icon").size() == 0) {
					element
	   		   			.find(".wc-time")
						.append('<span class="ui-icon ui-icon-closethick" style="float: right"></span>')
	   		   			.css({
	   		   				cursor : 'pointer'
	   		   			}).find(".ui-icon").click(function() {
	   		   				jQuery(this).parents(".wc-cal-event").remove();
	   		   				return false;
	   		   			});
	    	   	}
	       },
	       eventAfterRender : function(calEvent, element) {
				if (element.find(".wc-time").find(".ui-icon").size() == 0) {
					element
	   		   			.find(".wc-time")
						.append('<span class="ui-icon ui-icon-closethick" style="float: right"></span>')
	   		   			.css({
	   		   				cursor : 'pointer'
	   		   			}).find(".ui-icon").click(function() {
	   		   				jQuery(this).parents(".wc-cal-event").remove();
	   		   				return false;
	   		   			});
	    	   	}
	    	   
	    	   return element;
	       },
	       eventDrag : function(calEvent, element) {
	       },
	       eventDrop : function(calEvent, element) {
	       },
	       eventResize : function(newCalEvent, calEvent, element) {
	    	   if (element.find(".wc-time").find(".ui-icon").size() == 0) {
	    		   element
	    		   		.find(".wc-time")
	    		   		.append('<span class="ui-icon ui-icon-closethick" style="float: right"></span>')
	    		   		.css({
	    		   			cursor : 'pointer'
	    		   		}).find(".ui-icon").click(function() {
	    		   			jQuery(this).parents(".wc-cal-event").remove();
	    		   			return false;
	    		   		});
	    	   }
	    	   
	    	   return element;
	       },
	       eventNew : function(calEvent, element) {
	    	   // OPEN DIALOG TO CREATE
               calEvent.id = -1;

               $calendar.weekCalendar("updateEvent", calEvent);
	       },
	       eventMouseover : function(calEvent, $event) {
	       },
	       eventMouseout : function(calEvent, $event) {
	       },
	       calendarBeforeLoad : function(calendar) {
	       },
	       calendarAfterLoad : function(calendar) {
	       },
	       noEvents : function() {
	       },
	       //shortMonths : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	       //longMonths : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	       //shortDays : ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
	       longDays : ['Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado'],
	       height : function($calendar) {
	    	   return 350;
	       },
			data : function(weekStart, weekEnd, callback) {
				var MILLIS_IN_DAY = 86400000;

				eventData = { events : [] };
				
				if (classData != null && classData.schedules.length > 0) {
					
					var year = new Date().getFullYear();
					var month = new Date().getMonth();
					var day = new Date().getDate();

					weekDates = [
						new Date(weekStart.getTime() - MILLIS_IN_DAY),
						new Date(weekStart.getTime()),
						new Date(weekStart.getTime() + MILLIS_IN_DAY * 1),
						new Date(weekStart.getTime() + MILLIS_IN_DAY * 2),
						new Date(weekStart.getTime() + MILLIS_IN_DAY * 3),
						new Date(weekStart.getTime() + MILLIS_IN_DAY * 4),
						new Date(weekStart.getTime() + MILLIS_IN_DAY * 5),
					];

					for (var key = 0; key < classData.schedules.length; key++) {

						//get date from week_day and week_dates
						var eventDate = weekDates[classData.schedules[key]['week_day']];

						var eventTimes = {
							"start"	: new Date("Mon Feb 21 2011 " + classData.schedules[key]['start'] + " GMT-0300"),
							"end"	: new Date("Mon Feb 21 2011 " + classData.schedules[key]['end'] + " GMT-0300")
						};

						eventData.events.push(
							{
								"id" 	: classData.schedules[key]['id'], 
								"start"	: new Date(eventDate.getFullYear(), eventDate.getMonth(), eventDate.getDate(), eventTimes.start.getHours(), eventTimes.start.getMinutes()), 
								"end"	: new Date(eventDate.getFullYear(), eventDate.getMonth(), eventDate.getDate(), eventTimes.end.getHours(), eventTimes.end.getMinutes()),
								"title"	:""
							}
						);
					}

				}
				callback(eventData);
			}
	   });
});
{/literal}
</script>

<div id="calendar-dialog" style="display: none;">
	<h3></h3>
	<div id="calendar"></div>
</div>

<!--ajax:classesTable-->
	<table style = "width:100%" class = "sortedTable" size = "{$T_TABLE_SIZE}" sortBy = "4" order = "desc" useAjax = "1" id = "classesTable" rowsPerPage = "{$smarty.const.G_DEFAULT_TABLE_SIZE}" url = "{$smarty.server.PHP_SELF}?ctg=courses&edit_course={$smarty.get.edit_course}&">
		<tr class = "topTitle defaultRowHeight">
			<td class = "topTitle" name = "name">{$smarty.const._NAME} </td>
			<td class = "topTitle" name = "start_date" style = "width:10%">{$smarty.const._CLASSSTARTON}</td>
			<td class = "topTitle" name = "end_date" style = "width:10%">{$smarty.const._CLASSFINISHON}</td>
			<td class = "topTitle" name = "max_users" style = "width:10%">{$smarty.const._MAXSTUDENTS}</td>
			<td class = "topTitle" name = "count_users" style = "width:10%">{$smarty.const._STUDENTSCOUNT}</td>
			<!--  <td class = "topTitle centerAlign" name = "has_lesson" >{$smarty.const._SELECT}</td>  -->
			<td class = "topTitle centerAlign">{$smarty.const._FUNCTIONS}</td>
		</tr>
		{foreach name = 'classes_list2' key = 'key' item = 'classe' from = $T_DATA_SOURCE}
			<tr class = "defaultRowHeight {cycle values = "oddRowColor, evenRowColor"} {if !$classe.active}deactivatedTableElement{/if}" metadata="{Mag_Json_Encode data = $classe}">
				<td>
					<a 
						target="POPUP_FRAME"
						title = "{$smarty.const._EDITCOURSECLASS}" 
						onclick="sC_js_showDivPopup('{$smarty.const._EDITCOURSECLASS}', 1)" 
						href="{$smarty.server.PHP_SELF}?ctg=courses&courseclass={$T_EDIT_COURSE->course.id}&edit_courseclass={$classe.id}&popup=1"
						class = "editLink">
							{$classe.name}
					</a>
				</td>
				<td class = "centerAlign">#filter:timestamp-{$classe.start_date}#</td>
				<td class = "centerAlign">#filter:timestamp-{$classe.end_date}#</td>
				<td class = "centerAlign">{$classe.max_users}</td>
				<td class = "centerAlign">{$classe.count_users}</td>
				<td class = "centerAlign">
					<!--  <input type = "checkbox" id = "{$classe.id}" onclick = "courseClassAjaxPost('{$classe.id}', this);" {if $classe.has_course}checked{/if}>{if $classe.has_course}<span style = "display:none">checked</span>{/if} {*Span is for sorting here*}  -->
					<img 
						class = "ajaxHandle open-calendar-link" 
						src = "images/16x16/calendar.png" 
						title = "{$smarty.const._EDIT_HOURS}" 
						alt = "{$smarty.const._EDIT_HOURS}" 
						
					/>
						
						
						
					<img 
						class = "ajaxHandle" 
						src = "images/16x16/error_delete.png" 
						title = "{$smarty.const._DELETE}" 
						alt = "{$smarty.const._DELETE}" 
						onclick = "if (confirm ('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')) deleteCourseClass(this, {$classe.courses_ID}, {$classe.id})"/>
						
						
				</td>
			</tr>
		{foreachelse}
			<tr class = "defaultRowHeight oddRowColor"><td class = "emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td></tr>
		{/foreach}
	</table>
<!--/ajax:classesTable-->

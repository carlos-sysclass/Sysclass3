<?php
/**

 * Smarty plugin: smarty_function_eF_template_printCalendar function. Prints inner table

 *

 */
function smarty_function_eF_template_printCalendar($params, &$smarty) {
 $events = $params['events'];
 //isset($params['ctg']) ? $current_ctg = $params['ctg'] : $current_ctg = 'control_panel';             //If a ctg is defined (e.g. ctg=calendar), use this as the links target. Otherwise, use control_panel (default)
 foreach ($events as $key => $event) {
  $temp = getdate($key);
  foreach ($event['data'] as $key2 => $value) {
   $event['data'][$key2] = str_replace("&#039;","&amp;#039;", $event['data'][$key2]);
  }
  $events_per_day[mktime(0, 0, 0, $temp['mon'], $temp['mday'], $temp['year'])][$key] = $event['data'];
 }
 if (!isset($params['timestamp'])) {
  $params['timestamp'] = time();
 }
 $timestamp_info = getdate($params['timestamp']);

 $previous_month = mktime(0, 0, 0, $timestamp_info['mon'] - 1, 1, $timestamp_info['year']);
 $next_month = mktime(0, 0, 0, $timestamp_info['mon'] + 1, 1, $timestamp_info['year']);
 $previous_year = mktime(0, 0, 0, $timestamp_info['mon'] , 1, $timestamp_info['year'] - 1);
 $next_year = mktime(0, 0, 0, $timestamp_info['mon'] , 1, $timestamp_info['year'] + 1);

 $firstday = mktime(0, 0, 0, $timestamp_info['mon'] , 1, $timestamp_info['year']);
 $lastday = mktime(0, 0, 0, $timestamp_info['mon'] + 1, 0, $timestamp_info['year']);
 $firstday_info = getdate($firstday);
 
  if ($firstday_info['wday'] == 0) {
//  $firstday_info['wday'] = 7;
 }

 $lastday_info = getdate($lastday);
 if ($lastday_info['wday'] == 0) {
//  $lastday_info['wday'] = 7;
 }
 $today = getdate(time());
 $today = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);

 isset($_GET['view_calendar']) && eF_checkParameter($_GET['view_calendar'], 'timestamp') ? $view_calendar = $_GET['view_calendar'] : $view_calendar = $today;
 isset($_GET['show_interval']) ? $show_interval_link = '&show_interval='.$_GET['show_interval'] : $show_interval_link = '';

 
 
  

 
 $str = '
	<div
		class="ui-datepicker-inline ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"
		style="display: block;">
		<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all">
			<a class="ui-datepicker-prev ui-corner-all" href = "'.basename($_SERVER['PHP_SELF']).'?ctg=calendar&view_calendar='.$previous_month.$show_interval_link.'">
				<span class="ui-icon ui-icon-circle-triangle-w">Prev</span>
			</a>
			<a class="ui-datepicker-next ui-corner-all" href = "'.basename($_SERVER['PHP_SELF']).'?ctg=calendar&view_calendar='.$next_month.$show_interval_link.'">
				<span class="ui-icon ui-icon-circle-triangle-e">Next</span>
			</a>
			<div class="ui-datepicker-title">
				<span class="ui-datepicker-month">' . iconv(_CHARSET, 'UTF-8', strftime('%B', $params['timestamp'])) . '</span>&nbsp;
				<span class="ui-datepicker-year">' . $timestamp_info['year'] . '</span>
			</div>
		</div>
		
		<table class="ui-datepicker-calendar">
        	<thead>
				<tr>
					<th class="ui-datepicker-week-end"><span title="Sunday">D</span></th>
					<th><span title="Monday">S</span></th>
					<th><span title="Tuesday">T</span></th>
					<th><span title="Wednesday">Q</span></th>
					<th><span title="Thursday">Q</span></th>
					<th><span title="Friday">S</span></th>
					<th class="ui-datepicker-week-end"><span title="Saturday">S</span></th>
				</tr>
			</thead>
			<tbody>
				<tr>';

 $weeks = ceil(($firstday_info[wday] + $lastday_info[mday] - 1) / 7);
 $count = 1;

 for ($i = 1; $i < $weeks + 1; $i++) {
  $str .= '
            <tr>';
  for ($j = 0; $j <= 6; $j++) {

	if ($count > $firstday_info['wday'] && $count <= $lastday_info['mday'] + $firstday_info['wday']) {
		$day = $count - $firstday_info['wday']/* + 1*/;
	} elseif ($count <= $lastday_info['mday'] + $firstday_info['wday'] && $params['other_months']) {
		$day = $j - $firstday_info['wday'];
		$className .= ' ui-datepicker-other-month ui-state-disabled';
	} elseif ($count > $firstday_info['wday'] && $params['other_months']) {
		$day = $j - $lastday_info['wday'];
		$className .= ' ui-datepicker-other-month ui-state-disabled';
	} else {
		$day = '&nbsp;';
	}
   $day_timestamp = mktime(0, 0, 0, (int)$timestamp_info['mon'], (int)$day, (int)$timestamp_info['year']);
	if ($params['other_months']) {
		$day = date('d', $day_timestamp);
	}
   
   $count++;
   
	$linkClassName = '';
   
	if ($day_timestamp == $today) {
		$className .= ' ui-datepicker-days-cell-over  ui-datepicker-current-day ui-datepicker-today';
		$linkClassName .= ' ui-state-highlight';
	}

	if ($day_timestamp == $view_calendar) {
    	$className .= ' ui-state-error';
	}
   
	if (in_array(date('w', $day_timestamp), array(0,6))) {
		$className .= ' ui-datepicker-week-end';
	}   
   

   if (!empty($events_per_day[$day_timestamp])) {
    $className = 'eventCalendar';
    $dayEvents = array();
    foreach ($events_per_day[$day_timestamp] as $key => $value) {
     foreach ($value as $event) {
      if (date("His", $key) == '0') {
       $dayEvents[] = '<b>'._ALLDAY.'</b> '.(strip_tags($event));
      } else {
       $dayEvents[] = '<b>#filter:timestamp_time_only_nosec-'.$key.'#</b> '.(strip_tags($event));
      }
     }
    }
    sort($dayEvents);

    $dayEvents = implode("<br>", $dayEvents);

    $day_str = '<a href = "'.basename($_SERVER['PHP_SELF']).'?ctg=calendar&view_calendar='.$day_timestamp.'" class = "info ui-state-default ' . $linkClassName . '  ui-state-active" >
		<span class = "tooltipSpan">'.$dayEvents.'</span>
	'.$day.'</a>';
    
	} else {
		if ($day != '&nbsp;') {
    		$day_str = '<a href = "'.basename($_SERVER['PHP_SELF']).'?ctg=calendar&view_calendar='.$day_timestamp.'" class="ui-state-default ' . $linkClassName . '">'.$day.'</a>';
   		} else {
   			$day_str = '';
   		}
   }


   

   $str .= '
                <td class = "'.$className.'">'./*$day_timestamp .*/ ' ' .$day_str.'</td>';
   $className = '';
  }

  $str .= '</tr>';
 }

 $str .= '
 			</tbody>
    	</table>
	</div>';

 return $str;
?>
 <!-- 
<div align="center" style="margin: 0 10px 10px;">
	<div
		class="ui-datepicker-inline ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"
		style="display: block;">

	<table class="ui-datepicker-calendar">
		<thead>
			<tr>
				<th class="ui-datepicker-week-end"><span title="Sunday">Su</span></th>
				<th><span title="Monday">Mo</span></th>
				<th><span title="Tuesday">Tu</span></th>
				<th><span title="Wednesday">We</span></th>
				<th><span title="Thursday">Th</span></th>
				<th><span title="Friday">Fr</span></th>
				<th class="ui-datepicker-week-end"><span title="Saturday">Sa</span></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td
					class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
				<td
					class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">1</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">2</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">3</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">4</a></td>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">5</a></td>
			</tr>
			<tr>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">6</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">7</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">8</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">9</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">10</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">11</a></td>
				<td
					class=" ui-datepicker-week-end ui-datepicker-days-cell-over  ui-datepicker-current-day ui-datepicker-today"
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default ui-state-highlight ui-state-active" href="#">12</a></td>
			</tr>
			<tr>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">13</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">14</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">15</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">16</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">17</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">18</a></td>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">19</a></td>
			</tr>
			<tr>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">20</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">21</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">22</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">23</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">24</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">25</a></td>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">26</a></td>
			</tr>
			<tr>
				<td class=" ui-datepicker-week-end "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">27</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">28</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">29</a></td>
				<td class=" "
					onclick="DP_jQuery_1321121322439.datepicker._selectDay('#datepicker',10,2011, this);return false;"><a
					class="ui-state-default" href="#">30</a></td>
				<td
					class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
				<td
					class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
				<td
					class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
			</tr>
		</tbody>
	</table>
	</div>
</div>
 -->
<?php 
}
?>

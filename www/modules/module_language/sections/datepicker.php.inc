<?php 
switch ($GLOBALS['configuration']['date_format']) {
	case "YYYY/MM/DD":
		$date_format = 'yy/mm/dd';
		break;
	case "MM/DD/YYYY":
		$date_format = 'mm/md/yy';
		break;
	case "DD/MM/YYYY":
	default:
		$date_format = 'dd/mm/yy';
		break;
}


$result = array(
	'data'	=> array(
		"closeText"			=> __DATEPICKER_CLOSE,
		"prevText"			=> __DATEPICKER_PREVTEXT,
		"nextText"			=> __DATEPICKER_NEXTTEXT,
		"currentText"			=> __DATEPICKER_TODAY,
		"monthNames"			=> array(
			__L10N_JANUARY, 
			__L10N_FEBRUARY, 
			__L10N_MARCH, 
			__L10N_APRIL, 
			__L10N_MAY, 
			__L10N_JUNE, 
			__L10N_JULY, 
			__L10N_AUGUST, 
			__L10N_SEPTEMBER,
			__L10N_OCTOBER, 
			__L10N_NOVEMBER, 
			__L10N_DECEMBER
		),
		"monthNamesShort"		=> array(
			__L10N_SHORT_JANUARY, 
			__L10N_SHORT_FEBRUARY, 
			__L10N_SHORT_MARCH, 
			__L10N_SHORT_APRIL, 
			__L10N_SHORT_MAY, 
			__L10N_SHORT_JUNE, 
			__L10N_SHORT_JULY, 
			__L10N_SHORT_AUGUST, 
			__L10N_SHORT_SEPTEMBER,
			__L10N_SHORT_OCTOBER, 
			__L10N_SHORT_NOVEMBER, 
			__L10N_SHORT_DECEMBER
		), 
		"dayNames"			=> array(
			__L10N_SUNDAY, 
			__L10N_MONDAY, 
			__L10N_TUESDAY, 
			__L10N_WEDNESDAY, 
			__L10N_THURSDAY, 
			__L10N_FRIDAY, 
			__L10N_SATURDAY, 
		),
		"dayNamesShort"		=> array(
			__L10N_SHORT_SUNDAY, 
			__L10N_SHORT_MONDAY, 
			__L10N_SHORT_TUESDAY, 
			__L10N_SHORT_WEDNESDAY, 
			__L10N_SHORT_THURSDAY, 
			__L10N_SHORT_FRIDAY, 
			__L10N_SHORT_SATURDAY, 
		),
		"dayNamesMin"			=> array(
			__L10N_MIN_SUNDAY, 
			__L10N_MIN_MONDAY, 
			__L10N_MIN_TUESDAY, 
			__L10N_MIN_WEDNESDAY, 
			__L10N_MIN_THURSDAY, 
			__L10N_MIN_FRIDAY, 
			__L10N_MIN_SATURDAY, 
		),
		"weekHeader"			=> __DATEPICKER_WEEK_HEADER,
		"dateFormat"			=> $date_format
//		firstDay			=> 0,
//		isRTL				=> __DATEPICKER_CLOSE,
//		showMonthAfterYear	=> false,
//		yearSuffix			=> ''
	),
	'description' => array(
		"closeText"			=> __DATEPICKER_CLOSE,
		"prevText"			=> __DATEPICKER_PREVTEXT,
		"nextText"			=> __DATEPICKER_NEXTTEXT,
		"currentText"			=> __DATEPICKER_TODAY,
		"monthNames"			=> array(
			__L10N_JANUARY,
			__L10N_FEBRUARY,
			__L10N_MARCH,
			__L10N_APRIL,
			__L10N_MAY,
			__L10N_JUNE,
			__L10N_JULY,
			__L10N_AUGUST,
			__L10N_SEPTEMBER,
			__L10N_OCTOBER,
			__L10N_NOVEMBER,
			__L10N_DECEMBER
		),
		"monthNamesShort"		=> array(
			__L10N_SHORT_JANUARY,
			__L10N_SHORT_FEBRUARY,
			__L10N_SHORT_MARCH,
			__L10N_SHORT_APRIL,
			__L10N_SHORT_MAY,
			__L10N_SHORT_JUNE,
			__L10N_SHORT_JULY,
			__L10N_SHORT_AUGUST,
			__L10N_SHORT_SEPTEMBER,
			__L10N_SHORT_OCTOBER,
			__L10N_SHORT_NOVEMBER,
			__L10N_SHORT_DECEMBER
		),
		"dayNames"			=> array(
			__L10N_SUNDAY,
			__L10N_MONDAY,
			__L10N_TUESDAY,
			__L10N_WEDNESDAY,
			__L10N_THURSDAY,
			__L10N_FRIDAY,
			__L10N_SATURDAY
		),
		"dayNamesShort"		=> array(
			__L10N_SHORT_SUNDAY,
			__L10N_SHORT_MONDAY,
			__L10N_SHORT_TUESDAY,
			__L10N_SHORT_WEDNESDAY,
			__L10N_SHORT_THURSDAY,
			__L10N_SHORT_FRIDAY,
			__L10N_SHORT_SATURDAY
		),
		"dayNamesMin"			=> array(
			__L10N_MIN_SUNDAY,
			__L10N_MIN_MONDAY,
			__L10N_MIN_TUESDAY,
			__L10N_MIN_WEDNESDAY,
			__L10N_MIN_THURSDAY,
			__L10N_MIN_FRIDAY,
			__L10N_MIN_SATURDAY
		),
		"weekHeader"			=> __DATEPICKER_WEEK_HEADER,
		"dateFormat"			=> $date_format
	)
);

return $result;

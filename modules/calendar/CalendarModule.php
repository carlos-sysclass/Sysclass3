<?php 
class CalendarModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array()) {
        $this->putScript("plugins/fullcalendar/fullcalendar/fullcalendar.min");
    	//$this->putScript("scripts/calendar");
        $this->putModuleScript("calendar");
        return array(
            'calendar' => array(
                'type'      => 'calendar', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'calendar-widget',
                'title'     => self::$t->translate('Calendar'),
                'template'  => $this->template("calendar.block"),
                'icon'      => 'calendar',
                'box'       => 'dark-blue calendar'
            )
        );
    }

    /**
     * Module Entry Point
     *
     * @url GET /data
     */
    public function dataAction()
    {
        $currentUser    = $this->getCurrentUser();

        $from   = intval($_GET['start']);
        $to     = intval($_GET['end']);

        $this->mapcolors = array(
            'global'    => 'label label-primary',
            'lesson'    => 'label label-success',
            'private'   => 'label label-danger',
            'group'     => 'label label-warning'
        );

        $events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();
        foreach($events aS $evt) {
            $timestamp = intval($evt['timestamp']);
            if ($timestamp >= $from && $timestamp <= $to) {
                $items[] = array(
                    'id'            => $evt['id'],
                    'title'         => substr(str_replace("\n", " ", strip_tags($evt['data'])), 0, 25),
                    'description'   => $evt['data'],
                    'start'         => $evt['timestamp'],
                    'allDay'        => true,
                    'className'     => $this->mapcolors[$evt['type']],
                    'editable'      => $evt['type'] == 'private' ? true : false
                );
            }
        }
        return $items;
    }

}

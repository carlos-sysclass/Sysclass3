<?php 
class CalendarModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets() {
        $this->putScript("plugins/fullcalendar/fullcalendar/fullcalendar.min");
    	$this->putScript("scripts/calendar");
        return array(
            'calendar' => array(
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
        $currentUser    = $this->getCurrentUser();;

        $events = calendar :: getCalendarEventsForUser($currentUser);
        var_dump($events);
        exit;
    }

}

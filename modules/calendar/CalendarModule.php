<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class CalendarModule extends SysclassModule implements ISummarizable, IWidgetContainer
{
    public function getSummary() {
        $data = array(1); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => self::$t->translate('Calendar Events'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all',
            )
        );
    }
    
    public function getWidgets($widgetsIndexes = array())
    {
        $this->putComponent("select2");

            $this->putModuleScript("widget.news");

        if (in_array('calendar', $widgetsIndexes))
        {
            $this->putScript("plugins/fullcalendar/fullcalendar/fullcalendar.min");
        	//$this->putScript("scripts/calendar");
            $this->putModuleScript("calendar");
        
            return array
            (
                'calendar' => array
                (
                    'type'      => 'calendar', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                    'id'        => 'calendar-widget',
                    'title'     => self::$t->translate('Calendar'),
                    'template'  => $this->template("calendar.block"),
                    'icon'      => 'calendar',
                    'box'       => 'dark-blue calendar'
                )
            );
        }
        return false;
    }

    /**
     * Module Entry Point
     *
     * @url GET /data
     */
    public function dataAction()
    {
        $currentUser    = $this->getCurrentUser();

        $events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();

        foreach($events aS $evt)
        {
            $items[] = array
            (
                'id'            => $evt['event_id'],
                'title'         => substr(str_replace("\n", " ", strip_tags($evt['event_name'])), 0, 25),
                'description'   => $evt['event_description'],
                'start'         => $evt['event_date'],
                'allDay'        => true,
                'color'         => $evt['event_type_color'],
                'editable'      => false
            );
        }

        return $items;
    }

    /**
     * Get the event according to the id
     *
     * @url GET /item/me/:id
    */
    public function getItemAction($id)
    {
         $editItem = $this->model("events/collection")->getItem($id);
         return $editItem;
    }

    /**
     * Insert a event model
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        $request = $this->getMatchedUrl();

        $itemModel = $this->model("events/item");

        if ($userData = $this->getCurrentUser())
        {
            $data = $this->getHttpData(func_get_args());

            $data['date'] = date("y-m-d", strtotime($data['date']));

            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE)
            {
                return $this->createAdviseResponse(self::$t->translate("Event created with success"),
                    "success"
                );
                
            }
            else
            {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        }
        else
        {
            return $this->notAuthenticatedError();
        }
    }
}

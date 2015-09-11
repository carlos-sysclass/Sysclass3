<?php
/**
 * Module Class File
 * @filesource
 */
use \Sysclass\Models\Calendar\Event;
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

        //$this->putModuleScript("widget.news");

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
     * [ add a description ]
     *
     * @url GET /config
     */
    public function configPage($route)
    {
        // MUST SHOW ALL AVALIABLE CALENDARS TYPES
        $this->createClientContext("config");
        if (!$this->createClientContext("config")) {
            $this->entryPointNotFoundError($this->getSystemUrl('home'));
        }
        $this->display($this->template);
    }

    /**
     * [ add a description ]
     *
     * @url GET /items/events-source
     */

    public function eventSourcesAction() {

        $item = array(
            'id' => 1,
            'url' => '/module/calendar/data',
            //'color' => '#005999',   // a non-ajax option
            //'borderColor' => "#aaaaaa",
            //'textColor' => 'white', // a non-ajax option
            'className' => 'calendar'
        );

        return array($item);
    }


    /**
     * [ add a description ]
     *
     * @url GET /data
     */
    public function dataAction()
    {
        $currentUser    = $this->getCurrentUser();

        $eventRS = Event::find();

        //$events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();

        foreach($eventRS aS $evt)
        {
            $items[] = array
            (
                'id'            => $evt->id,
                'title'         => substr(str_replace("\n", " ", strip_tags($evt->name)), 0, 25),
                'description'   => $evt->description,
                'start'         => $evt->start_date,
                'end'         => $evt->end_date,
                'allDay'        => true,
                //'color'         => $evt->type->color,
                'editable'      => true
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

            $data['date'] = date("Y-m-d H:i:s", strtotime($data['date']));

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

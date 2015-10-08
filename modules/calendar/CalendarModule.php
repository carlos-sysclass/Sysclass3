<?php
/**
 * Module Class File
 * @filesource
 */

use Sysclass\Models\Calendar\Sources as CalendarSource,
    Sysclass\Models\Calendar\Event;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class CalendarModule extends SysclassModule implements ISummarizable, IWidgetContainer, ILinkable, IBreadcrumbable, IActionable
{
    public function getSummary() {
        $data = Event::count(); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data,
            'text'  => self::$t->translate('Calendar Events'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all',
            )
        );
    }

    /* ILinkable */
    public function getLinks() {
        $count = Event::count(); // FAKE, PUT HERE DUE PAYMENTS
        $depinject = Phalcon\DI::getDefault();
        if ($depinject->get("acl")->isUserAllowed(null, "Calendar", "Edit")) {
            return array(
                'communication' => array(
                    array(
                        'count' => $count,
                        'text'  => self::$t->translate('Calendar Events'),
                        'icon'  => 'fa fa-calendar',
                        'link'  => $this->getBasePath() . 'manage'
                    )
                )
            );
        }
    }

    public function getWidgets($widgetsIndexes = array())
    {
        $this->putComponent("select2");

        //$this->putModuleScript("widget.news");

        if (in_array('calendar', $widgetsIndexes))
        {
            $this->putComponent("fullcalendar");
        	//$this->putScript("scripts/calendar");
            $this->putModuleScript("calendar");

            $widgets['calendar'] = array(
                'type'      => 'calendar', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'calendar-widget',
                'title'     => self::$t->translate('Calendar'),
                'template'  => $this->template("calendar.block"),
                'icon'      => 'calendar',
                'box'       => 'dark-blue calendar'
            );
        }

        if (in_array('news.latest', $widgetsIndexes)) {
            //$this->putModuleScript("models.news");
            $this->putModuleScript("widget.news");

            $this->putSectionTemplate("foot", "dialogs/news.view");

            $widgets['news.latest'] = array(
                'type'      => 'news', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'news-widget',
                'title'     => self::$t->translate('Announcements'),
                'template'  => $this->template("news.widget"),
                'icon'      => 'bell',
                'box'       => 'dark-blue tabbable',

                'tools'     => array(
                    'search'        => true,
                    //'reload'      => 'javascript:void(0);',
                    //'collapse'      => true,
                    'fullscreen'    => true
                )
            );
        }
        if (count($widgets) > 0) {
            return $widgets;
        }
        return false;
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-calendar',
                'link'  => $this->getBasePath() . "manage",
                'text'  => self::$t->translate("Calendar Events")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "event-source/add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New Event Source"));
                break;
            }
            /*
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Annoucement"));
                break;
            }
            */
        }
        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions()
    {
        $request = $this->getMatchedUrl();

        $actions = array
        (
            'manage'  => array
            (
                array(
                    'text'      => self::$t->translate('New Event Source'),
                    'link'      => $this->getBasePath() . "event-source/add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )
        );

        return $actions[$request];
    }

    /**
     * [ add a description ]
     *
     * @url GET /manage
     * @allow(resource=calendar, action=edit)
     */

    public function managePage($route)
    {
        // MUST SHOW ALL AVALIABLE CALENDARS TYPES
        $this->createClientContext("manage");
        if (!$this->createClientContext("manage")) {
            $this->entryPointNotFoundError($this->getSystemUrl('home'));
        }
        $eventSources = CalendarSource::find()->toArray();
        $this->putItem("event_sources", $eventSources);

        $this->display($this->template);
    }

    /**
     * [ add a description ]
     *
     * @url GET /event-source/add
     */

    public function addEventSourcePage()
    {
        // MUST SHOW ALL AVALIABLE CALENDARS TYPES
        $this->createClientContext("event-source/add");
        if (!$this->createClientContext("event-source/add")) {
            $this->entryPointNotFoundError($this->getSystemUrl('home'));
        }
        $this->display($this->template);
    }

    /**
     * [ add a description ]
     *
     * @url GET /items/event-sources
     */

    public function eventSourcesAction() {
        return CalendarSource::find()->toArray();

    }

    /**
     * [ add a description ]
     *
     * @url GET /items/calendar
     */
    public function calendarListAction()
    {
        $currentUser    = $this->getCurrentUser();

        $start = date_create_from_format("Y-m-d", $_GET['start']);
        $end = date_create_from_format("Y-m-d", $_GET['end']);

        $eventRS = Event::find(array(
            'conditions' => 'start BETWEEN ?0 AND ?1 OR [end] BETWEEN ?0 AND ?1',
            'bind' => array($start->format("U"), $end->format("U"))
        ));

        //$events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();

        foreach($eventRS as $evt)
        {
            $evtArray = $evt->toFullArray();
            //$evtArray['source'] = $evt->getSource()->toFullArray();

            $evtArray['className'] = $evt->calendarSource->class_name;
            $evtArray['allDay'] = true;
            $evtArray['editable'] = true;
            //$evtArray['start'] = "2015-09-19";
            $items[] = $evtArray;
            /*array
            (
                'id'            => $evt->id,
                //'title'         => substr(str_replace("\n", " ", strip_tags($evt->title)), 0, 25),
                'title'         => str_replace("\n", " ", strip_tags($evt->title)),
                'description'   => $evt->description,
                'start'         => $evt->start,
                'end'           => $evt->end,
                'allDay'        => true,
                //'color'         => $evt->type->color,
                'editable'      => true,
                'className'     => $evt->source->class_name
            );
            */
        }

        return $items;
    }

    /**
     * [ add a description ]
     *
     * @url GET /items/me/:filter
     */
    public function getItemsAction($filter)
    {
        $currentUser    = $this->getCurrentUser(true);

        $filter = json_decode($filter, true);

        $modelFilters = $filterData = array();

        if (is_array($filter)) {
            $index = 0;
            foreach($filter as $key => $item) {
                $modelFilters[] = "{$key} = ?{$index}";
                $filterData[$index] = $item;
                $index++;
            }
            $eventRS = Event::find(array(
                'conditions'    => implode(" AND ", $modelFilters),
                'bind' => $filterData
            ));
        } else {
            $eventRS = Event::find();
        }


        //$events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();

        foreach($eventRS as $evt)
        {
            $evtArray = $evt->toFullArray();
            //$evtArray['source'] = $evt->getSource()->toFullArray();

            //$evtArray['className'] = $evt->calendarSource->class_name;
            //$evtArray['allDay'] = true;
            //$evtArray['editable'] = true;
            $items[] = $evtArray;
            /*array
            (
                'id'            => $evt->id,
                //'title'         => substr(str_replace("\n", " ", strip_tags($evt->title)), 0, 25),
                'title'         => str_replace("\n", " ", strip_tags($evt->title)),
                'description'   => $evt->description,
                'start'         => $evt->start,
                'end'           => $evt->end,
                'allDay'        => true,
                //'color'         => $evt->type->color,
                'editable'      => true,
                'className'     => $evt->source->class_name
            );
            */
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

        if ($userModel = $this->getCurrentUser(true))
        {
            $data = $this->getHttpData(func_get_args());

            $eventModel = new Event();
            $eventModel->assign($data);
            $eventModel->user_id = $userModel->id;

            if ($eventModel->save()) {
                $response = $this->createAdviseResponse(self::$t->translate("Event created with success"),
                    "success"
                );

                $eventData = $eventModel->toFullArray();
                $eventData['className'] = $eventModel->calendarSource->class_name;

                return array_merge($response, $eventData);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(
                    self::$t->translate("A problem ocurred when tried to save you data. Please try again."),
                    "error"
                );
            }
        }
        else
        {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * Insert a event model
     *
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {

        if ($userModel = $this->getCurrentUser(true))
        {
            $data = $this->getHttpData(func_get_args());

            $eventModel = Event::findFirstById($id);
            $eventModel->assign($data);
            //$eventModel->user_id = $userModel->id;

            if ($eventModel->save()) {
                $response = $this->createAdviseResponse(self::$t->translate("Event updated with success"),
                    "success"
                );

                $eventData = $eventModel->toFullArray();
                $eventData['className'] = $eventModel->calendarSource->class_name;

                return array_merge($response, $eventData);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(
                    self::$t->translate("A problem ocurred when tried to save you data. Please try again."),
                    "error"
                );
            }
        }
        else
        {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url DELETE /item/me/:id
     */
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $eventModel = Event::findFirstById($id);

            if ($eventModel->delete()) {
                $response = $this->createAdviseResponse(self::$t->translate("Event removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(
                    self::$t->translate("A problem ocurred when tried to save you data. Please try again."),
                    "error"
                );
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
}

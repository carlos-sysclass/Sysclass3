<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\Calendar;

use Sysclass\Models\Calendar\Sources as CalendarSource,
    Sysclass\Models\Calendar\Event;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/calendar")
 */
class CalendarModule extends \SysclassModule implements \ISummarizable, \IWidgetContainer, \ILinkable, \IBreadcrumbable, \IActionable
{
    public function getSummary() {
        $data = Event::count(); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data,
            'text'  => $this->translate->translate('Events'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => "javascript:App.scrollTo($('#calendar-widget'))"


            )
        );
    }

    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, "Calendar", "Edit")) {
            $count = Event::count();

            return array(
                'communication' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Events'),
                        'icon'  => 'fa fa-calendar',
                        'link'  => $this->getBasePath() . 'manage'
                    )
                )
            );
        }
    }

    public function getWidgets($widgetsIndexes = array(), $caller = null)
    {
        $this->putComponent("select2");
        $this->putComponent("wysihtml5");

        //$this->putModuleScript("widget.news");

        if (in_array('calendar', $widgetsIndexes))
        {
            $this->putComponent("fullcalendar");
        	$this->putScript("plugins/fullcalendar/fullcalendar/gcal");
            
            $this->putModuleScript("calendar");

            $sources = CalendarSource::find();

            $this->putItem("calendar_sources", $sources->toArray());


            $widgets['calendar'] = array(
                'type'      => 'calendar', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'calendar-widget',
                'title'     => '',
                'template'  => $this->template("widgets/calendar"),
                'icon'      => 'calendar',
                'box'       => 'dark-blue calendar', 
                'tools'     => array(
                    'filter'        => true,
                    //'reload'      => 'javascript:void(0);',
                    //'collapse'      => true,
                    'fullscreen'    => true
                )
            );
        }

        if (in_array('news.latest', $widgetsIndexes)) {
            //$this->putModuleScript("models.news");
            $this->putModuleScript("widget.news");

            $this->putSectionTemplate("foot", "dialogs/news.view");

            $widgets['news.latest'] = array(
                'type'      => 'news', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'news-widget',
                'title'     => $this->translate->translate('Announcements'),
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
                'text'  => $this->translate->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-calendar',
                'link'  => $this->getBasePath() . "manage",
                'text'  => $this->translate->translate("Calendar Events")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "event-source/add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Event Source"));
                break;
            }
            /*
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Annoucement"));
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
                    'text'      => $this->translate->translate('View calendar sources'),
                    'link'      => $this->loader->module('CalendarSource')->getBasePath() . "view",
                    'icon'      => 'fa fa-list'
                ),
                array(
                    'separator' => true
                ),
                array(
                    'text'  => $this->translate->translate('Add calendar source'),
                    'link'  => $this->loader->module('CalendarSource')->getBasePath() . "add",
                    'icon'      => 'fa fa-plus-circle'
                ),
            )
        );

        return $actions[$request];
    }

    /**
     * [ add a description ]
     *
     * @Get("/manage")
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
     * @Get("/datasource/calendar")
     * @allow(resource=calendar, action=view)
     */
    public function calendarListRequest()
    {
        $currentUser    = $this->getCurrentUser();

        $start = date_create_from_format("Y-m-d", $this->request->getQuery('start'));
        $end = date_create_from_format("Y-m-d", $this->request->getQuery('end'));

        $eventRS = Event::find(array(
            'conditions' => 'start BETWEEN ?0 AND ?1 OR [end] BETWEEN ?0 AND ?1',
            'bind' => array($start->format("U"), $end->format("U"))
        ));

        //$events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();

        $editable = $this->acl->isUserAllowed(null, "calendar", "Edit");

        foreach($eventRS as $evt)
        {
            $evtArray = $evt->toFullArray();
            //$evtArray['source'] = $evt->getSource()->toFullArray();

            $evtArray['className'] = $evt->calendarSource->class_name;
            $evtArray['allDay'] = true;
            $evtArray['editable'] = $editable;
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
     * @Get("/items/me/{filter}")
     */
    /*
    public function getItemsRequest($filter)
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
        }

        return $items;
    }
    */
    /**
     * Get the event according to the id
     *
     * @Get("/item/me/{id}")
    */

    public function getItemRequest($id)
    {
         $editItem = $this->model("events/collection")->getItem($id);
         return $editItem;
    }

    /**
     * Insert a event model
     *
     * @Post("/item/me")
     */
    /*
    public function addItemRequest($id)
    {

        if ($userModel = $this->getCurrentUser(true))
        {
            $data = $this->getHttpData(func_get_args());

            $eventModel = new Event();
            $eventModel->assign($data);
            $eventModel->user_id = $userModel->id;

            if ($eventModel->save()) {
                $response = $this->createAdviseResponse($this->translate->translate("Event created with success"),
                    "success"
                );

                $eventData = $eventModel->toFullArray();
                $eventData['className'] = $eventModel->calendarSource->class_name;

                return array_merge($response, $eventData);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(
                    $this->translate->translate("A problem ocurred when tried to save you data. Please try again."),
                    "error"
                );
            }
        }
        else
        {
            return $this->notAuthenticatedError();
        }
    }
    */
    public function beforeModelCreate($evt, $model, $data) {
    }
    /**
     * Insert a event model
     *
     * @Put("/item/me/{id}")
     */
    public function setItemRequest($id)
    {

        if ($userModel = $this->getCurrentUser(true))
        {
            $data = $this->getHttpData(func_get_args());

            $eventModel = Event::findFirstById($id);
            $eventModel->assign($data);
            //$eventModel->user_id = $userModel->id;

            if ($eventModel->save()) {
                $response = $this->createAdviseResponse($this->translate->translate("Event updated."),
                    "success"
                );

                $eventData = $eventModel->toFullArray();
                $eventData['className'] = $eventModel->calendarSource->class_name;

                return array_merge($response, $eventData);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(
                    $this->translate->translate("A problem ocurred when tried to save you data. Please try again."),
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
     * @Delete("/item/me/{id}")
     */
    public function deleteItemRequest($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $eventModel = Event::findFirstById($id);

            if ($eventModel->delete()) {
                $response = $this->createAdviseResponse($this->translate->translate("Event removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(
                    $this->translate->translate("A problem ocurred when tried to save you data. Please try again."),
                    "error"
                );
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
}

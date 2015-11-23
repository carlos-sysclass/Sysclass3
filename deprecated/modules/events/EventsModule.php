<?php
/**
 * Module Class File
 * @filesource
 */
use \Sysclass\Models\Calendar\Event,
    \Sysclass\Models\Calendar\EventTypes;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 * @deprecated 3.0.1.1 Moved to calendar module
 */
class EventsModule extends SysclassModule implements /*ILinkable, */IBreadcrumbable, IActionable
{
    /* ILinkable */
    public function getLinks()
    {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator')
        {
            $eventsItems = $this->model("events/collection")->getItems();

            return array
            (
                'communication' => array
                (
                    array
                    (
                        'count' => count($eventsItems),
                        'text'  => $this->translate->translate('Event'),
                        'icon'  => 'fa fa-thumb-tack',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb()
    {
        $breadcrumbs = array
        (
            array
            (
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request)
        {
            case "view" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'fa fa-thumb-tack',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Event")
                );

                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));

                break;
            }
            case "add" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'fa fa-thumb-tack',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Event")
                );

                $breadcrumbs[] = array('text'   => $this->translate->translate("New Event"));

                break;
            }
            case "edit/:id" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'fa fa-thumb-tack',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Event")
                );

                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Event"));

                break;
            }
        }

        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions()
    {
        $request = $this->getMatchedUrl();

        $actions = array
        (
            'view'  => array
            (
                array(
                    'text'      => $this->translate->translate('New Event'),
                    'link'      => $this->getBasePath() . "add",
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
     * @url GET /add
     */
    public function addPage()
    {
        $eventTypesmodel = EventTypes::find();
        $this->putItem("event_types", $eventTypesmodel->toArray());

        // HANDLE PAGE
        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        $eventTypesmodel = EventTypes::find();
        $this->putItem("event_types", $eventTypesmodel->toArray());

        parent::editPage($id);
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
    */
    public function getItemAction($id)
    {
        $eventModel = Event::findFirstById($id);

        return $eventModel->toFullArray();
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        if ($userData = $this->getCurrentUser(true))
        {
            $data = $this->getHttpData(func_get_args());

            $eventModel = new Event();
            $eventModel->assign($data);

            $eventModel->user_id = $userData->id;

            //$data['login'] = $userData['login'];
            if ($eventModel->save())
            {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $eventModel->id,
                    $this->translate->translate("Event created with success"),
                    "success"
                );
            }
            else
            {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("There's ocurred a problen when the system tried to save your data. Please check your data and try again", "error");
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
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser())
        {
            $data = $this->getHttpData(func_get_args());

            $eventModel = Event::findFirstById($id);
            $eventModel->assign($data);

            if ($eventModel->save() !== FALSE)
            {
                $response = $this->createAdviseResponse($this->translate->translate("Event updated with success"), "success");

                return array_merge($response, $eventModel->toFullArray());
            }
            else
            {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
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
        if ($userData = $this->getCurrentUser())
        {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("events/item");
            if ($itemModel->deleteItem($id) !== FALSE)
            {
                $response = $this->createAdviseResponse($this->translate->translate("Event removed with success"), "success");
                return $response;
            }
            else
            {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("There's ocurred a problen when the system tried to save your data. Please check your data and try again", "error");
            }
        }
        else
        {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * Get all event type visible to the current user
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    public function getItemsAction($type)
    {
        if ($currentUser = $this->getCurrentUser(true)) {
            $itemsRS = Event::find();

            /*
            $currentUser    = $this->getCurrentUser(true);
            $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

            $modelRoute = "events/collection";
            $baseLink = $this->getBasePath();

            $itemsCollection = $this->model($modelRoute);
            $itemsData = $itemsCollection->getItems();

            $items = $itemsData;
            */
            if ($type === 'combo')
            {
                $q = $_GET['q'];

                $items = $itemsCollection->filterCollection($items, $q);

                foreach($items as $event) {
                    // Events
                    $result[] = array
                    (
                        'id'            => intval($event['id']),
                        'title'         => substr(str_replace("\n", " ", strip_tags($event['name'])), 0, 25),
                        'description'   => $event['description'],
                        'start'         => $event['date'],
                        'allDay'        => true,
                        'color'         => $event['event_type_color'],
                        'editable'      => false
                    );
                }

                return $result;
            }
            else if ($type === 'datatable')
            {
                $items = array();
                foreach($itemsRS as $key => $model)
                {
                    $items[$key] = $model->toFullArray();

                    $items[$key]['options'] = array
                    (
                        'edit'  => array
                        (
                            'icon'  => 'icon-edit',
                            'link'  => $baseLink . "edit/" . $model->id,
                            'class' => 'btn-sm btn-primary'
                        ),
                        'remove'    => array
                        (
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                }
                return array
                (
                    'sEcho'                 => 1,
                    'iTotalRecords'         => count($items),
                    'iTotalDisplayRecords'  => count($items),
                    'aaData'                => array_values($items)
                );
            }
        }

        return $items->toArray();
    }


    /**
     * [ add a description ]
     *
     * @url GET /item/users/
     * @deprecated
    */
    public function getEvents()
    {
        $data = $this->getHttpData(func_get_args());

        $eventTypesModel = $this->model("events/item");

        $eventTypes = $eventTypesModel->getEvents();

        return $eventTypes;
    }


    /**
     * [ add a description ]
     *
     * @url GET /data/:id
     * @deprecated
     */
    public function dataAction($id)
    {
        $currentUser    = $this->getCurrentUser();

        $events = calendar :: getCalendarEventsForUser($currentUser);

        $items = array();

        foreach($events aS $evt)
        {
            if($id == 0)
            {
                $items[] = array
                (
                    'id'            => intval($evt['event_id']),
                    'title'         => substr(str_replace("\n", " ", strip_tags($evt['event_name'])), 0, 25),
                    'description'   => $evt['event_description'],
                    'start'         => $evt['event_date'],
                    'allDay'        => true,
                    'color'         => $evt['event_type_color'],
                    'editable'      => false
                );
            }
            else if($evt['event_type_id'] === $id)
            {
                $items[] = array
                (
                    'id'            => intval($evt['event_id']),
                    'title'         => substr(str_replace("\n", " ", strip_tags($evt['event_name'])), 0, 25),
                    'description'   => $evt['event_description'],
                    'start'         => $evt['event_date'],
                    'allDay'        => true,
                    'color'         => $evt['event_type_color'],
                    'editable'      => false
                );
            }
        }

        return $items;
    }
}

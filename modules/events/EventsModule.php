<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */
class EventsModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable
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
                        'text'  => self::$t->translate('Event'),
                        'icon'  => 'icon-event',
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
                'text'  => self::$t->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request)
        {
            case "view" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'icon-event',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Event")
                );

                $breadcrumbs[] = array('text'   => self::$t->translate("View"));

                break;
            }
            case "add" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'icon-event',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Event")
                );

                $breadcrumbs[] = array('text'   => self::$t->translate("New Event"));

                break;
            }
            case "edit/:id" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'icon-event',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Event")
                );

                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Event"));

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
                    'text'      => self::$t->translate('New Event'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )
        );

        return $actions[$request];
    }
    /**
     * Get the institution visible to the current user
     *
     * @url GET /item/users/
    */
    public function getEvents()
    {
        $data = $this->getHttpData(func_get_args());

        $eventTypesModel = $this->model("events/item");

        $eventTypes = $eventTypesModel->getEvents();

        return $$eventTypes;
    }

    /**
     * Get the institution visible to the current user
     *
     * @url GET /item/me/:id
    */
    public function getItemAction($id)
    {
         $editItem = $this->model("events/collection")->getItem($id);
         return $editItem;
    }

    /**
     * Insert a news model
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

            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE)
            {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("Event created with success"),
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

    /**
     * Update a news model
     *
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        $itemModel = $this->model("events/item");

        if ($userData = $this->getCurrentUser())
        {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $id) !== FALSE)
            {
                $response = $this->createAdviseResponse(self::$t->translate("Event updated with success"), "success");
                return array_merge($response, $data);
            }
            else
            {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
            }
        }
        else
        {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * DELETE a news model
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
                $response = $this->createAdviseResponse(self::$t->translate("Event removed with success"), "success");
                return $response;
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
    /**
     * Get all event type visible to the current user
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    public function getItemsAction($type)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRoute = "events/collection";
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();

        $items = $itemsData;

        if ($type === 'combo')
        {
        	/*
            $q = $_GET['q'];

            $items = $itemsCollection->filterCollection($items, $q);

            foreach($items as $course) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($course['id']),
                    'name'  => $course['name']
                );
            }
            return $result;
            */
        }
        else if ($type === 'datatable')
        {
            $items = array_values($items);
            foreach($items as $key => $item)
            {
                $items[$key]['options'] = array
                (
                    'edit'  => array
                    (
                        'icon'  => 'icon-edit',
                        'link'  => $baseLink . "edit/" . $item['id'],
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

        return array_values($items);
    }

}

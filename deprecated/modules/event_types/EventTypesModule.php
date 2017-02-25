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
class EventTypesModule extends SysclassModule implements IBreadcrumbable, IActionable
{
    /* ILinkable */
    public function getLinks()
    {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator')
        {
            $eventTypesItems = $this->model("event/types/collection")->getItems();

            return array
            (
                'communication' => array
                (
                    array
                    (
                        'count' => count($eventTypesItems),
                        'text'  => $this->translate->translate('Event Type'),
                        'icon'  => 'fa fa-pencil-square-o',
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
                    'icon'  => 'icon-event_types',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Event Types")
                );

                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));

                break;
            }
            case "add" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'icon-event_types',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Event Types")
                );

                $breadcrumbs[] = array('text'   => $this->translate->translate("New Event Type"));

                break;
            }
            case "edit/:id" :
            {
                $breadcrumbs[] = array
                (
                    'icon'  => 'icon-event_types',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Event Types")
                );

                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Event Type"));

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
                    'text'      => $this->translate->translate('New Event Type'),
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
     * @url GET /item/users/
    */
    public function getEventTypes()
    {
        $data = $this->getHttpData(func_get_args());

        $eventTypesModel = $this->model("event/types/item");

        $eventTypes = $eventTypesModel->getEvents();

        return $$eventTypes;
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
    */
    public function getItemAction($id)
    {
         $editItem = $this->model("event/types/collection")->getItem($id);
         return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        $request = $this->getMatchedUrl();

        $itemModel = $this->model("event/types/item");

        if ($userData = $this->getCurrentUser())
        {
            $data = $this->getHttpData(func_get_args());

            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE)
            {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    $this->translate->translate("New event type created."),
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
     * [ add a description ]
     *
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        $itemModel = $this->model("event/types/item");

        if ($userData = $this->getCurrentUser())
        {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $id) !== FALSE)
            {
                $response = $this->createAdviseResponse($this->translate->translate("Event updated."), "success");
                return array_merge($response, $data);
            }
            else
            {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problen when the system tried to save your data. Please, check your data and try again"), "error");
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

            $itemModel = $this->model("event/types/item");
            if ($itemModel->deleteItem($id) !== FALSE)
            {
                $response = $this->createAdviseResponse($this->translate->translate("Event type removed."), "success");
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

        $modelRoute = "event/types/collection";
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();

        $items = $itemsData;

        if ($type === 'combo')
        {

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

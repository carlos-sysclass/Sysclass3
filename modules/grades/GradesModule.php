<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class GradesModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable
{

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $groupItems = $this->model("grades/groups/collection")->addFilter(array(
                'active'    => true
            ))->getItems();
            // $items = $this->module("permission")->checkRules($itemsData, "course", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => 0, //count($groupItems),
                        'text'  => self::$t->translate('Grades Settings'),
                        'icon'  => 'fa fa-cog',
                        'link'  => $this->getBasePath() . 'view'
                    ),
                    array(
                        'count' => count($groupItems),
                        'text'  => self::$t->translate('Grades Groups'),
                        'icon'  => 'fa fa-cogs',
                        'link'  => $this->getBasePath() . 'view-group'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Grades Rules")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Grades Rules")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("New Grade Rules"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Grades Rules")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Grade Rule"));
                break;
            }
            case "view-group" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view-group",
                    'text'  => self::$t->translate("Grades Groups")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add-group" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view-group",
                    'text'  => self::$t->translate("Grades Groups")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("New Grade Group"));
                break;
            }
            case "edit-group/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view-group",
                    'text'  => self::$t->translate("Grades Groups")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Grade Group"));
                break;
            }
        }
        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'      => self::$t->translate('New Grade Rule'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-plus",
                    'icon'      => 'icon-plus'
                ),
                array(
                    'text'      => self::$t->translate('View Grade Groups'),
                    'link'      => $this->getBasePath() . "view-group",
                    'class'     => "btn-default",
                    'icon'      => 'icon-group'
                ),
                array(
                    'text'      => self::$t->translate('New Grade Group'),
                    'link'      => $this->getBasePath() . "add-group",
                    'class'     => "btn-default",
                    'icon'      => 'icon-group'
                )
            ),
            'view-group'  => array(
                array(
                    'text'      => self::$t->translate('New Grade Group'),
                    'link'      => $this->getBasePath() . "add-group",
                    'class'     => "btn-default",
                    'icon'      => 'icon-plus'
                ),
                array(
                    'text'      => self::$t->translate('View Grade Rules'),
                    'link'      => $this->getBasePath() . "view",
                    'class'     => "btn-default",
                    'icon'      => 'icon-briefcase'
                ),
                array(
                    'text'      => self::$t->translate('New Grade Rule'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-default",
                    'icon'      => 'icon-briefcase'
                ),

            )
        );

        return $actions[$request];
    }

    /**
     * Module Entry Point
     *
     * @url GET /view-group
     */
    public function viewGroupPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        if ($currentUser->getType() == 'administrator') {

            $this->createClientContext("view-group");
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }


    /**
     * New model entry point
     *
     * @url GET /add-group
     */
    public function addGroupPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        if ($currentUser->getType() == 'administrator') {
            if (!$this->createClientContext("add-group")) {
                $this->entryPointNotFoundError($this->getSystemUrl('home'));
            }
            $this->display($this->template);

        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * New model entry point
     *
     * @url GET /edit-group/:id
     */
    public function editGroupPage($id)
    {
        $currentUser    = $this->getCurrentUser(true);

        if ($currentUser->getType() == 'administrator') {
            if (!$this->createClientContext("edit-group", array('entity_id' => $id))) {
                $this->entryPointNotFoundError($this->getSystemUrl('home'));
            }
            $this->display($this->template);

        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * Get the institution visible to the current user
     *
     * @url GET /item/me/:id
     * @url GET /group/item/me/:id
    */
    public function getItemAction($id) {
        $matchedurl = $this->getMatchedUrl();
        if (strpos($matchedurl, "group") === 0) {
            $modelRoute = "grades/groups/collection";
        } else {
            $modelRoute = "grades/rules/collection";
        }

        $editItem = $this->model($modelRoute)->getItem($id);
        return $editItem;
    }

    /**
     * Insert a news model
     *
     * @url POST /item/me
     * @url POST /group/item/me
     */
    public function addItemAction($id)
    {
        $matchedurl = $this->getMatchedUrl();

        if (strpos($matchedurl, "group") === 0) {
            $modelRoute = "grades/groups/item";
            $successMessage = "Grade Group created with success";
            $optionsRoute = "edit-group";
        } else {
            $modelRoute = "grades/rules/item";
            $successMessage = "Grade Rule created with success";
            $optionsRoute = "edit";
        }

        $itemModel = $this->model($modelRoute);

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . $optionsRoute . "/" . $data['id'],
                    self::$t->translate($successMessage),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * Update a news model
     *
     * @url PUT /item/me/:id
     * @url PUT /group/item/me/:id
     */
    public function setItemAction($id)
    {
        $matchedurl = $this->getMatchedUrl();

        if (strpos($matchedurl, "group") === 0) {
            $modelRoute = "grades/groups/item";
            $successMessage = "Grade Group updated with success";
            $optionsRoute = "edit-group";
        } else {
            $modelRoute = "grades/rules/item";
            $successMessage = "Grade Rule updated with success";
            $optionsRoute = "edit";
        }

        $itemModel = $this->model($modelRoute);

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate($successMessage), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * DELETE a news model
     *
     * @url DELETE /item/me/:id
     * @url DELETE /group/item/me/:id
     */
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

        $matchedurl = $this->getMatchedUrl();

            if (strpos($matchedurl, "group") === 0) {
                $modelRoute = "grades/groups/item";
                $successMessage = "Grade Group removed with success";
                $optionsRoute = "edit-group";
            } else {
                $modelRoute = "grades/rules/item";
                $successMessage = "Grade Rule removed with success";
                $optionsRoute = "edit";
            }


            $itemModel = $this->model($modelRoute);
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate($successMessage ), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * Get all users visible to the current user
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     * @url GET /group/items/me
     * @url GET /group/items/me/:type
     */
    public function getItemsAction($type)
    {
        $matchedurl = $this->getMatchedUrl();
        if (strpos($matchedurl, "group") === 0) {
            $modelRoute = "grades/groups/collection";
            $optionsRoute = "edit-group";
        } else {
            $modelRoute = "grades/rules/collection";
            $optionsRoute = "edit";
        }

        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        //$modelRoute = "users/groups/collection";
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();


 		// $items = $this->module("permission")->checkRules($itemsData, "users", 'permission_access_mode');
        $items = $itemsData;

        if ($type === 'combo') {
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
        } elseif ($type === 'datatable') {

            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $baseLink . $optionsRoute . "/" . $item['id'],
                        'class' => 'btn-sm btn-primary'
                    ),
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    )
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }

        return array_values($items);
    }

}

<?php
namespace Sysclass\Modules\Grades;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Courses\Grades\Grade;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/grades")
 */
class GradesModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable
{
    //protected $_modelRoute = "grades";

    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, $this->module_id, "View")) {

            $count = Grade::count("active = 1");

            return array(
                'content' => array(
                    array(
                        'count' => $count,
                        'text'  => self::$t->translate('Grades'),
                        'icon'  => 'fa fa-cogs',
                        'link'  => $this->getBasePath() . 'view'
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
                    'text'  => self::$t->translate("Grades")
                );
                //$breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Grades")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("New Grade Rule"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-briefcase',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Grades")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Grade Rule"));
                break;
            }
            /*
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
            */
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
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )/*,
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
            */
        );

        return $actions[$request];
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
    */
   /*
    public function getItemAction($id) {
        $editItem = $this->model($this->_modelRoute)->getItem($id);
        return $editItem;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/:model
     */
    /*
    public function addItemAction($model)
    {
        $matchedurl = $this->getMatchedUrl();

        if ($model === "me") {
            $modelRoute = $this->_modelRoute;
            $successMessage = "Grade Group created with success";
        } else {
            return $this->invalidRequestError();
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
    */
    /**
     * [ add a description ]
     *
     * @url PUT /item/:model/:identifier
     */
    /*
    public function setItemAction($model, $identifier)
    {

        if ($model === "me") {
            $modelRoute = $this->_modelRoute;
            $successMessage = "Grade Group created with success";
        } else {
            return $this->invalidRequestError();
        }
        $itemModel = $this->model($modelRoute);

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $identifier) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate($successMessage), "success");
                $data = $itemModel->getItem($identifier);
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url DELETE /item/me/:id
     */
    /*
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
    */
    /**
     * [ add a description ]
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    /*
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        // DEFAULT OPTIONS ROUTE
        $optionsRoute = array(
            'edit'  => array(
                'icon'  => 'icon-edit',
                'link'  => 'edit/%id$s',
                'class' => 'btn-sm btn-primary'
            ),
            'remove'    => array(
                'icon'  => 'icon-remove',
                'class' => 'btn-sm btn-danger'
            )
        );


        if ($model == "me") {
            $itemsCollection = $this->model($this->_modelRoute);
            if (!empty($filter)) {
                $filter = json_decode($filter, true);
                if (is_array($filter)) {
                    // SANITIZE ARRAY
                    $itemsCollection->addFilter($filter);
                }
            }
            $itemsData = $itemsCollection->getItems();
        } else {
            return $this->invalidRequestError();
        }

        if ($type === 'combo') {
        } elseif ($type === 'datatable') {
            $stringsHelper = $this->helper("strings");
            $itemsData = array_values($itemsData);
            foreach ($itemsData as $key => $item) {
                $itemsData[$key]['options'] = array();
                foreach($optionsRoute as $index => $optItem) {
                    //var_dump($item);
                    if (array_key_exists('link', $optItem)) {
                        $optItem['link'] = $this->getBasePath() . $stringsHelper->vksprintf($optItem['link'], $item);
                    } elseif (array_key_exists('action', $optItem)) {
                        $optItem['action'] = $this->getBasePath() . $stringsHelper->vksprintf($optItem['action'], $item);
                    }

                    $itemsData[$key]['options'][$index] = $optItem;
                }
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($itemsData),
                'iTotalDisplayRecords'  => count($itemsData),
                'aaData'                => array_values($itemsData)
            );
        }

        return array_values($itemsData);
    }
    */
    
    /**
     * [ add a description ]
     *
     * @url GET /view-group
     * @deprecated 3.0.0.24
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
     * [ add a description ]
     *
     * @url GET /add-group
     * @deprecated 3.0.0.24
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
     * [ add a description ]
     *
     * @url GET /edit-group/:id
     * @deprecated 3.0.0.24
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
}

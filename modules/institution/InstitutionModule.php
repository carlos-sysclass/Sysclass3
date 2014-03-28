<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class InstitutionModule extends SysclassModule implements IWidgetContainer, ILinkable, IBreadcrumbable, IActionable
{
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('institution.overview', $widgetsIndexes)) {
            $this->putModuleScript("widget.institution");

        	return array(
        		'institution.overview' => array(
       				//'title' 	=> 'User Overview',
                    'id'        => 'institution-widget',
       				'template'	=> $this->template("overview.widget"),
                    'panel'     => true
        		)
        	);
        }
    }
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        //if ($this->getCurrentUser(true)->getType() == 'administrator') {
            return array(
                'general' => array(
                    array(
                        //'count' => '20',
                        'text'  => self::$t->translate('Institution'),
                        'icon'  => 'glyphicon glyphicon-home',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        //}
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
                'icon'  => 'glyphicon glyphicon-home',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Institutions")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New Institution"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Institution"));
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
                    'text'      => self::$t->translate('New Institution'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )/*,
                array(
                    'separator' => true,
                ),
                array(
                    'text'      => 'Add New 2',
                    'link'      => $this->getBasePath() . "add",
                    //'class'       => "btn-primary",
                    //'icon'      => 'icon-plus'
                )*/
            )
        );

        return $actions[$request];
    }

    /**
     * Get the institution visible to the current user
     *
     * @url GET /item/me/:id
     */
    public function getItemAction($id) {

        $editItem = $this->model("institution")->getItem($id);     
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        return $editItem;
    }
    /**
     * Insert a news model
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");
            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("Institution created with success"), 
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
     */
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution")->debug();
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Institution updated with success"), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
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
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");
            // TODO Create some  way to make check if this entity can be removed!
            //if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Institution removed with success"), "success");
                return $response;
            //} else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
            //    return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos", "error");
            //}
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * Get all news visible to the current user
     *
     * @url GET /items/me
     * @url GET /items/me/:datatable
     */
    public function getItemsAction($datatable)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $itemsData = $this->model("institution")->addFilter(array(
            'active' => 1
        ))->getItems();
        //echo "<pre>";
        //var_dump($itemsData);

        $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

        if ($datatable === 'datatable') {
            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . "edit/" . $item['id'],
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

    /**
     * Module Entry Point
     *
     * @url GET /view
     */
    public function viewPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        // SHOW ANNOUCEMENTS BASED ON USER TYPE
        if ($currentUser->getType() == 'administrator') {
            $this->putItem("page_title", self::$t->translate('Institution'));
            $this->putItem("page_subtitle", self::$t->translate('Manage your Institution(s)'));

            $this->putComponent("select2");
            $this->putComponent("data-tables");
            $this->putModuleScript("models.institution");
            $this->putModuleScript("views.institution.view");

            //return array_values($news);
            $this->display("view.tpl");
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }
    /**
     * New model entry point
     *
     * @url GET /add
     */
    public function addPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        $this->putComponent(/* "datepicker", "timepicker", "select2", */"wysihtml5", "validation");
        $this->putModuleScript("models.institution");
//      $this->putModuleScript("views.news");
        $this->putModuleScript("views.institution.add");

        $this->putItem("page_title", self::$t->translate('Institution'));
        $this->putItem("page_subtitle", self::$t->translate('Manage your Institution(s)'));

        //return array_values($news);
        $this->display("form.tpl");
    }

    /**
     * Module Entry Point
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        $currentUser    = $this->getCurrentUser(true);

        $editItem = $this->model("news")->getItem($id);
        // TODO CHECK PERMISSION FOR OBJECT

        $this->putComponent("datepicker", "timepicker", "select2", "wysihtml5", "validation");

        // TODO CREATE MODULE BLOCKS, WITH COMPONENT, CSS, JS, SCRIPTS AND TEMPLATES LISTS TO INSERT
        // Ex: 
        // $this->putBlock("block-name") or $this->putCrossModuleBlock("permission", "block-name")
        
        $this->putBlock("address.add");
        $this->putBlock("permission.add");

/*
        $this->putComponent("modal");
        $this->putCrossModuleScript("permission", "dialog.permission");
        $this->putCrossSectionTemplate("permission", null, "blocks/permission");
        $this->putCrossSectionTemplate("permission", "foot", "dialogs/add");
        $this->putCrossModuleScript("permission", "dialog.permission");
*/

        $this->putModuleScript("models.institution");
        //$this->putModuleScript("views.news");
        $this->putModuleScript("views.institution.edit", array('id' => $id));

        $this->putItem("page_title", self::$t->translate('Institution'));
        $this->putItem("page_subtitle", self::$t->translate('Manage your Institution(s)'));

        $this->putItem("form_action", $_SERVER['REQUEST_URI']);
        //$this->putItem("entity", $editItem);

        //return array_values($news);
        $this->display("form.tpl");
    }
}

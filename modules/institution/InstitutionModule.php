<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class InstitutionModule extends SysclassModule implements IWidgetContainer, ILinkable, IBreadcrumbable, IBlockProvider
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'institution.social-gadgets' => function($data, $self) {
                //$this->putComponent("modal");
                //$this->putModuleScript("dialog.permission");
                //$this->putSectionTemplate(null, "blocks/permission");
                $self->putSectionTemplate("bottom", "blocks/social-gadgets");

                return true;

            }
        );
    }

    public function getWidgets($widgetsIndexes = array())
    {

        $itemsData = $this->model("institution")->getItem(1);

        //exit;

        //$this->putItem("institution", $itemsData);

        if (in_array('institution.overview', $widgetsIndexes)) {
            //$this->putModuleScript("widget.institution");

        	return array(
        		'institution.overview' => array(
       				//'title' 	=> 'User Overview',
                    'id'        => 'institution-widget',
       				'template'	=> $this->template("widgets/overview"),
                    'panel'     => true,
                    'data' => $itemsData
        		)
        	);
        }
    }
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {

            $itemsData = $this->model("institution")->addFilter(array(
                'active'    => true
            ))->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

            return array(
                'administration' => array(
                    array(
                        'count' => count($items),
                        'text'  => self::$t->translate('Institution'),
                        'icon'  => 'fa fa-university',
                        'link'  => $this->getBasePath() . 'edit/1'
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
            ),
            array(
                'icon'  => 'fa fa-university',
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
    /*
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'      => self::$t->translate('New Institution'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )
        );

        return $actions[$request];
    }
    */
    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
     */
    public function getItemAction($id) {

        $editItem = $this->model("institution")->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS

        //$editItem['logo'] = $this->model("dropbox")->getItem($editItem['logo_id']);
        return $editItem;
    }
    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        return $this->invalidRequestError(self::$t->translate("There's no multi-institution support yet!"), "error");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");
            $data['login'] = $userData['login'];

            if (!empty($data['website']) && !preg_match('/(http[s]?:\/\/)/', $data['website']))
            {
                $data['website'] = 'http://' . $data['website'];
            }

            /*if (!preg_match('/(https:\/\/)/', $data['facebook']) && !preg_match('/(http:\/\/)/', $data['facebook']))
            {
                $data['facebook'] = 'https://' . $data['facebook'];
            }
            */

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
     * [ add a description ]
     *
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");

            if (!empty($data['website']) && !preg_match('/(http[s]?:\/\/)/', $data['website']))
            {
                $data['website'] = 'http://' . $data['website'];
            }

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
     * [ add a description ]
     *
     * @url DELETE /item/me/:id
     */
    public function deleteItemAction($id)
    {
        return $this->invalidRequestError(self::$t->translate("You can delete the last institution in the system!"), "error");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("institution");
            // TODO Create some  way to make check if this entity can be removed!
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Institution removed with success"), "success");
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
     * Get all news visible to the current user
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    public function getItemsAction($type)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $itemsCollection = $this->model("institution");
        $itemsData = $itemsCollection->getItems();
        $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

        if ($type === 'combo') {
            $q = $_GET['q'];

            $items = $itemsCollection->filterCollection($items, $q);

            foreach($items as $item) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($item['id']),
                    'name'  => $item['name']
                );
            }
            return $result;
        } elseif ($type === 'datatable') {
            $itemsData = $this->model("institution")->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');


            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['country_code'] = $this->translateHttpResource(sprintf(
                    "img/flags/%s.png",
                    strtolower($item['country_code'])
                ));

                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . "edit/" . $item['id'],
                        'class' => 'btn-sm btn-primary'
                    )/*,
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    ),
                    'website'    => array(
                        'icon'  => 'icon-link',
                        'link'  => $item['website'],
                        'class' => 'btn-sm'
                    ),
                    'facebook'    => array(
                        'icon'  => 'icon-facebook',
                        'link'  => $item['facebook'],
                        'class' => 'btn-sm'
                    )
                    */
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }
        $itemsData = $this->model("institution")->getItems();
        $items = $this->module("permission")->checkRules($itemsData, "institution", 'permission_access_mode');

        return array_values($items);
    }
}

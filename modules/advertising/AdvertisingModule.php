<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * Manage and control the advertising system strategy
 * @package Sysclass\Modules
 */
class AdvertisingModule extends SysclassModule implements IWidgetContainer, ILinkable, IBreadcrumbable, IBlockProvider
{
    protected $_modelRoute = "advertising";
    /* IWidgetContainer */
    public function getWidgets($widgetsIndexes = array()) {

        $leftbar_data = $this->getConfig("widgets\ads.leftbar.banner\context");
        $rightbar_data = $this->getConfig("widgets\ads.rightbar.banner\context");

        $adsModel = $this->model($this->_modelRoute);
        $adsContentModel = $this->model("advertising/content");

        $items = $adsModel->getItems();


        $widgetsData = array();

        foreach($items as $item) {
            if (!array_key_exists($item['placement'], $widgetsData)) {
                $widgetsData[$item['placement']] = array(
                    'id'        => str_replace('.', '-', $item['placement']),
                    'template'  => $this->template("widgets/" . $item['placement']),
                    'data'      => array(
                        'type' => $item['view_type']
                    )
                );
            }
            $adsContentData = $adsContentModel->clear()->addFilter(array(
                'active'    => 1,
                'advertising_id' => $item['id']
            ))->getItems();

            foreach($adsContentData as $content) {
                if (!is_array($widgetsData[$item['placement']]['data']['content'])) {
                    $widgetsData[$item['placement']]['data']['content'] = array();
                }

                if ($content['content_type'] == "file") {
                    $widgetsData[$item['placement']]['data']['content'][] = array(
                        'type' => 'image',
                        'url' => $content['file']['url']
                    );
                } elseif ($content['content_type'] == "text") {
                    $widgetsData[$item['placement']]['data']['content'][] = array(
                        'type' => 'text',
                        'html' => $content['info']
                    );
                }
            }
        }



        return $widgetsData;
    }

    /* ILinkable */
    public function getLinks() {

        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model($this->_modelRoute)->getItems();
            //$items = $this->module("permission")->checkRules($itemsData, "test", 'permission_access_mode');

            return array(
                'communication' => array(
                    array(
                        'count' => count($itemsData),
                        'text'  => self::$t->translate('Advertising'),
                        'icon'  => 'fa fa-money',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb()
    {
        $breadcrumbs = array(
            array(
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-money',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Advertising")
            )
        );

        $request = $this->getMatchedUrl();
        switch ($request) {
            case "view":
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            case "edit/:id":
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Advertising"));
                break;
        }
        return $breadcrumbs;
    }

    /* IActionable */
    /*
    public function getActions()
    {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'      => self::$t->translate('New Lesson'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus'
                )
            )
        );

        return $actions[$request];
    }
    */
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'advertising.banners' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload-image");
                $self->putComponent("bootstrap-confirmation");

                $self->putModuleScript("blocks.advertising.banners");

                $self->putSectionTemplate("advertising-banners", "blocks/banners.list");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            }
        );
    }



    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    public function addPage()
    {
        $placements = array(
            array('id' => 'ads.leftbar.banner', 'name' => self::$t->translate('Left Side')),
            array('id' => 'ads.rightbar.banner', 'name' => self::$t->translate('Right Side'))
        );
        $this->putitem("placements", $placements);

        $view_types = array(
            array('id' => 'serial', 'name' => 'Serial'),
            array('id' => 'carrousel', 'name' => 'Carroussel')
        );
        $this->putitem("view_types", $view_types);

        parent::addPage();
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:identifier
     */
    public function editPage($identifier)
    {
        $placements = array(
            array('id' => 'ads.leftbar.banner', 'name' => self::$t->translate('Left Side')),
            array('id' => 'ads.rightbar.banner', 'name' => self::$t->translate('Right Side'))
        );
        $this->putitem("placements", $placements);

        $view_types = array(
            array('id' => 'serial', 'name' => 'Serial'),
            array('id' => 'carrousel', 'name' => 'Carroussel')
        );
        $this->putitem("view_types", $view_types);

        parent::editPage($identifier);
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/:model/:identifier
     */
    public function getItemAction($model = "me", $identifier = null)
    {
        $editItem = $this->model($this->_modelRoute)->getItem($identifier);

        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/:model
     */
    public function addItemAction($model, $type)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($model == "me") {
                $itemModel = $this->model($this->_modelRoute);
                $messages = array(
                    'success' => "Lesson created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "content") {
                $itemModel = $this->model("advertising/content");
                $messages = array(
                    'success' => "Advertising content created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $data['language_code'] = self::$t->getUserLanguageCode();

                $_GET['redirect'] = "0";
            /*
            } elseif ($model == "question-content") {
                $itemModel = $this->model("lessons/content/question");
                $messages = array(
                    'success' => "Question included with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $_GET['redirect'] = "0";
            }
            */
            } else {
                return $this->invalidRequestError();
            }


            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->debug()->addItem($data)) !== false) {
                if ($_GET['redirect'] === "0") {
                    $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                    return array_merge($response, $data);
                } else {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        self::$t->translate($messages['success']),
                        "success"
                    );
                }
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($messages['error'], "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url PUT /item/:model/:id
     */
    public function setItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($model == "me") {
                $itemModel = $this->model($this->_modelRoute);
                $messages = array(
                    'success' => "Advertising updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "content") {
                $itemModel = $this->model("advertising/content");
                $messages = array(
                    'success' => "Advertising content updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            if ($itemModel->setItem($data, $id) !== false) {
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                $data = $itemModel->getItem($id);
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url DELETE /item/:model/:id
     */
    public function deleteItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $itemModel = $this->model("lessons");
                $messages = array(
                    'success' => "Lesson removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } elseif ($model == "content") {
                $itemModel = $this->model("advertising/content");
                $messages = array(
                    'success' => "Advertising content removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            }

            $data = $this->getHttpData(func_get_args());

            if ($itemModel->deleteItem($id) !== false) {
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * [ add a description ]
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
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
            $modelRoute = $this->_modelRoute;

            $itemsCollection = $this->model($modelRoute);
            if (!empty($filter)) {
                $filter = json_decode($filter, true);
                if (is_array($filter)) {
                    // SANITIZE ARRAY
                    $itemsCollection->addFilter($filter);
                }
            }
            //var_dump($filter);
            //exit;
            $itemsData = $itemsCollection->getItems();
        } elseif ($model == "content") {
            $modelRoute = "advertising/content";

            $itemsCollection = $this->model($modelRoute);
            // APPLY FILTER
            if (is_null($filter) || !is_numeric($filter)) {
                return $this->invalidRequestError();
            }
            $itemsData = $itemsCollection->addFilter(array(
                'active'    => 1,
                'advertising_id' => $filter/*,
                "parent_id" => null*/
            ))->getItems();
        }

        //$currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        if ($type === 'combo') {
            $q = $_GET['q'];
            $itemsData = $itemsCollection->filterCollection($itemsData, $q);

            $result = array();

            foreach ($itemsData as $item) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($item['id']),
                    'name'  => $item['name']
                );
            }
            return $result;
        } elseif ($type === 'datatable') {
            $stringsHelper = $this->helper("strings");
            $itemsData = array_values($itemsData);
            foreach ($itemsData as $key => $item) {
                $itemsData[$key]['options'] = array();
                foreach($optionsRoute as $index => $optItem) {
                    //var_dump($item);
                    $optItem['link'] = $this->getBasePath() . $stringsHelper->vksprintf($optItem['link'], $item);
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

    /**
     * [ add a description ]
     *
     * @url PUT /items/content/set-order/:advertising_id
     */
    public function setContentOrderAction($advertising_id)
    {
        $modelRoute = "advertising/content";

        $itemsCollection = $this->model($modelRoute);
        // APPLY FILTER
        if (is_null($advertising_id) || !is_numeric($advertising_id)) {
            return $this->invalidRequestError();
        }
        $itemsData = $itemsCollection->addFilter(array(
            'active'    => 1,
            'advertising_id' => $advertising_id
        ))->getItems();

        $messages = array(
            'success' => "Advertising content order updated with success",
            'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
        );

        $data = $this->getHttpData(func_get_args());

        if ($itemsCollection->setContentOrder($advertising_id, $data['position'])) {
            return $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
        } else {
            return $this->invalidRequestError(self::$t->translate($messages['success']), "success");
        }
    }
}

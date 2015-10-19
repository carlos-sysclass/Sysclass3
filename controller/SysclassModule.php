<?php
abstract class SysclassModule extends BaseSysclassModule
{
    protected $module_id = null;
    protected $module_folder = null;
    protected $module_request;

    protected $model_info;

    protected $context;

    public function init() {
        $this->beforeExecuteRoute();
    }

    public function beforeExecuteRoute() {
        parent::beforeExecuteRoute();

        $reflect = new ReflectionClass($this);
        $class_name = $reflect->getShortName();

        $this->module_id = strtolower(str_replace("Module", "", $class_name));

        $this->module_folder = $this->environment["path/modules"] . ucfirst($this->module_id);

        $baseUrl = $this->environment['module/base_path'] . "/" . $this->module_id;
        //$url = $baseUrl;
        $this->context['basePath'] = $baseUrl . "/";

        $route = $this->router->getMatchedRoute();

        $this->module_request = str_replace($this->context['basePath'], "", $route->getPattern());

        if (is_null($this->context)) {
            $this->context = array();
        }

        $this->module_folder = $this->environment["path/modules"] . ucfirst($this->module_id);

        //$this->module_request = str_replace($this->getBasePath(), "", $this->context['urlMatch']);

        $this->context['urlMatch'] = $this->module_request;

        $this->context['module_id']         = $this->module_id;
        $this->context['module_request']    = $this->module_request;
        $this->context['module_folder']     = $this->module_folder;
        
        //$this->context['basePath'] = sprintf("/module/%s/", strtolower($this->module_id));


        //$this->module_request = str_replace($this->getBasePath(), "", $this->context['module_request']);
        $this->context['module_request']    = $this->module_request;

        // LOAD MODEL INFO
        $this->model_info = $this->getModelInfo();

        $this->eventsManager->attach("module-{$this->module_id}", $this);

        $this->loadConfigFile();

    }


    protected $clientContext;


    /**
     * [ add a description ]
     * @Get("/view")
     */

    public function viewPage()
    {
        $depinject = Phalcon\DI::getDefault();
        if ($this->acl->isUserAllowed(null, $this->module_id, "View")) {
            $this->createClientContext("view");
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    public function addPage()
    {
        $depinject = Phalcon\DI::getDefault();
        if ($depinject->get("acl")->isUserAllowed(null, $this->module_id, "Create")) {
            if (!$this->createClientContext("add")) {
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
     * @Get("/edit/{id}")
     */
    public function editPage($id)
    {
        if ($this->acl->isUserAllowed(null, $this->module_id, "Edit")) {
            $this->createClientContext("edit", array('entity_id' => $id));
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    protected function getModelData() {
        $args = func_get_args();

        if (is_array($this->model_info)) {
            return call_user_func_array(
                array($this->model_info['class'], $this->model_info['findMethod']), $args
            );
        }
        return false;
    }

    protected function getModelInfo() {
        $default = array(
            'exportMethod'  => array(
                'toArray',
                array()
            ),
            'listMethod'  => 'find',
            'findMethod'  => 'findFirstById'
        );

        if (array_key_exists($this->module_id, $this->environment['models/map'])) {
            $class_info = $this->environment['models/map'][$this->module_id];

            if (is_string($class_info)) {
                $class_info = array(
                    'class' => $class_info
                );
                $class_info = array_merge($default, $class_info);
            } else {
                $class_info = array_merge($default, $class_info->toArray());
            }

            return $class_info;
        } else {
            return false;
        }
    }


    /**
     * [ add a description ]
     *
     * @Get("/item/me/{id}")
    */
    public function getItemRequest($id) {

        $editItem = $this->getModelData($id);

        $this->response->setContentType('application/json', 'UTF-8');

        if (is_object($editItem)) {
            $this->response->setJsonContent(call_user_func_array(
                array($editItem, $this->model_info['exportMethod'][0]),
                $this->model_info['exportMethod'][1]
            ));
            return true;   
        } else {
            
            $this->response->setJsonContent(
                $this->createAdviseResponse(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "warning")
            );
            return true;
        }
    }


    /**
     * [ add a description ]
     *
     * @Post("/item/me")
     */
    public function addItemRequest($id)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        if ($this->acl->isUserAllowed(null, $this->module_id, "create")) {
            // TODO CHECK IF CURRENT USER CAN DO THAT
            $data = $this->request->getJsonRawBody(true);
            
            $model_class = $this->model_info['class'];
            $itemModel = new $model_class();
            $itemModel->assign($data);

            //$this->beforeModelCreate($itemModel, $data);
            //
            //$this->eventsManager->collectResponses(true);
            $this->eventsManager->fire("module-{$this->module_id}:beforeModelCreate", $itemModel, $data);

            //$response = $this->eventsManager->getResponses();
            /*
            if (in_array(false, $response, true)) {
                // CANCEL SAVE
                $response = $this->createAdviseResponse(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                $this->response->setJsonContent(
                    array_merge($response, $itemModel->toFullArray())
                );
            }
            */
            if ($itemModel->save()) {
                $this->eventsManager->fire("module-{$this->module_id}:afterModelCreate", $itemModel, $data);
                
                $this->response->setJsonContent(
                    $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $itemModel->id,
                        self::$t->translate("User created with success"),
                        "success"
                    )
                );
                return true;
            } else {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelCreate", $itemModel, $data);

                $response = $this->invalidRequestError(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                $this->response->setJsonContent(
                    array_merge($response, $itemModel->toFullArray())
                );
                return true;
            }
        } else {
            $this->response->setJsonContent(
                $this->notAuthenticatedError()
            );
            return true;
        }
    }

    /**
     * [ add a description ]
     *
     * @Put("/item/me/{id}")
     */
    public function setItemRequest($id)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        if ($allowed = $this->acl->isUserAllowed(null, $this->module_id, "edit")) {
            $data = $this->request->getJsonRawBody(true);

            $model_class = $this->model_info['class'];
            $itemModel = new $model_class();
            $itemModel->assign($data);
            $itemModel->id = $id;

            $this->eventsManager->fire("module-{$this->module_id}:beforeModelUpdate", $itemModel, $data);

            if ($itemModel->save()) {
                $this->eventsManager->fire("module-{$this->module_id}:afterModelUpdate", $itemModel, $data);

                $response = $this->createAdviseResponse(self::$t->translate("Item updated with success"), "success");



                if ($_GET['redirect'] == "1") {
                    $response = $this->createRedirectResponse(
                        null,
                        self::$t->translate("Item updated with success"),
                        "success"
                    );
                } elseif ($_GET['object'] == "1") {
                    $itemData = call_user_func_array(
                        array($itemModel, $this->model_info['exportMethod'][0]),
                        $this->model_info['exportMethod'][1]
                    );
                    $response = array_merge($response, $itemData);
                }
            } else {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelUpdate", $itemModel, $data);

                $response = $this->createAdviseResponse(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
            }
        } else {
            $response = $this->notAuthenticatedError();
        }
        $this->response->setJsonContent(
            $response
        );
        return true;
    }

    /**
     * [ add a description ]
     *
     * @Delete("/item/me/{id}")
     */
    public function deleteItemRequest($id)
    {
        if ($this->acl->isUserAllowed(null, $this->module_id, "delete")) {

            $itemModel = $this->getModelData($id);

            if ($itemModel) {

                $this->eventsManager->fire("module-{$this->module_id}:beforeModelDelete", $itemModel);

                if ($itemModel->delete()) {
                    $this->eventsManager->fire("module-{$this->module_id}:afterModelDelete", $itemModel);
                    $response = $this->createAdviseResponse(self::$t->translate("User removed with success"), "success");
                } else {
                    $this->eventsManager->fire("module-{$this->module_id}:errorModelDelete", $itemModel);
                    $response = $this->invalidRequestError("", "warning");
                }
            } else {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelDelete", $itemModel);
                $response = $this->invalidRequestError("", "warning");
            }
        } else {
            $response = $this->notAuthenticatedError();
        }
        $this->response->setJsonContent($response);
        return true;
    }

    /**
     * [ add a description ]
     * 
     * @Get("/items/me")
     * @Get("/items/me/{type}")
     * @Get("/items/me/{type}/{filter}")
     */
    public function getItemsRequest($type, $filter)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        if ($this->acl->isUserAllowed(null, $this->module_id, "View")) {
            $currentUser    = $this->getCurrentUser(true);

            $model_class = $this->model_info['class'];
            $itemModel = new $model_class();
            
            //$args = $this->getparamentrs();
            $filter = filter_var($filter, FILTER_DEFAULT);

            if (!empty($filter)) {

                if (!is_array($filter)) {
                    $filter = json_decode($filter, true);
                }

                $index = 0;
                foreach($filter as $key => $item) {
                    $modelFilters[] = "{$key} = ?{$index}";
                    $filterData[$index] = $item;
                    $index++;
                }

                $args = array(
                    array(
                        'conditions'    => implode(" AND ", $modelFilters),
                        'bind' => $filterData
                    )
                );
            } else {
                $args = array();
            }
            /**
             * @todo Get parameters to filter, if possibile, the info
             */
            $resultRS = call_user_func_array(
                array($this->model_info['class'], $this->model_info['listMethod']), $args
            );

            $globalOptions = $this->getDatatableItemOptions($item);

            if ($type === 'datatable') {
                //$items = array_values($items);
                $baseLink = $this->getBasePath();

                /**
                 * @todo Call a controller / module method to get the options, or load from config
                 */
                $editAllowed = $this->acl->isUserAllowed(null, $this->module_id, "Edit");
                $deleteAllowed = $this->acl->isUserAllowed(null, $this->module_id, "Delete");

                $items = array();

                foreach($resultRS as $key => $item) {
                    // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                    $items[$key] = $item->toArray();
                    $items[$key]['options'] = array();
                    if ($editAllowed) {
                        $items[$key]['options']['edit']  = array(
                            'icon'  => 'icon-edit',
                            'link'  => $baseLink . "edit/" . $item->id,
                            'class' => 'btn-sm btn-primary'
                        );
                    }

                    if (is_array($globalOptions)) {
                        foreach($globalOptions as $index => $option) {
                            $option['link'] = Plico\Text::vksprintf($option['link'], $item->toArray());

                            $items[$key]['options'][$index] = $option;
                        }
                    }

                    $options = $this->getDatatableSingleItemOptions($item);
                    if (is_array($options)) {
                        $items[$key]['options'] = array_merge($items[$key]['options'], $options);
                    }
                    
                    if ($deleteAllowed) {
                        $items[$key]['options']['remove']  = array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        );
                    }
                }
                $this->response->setJsonContent(array(
                    'sEcho'                 => 1,
                    'iTotalRecords'         => count($items),
                    'iTotalDisplayRecords'  => count($items),
                    'aaData'                => array_values($items)
                ));
                return true;
            }
            $this->response->setJsonContent(array_values($result->toArray()));

        } else {
            $this->response->setJsonContent(
                $this->notAuthenticatedError()
            );
        }
    }


    /**
     * This function is called ONE-TIME inside getItemsRequest function. 
     * MUST return a options array, to bve applied to all finded records.
     * @return [array|null] [description]
     */
    protected function getDatatableItemOptions() {
        return null;
    }

    /**
     * This function is called MULTIPLE-TIMES, ONE PER RECORD, inside getItemsRequest function. 
     * MUST return a options array, to be applied to all the especific record.
     * 
     * @param  object $item The record data, as object
     * @return [array|null] [description]
     */
    protected function getDatatableSingleItemOptions($item) {
        return null;
    }


}

<?php
abstract class SysclassModule extends BaseSysclassModule
{
    protected $module_id = null;
    protected $module_folder = null;
    protected $module_request;

    protected $model_info;

    protected $context;

    protected $_args;

    public function init() {
        $this->beforeExecuteRoute();
    }

    protected function setArgs(array $args) {
        $this->_args = $args;
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

        $this->eventsManager->attach("module-{$this->module_id}", $this);

        $this->loadConfigFile();

        // LOAD MODEL INFO
        $this->model_info = $this->loadModelInfo();
    }


    protected $clientContext;

     // THIS ACTION RECEIVE ALL NOT MATCHED ROUTE FROM MODULE
    public function handleDefaultRequest() {
        // CHECK FOR MODEL TO LOAD
        
        if ($status = $this->createClientContext($this->module_request)) {
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }

    /**
     * Check if the request resource is avaliable to current user. The resource can overriden in config.yml
     * @param  [type]  $route   [description]
     * @param  [type]  $default [description]
     * @return boolean          [description]
     */
    protected function isResourceAllowed($default = null) {
        $resource = $this->getConfig("crud\\routes\\{$this->context['module_request']}\\acl\\resource");
        if (is_null($resource)) {
            if (is_null($default)) {
                return false;
            }
            $resource = $default;
        }
        return $this->acl->isUserAllowed(null, $this->module_id, $resource);
    }


    /**
     * [ add a description ]
     * @Get("/view")
     */

    public function viewPage()
    {
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

    protected function getModelData($model) {
        $args = func_get_args();

        array_shift($args);

        if (array_key_exists($model, $this->model_info)) {
            $model_info = $this->model_info[$model];
            return call_user_func_array(
                array($model_info['class'], $model_info['findMethod']), $args
            );
        }
        return false;
    }

    protected function loadModelInfo() {
        $default = array(
            'exportMethod'  => array(
                'toArray',
                array()
            ),
            'findMethod'  => 'findFirstById',
            'listMethod'  => 'find'
        );

        $models = $this->getConfig('models');

        if ($models) {
            foreach($models as $key => $class_info) {
                if (is_string($class_info)) {
                    $class_info = array(
                        'class' => $class_info
                    );
                    $class_info = array_merge($default, $class_info);
                } else {
                    $class_info = array_merge($default, $class_info);
                }
                $result[$key] = $class_info;
            }

            return $result;
        }
        /** 
         * REMOVE AFTER MIGRATION ALL DATA TO config.yml
         */
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
     * @Get("/item/{model}/{identifier}")
    */
    public function getItemRequest($model, $identifier) {

        $editItem = $this->getModelData($model, $identifier);

        $this->response->setContentType('application/json', 'UTF-8');

        if (is_object($editItem)) {
            $model_info = $this->model_info[$model];
            $this->response->setJsonContent(call_user_func_array(
                array($editItem, $model_info['exportMethod'][0]),
                $model_info['exportMethod'][1]
            ));
            return true;   
        } else {
            $this->response->setJsonContent(
                $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning")
            );
            return true;
        }
    }


    /**
     * [ add a description ]
     *
     * @Post("/item/{model}")
     */
    public function addItemRequest($model)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        if ($this->isResourceAllowed("create")) {
            // TODO CHECK IF CURRENT USER CAN DO THAT
            // 
            $data = $this->request->getJsonRawBody(true);

            if (!array_key_exists($model, $this->model_info)) {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelDoesNotExists", $model, $data);

                $response = $this->invalidRequestError($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                $this->response->setJsonContent(
                    array_merge($response, $data)
                );
                return true;
            }
            $model_info = $this->model_info[$model];
            
            $model_class = $model_info['class'];
            $itemModel = new $model_class();
            $itemModel->assign($data);

            $this->eventsManager->fire("module-{$this->module_id}:beforeModelCreate", $itemModel, $data);

            if ($itemModel->save()) {
                $this->eventsManager->fire("module-{$this->module_id}:afterModelCreate", $itemModel, $data);


                
                if ($this->request->hasQuery('object')) {
                    $this->response->setJsonContent(
                        $this->createAdviseResponse(
                            $this->translate->translate("Entity created with success"),
                            "success"
                        )
                    );
                } else {
                    $this->response->setJsonContent(
                        $this->createRedirectResponse(
                            $this->getBasePath() . "edit/" . $itemModel->id,
                            $this->translate->translate("User created with success"),
                            "success"
                        )
                    );
                }
                return true;
            } else {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelCreate", $itemModel, $data);


                // ABORT WITH PROVIDED MESSAGES
                $afterMessages = $itemModel->getMessages();
                if (count($afterMessages) > 0) {
                    foreach($afterMessages as $messageObject) {
                        $message = $this->translate->translate($messageObject->getMessage());
                        $type = $messageObject->getType();
                        break;
                    }
                } else {
                    $message = $this->translate->translate("A problem ocurred when tried to save you data. Please try again.");
                    $type = "warning";
                }

                $itemData = call_user_func_array(
                    array($itemModel, $model_info['exportMethod'][0]),
                    $model_info['exportMethod'][1]
                );


                $response = $this->invalidRequestError($message, $type);
                $this->response->setJsonContent(
                    array_merge($response, $data)
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
     * @Put("/item/{model}/{id}")
     */
    public function setItemRequest($model, $id)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        $itemModel = $this->getModelData($model, $id);

        $this->setArgs(array(
            'model' => $model,
            'id' => $id,
            'object' => $itemModel
        ));

        if ($allowed = $this->isUserAllowed("edit")) {
            if ($itemModel) {

                $data = $this->request->getJsonRawBody(true);

                if (!array_key_exists($model, $this->model_info)) {
                    $this->eventsManager->fire("module-{$this->module_id}:errorModelDoesNotExists", $model, $data);

                    $response = $this->invalidRequestError($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                    $this->response->setJsonContent(
                        array_merge($response, $data)
                    );
                    return true;
                }

                /*
                $model_info = $this->model_info[$model];

                $model_class = $model_info['class'];
                $itemModel = new $model_class();
                */

                $itemModel->assign($data);
                $itemModel->id = $id;

                $this->eventsManager->collectResponses(true);
                
                $status = $this->eventsManager->fire("module-{$this->module_id}:beforeModelUpdate", $itemModel, $data);

                $responses = $this->eventsManager->getResponses();

                if (in_array(false, $responses, true)) {
                    // ABORT WITH PROVIDED MESSAGES
                    $beforeMessages = $itemModel->getMessages();
                    foreach($beforeMessages as $messageObject) {
                        $message = $this->translate->translate($messageObject->getMessage());
                        $type = $messageObject->getType();
                        break;
                    }

                    $response = $this->createAdviseResponse($message, $type);
                    $this->response->setJsonContent(
                        $response
                    );
                    return true;
                }

                $beforeMessages = $itemModel->getMessages();

                if ($itemModel->save()) {
                    $this->eventsManager->fire("module-{$this->module_id}:afterModelUpdate", $itemModel, $data);

                    $afterMessages = $itemModel->getMessages();

                    $modelMessages = array_merge($beforeMessages, $afterMessages);

                    if (count($modelMessages) > 0) {
                        foreach($modelMessages as $messageObject) {
                            $message = $this->translate->translate($messageObject->getMessage());
                            $type = $messageObject->getType();
                            break;
                        }
                    } else {
                        $message = $this->translate->translate("Item updated with success");
                        $type = "success";
                    }

                    $response = $this->createAdviseResponse($message, $type);

                    if ($_GET['redirect'] == "1") {
                        $response = $this->createRedirectResponse(
                            null,
                            $message, 
                            $type
                        );
                    } elseif ($_GET['object'] == "1") {
                        $itemData = call_user_func_array(
                            array($itemModel, $model_info['exportMethod'][0]),
                            $model_info['exportMethod'][1]
                        );
                        $response = array_merge($response, $itemData);
                    }
                } else {
                    $this->eventsManager->fire("module-{$this->module_id}:errorModelUpdate", $itemModel, $data);

                    $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                }
            } else {
                $this->eventsManager->fire("module-{$this->module_id}:errorModelUpdate", $itemModel, $data);

                $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
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
     * @Delete("/item/{model}/{id}")
     */
    public function deleteItemRequest($model, $id)
    {
        $itemModel = $this->getModelData($model, $id);

        $this->setArgs(array(
            'model' => $model,
            'id' => $id,
            'object' => $itemModel
        ));

        if ($allowed = $this->isUserAllowed("delete")) {

            if ($itemModel) {

                $this->eventsManager->fire("module-{$this->module_id}:beforeModelDelete", $itemModel);

                if ($itemModel->delete()) {
                    $this->eventsManager->fire("module-{$this->module_id}:afterModelDelete", $itemModel);
                    $response = $this->createAdviseResponse($this->translate->translate("User removed with success"), "success");
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
     * @Get("/items/{model}")
     * @Get("/items/{model}/{type}")
     * @Get("/items/{model}/{type}/{filter}")
     */
    public function getItemsRequest($model, $type, $filter)
    {
        $this->response->setContentType('application/json', 'UTF-8');

        //if ($allowed = $this->isUserAllowed("delete")) {
        $this->setArgs(array(
            'model' => $model
        ));

        if ($allowed = $this->isUserAllowed("view")) {
            $currentUser    = $this->getCurrentUser(true);

            $model_info = $this->model_info[$model];

            $model_class = $model_info['class'];

            $sort = @$model_info['sort'];

            //$itemModel = new $model_class();
            
            //$args = $this->getparamentrs();
            $filter = filter_var($filter, FILTER_DEFAULT);

            if (!empty($filter)) {

                if (!is_array($filter)) {
                    $filter = json_decode($filter, true);
                }

                $options = array();

                foreach($filter as $key => $item) {
                    if (strpos($key, "_") === 0) {
                        $options[$key] = $item;
                        unset($filter[$key]);
                    }
                }

                $index = 0;
                foreach($filter as $key => $item) {
                    if (strpos($key, "_") === 0) {
                        $opt[$key] = $item;
                        unset($filter[$key]);
                    }
                    if (is_null($item)) {
                        if (@$options['_exclude'] === TRUE) {
                            $modelFilters[] = "{$key} IS NOT NULL";
                        } else {
                            $modelFilters[] = "{$key} IS NULL";
                        }
                    } else {
                        if (@$options['_exclude'] === TRUE) {
                            $modelFilters[] = "{$key} <> ?{$index}";
                        } else {
                            $modelFilters[] = "{$key} = ?{$index}";
                        }

                        $filterData[$index] = $item;
                        $index++;
                    }
                }

                $args = array(
                    'conditions'    => implode(" AND ", $modelFilters),
                    'bind' => $filterData,
                    'args'  => $filter,
                    'order'  => $sort
                );
            } else {
                $args = array(
                    'order'  => $sort
                );
            }
            /**
             * @todo Get parameters to filter, if possibile, the info
             */
            //var_dump(array($model_info['class'], $model_info['listMethod']), $args);
            $resultRS = call_user_func(
                array($model_info['class'], $model_info['listMethod']), $args
            );

            

            if ($type === 'datatable') {
                //$items = array_values($items);
                $baseLink = $this->getBasePath();

                $globalOptions = $this->getDatatableItemOptions($item);

                //$editAllowed = $this->acl->isUserAllowed(null, $this->module_id, "Edit");
                //$deleteAllowed = $this->acl->isUserAllowed(null, $this->module_id, "Delete");

                $items = array();

                foreach($resultRS as $key => $item) {
                    // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                    $items[$key] = call_user_func_array(
                        array($item, $model_info['exportMethod'][0]),
                        $model_info['exportMethod'][1]
                    );
                    $items[$key]['options'] = array();

                    $options = $this->getDatatableSingleItemOptions($item);
                    if (is_array($options)) {
                        $items[$key]['options'] = array_merge($items[$key]['options'], $options);
                    }

                    if (is_array($globalOptions)) {
                        foreach($globalOptions as $index => $option) {
                            $option['link'] = Plico\Text::vksprintf($option['link'], $item->toArray());

                            $items[$key]['options'][$index] = $option;
                        }
                    }
                }
                $this->response->setJsonContent(array(
                    'sEcho'                 => 1,
                    'iTotalRecords'         => count($items),
                    'iTotalDisplayRecords'  => count($items),
                    'aaData'                => array_values($items)
                ));
                return true;
            } else {
                $items = array();
                //var_dump($resultRS->toArray());
                //var_dump($model_info['exportMethod']);

                foreach($resultRS as $key => $item) {
                    // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                    //var_dump($item, $model_info['exportMethod'][0], $model_info['exportMethod'][1]);
                    $items[$key] = call_user_func(
                        array($item, $model_info['exportMethod'][0]),
                        count($model_info['exportMethod'][1]) > 0 ? $model_info['exportMethod'][1] : null
                    );
                }
                
                $this->response->setJsonContent($items);
            }
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
        $editAllowed = $this->acl->isUserAllowed(null, $this->module_id, "Edit");
        $deleteAllowed = $this->acl->isUserAllowed(null, $this->module_id, "Delete");

        $options = array();

        if ($editAllowed) {
            $options['edit']  = array(
                'icon'  => 'icon-edit',
                'link'  => $baseLink . 'edit/%id$s',
                'class' => 'btn-sm btn-primary'
            );
        }
        if ($deleteAllowed) {
            $options['remove']  = array(
                'icon'  => 'icon-remove',
                'class' => 'btn-sm btn-danger'
            );
        }
        return $options;
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


    protected function isUserAllowed($action, $args) {
        return $this->acl->isUserAllowed(null, $this->module_id, $action);
    }

    /* 
        TODO: THINK ABOUT MOVE ALL OPERATIONS TO THE MODELS ITSELF!!!

        LIST OF MODULE EVENTS 
        beforeModelCreate
        afterModelCreate
        errorModelCreate
        errorModelDoesNotExists
        beforeModelUpdate
        afterModelUpdate
        errorModelUpdate
        beforeModelDelete
        afterModelDelete
        errorModelDelete
        
    */
}

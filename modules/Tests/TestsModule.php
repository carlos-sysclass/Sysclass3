<?php
namespace Sysclass\Modules\Tests;
/**
 * Module Class File
 * @filesource
 */
use Phalcon\Acl\Adapter\Memory as AclList,
    Phalcon\Acl\Resource;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */


/**
 * @RoutePrefix("/module/tests")
 */
class TestsModule extends \SysclassModule implements \ISummarizable, \ILinkable, \IBreadcrumbable, \IActionable, \IBlockProvider
{
    private static $suitable_translate_contents = array("subtitle");

    /* ISummarizable */
    public function getSummary() {
        $data = array(1);

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => $this->translate->translate('New Tests'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => "javascript:App.scrollTo($('#calendar-widget'))"
            )
        );
    }

    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, "Tests", "View")) {
            $itemsData = $this->model("tests")->addFilter(array(
                'active'    => true
            ))->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "test", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => count($items),
                        'text'  => $this->translate->translate('Tests'),
                        'icon'  => 'fa fa-list-ol ',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbableViews = array("view", "add", "edit/:identifier", "execute/:identifier/:execution_id");

        $request = $this->getMatchedUrl();

        if (in_array($request, $breadcrumbableViews)) {

            $breadcrumbs = array(
                array(
                    'icon'  => 'fa fa-home',
                    'link'  => $this->getSystemUrl('home'),
                    'text'  => $this->translate->translate("Home")
                ),
                array(
                    'icon'  => 'icon-bookmark',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Tests")
                )
            );

            switch($request) {
                case "view" : {
                    $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                    break;
                }
                case "add" : {
                    $breadcrumbs[] = array('text'   => $this->translate->translate("New Test"));
                    break;
                }
                case "edit/:identifier" : {
                    $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Test"));
                    break;
                }
                case "execute/:identifier/:execution_id" : {
                    // TODO A WAY TO INJECT DATA INTO BREADCRUMB FROM HERE (string substitution FROM variables in the route)
                    $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Test"));
                    $breadcrumbs[] = array('text'   => $this->translate->translate("View Execution"));
                    break;
                }
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
                    'text'      => $this->translate->translate('New Test'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus'
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

        return array_key_exists($request, $actions) ? $actions[$request] : false;
    }

    /* IBlockProvider */
    public function registerBlocks()
    {
        return array(
            'tests.create.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");
                //$self->putComponent("select2");
                $self->putComponent("bootstrap-switch");

                //$block_context = $self->getConfig("blocks\\questions.select.dialog\\context");
                //$self->putItem("questions_select_block_context", $block_context);

                $self->putModuleScript("dialogs.tests.create");
                //$self->setCache("dialogs.questions.select", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/create");

                return true;
            },
            'tests.info.dialog' => function ($data, $self) {
                // CREATE BLOCK CONTEXT
                //$self->putComponent("jquery-file-upload-image");
                //$self->putComponent("jquery-file-upload-video");
                //$self->putComponent("jquery-file-upload-audio");
                //$self->putComponent("bootstrap-confirmation");

                //$self->putModuleScript("translate", "models.translate");

                $self->putModuleScript("dialogs.tests.info");

                $self->putSectionTemplate("dialogs", "dialogs/tests.info");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            },
            'tests.execution.list.table' => function ($data, $self) {
                // APLLY FILTER BASED ON $data['filter'] AND $data['context']
                //
                $stringsHelper = $self->helper("strings");

                // TODO: TEST FOR EMPTY KEYS IN $data
                $filter = array();
                foreach($data['filter'] as $key => $value) {
                    $filter[$key] = $stringsHelper->vksprintf($value, $data['context']);
                }

                $block_context = $self->getConfig("blocks\\tests.execution.list.table\context");

                $block_context['ajax_source'] = $stringsHelper->vksprintf(
                    $block_context['ajax_source'],
                    array('filter' => $filter)
                );

                $self->putComponent("data-tables");
                $self->putScript("scripts/utils.datatables");

                $self->putItem("tests_execution_context", $block_context);

                $self->putSectionTemplate("tests_execution", "blocks/table");

                return true;
            }
        );
    }

    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    public function addPage()
    {
        $items = $this->model("classes")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);

        $items = $this->model("grades")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("grades", $items);



        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{identifier}")
     */
    public function editPage($identifier)
    {
        $items = $this->model("classes")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);


        $items = $this->model("grades")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("grades", $items);



        parent::editPage($identifier);
    }

    /**
     * Test landing page, before execution
     *
     * @Get("/open/{identifier}")
     */
    public function openPage($identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            $testData = $this->model("roadmap/tests")->getItem($identifier);
            /*
            echo "<pre>";
            var_dump($testData);
            echo "</pre>";
            */

            $testData = $this->model("roadmap/tests")->calculateTestScore($testData);
            // LOAD USER PROGRESS ON THIS TEST
            //
            $testData['executions'] = $this->model("tests/execution")->addFilter(array(
                'test_id' => $identifier,
                'pending' => 0,
                'user_id' => $userData['id']
            ))->getItems();

            // IF THE USER CANT'T TRY ANOTHER TIME, REDIRECT OR RENDER TEST STATUS VIEW

            //var_dump($testData['executions']);

            //
            //$testData['info'] = "Test execution Info";
            /*
            $testData['user_tries'] = array(
                array(
                    'user_score' => '150',
                    'total_questions_completed' => 3,
                    'time_spent' => 40,
                    'pass' => true
                )
            );
            */

            //exit;
            //
            $this->putItem("test", $testData);
            $this->createClientContext("execute", null, "open");

            // CHECK IF THE USER IS ENROLLED IN THIS CLASS, AND IF HE CAN EXECUTE THE TEST NOW
            if (array_key_exists('dialog', $_GET)) {
                $this->putItem("extends_file", "layout/dialog.tpl");
            } else {
                $this->putItem("extends_file", "layout/default.tpl");
            }

            $this->display($this->template);
        }
    }



    /**
     * The test execution itself
     *
     * @Post("/execute/{identifier}")
     */
    public function executePage($identifier)
    {
        // CHECK IF THE USER IS ENROLLED IN THIS CLASS, AND IF HE CAN EXECUTE THE TEST NOW
        //
        if ($userData = $this->getCurrentUser()) {
            // START PROGRESS
            $testData = $this->model("roadmap/tests")->getItem($identifier);

            $executionModel = $this->model("tests/execution");

            $testData['executions'] = $executionModel->addFilter(array(
                'test_id' => $identifier,
                'user_id' => $userData['id']
            ))->getItems();

            // PREPARE TEST TO EXECUTE
            $executionId = $executionModel->addItem(array(
                'test_id' => $identifier,
                'user_id' => $userData['id']
            ));


            if ($executionId) {
                $executionData = $executionModel->getItem($executionId);

                $this->module("settings")->put("test_execution_id", $executionId);

                //$this->putModuleScript();

                $testData = $this->model("roadmap/tests")->calculateTestScore($testData);

                $this->putItem("test", $testData);
                $this->putItem("execution", $executionData);

                $this->createClientContext("execute", null, "execute");

                $this->display($this->template);
            } else {
                $this->redirect(
                    '/module/tests/open/' . $identifier,
                    $this->translate->translate("You can not run this test more often"),
                    "warning"
                );
                //$this->openPage();
            }

        }
    }

    /**
     * The test execution itself
     *
     * @Get("/execute/{identifier}")
     * @Get("/execute/{identifier}/{execution_id}")
     */
    public function executeViewPage($identifier, $execution_id)
    {
        // CHECK IF THE USER IS ENROLLED IN THIS CLASS, AND IF HE CAN EXECUTE THE TEST NOW
        //
        if ($userData = $this->getCurrentUser()) {
            // WHO CAN VIEW THE TEST????
            // 1 - THE USER ITSELF
            // 2 - THE TEST INSTRUCTOR
            // 3 - THE CLASS INSTRUCTOR
            // 4 - THE COURSE COORDINATOR
            /*
            $acl = new AclList();
            $acl->setDefaultAction(Phalcon\Acl::DENY);

            $acl->addRole("Owner");
            $acl->addRole("Instructor");
            $acl->addRole("Coordinator");
            $acl->addRole("Administrator");

            // Define the "Customers" resource
            // Add "customers" resource with a couple of operations
            $acl->addResource("TestsExecution", array("view", "execute"));

            $acl->allow("Owner", "TestsExecution", "view");
            $acl->allow("Owner", "TestsExecution", "execute");
            $acl->allow("Instructor", "TestsExecution", "view");
            $acl->allow("Coordinator", "TestsExecution", "view");
            $acl->allow("Administrator", "TestsExecution", "view");

            var_dump($acl->isAllowed("", "TestsExecution", "execute"));
            exit;

            var_dump($userData);
            */

            // START PROGRESS
            $testModel = $this->model("roadmap/tests");
            $testData = $this->model("roadmap/tests")->getItem($identifier);

            $executionModel = $this->model("tests/execution");

            $executions = array();

            if (!is_null($execution_id)) {
                $executions = $executionModel->addFilter(array(
                    'test_id' => $identifier,
                    //'user_id' => $userData['id'],
                    'id' => $execution_id
                ))->getItems();

            }
            if (count($executions) == 0) {
                $executions = $executionModel->clear()->addFilter(array(
                    'test_id' => $identifier,
                    'user_id' => $userData['id']
                ))->getItems();
            }
            $execution = end($executions);





            if (count($executions) > 0) {
                $execution_id = $execution['id'];
                $executionData = $executionModel->getItem($execution['id']);

                $this->module("settings")->put("test_execution_id", $execution_id);

                $testData = $this->model("roadmap/tests")->calculateTestScore($testData);

                $this->putItem("test", $testData);
                $this->putItem("execution", $executionData);

                $this->createClientContext("execute", null, "execute");

                $this->display($this->template);
            } else {
                $this->redirect('/module/tests/open/' . $identifier);
                //$this->openPage();
            }

        }
    }


    /**
     * [ add a description ]
     *
     * @Get("/item/{model}/{identifier}")
     */
    public function getItemRequest($model = "me", $identifier = null)
    {
        if ($model == "me") {
            $itemModel = $this->model("tests");
        } elseif ($model == "question") {
            $itemModel = $this->model("tests/question");
        } elseif ($model == "execution") {
            $itemModel = $this->model("tests/execution");
        }

        $editItem = $itemModel->getItem($identifier);

        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @Post("/item/{model}")
     */
    public function addItemRequest($model, $type)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $advise = true;

            if ($model == "me") {
                $itemModel = $this->model("tests");
                $messages = array(
                    'success' => "Lesson created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $data['language_code'] = $this->translate->getSource();

                $_GET['redirect'] = "0";
            } elseif ($model == "execution") {
                $itemModel = $this->model("tests/execution");

                $advise = false;
                $_GET['redirect'] = "0";

                $messages = array(
                    'success' => "Test created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again",
                    'try_limit' => "You can not run this test more often"
                );
            } else {
                return $this->invalidRequestError();
            }



            $data['login'] = $userData['login'];
            $data['user_id'] = $userData['id'];
            if (($data['id'] = $itemModel->addItem($data)) !== false) {
                if ($_GET['redirect'] === "0") {
                    if ($advise) {
                        $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                    } else {
                        $response = $this->createNonAdviseResponse($this->translate->translate($messages['success']), "success");
                    }

                    $data = $itemModel->getItem($data['id']);

                    return array_merge($response, $data);
                } else {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        $this->translate->translate($messages['success']),
                        "success"
                    );
                }
            } else {
                if ($model == "execution") {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "open/" . $data['test_id'],
                        $this->translate->translate($messages['try_limit']),
                        "warning"
                    );
                }
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
     * @Put("/item/{model}/{identifier}")
     */
    public function setItemRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            //var_dump($data);
            //question_points
            //question_weights
            if ($model == "me") {
                $itemModel = $this->model("tests");
                $messages = array(
                    'success' => "Lesson updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "execution") {
                $itemModel = $this->model("tests/execution");

                $advise = false;

                $messages = array(
                    'success' => "Test updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

            } else {
                return $this->invalidRequestError();
            }

            $data['login'] = $userData['login'];
            $data['user_id'] = $userData['id'];

            if ($itemModel->setItem($data, $identifier) !== false) {
                if ($model == "execution" && $data['complete'] == 1) {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "execute/" . $data['test_id'] . "/" . $identifier,
                        $this->translate->translate("Test completed with success"),
                        "success"
                    );
                }
               if ($advise) {
                    $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                } else {
                    $response = $this->createNonAdviseResponse($this->translate->translate($messages['success']), "success");
                }

                $data = $itemModel->getItem($identifier);
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @Delete("/item/{model}/{identifier}")
     */
    public function deleteItemRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $itemModel = $this->model("tests");
                $messages = array(
                    'success' => "Lesson removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            if ($itemModel->deleteItem($identifier) !== false) {
                $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/items/{model}")
     * @Get("/items/{model}/{type}")
     * @Get("/items/{model}/{type}/{filter}")
     */
    public function getItemsRequest($model = "me", $type = "default", $filter = null)
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
            $modelRoute = "tests";

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
            //$itemsData = $this->module("permission")->checkRules($itemsData, "lesson", 'permission_access_mode');
        } elseif ($model == "question") {
            $modelRoute = "tests/question";

            $itemsCollection = $this->model($modelRoute);

            if (!empty($filter)) {
                $filter = json_decode($filter, true);
                if (is_array($filter)) {
                    // SANITIZE ARRAY
                    $itemsCollection->addFilter($filter);
                }
            }

            $itemsData = $itemsCollection->getItems();
        } elseif ($model ==  "execution") {
            $modelRoute = "tests/execution";

            $optionsRoute = array(
                'view'  => array(
                    'icon'  => 'icon-search',
                    'link'  => 'execute/%test_id$s/%try_index$s',
                    'class' => 'btn-sm btn-primary'
                ),
                'recalculate'  => array(
                    'icon'  => 'fa fa-refresh',
                    'class' => 'btn-sm btn-warning datatable-actionable',
                    'action' => 'recalculate/%test_id$s/%try_index$s',
                    'method' => 'POST'
                ),

                /*,
                'remove'    => array(
                    'icon'  => 'icon-remove',
                    'class' => 'btn-sm btn-danger'
                )*/
            );

            $filter = filter_var($filter, FILTER_DEFAULT);

            if (!is_array($filter)) {
                $filter = json_decode($filter, true);
            }

            $itemsCollection = $this->model($modelRoute);
            $itemsData = $itemsCollection->addFilter($filter)->getItems();

        } else {
            return $this->invalidRequestError();
        }

        if ($type === 'combo') {
            $query = $_GET['q'];
            $itemsData = $itemsCollection->filterCollection($itemsData, $query);

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
                    if (array_key_exists('link', $optItem)) {
                        $optItem['link'] = $this->getBasePath() . $stringsHelper->vksprintf($optItem['link'], $item);
                    } elseif (array_key_exists('action', $optItem)) {
                        $optItem['action'] = $this->getBasePath() . $stringsHelper->vksprintf($optItem['action'], $item);
                    }

                    $itemsData[$key]['options'][$index] = $optItem;
                }
                /*

                $itemsData[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . $stringsHelper->vksprintf($optionsRoute, $item['id']),
                        'class' => 'btn-sm btn-primary'
                    ),
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    )
                );
                */
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
     * The test execution itself
     *
     * @Get("/recalculate/{identifier}/{execution_id}")
     * @Post("/recalculate/{identifier}/{execution_id}")
     */
    public function recalculateViewPage($identifier, $execution_id)
    {
        // CHECK IF THE USER IS ENROLLED IN THIS CLASS, AND IF HE CAN EXECUTE THE TEST NOW
        //
        if ($userData = $this->getCurrentUser()) {
            // WHO CAN RECALCULATE THE TEST????
            // 1 - THE USER ITSELF
            // 2 - THE TEST INSTRUCTOR
            // 3 - THE CLASS INSTRUCTOR
            // 4 - THE COURSE COORDINATOR
            /*
            $acl = new AclList();
            $acl->setDefaultAction(Phalcon\Acl::DENY);

            $acl->addRole("Owner");
            $acl->addRole("Instructor");
            $acl->addRole("Coordinator");
            $acl->addRole("Administrator");

            // Define the "Customers" resource
            // Add "customers" resource with a couple of operations
            $acl->addResource("TestsExecution", array("view", "execute"));

            $acl->allow("Owner", "TestsExecution", "view");
            $acl->allow("Owner", "TestsExecution", "execute");
            $acl->allow("Instructor", "TestsExecution", "view");
            $acl->allow("Coordinator", "TestsExecution", "view");
            $acl->allow("Administrator", "TestsExecution", "view");

            var_dump($acl->isAllowed("", "TestsExecution", "execute"));
            exit;

            var_dump($userData);
            */

            // START PROGRESS
            $testModel = $this->model("roadmap/tests");
            $testData = $this->model("roadmap/tests")->getItem($identifier);

            $executionModel = $this->model("tests/execution");

            $executions = array();

            if (!is_null($execution_id)) {
                $executions = $executionModel->addFilter(array(
                    'test_id' => $identifier,
                    //'user_id' => $userData['id'],
                    'id' => $execution_id
                ))->getItems();
            } else {
                return $this->invalidRequestError();
            }
            $execution = end($executions);

            if (count($executions) > 0) {
                $execution_id = $execution['id'];

                $executionModel->calculateUserScore($execution_id);

                $response = $this->createAdviseResponse(
                    $this->translate->translate("Score recalculated with sucess"),
                    "success"
                );

                $data = $executionModel->getItem($execution_id);

                return array_merge($response, $data);
            } else {
                return $this->invalidRequestError();
            }
        }
    }


    /**
     * [ add a description ]
     *
     * @Put("/items/{model}/set-order/{lesson_id}")
     */
    public function setOrderRequest($model, $lesson_id)
    {
        if ($model == "me") {
            return $this->invalidRequestError();
        } elseif ($model == "question") {
            $modelRoute = "tests/question";
            $optionsRoute = "edit";
        } else {
            return $this->invalidRequestError();
        }


        $itemsCollection = $this->model($modelRoute);
        // APPLY FILTER
        if (is_null($lesson_id) || !is_numeric($lesson_id)) {
            return $this->invalidRequestError();
        }

        $messages = array(
            'success' => "Question order updated with success",
            'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
        );

        $data = $this->getHttpData(func_get_args());

        if ($itemsCollection->setOrder($lesson_id, $data['position'])) {
            return $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
        } else {
            return $this->invalidRequestError($this->translate->translate($messages['success']), "success");
        }
    }


}
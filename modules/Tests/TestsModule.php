<?php
namespace Sysclass\Modules\Tests;
/**
 * Module Class File
 * @filesource
 */
use Phalcon\Acl\Adapter\Memory as AclList,
    Phalcon\Acl\Resource,
    Sysclass\Models\Content\Course as Classe,
    Sysclass\Models\Acl\Role,
    Sysclass\Models\Courses\Grades\Grade,
    Sysclass\Models\Courses\Tests\Lesson as TestUnit,
    Sysclass\Models\Content\Tests\Execution as TestExecution;
    
/**
 * @RoutePrefix("/module/tests")
 */
class TestsModule extends \SysclassModule implements \ISummarizable, \ILinkable, \IBreadcrumbable, \IActionable, \IBlockProvider
{
    private static $suitable_translate_contents = array("subtitle");

    /* ISummarizable */
    public function getSummary() {
        // GET THE USER NOT DONE YET TESTS
        $pendingTests = TestUnit::getUserPendingTests($this->user->id);

        $summary = array(
            'type'  => 'danger',
            'count' => $pendingTests->count(),
            'text'  => $this->translate->translate('New tests')
        );

        if ($pendingTests->count() > 0) {
            $test_id = $pendingTests[0]->id;

            $summary['link'] = array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath() . "open/" . $test_id,
                'link'  => 'javascript: void(0)'
            );
        }

        return $summary;
    }

    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, "Tests", "View")) {

            $total = TestUnit::count("type='test' AND active = 1");

            return array(
                'content' => array(
                    array(
                        'count' => $total,
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
        $breadcrumbableViews = array("view", "add", "edit/{identifier}", "execute/:identifier/:execution_id");

        $request = $this->getMatchedUrl();

        if (in_array($request, $breadcrumbableViews)) {

            $breadcrumbs = array(
                array(
                    'icon'  => 'fa fa-home',
                    'link'  => $this->getSystemUrl('home'),
                    'text'  => $this->translate->translate("Home")
                ),
                array(
                    'icon'  => 'fa fa-list-ol',
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
                    $breadcrumbs[] = array('text'   => $this->translate->translate("New test"));
                    break;
                }
                case "edit/{identifier}" : {
                    $breadcrumbs[] = array('text'   => $this->translate->translate("Edit test"));
                    break;
                }
                case "execute/{identifier}/{execution_id}" : {
                    // TODO A WAY TO INJECT DATA INTO BREADCRUMB FROM HERE (string substitution FROM variables in the route)
                    $breadcrumbs[] = array('text'   => $this->translate->translate("Edit test"));
                    $breadcrumbs[] = array('text'   => $this->translate->translate("View execution"));
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
                    'text'      => $this->translate->translate('New test'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus-circle'
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
                //$self->putScript("scripts/utils.datatables");

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
        $classes = Classe::find(array(
            'conditions' => 'active = 1'
        ));
        $this->putItem("classes", $classes->toArray());

        $teacherRole = Role::findFirstByName('Teacher');
        $users = $teacherRole->getAllUsers();

        $this->putItem("instructors", $users);

        $items = Grade::find("active =1");

        $this->putItem("grades", $items->toArray());

        parent::addPage();
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{identifier}")
     */
    public function editPage($identifier)
    {
        $classes = Classe::find(array(
            'conditions' => 'active = 1'
        ));
        $this->putItem("classes", $classes->toArray());

        $teacherRole = Role::findFirstByName('Teacher');
        $users = $teacherRole->getAllUsers();

        $this->putItem("instructors", $users);

        $items = Grade::find("active =1");

        $this->putItem("grades", $items->toArray());

        parent::editPage($identifier);
    }

    /**
     * Test landing page, before execution
     *
     * @Get("/open/{identifier}")
     */
    public function openPage($identifier)
    {
        //if ($userData = $this->getCurrentUser()) {
            $testModel = TestUnit::findFirstById($identifier);

            $testData = $testModel->toArray();

            $testData['test'] = $testModel->getTest()->toArray();
            $testData['questions'] = $testModel->getQuestions()->toArray();
            //$testData = $this->model("roadmap/tests")->getItem($identifier);
            
            //echo "<pre>";
            //var_dump($testData);
            //echo "</pre>";
            //exit;
           
            $testData['score'] = $testData['test']['score'] = $testModel->calculateTestScore($testData);

            // LOAD USER PROGRESS ON THIS TEST
            
            $executions = TestExecution::find(array(
                'conditions' => 'test_id = ?0 AND pending = 0 AND user_id = ?1',
                'bind' => array($identifier, $this->user->id)
            ));


            $testData['executions'] = $executions->toArray();
            /*
            $testData['executions'] = $this->model("tests/execution")->addFilter(array(
                'test_id' => $identifier,
                'pending' => 0,
                'user_id' => $userData['id']
            ))->getItems();*/

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
        //}
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
            $this->disableSection('title');

            $executionModel = $this->model("tests/execution");

            // PREPARE TEST TO EXECUTE
            $executionId = $executionModel->addItem(array(
                'test_id' => $identifier,
                'user_id' => $this->user->id
            ));

            $testModel = TestUnit::findFirstById($identifier);

            $testData = $testModel->toArray();


            $testData['course'] = $testModel->getCourse()->toArray();

            $testData['test'] = $testModel->getTest()->toArray();
            //$testData['questions'] = $testModel->getQuestions()->toArray();
            $testQuestions = $testModel->shuffleTestQuestions($executionId);

            $testData['questions'] = array();

            foreach($testQuestions as $i => $question) {
                $testData['questions'][$i] = $question->toArray();
                $testData['questions'][$i]['question'] = $question->getQuestion()->toArray();
            }

            //$testData = $this->model("roadmap/tests")->getItem($identifier);
            
            // echo "<pre>";
            // print_r($testData);
            // echo "</pre>";
            
           //exit;
            $testData['score'] = $testData['test']['score'] = $testModel->calculateTestScore($testData);

            // LOAD USER PROGRESS ON THIS TEST
            
            $executions = TestExecution::find(array(
                'conditions' => 'test_id = ?0 AND pending = 0 AND user_id = ?1',
                'bind' => array($identifier, $this->user->id)
            ));


            $testData['executions'] = $executions->toArray();

            /*            
            $testData = $this->model("roadmap/tests")->getItem($identifier);

            var_dump($testData);
            exit;

            

            $testData['executions'] = $executionModel->addFilter(array(
                'test_id' => $identifier,
                'user_id' => $userData['id']
            ))->getItems();
            */



            /// SAVE THE TEST QUESTIONS USED



            if ($executionId) {
                $executionData = $executionModel->getItem($executionId);
                /*
                var_dump($testData);
                var_dump($executionData);
                exit;
                */

                $this->module("settings")->put("test_execution_id", $executionId);

                //$this->putModuleScript();
                //
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
            $this->disableSection('title');

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

            if (!is_null($execution_id)) {
                $executions = TestExecution::find(array(
                    'conditions' => 'test_id = ?0 AND pending = 0 AND user_id = ?1 AND id = ?2',
                    'bind' => array($identifier, $this->user->id, $execution_id)
                ));
                /*
                $executions = $executionModel->addFilter(array(
                    'test_id' => $identifier,
                    //'user_id' => $userData['id'],
                    'id' => $execution_id
                ))->getItems();
                */
            }

            if (!$executions) {
                $executions = TestExecution::find(array(
                    'conditions' => 'test_id = ?0 AND pending = 0 AND user_id = ?1',
                    'bind' => array($identifier, $this->user->id)
                ));
            }
            $execution = $executions->getLast();

            if ($executions->count() > 0) {
                $executionId = $execution->id;
                $executionData = $execution->toArray();

                $testObject = TestUnit::findFirstById($identifier);

                $testData = $testObject->toArray();

                $testData['test'] = $testObject->getTest()->toArray();

                $testQuestions = $testObject->shuffleTestQuestions($executionId);

                $testData['questions'] = array();

                foreach($testQuestions as $i => $question) {
                    $testData['questions'][$i] = $question->toArray();
                    $testData['questions'][$i]['question'] = $question->getQuestion()->toArray();
                }

                $testData['score'] = $testData['test']['score'] = $testModel->calculateTestScore($testData);

                // LOAD USER PROGRESS ON THIS TEST
                // CHECK IF THE USER CAN EXECUTE AGAIN

                $this->putBlock("tests.info.dialog");

                $this->module("settings")->put("test_execution_id", $execution_id);

                $testData = $this->model("roadmap/tests")->calculateTestScore($testData);
                

                $this->putItem("test", $testData);
                $this->putItem("execution", $executionData);
                $this->putItem("can_execute_again", $execution->canExecuteAgain($this->user));
                

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
     * @Get("/datasource/{model}/{identifier}")
     */
    
    public function getDatasourceRequest($model = "me", $identifier = null)
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
    
    /*
    public function addDatasourceRequest($model, $type)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $advise = true;

            if ($model == "me") {
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question created.",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $data['language_code'] = $this->translate->getSource();

                $_GET['redirect'] = "0";
            } elseif ($model == "execution") {
                $itemModel = $this->model("tests/execution");

                $advise = false;
                $_GET['redirect'] = "0";

                $messages = array(
                    'success' => "Test created.",
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
    */
    /**
     * [ add a description ]
     *
     * @Put("/datasource/{model}/{identifier}")
     */
    
    public function setDatasourceRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($model == "me") {
            } elseif ($model == "question") {
            } elseif ($model == "execution") {
                $messages = array(
                    'success' => "Test updated.",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $execution = TestExecution::findFirstById($identifier);

                $data['login'] = $this->user->login;
                $data['user_id'] = $this->user->id;

                if (!$execution) {
                    $response = $this->invalidRequestError($this->translate->translate($messages['error']), "error");         
                } else {
                    $status = $execution->updateProgress($data);

                    if ($status) {
                        $advise = !$execution->pending;


                       if ($advise) {
                            return $this->createRedirectResponse(
                                $this->getBasePath() . "execute/" . $execution->test_id . "/" . $identifier,
                                $this->translate->translate("Test completed."),
                                "success"
                            );
                        } else {
                            $response = $this->createNonAdviseResponse($this->translate->translate($messages['success']), "success");
                        }
                    } else {
                        $response = $this->invalidRequestError($this->translate->translate($messages['error']), "error");             
                    }

                    return array_merge($response, $execution->toArray());
                }
            } else {
                return $this->invalidRequestError();
            }

            //$data['login'] = $userData['login'];
            //$data['user_id'] = $userData['id'];

            //if ($itemModel->setItem($data, $identifier) !== false) {
                if ($model == "execution" && $data['complete'] == 1) {

                    return $this->createRedirectResponse(
                        $this->getBasePath() . "execute/" . $data['test_id'] . "/" . $identifier,
                        $this->translate->translate("Test completed."),
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
            //} else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                //return $this->invalidRequestError($this->translate->translate($messages['error']), "error");
            //}

        } else {
            return $this->notAuthenticatedError();
        }
    }
    
    /*
    public function deleteDatasourceRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question removed.",
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
    */
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
        if ($this->isUserAllowed("edit")) {
            $data = $this->request->getPut();
            
            if ($model == "me") {
                return $this->invalidRequestError();
            } elseif ($model == "question") {
                $messages = array(
                    'success' => "Questions order updated.",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $itemModel = $this->getModelData("me", $lesson_id);

                if ($itemModel->setQuestionOrder($data['position'])) {
                    $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                } else {
                    $response = $this->invalidRequestError($this->translate->translate($messages['success']), "success");
                }

                
                return $response;

            } else {
                return $this->invalidRequestError();
            }

        } else {
            return $this->notAuthenticatedError();
        }
    }


    /**
     * [ add a description ]
     *
     * @Put("/items/lessons/set-order/{class_id}")
     */
    public function setUnitOrderRequest($class_id)
    {
        if ($this->isUserAllowed("edit")) {
            

            $itemModel = $this->getModelData("me", $class_id);

            $messages = array(
                'success' => "Lesson order updated.",
                'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
            );

            if ($itemModel->setQuestionOrder($data['position'])) {
                $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
            } else {
                $response = $this->invalidRequestError($this->translate->translate($messages['success']), "success");
            }
        } else {
            $response = $this->notAuthenticatedError();
        
        }
        $this->response->setJsonContent(
            $response
        );
    }

        /**
     * This function is called ONE-TIME inside getItemsRequest function. 
     * MUST return a options array, to bve applied to all finded records.
     * @return [array|null] [description]
     */
    protected function getDatatableItemOptions($model = 'me') {

        if ($model == "execution") {
            $options = array();
            /*
            $model_info = $this->model_info[$model];

            $deleteAllowed = $this->isResourceAllowed("delete", $model_info);

            if ($deleteAllowed) {
                $options['remove']  = array(
                    'icon'  => 'fa fa-remove',
                    'class' => 'btn-sm btn-danger tooltips',
                    'attrs' => array(
                        'data-original-title' => 'Remove'
                    )
                );
            }
            */
            return $options;
        } else {
            return parent::getDatatableItemOptions($model);
        }
    }

}

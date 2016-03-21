<?php
namespace Sysclass\Modules\Roadmap;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Enrollments\CourseUsers as EnrolledCourse,
    Sysclass\Services\MessageBus\INotifyable,
    Sysclass\Collections\MessageBus\Event;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/roadmap")
 */
class RoadmapModule extends \SysclassModule implements \IBlockProvider, INotifyable
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'roadmap.classes' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                $self->putModuleScript("blocks.roadmap.classes");

                $block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                $self->putItem("roadmap_block_context", $block_context);

                $self->putSectionTemplate("roadmap-classes", "blocks/roadmap.classes");

                $self->putSectionTemplate("foot", "dialogs/period.add");

                return true;
            },
            'roadmap.grouping' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                $self->putModuleScript("blocks.roadmap.grouping");

                $block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                $self->putItem("roadmap_block_context", $block_context);

                $self->putSectionTemplate("roadmap-grouping", "blocks/roadmap.grouping");

                $self->putSectionTemplate("foot", "dialogs/grouping.add");

                return true;
            },
        );
    }

    // INotifyable
    public function getAllActions() {

    }

    public function processNotification($action, Event $event) {
        switch($action) {
            case "calculate-progress" : {
                $data = $event->data;
                /**
                 * @todo  Recalculate all progress based on following variables
                 * user_id
                 * content_id OR lesson_id OR class_id OR course_id
                 */
                var_dump($data);
                return true;
                $course = Course::findFirstById($data['course_id']);
                $user = User::findFirstById($data['user_id']);

                if ($course && $user) {
                    $this->notification->createForUser(
                        $user,
                        sprintf(
                            'You have a certificate avaliable for course %s', 
                            $course->name
                        ),
                        'info',
                        array(
                            'text' => "View",
                            'link' => $this->getBasePath() . "view/" . $course->id
                        )
                    );
                } else {
                    echo 'error found';
                }
                //var_dump($action, $event->toArray());

                // CREATE A SYSTEM NOTIFICATION TO USER
                break;
            }
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/datasources/{model}")
     * @Get("/datasources/{model}/{type}")
     * @Get("/datasources/{model}/{type}/{filter}")
     */
    public function getItemsRequest($model = "me", $type = "default", $filter = null)
    {
        if ($currentUser = $this->getCurrentUser(true)) {
            if ($model ==  "periods") {
                $modelRoute = "roadmap/periods";
                $itemsCollection = $this->model($modelRoute);

                $courses = filter_var($filter, FILTER_DEFAULT);

                if (!is_array($courses)) {
                    $courses = json_decode($courses, true);
                }
                //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

                $itemsData = $itemsCollection->addFilter(array(
                    'c.active'     => 1,
                    'cp.course_id'  => $courses
                ), array("operator" => "="))->getItems();
                //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
            } elseif ($model ==  "classes") {
                $modelRoute = "roadmap/classes";
                $itemsCollection = $this->model($modelRoute);

                $filter = filter_var($filter, FILTER_DEFAULT);

                if (!is_array($filter)) {
                    $filter = json_decode($filter, true);
                }
                //var_dump($filter);
                //exit;
                //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

                $itemsData = $itemsCollection->addFilter(array(
                    'c.active'      => 1,
                    'cl.active'     => 1,
                    'c2c.course_id' => $filter['course_id'],
                    'clp.period_id' => $filter['period_id']
                )/*, array("operator" => "=")*/)->getItems();

                //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
            } elseif ($model ==  "grouping") {
                $modelRoute = "roadmap/grouping";
                $itemsCollection = $this->model($modelRoute);

                $courses = filter_var($filter, FILTER_DEFAULT);

                if (!is_array($courses)) {
                    $courses = json_decode($courses, true);
                }
                //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

                $itemsData = $itemsCollection->addFilter(array(
                    'c.active'      => true,
                    'cg.course_id' => $courses
                ), array("operator" => "="))->getItems();

                //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
            } elseif ($model ==  "courses") {
                // GET USERS COURSES
                $userCourses = EnrolledCourse::find(
                    "user_id = {$currentUser->id}"
                );
                $itemsData = array();
                foreach($userCourses as $usercourse) {
                    $itemsData[] = $usercourse->getCourse()->toArray();
                }
            } else {
                $modelRoute = "courses/collection";
                $optionsRoute = "edit";

                $itemsCollection = $this->model($modelRoute);
                $itemsData = $itemsCollection->getItems();
                $itemsData = $this->module("permission")->checkRules($itemsData, "course", 'permission_access_mode');
            }

            //$currentUser    = $this->getCurrentUser(true);
            //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);


            if ($type === 'combo') {
                $query = $_GET['q'];
                $itemsData = $itemsCollection->filterCollection($itemsData, $query);

                $result = array();

                foreach($itemsData as $item) {
                    // @todo Group by course
                    $result[] = array(
                        'id'    => intval($item['id']),
                        'name'  => ($model ==  "instructor") ? $item['name'] . ' ' . $item['surname'] : $item['name']
                    );
                }
                return $result;
            } elseif ($type === 'datatable') {

                $itemsData = array_values($itemsData);
                foreach($itemsData as $key => $item) {
                    $itemsData[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'icon-edit',
                            'link'  => $this->getBasePath() . $optionsRoute . "/" . $item['id'],
                            'class' => 'btn-sm btn-primary'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                }
                $this->response->setJsonContent(array(
                    'sEcho'                 => 1,
                    'iTotalRecords'         => count($itemsData),
                    'iTotalDisplayRecords'  => count($itemsData),
                    'aaData'                => array_values($itemsData)
                ));
                return true;
            }

            $this->response->setJsonContent(array_values($itemsData));
            return true;
        } else {

        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/item/{model}/{identifier}")
    */
    public function getItemRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser(true)) {
            if ($model ==  "courses") {
                $modelRoute = "roadmap/courses";
            } elseif ($model ==  "classes") {
                $modelRoute = "roadmap/classes";
            } elseif ($model ==  "lessons") {
                $modelRoute = "roadmap/lessons";
            } elseif ($model ==  "grouping") {
                $modelRoute = "roadmap/grouping";
            } elseif ($model ==  "periods") {
                $modelRoute = "roadmap/periods";

            //} elseif ($model ==  "periods") {
                // GET USER CURRENT SETTINGS
                // (CURRENT COURSE, CLASS, LESSON, CONTENT)
            } else {
                return $this->invalidRequestError();
            }

            $itemModel = $this->model($modelRoute);
            $editItem = $itemModel->setUserFilter($this->user->id)->getItem($identifier);

            $this->response->setJsonContent($editItem);
        }
    }
    /**
     * [ add a description ]
     *
     * @Post("/item/{model}")
     */
    /*
    public function addItemRequest($model)
    {

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());
            if ($model ==  "classes") {
                $modelRoute = "roadmap/classes";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Class created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "grouping") {
                $modelRoute = "roadmap/grouping";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Grouping created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "periods") {
                $modelRoute = "roadmap/periods";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Period created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "periods") {
                // GET USER CURRENT SETTINGS
                // (CURRENT COURSE, CLASS, LESSON, CONTENT)
            } elseif ($model ==  "content-progress") {
                $modelRoute = "lessons/content/progress";

                $itemModel = $this->model($modelRoute);

                $itemModel->setUserFilter($userData['id']);

                $messages = array(
                    'success' => false,
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                if ($_GET['redirect'] == 0) {
                    if ($messages['success']) {
                        $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                    } else {
                        $response = array();
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
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @Put("/item/{model}/{id}")
     */    
    public function setItemRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            //$data = $this->request->getPut();
            $data = $this->getHttpData(func_get_args());

            if ($model ==  "classes") {
                $modelRoute = "roadmap/classes";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Class updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "grouping") {
                $modelRoute = "roadmap/grouping";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Grouping updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "periods") {
                $modelRoute = "roadmap/periods";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Period created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "content-progress") {
                $modelRoute = "lessons/content/progress";

                $itemModel = $this->model($modelRoute);

                $itemModel->setUserFilter($userData['id']);

                $messages = array(
                    'success' => false,
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            if ($itemModel->setItem($data, $identifier) !== FALSE) {
                if ($messages['success']) {
                    $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                } else {
                    $response = array();
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
     * @Delete("/item/{model}/{id}")
     */
    public function deleteItemRequest($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());
            if ($model ==  "classes") {
                $modelRoute = "roadmap/classes";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Class removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } elseif ($model ==  "grouping") {
                $modelRoute = "roadmap/grouping";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Grouping removed with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "periods") {
                $modelRoute = "roadmap/periods";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Periods created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            if ($itemModel->deleteItem($identifier) !== FALSE) {
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
     * @url POST /item/season
     */
    /*
    public function addSeasonItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("roadmap/courses/seasons/collection");
            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {

                $data = $itemModel->getItem($data['id']);

                return array_merge(
                    $data,
                    $this->createAdviseResponse(
                        $this->translate->translate("Season created with success"),
                        "success"
                    )
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
     * @url POST /item/class/:id
     */
    /*
    public function switchClassInCourse() {
        $data = $this->getHttpData(func_get_args());

        $userCourseModel = $this->model("roadmap/courses/classes/collection");

        $status = $userCourseModel->switchClassInCourse(
            $data['course_id'],
            $data['lesson_id']
        );

        if ($status == 1) {
            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse($this->translate->translate("Class added to course with success"), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse($this->translate->translate("Class removed from course with success"), "error");
        }
        return array_merge($response, $info);
    }
    */
    /**
     * [ add a description ]
     *
     * @Put("/datasources/{model}/set-order/{course_id}")
     */
    public function setOrderRequest($model, $course_id)
    {
        if ($userData = $this->getCurrentUser()) {

            if ($model ==  "classes") {
                $modelRoute = "roadmap/classes";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Classes order updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "grouping") {
                $modelRoute = "roadmap/grouping";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Grouping order updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model ==  "periods") {
                $modelRoute = "roadmap/periods";
                $itemModel = $this->model($modelRoute);
                $messages = array(
                    'success' => "Course Periods updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            //$modelRoute = "classes/lessons/collection";

            // $itemsCollection = $this->model($modelRoute);
            // APPLY FILTER
            if (is_null($course_id) || !is_numeric($course_id)) {
                return $this->invalidRequestError();
            }

            $data = $this->request->getPut();

            if ($itemModel->setOrder($course_id, $data['position'], $data['period_id'])) {
                return $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
            } else {
                return $this->invalidRequestError($this->translate->translate($messages['success']), "success");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
}

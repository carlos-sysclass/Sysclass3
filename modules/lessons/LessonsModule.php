<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class LessonsModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable, IBlockProvider
{

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model("classes/lessons/collection")->addFilter(array(
                'active'    => true
            ))->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "lesson", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => count($items),
                        'text'  => self::$t->translate('Lessons'),
                        'icon'  => 'fa fa-file',
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
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-file',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Lesson")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New Lesson"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Lesson"));
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
                    'text'      => self::$t->translate('New Lesson'),
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

        return $actions[$request];
    }
    public function registerBlocks() {
        return array(
            'lessons.content' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload-image");
                $self->putComponent("jquery-file-upload-video");
                $self->putComponent("jquery-file-upload-audio");
                $self->putComponent("bootstrap-confirmation");
                $self->putModuleScript("blocks.lessons.content");

                //$block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                //$self->putItem("classes_lessons_block_context", $block_context);

                $self->putSectionTemplate("lessons_content", "blocks/lessons.content");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            }/*,
            'lessons.content.text' => function($data, $self) {
                $items = $self::$t->getItems();

                $userLanguageCode =  $self::$t->getUserLanguageCode();

                $self->putItem("lessons_content_text_user_language", $userLanguageCode);
                $self->putItem("lessons_content_text_languages", $items);
                // CREATE BLOCK CONTEXT
                //$self->putComponent("jquery-file-upload");
                //$self->putModuleScript("blocks.roadmap");

                //$block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                //$self->putItem("classes_lessons_block_context", $block_context);

                $self->putSectionTemplate("lessons_content_text", "blocks/lessons.content.text");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            },
            'lessons.content.video' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload");
                //$self->putModuleScript("blocks.roadmap");

                //$block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                //$self->putItem("classes_lessons_block_context", $block_context);

                $self->putSectionTemplate("lessons_content_video", "blocks/lessons.content.video");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            }
            */
        );
    }


    /**
     * New model entry point
     *
     * @url GET /add
     */
    public function addPage()
    {
        $items = $this->model("courses/classes/collection")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        parent::addPage($id);
    }

    /**
     * Module Entry Point
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        $items = $this->model("courses/classes/collection")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        parent::editPage($id);
    }

    /**
     * Get the institution visible to the current user
     *
     * @url GET /item/users/:course_id
    */
    /*
    public function getUsersInCourse($course_id) {
        $data = $this->getHttpData(func_get_args());

        $userCourseModel = $this->model("user/courses/item");

        $users = $userCourseModel->getUsersInCourse($course_id);

        return $users;
    }
    */
    /**
     * Get the institution visible to the current user
     *
     * @url POST /item/users/switch
    */
    /*
    public function switchUserInGroup() {
        $data = $this->getHttpData(func_get_args());

        $userCourseModel = $this->model("user/courses/item");

        $status = $userCourseModel->switchUserInCourse(
            $data['course_id'],
            $data['user_login']
        );

        if ($status == 1) {
            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse(self::$t->translate("User added to group with success"), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse(self::$t->translate("User removed from group with success"), "error");
        }
        return array_merge($response, $info);
    }
    */
    /**
     * Get all users visible to the current user
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        if ($model == "me") {
            $modelRoute = "classes/lessons/collection";
            $optionsRoute = "edit";

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
            $itemsData = $this->module("permission")->checkRules($itemsData, "lesson", 'permission_access_mode');

        } elseif ($model == "lesson-content") {
            $modelRoute = "lessons/content";
            $optionsRoute = "edit";

            $itemsCollection = $this->model($modelRoute);
            // APPLY FILTER
            if (is_null($filter) || !is_numeric($filter)) {
                return $this->invalidRequestError();
            }
            $itemsData = $itemsCollection->addFilter(array(
                'active'    => 1,
                'lesson_id' => $filter
            ))->getItems();

            //$itemsData = $this->module("permission")->checkRules($itemsData, "lesson", 'permission_access_mode');
        }

        //$currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);


        if ($type === 'combo') {
            $q = $_GET['q'];
            $itemsData = $itemsCollection->filterCollection($itemsData, $q);

            $result = array();

            foreach($itemsData as $item) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($item['id']),
                    'name'  => $item['name']
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
     * Get all users visible to the current user
     *
     * @url PUT /items/lesson-content/set-order/:lesson_id
     */
    public function setContentOrderAction($lesson_id)
    {
        $modelRoute = "lessons/content";
        $optionsRoute = "edit";

        $itemsCollection = $this->model($modelRoute);
        // APPLY FILTER
        if (is_null($lesson_id) || !is_numeric($lesson_id)) {
            return $this->invalidRequestError();
        }
        $itemsData = $itemsCollection->addFilter(array(
            'active'    => 1,
            'lesson_id' => $lesson_id
        ))->getItems();

        $messages = array(
            'success' => "Lesson content order updated with success",
            'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
        );

        $data = $this->getHttpData(func_get_args());

        if ($itemsCollection->setContentOrder($lesson_id, $data['position'])) {
            return $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
        } else {
            return $this->invalidRequestError(self::$t->translate($messages['success']), "success");
        }
    }

    /**
     * Get all users visible to the current user
     *
     * @url POST /upload/:id
     * @url POST /upload/:id/:type
     * @deprecated
     */
    public function receiveFilesAction($id, $type = "default")
    {
        $param_name = $_GET['name'];

        if (!in_array($type, array("video", "material", "default"))) {
            $type = "default";
        }

        $helper = $this->helper("file/upload");
        $filewrapper = $this->helper("file/wrapper");
        $upload_dir = $filewrapper->getLessonPath($id, $type);
        $upload_url = $filewrapper->getLessonUrl($id, $type);

        $helper->setOption('upload_dir', $upload_dir . "/");
        $helper->setOption('upload_url', $upload_url . "/");

        $helper->setOption('param_name', $param_name);
        $helper->setOption('print_response', false);

        switch($type) {
            case 'video' :{
                $helper->setOption('accept_file_types', '/(\.|\/)(mp4|webm)$/i');
                break;
            }
        }


        $result = $helper->execute();

        //if ($type == "video") {
            /*
            $filedata = (array) reset($result[$param_name]);

            $filedata['lesson_id'] = $id;
            $filedata['upload_type'] = $type;
            $this->model("lessons/files")->setVideo($filedata);
            */

        //} elseif ($type == "material") {
            $file_result = array(
                $param_name => array()
            );
            foreach($result[$param_name] as $fileObject) {
                $filedata = (array) $fileObject;
                $filedata['lesson_id'] = $id;
                $filedata['upload_type'] = $type;
                $filedata['id'] = $this->model("lessons/files")->addItem($filedata);

                $file_result[$param_name][] = $filedata;
            }
        //}
        return $file_result;
    }

    /**
     * Get all users visible to the current user
     *
     * @url DELETE /upload/:lesson_id/:file_id
     * @deprecated
     */
    public function removeFilesAction($lesson_id, $file_id)
    {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = $this->model("lessons/files");

            $files = $itemModel->clear()->addFilter(array(
                'lesson_id' => $lesson_id,
                'id'        => $file_id
            ))->getItems();

            if (count($files) > 0 && $itemModel->deleteItem($file_id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("File removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/:model/:id
     */
    public function getItemAction($model = "me", $id) {

        $editItem = $this->model("classes/lessons/collection")->getItem($id);
        //if ($model == "content") {
            //$editItem['files'] = $this->model("classes/lessons/collection")->loadContentFiles($id);
            $lessonFiles = $this->model("lessons/files");
            $videos = $lessonFiles->clear()->addFilter(array(
                'lesson_id'     => $id,
                'upload_type'   => 'video',
                'active'        => 1
            ))->getItems();

            $materials = $lessonFiles->clear()->addFilter(array(
                'lesson_id'     => $id,
                'upload_type'   => 'material',
                'active'        => 1
            ))->getItems();

            $editItem['files'] = array(
                'video' => $videos,
                'material'  => $materials
            );

        //}
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
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
                $itemModel = $this->model("classes/lessons/collection");
                $messages = array(
                    'success' => "Lesson created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "lesson-content") {
                $itemModel = $this->model("lessons/content");
                $messages = array(
                    'success' => "Lesson content created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $_GET['redirect'] = 0;
            }


            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                if ($_GET['redirect'] == 0) {
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
                $itemModel = $this->model("classes/lessons/collection");
                $messages = array(
                    'success' => "Lesson updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "lesson-content") {
                $itemModel = $this->model("lessons/content");
                $messages = array(
                    'success' => "Lesson content updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

            }

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
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
     * DELETE a news model
     *
     * @url DELETE /item/:model/:id
     */
    public function deleteItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
           if ($model == "me") {
                $itemModel = $this->model("classes/lessons/collection");
                $messages = array(
                    'success' => "Lesson removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } elseif ($model == "lesson-content") {
                $itemModel = $this->model("lessons/content");
                $messages = array(
                    'success' => "Lesson content removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );

            }

            $data = $this->getHttpData(func_get_args());

            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Lesson removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

}

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
    private static $suitable_translate_contents = array("subtitle");

    /* ILinkable */
    public function getLinks()
    {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model("lessons")->addFilter(array(
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
    public function getBreadcrumb()
    {
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
        switch ($request) {
            case "view":
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            case "add":
                $breadcrumbs[] = array('text'   => self::$t->translate("New Lesson"));
                break;
            case "edit/:id":
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Lesson"));
                break;
        }
        return $breadcrumbs;
    }

    /* IActionable */
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
    public function registerBlocks()
    {
        return array(
            'lessons.content' => function ($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload-image");
                $self->putComponent("jquery-file-upload-video");
                $self->putComponent("jquery-file-upload-audio");
                $self->putComponent("bootstrap-confirmation");

                $self->putModuleScript("translate", "models.translate");

                $self->putModuleScript("blocks.lessons.content");



                $languages = $self::$t->getItems();

                $userLanguageCode = $self::$t->getUserLanguageCode();



                foreach ($languages as &$value) {
                    if ($value['code'] == $userLanguageCode) {
                        $value['selected'] = true;
                        break;
                    }
                }

                //$block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                $self->putItem("languages", $languages);

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
     * [ add a description ]
     *
     * @url GET /add
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


        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        $items = $this->model("classes")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);


        parent::editPage($id);
    }

    /**
     * [ add a description ]
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
     * [ add a description ]
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
     * [ add a description ]
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        if ($model == "me") {
            $modelRoute = "lessons";
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
        } elseif ($model == "lesson-and-test") {
            $modelRoute = "base/lessons";
            $optionsRoute = "edit";

            $itemsCollection = $this->model($modelRoute);
            if (!empty($filter)) {
                $filter = json_decode($filter, true);
                if (is_array($filter)) {
                    // SANITIZE ARRAY
                    $itemsCollection->addFilter($filter);
                }
            }
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
                'lesson_id' => $filter/*,
                "parent_id" => null*/
            ))->getItems();

            //$itemsData = $this->module("permission")->checkRules($itemsData, "lesson", 'permission_access_mode');
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
            $itemsData = array_values($itemsData);
            foreach ($itemsData as $key => $item) {
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
     * [ add a description ]
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
     * [ add a description ]
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

        switch ($type) {
            case 'video':
                $helper->setOption('accept_file_types', '/(\.|\/)(mp4|webm)$/i');
                break;
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
        foreach ($result[$param_name] as $fileObject) {
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
     * [ add a description ]
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

            if (count($files) > 0 && $itemModel->deleteItem($file_id) !== false) {
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
     * @url GET /item/:model/:identifier
     */
    public function getItemAction($model = "me", $identifier = null)
    {
        $editItem = $this->model("lessons")->getItem($identifier);
        /*
        //if ($model == "content") {
            //$editItem['files'] = $this->model("classes/lessons/collection")->loadContentFiles($id);
            $lessonFiles = $this->model("lessons/files");
        $videos = $lessonFiles->clear()->addFilter(array(
                'lesson_id'     => $identifier,
                'upload_type'   => 'video',
                'active'        => 1
            ))->getItems();

        $materials = $lessonFiles->clear()->addFilter(array(
                'lesson_id'     => $identifier,
                'upload_type'   => 'material',
                'active'        => 1
            ))->getItems();

        $editItem['files'] = array(
                'video' => $videos,
                'material'  => $materials
            );

        //}
        */
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
                $itemModel = $this->model("lessons");
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

                $data['language_code'] = self::$t->getUserLanguageCode();

                $_GET['redirect'] = "0";
            } elseif ($model == "question-content") {
                $itemModel = $this->model("lessons/content/question");
                $messages = array(
                    'success' => "Question included with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $_GET['redirect'] = "0";
            }


            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== false) {
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
                $itemModel = $this->model("lessons");
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
            } elseif ($model == "question-content") {

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
            } elseif ($model == "lesson-content") {
                $itemModel = $this->model("lessons/content");
                $messages = array(
                    'success' => "Lesson content removed with success",
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
     * @url PUT /item/lesson-content/:id/translate
     */
    public function translateContent($model, $id)
    {
        $modelRoute = "lessons/content";

        $itemModel = $this->model($modelRoute);

        $http_data = $this->getHttpData(func_get_args());

        $translateModel = $this->model("translate");
        $lang_codes = $translateModel->getDisponibleLanguagesCodes();

        if (!is_array($http_data) && !in_array($http_data['to'], $lang_codes)) {
            return $this->invalidRequestError(self::$t->translate(""));
        }
        if (!in_array($http_data['from'], $lang_codes)) {
            $http_data['from'] = $translateModel->getUserLanguageCode();
        }
        // 1. GET FILE DATA
        $contentData = $itemModel->getItem($id);
        if (in_array($contentData['content_type'], self::$suitable_translate_contents)) {
            //var_dump($contentData);
            if (array_key_exists("file", $contentData) && is_array($contentData['file']) && is_numeric($contentData['file']['id'])) {
                $fileInfo = $this->model("dropbox")->getItem($contentData['file']['id']);
                //$fileInfo = $this->model("dropbox")->getItem(1453485);
                if (count($fileInfo) > 0) {
                    $filestream = $this->model("dropbox")->getFileContents($fileInfo);
                    $parsed = $this->parseWebVTTFile($filestream);

                    //$tokens = array_column($parsed, "text");
                    // TRANSLATE TOKENS

                    $translated = $this->model("translate")->translateTokens($http_data['from'], $http_data['to'], $parsed, "text");


                    $translatedFilestream = $this->makeWebVTTFile($translated, array("index", "from", "to", "translated"));

                    // CREATE FILE
                    $fileinfo = $this->model("dropbox")->createFile($translatedFilestream, $fileInfo);

                    unset($contentData['id']);
                    $contentData['info'] = json_encode($fileinfo);
                    $contentData['file'] = $fileinfo;
                    $contentData['language_code'] = $http_data['to'];
                    $contentData['content_type'] = "subtitle-translation";
                    $contentData['parent_id'] = $id;

                    $contentData['id'] = $itemModel->addItem($contentData);

                    $response = $this->createAdviseResponse(self::$t->translate("File translated with success"), "success");
                    return array_merge($response, $contentData);

                }
                exit;
            }
            return $this->invalidRequestError(self::$t->translate("The system can't translate this content. Please try again"), "info");
        } else {
            return $this->invalidRequestError(self::$t->translate("This content isn't suitable to translation. Please try again with another file"), "warning");
        }




        //
        // 2. PARSE INTO A ARRAY
        // 3. SEND TO TRANSLATE SERVICE, PASS A CALLBACK TO RECEIVE THE RESULTS (THE CALLBACK WILL CREATE A NEW FILE IN THE SAME FORMAT)
        // 4. RETURN THE SERVICE INFO TO UI PROCESSING
        exit;
        // APPLY FILTER
        if (is_null($filter) || !is_numeric($filter)) {
            return $this->invalidRequestError();
        }
        $itemsData = $itemsCollection->addFilter(array(
            'active'    => 1,
            'lesson_id' => $filter/*,
            "parent_id" => null*/
        ))->getItems();
    }
    /**
     * This function parse a WEBVTT File into a array
     * @todo  Must be moved to a proper helper
     * @return array [description]
     */
    protected function parseWebVTTFile($filestream)
    {
        if (is_string($filestream) && !empty($filestream)) {
            $lines = explode("\n", $filestream);
            //echo $filestream;

            $lines = preg_split("/\r?\n^$\r?\n/m", $filestream);
            $lines = preg_split("/\r?\n\r?\n/m", $filestream);

            $filestruct = array();

            foreach ($lines as $line) {
                if (preg_match('/(\d*)\r?\n*^(\d{2}:\d{2}[:.]\d{2,3}[.]?\d{0,3}) --> (\d{2}:\d{2}[:.]\d{2,3}[.]?\d{0,3})\r?\n(.*)/ms', $line, $match)) {
                    $filestruct[] = array(
                        "index" => $match[1],
                        "from"  => $match[2],
                        "to"    => $match[3],
                        "text"  => $match[4]
                    );
                }
            }
            return $filestruct;
        }
        return false;
    }
    /**
     * Create a string containing a webvtt structure
     * @param  array $filestruct The same structure returned by the function parseWebVTTFile
     * @return string
     */
    protected function makeWebVTTFile($filestruct, $columns = null)
    {
        if (is_null($columns)) {
            $columns = array("index", "from", "to", "text");
        }
        $lines = array("WEBVTT");
        foreach ($filestruct as $fileitem) {
            $item = sprintf("%s --> %s\n%s", $fileitem[$columns[1]], $fileitem[$columns[2]], $fileitem[$columns[3]]);
            if (is_numeric($fileitem[$columns[0]])) {
                $item = $fileitem[$columns[0]] . "\n" . $item;
            }
            $lines[] = $item;
        }
        return implode("\n\n", $lines);
    }
}

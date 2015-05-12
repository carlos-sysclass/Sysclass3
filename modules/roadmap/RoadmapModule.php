<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class RoadmapModule extends SysclassModule implements IBlockProvider
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'roadmap.courses.edit' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");


                $grouping = $this->model("roadmap/courses/grouping")->addFilter(array(
                    'active'    => true
                ))->getItems();

                $self->putItem("roadmap_courses_grouping", $grouping);

                $self->putModuleScript("blocks.roadmap");

                $block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                $self->putItem("roadmap_block_context", $block_context);

                $self->putSectionTemplate("roadmap", "blocks/roadmap.edit");
                $self->putSectionTemplate("foot", "dialogs/season.add");
                $self->putSectionTemplate("foot", "dialogs/class.add");
                $self->putSectionTemplate("foot", "dialogs/grouping.add");

                return true;
            }
        );
    }

    /**
     * Get all users visible to the current user
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        if ($model ==  "seasons") {
            $modelRoute = "roadmap/courses/seasons/collection";
            $itemsCollection = $this->model($modelRoute);

            $courses = filter_var($filter, FILTER_DEFAULT);

            if (!is_array($courses)) {
                $courses = json_decode($courses, true);
            }
            //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

            $itemsData = $itemsCollection->addFilter(array(
                'cr.active'     => 1,
                'cr.course_id'  => $courses
            ), array("operator" => "="))->getItems();
            //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
        } elseif ($model ==  "classes") {
            $modelRoute = "roadmap/courses/classes/collection";
            $itemsCollection = $this->model($modelRoute);

            $courses = filter_var($filter, FILTER_DEFAULT);

            if (!is_array($courses)) {
                $courses = json_decode($courses, true);
            }
            //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

            $itemsData = $itemsCollection->addFilter(array(
                'c.active'      => 1,
                'cl.active'     => 1,
                'c2c.course_id' => $courses
            ), array("operator" => "="))->getItems();

            //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');

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
            $q = $_GET['q'];
            $itemsData = $itemsCollection->filterCollection($itemsData, $q);

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
     * Insert a news model
     *
     * @url POST /item/season
     */
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
                        self::$t->translate("Season created with success"),
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

    /**
     * Insert a news model
     *
     * @url POST /item/class/:id
     */
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
            $response = $this->createAdviseResponse(self::$t->translate("Class added to course with success"), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse(self::$t->translate("Class removed from course with success"), "error");
        }
        return array_merge($response, $info);
    }

}

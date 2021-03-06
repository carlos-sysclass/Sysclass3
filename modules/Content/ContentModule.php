<?php
namespace Sysclass\Modules\Content;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Content\Program,
    Sysclass\Models\Content\Unit,
    Sysclass\Models\Enrollments\CourseUsers,
    Sysclass\Models\Users\Settings as UserSettings,
    Sysclass\Models\Acl\Role;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/content")
 */
class ContentModule extends \SysclassModule implements \IWidgetContainer, \IBlockProvider, \ISectionMenu
{
    /* IWidgetContainer */
	public function getWidgets($widgetsIndexes = array(), $caller = null) {
        
		if (in_array('content.overview', $widgetsIndexes) && $currentUser = $this->getCurrentUser(true)) {

            $this->putScript("plugins/screenfull/screenfull");

            // LOAD THE CURRENT USER UNIT, OR COURSE, OR PROGRAM, AND LOAD ALL ON WIDGET
            $settings = $this->module("settings")->getSettings(true);

            $fields = array(
                'content',
                'unit',
                'course',
                'program'
            );

            $checkScope = null;
            foreach($fields as $field) {
                if (is_numeric($settings[$field . "_id"])) {
                    $checkScope = $field;
                    $checkValue = $settings[$field . "_id"];
                    break;
                }
            }

            
            // LOAD THE CURRENT 
            if (!is_null($checkScope)) {
                $userPointers = Unit::getContentPointers(null, $checkScope, $checkValue);
            } else {
                $userPointers = Unit::getContentPointers();
            }

            $tree = Program::getUserContentTree($this->user, true);

            if ($userPointers && count($tree) > 0) {

                // TODO MOVE TO YOUR OWN COMPONENT
                //$this->putScript("plugins/jquery-easy-pie-chart/jquery.easy-pie-chart");
                $this->putComponent("datatables");
                $this->putComponent("bootstrap-switch");
                $this->putComponent("icheck");
                $this->putComponent("easy-pie-chart");
                $this->putComponent("videojs");

                $this->putCss("css/reset");
                $this->putScript("plugins/holder");
                
                //$this->putScript("plugins/videojs/vjs.youtube");

                //$this->putModuleScript("models.courses");
                $this->putBlock("content.info.dialog");
                $this->putBlock("messages.send.dialog");
                

                $this->putModuleScript("portlet.content");
                $this->putBlock("tests.info.dialog");

                $this->putBlock("storage.pdfviewer.dialog");

                
                //$this->putBlock("lessons.dialogs.exercises");
                
                //$this->putBlock("content.unit.dialog");


                $data = array(
                    'current' => array(
                        'program_id'    => $userPointers['program']->id,
                        'course_id'     => $userPointers['course']->id,
                        'unit_id'       => $userPointers['unit']->id,
                        'content_id'    => $userPointers['content']->id
                    ),
                    'tree' => array_values($tree),
                    'progress' => $this->getUserProgressRequest()
                );

    			return array(
    				'content.overview' => array(
    					'type'      => 'content', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
    					'id'        => 'content-widget',
    					'template'	=> $this->template("widgets/overview-new"),
    					'box'       => 'dark-blue tabbable tabbable-left',
                        //'title'     => '',
                        /*
    					'tools'     => array(
    						//'search'        => true,
    						//'fullscreen'    => true
    					),
                        */
                        'data' => $data,
                        'panel'     => true,
                        'body'      => 'no-padding',
    				)
    			);
            }

		}

		return false;
	}

    public function registerBlocks() {
        return array(
            'content.unit.dialog' => function($data, $self) {
                //$self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                $self->putScript("plugins/videojs/video");

                // CREATE BLOCK CONTEXT
                //$block_context = $self->getConfig("blocks\\blocks.questions.list\\context");
                //$self->putItem("questions_list_block_context", $block_context);

                //$self->putModuleScript("blocks.questions.list");
                //$self->setCache("blocks.questions.list", $block_context);
                $this->putModuleScript("dialogs.content.unit");

                $self->putSectionTemplate("dialogs", "dialogs/content-unit");

                return true;
            },
            'content.info.dialog' => function ($data, $self) {
                $self->putModuleScript("dialogs.content.info");
                $self->putSectionTemplate("dialogs", "dialogs/info");

                return true;
            },
        );
    }

    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {

            $this->putScript("scripts/ui.menu.content");

            $courses = $this->user->getPrograms([
                'conditions' => "approved = 1 AND active = 1"
            ]);

            $items = array();
            foreach($courses as $course) {
                $items[] = array(
                    'link' => "javascript:void(0);",
                    'text' => sprintf("%s", $course->name),
                    'attrs' => array(
                        'data-entity-id' => $course->id
                    )
                );
            }

            if (count($courses) > 0) {
                $menuItem = array(
                    'id'        => "users-topbar-menu",
                    'icon'      => ' fa fa-graduation-cap',
                    'text'      => $this->translate->translate('Programs'),
                    /*
                    'external'  => array(
                        'link'  => $this->getBasePath(),
                        'text'  => $this->translate->translate('See my statement')
                    ),
                    
                    'link'  => array(
                        'link'  => $this->getBasePath(),
                        'text'  => $this->translate->translate('Courses')
                    ),
                    */
                    'type'      => '',
                    'items'     => $items,
                    'extended'  => false,
                );

                return $menuItem;
            }
        }
        return false;
    }

    


    /**
     * [ add a description ]
     *
     * @Get("/stats/me/{identifier}")
     */
    public function getCourseStatsRequest($identifier)
    {
        //$user = $this->getCurrentUser(true);
        $enrollmentCourse = CourseUsers::findFirst(array(
            'conditions'    => "user_id = ?0 AND course_id = ?1",
            'bind' => array($this->user->id, $identifier)
        ));

        if (count($enrollmentCourse) > 0) {
            // CALCULATE COURSE PROGRESS
            $progress = $enrollmentCourse->getProgress(true);

            $this->response->setJsonContent($progress);
            return true;
        } else {
            // USER NOT ENROLLED IN REQUESTED COURSE
            $this->response->setJsonContent($this->invalidRequestError());
        }
    }
    /**
     * [ add a description ]
     *
     * @Post("/item/users/toggle")
     */
    public function switchUserInGroup() {
        $data = $this->getHttpData(func_get_args());

        $enrollCourseModel = $this->model("enrollment/course");

        $status = $enrollCourseModel->switchUser(
            $data['course_id'],
            $data['user_id']
        );

        if ($status == 1) {
            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse($this->translate->translate("User added to course."), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse($this->translate->translate("User removed from course."), "error");
        }
        return array_merge($response, $info);
    }

    /**
     * [ add a description ]
     *
     * @Post("/set-pointer")
     */
    public function setContentPointer() {
        $data = $this->request->getPost();

        $scopes = array('program', 'course', 'unit', 'content');

        if (in_array($data['scope'], $scopes)) {
            $userPointers = Unit::getContentPointers(null, $data['scope'], $data['entity_id']);

            $result = array(
                'program_id'    => $userPointers['program']->id,
                'course_id'     => $userPointers['course']->id,
                'unit_id'       => $userPointers['unit']->id,
                'content_id'    => $userPointers['content']->id
            );

            foreach($scopes as $scope) {
                $userSetting = new UserSettings();
                $userSetting->user_id = $this->user->id;
                $userSetting->item = $scope . '_id';
                $userSetting->value = $userPointers[$scope]->id;

                $userSetting->save();

            }

            return $result;
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/datasource/progress")
     */
    public function getUserProgressRequest() {
        $progress = Program::getUserProgressTree();

        return $progress;

        /*
        $userPointers = Unit::getContentPointers();
        var_dump($progress);
        //exit;
        
        $result = array();

        if ($userPointers['program']) {

            $result['programs'] = array();

            if ($progress = $userPointers['program']->getProgress(array(
                'conditions' => 'user_id = ?0',
                'bind' => array($this->user->id)
            ))) {
                $result['programs'][] = $progress->toArray();
            } else {
                $result['programs'][] = array(
                    'user_id' => $this->user->id,
                    'course_id' => $userPointers['program']->id,
                    'factor' => 0
                );
            }

            $result['courses'] = array();
            $result['units'] = array();
            $result['contents'] = array();

            foreach($userPointers['program']->getCourses() as $course) {  

                if ($progress = $course->getProgress(array(
                    'conditions' => 'user_id = ?0',
                    'bind' => array($this->user->id)
                ))) {
                    $result['courses'][] = $progress->toArray();
                } else {
                    $result['courses'][] = array(
                        'user_id' => $this->user->id,
                        'class_id' => $course->id,
                        'factor' => 0
                    );
                }

                foreach($course->getUnits() as $unit) {
                    if ($progress = $unit->getProgress(array(
                        'conditions' => 'user_id = ?0',
                        'bind' => array($this->user->id)
                    ))) {
                        $result['units'][] = $progress->toArray();
                    } else {
                        $result['units'][] = array(
                            'user_id' => $this->user->id,
                            'lesson_id' => $unit->id,
                            'factor' => 0
                        );
                    }

                    
                    foreach($unit->getContents() as $content) {
                        if ($progress = $content->getProgress(array(
                            'conditions' => 'user_id = ?0',
                            'bind' => array($this->user->id)
                        ))) {
                            $result['contents'][] = $progress->toArray();
                        } else {
                            $result['contents'][] = array(
                                'user_id' => $this->user->id,
                                'content_id' => $content->id,
                                'factor' => 0
                            );
                        }
                    }
                }
            }
        }
        var_dump($result);
        exit;
        return $result;
        */
    }

}

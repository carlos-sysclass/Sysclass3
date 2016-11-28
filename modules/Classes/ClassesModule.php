<?php
namespace Sysclass\Modules\Classes;
/**
 * Module Class File
 * @filesource
 */
use 
    Sysclass\Models\Content\Program,
    Sysclass\Models\Content\Course as Classe,
    Sysclass\Models\Content\Unit as Lesson,
    Sysclass\Models\Acl\Role;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/classes")
 */
class ClassesModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable, \IBlockProvider
{
    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, "Classes", "View")) {
            $count = Classe::count("active = 1");
            /*
            $itemsData = $this->model("classes")->addFilter(array(
                'active'    => true
            ))->getItems();
            */
            return array(
                'content' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Courses'),
                        'icon'  => 'fa fa-sitemap',
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
                'text'  => $this->translate->translate("Home")
            ),
            array(
                'icon'  => 'icon-bookmark',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Classes")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Course"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Course"));
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
                    'text'      => $this->translate->translate('New Class'),
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
            'courses.moreinfo' => function($data, $self) {
                $self->putSectionTemplate("moreinfo", "blocks/moreinfo");
            },
            'courses.unit.edit' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                $self->putModuleScript("blocks.classes.lessons.edit");

                $self->putSectionTemplate("units", "blocks/units.edit");

                return true;
            }
        );
    }
    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    /*
    public function addPage()
    {
        parent::addPage($id);
    }
    */

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     */
    public function editPage($id)
    {
        $programs = Program::find();
        $this->putItem("programs", $programs->toArray());

        // GET THE PROFESSORS
        $teacherRole = Role::findFirstByName('Teacher');
        $users = $teacherRole->getAllUsers();

        $this->putItem("instructors", $users);

        parent::editPage($id);
    }




    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
     */
    /*
    public function getItemAction($id) {

        $editItem = $this->model("classes")->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        return $editItem;
    }
    */

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    /*
    public function addItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("classes");
            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    $this->translate->translate("Class created."),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("There's ocurred a problen when the system tried to save your data. Please check your data and try again", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url PUT /item/me/:id
     */
    /*
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("classes");
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Class updated."), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url DELETE /item/me/:id
     */
    /*
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("courses/classes/collection");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Class removed."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    /*
    public function getItemsAction($type)
    {
        $modelRoute = "roadmap/classes";
        $optionsRoute = "edit";

        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        //$modelRoute = "users/groups/collection";
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();


        $items = $itemsData;

        if ($type === 'combo') {
        } elseif ($type === 'datatable') {

            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $baseLink . $optionsRoute . "/" . $item['class_id'],
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
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }

        return array_values($items);
    }
    */
    /**
     * [ add a description ]
     *
     * @Put("/items/lessons/set-order/{class_id}")
     */
    public function setLessonOrderRequest($class_id)
    {
        if ($this->isUserAllowed("edit")) {
            $data = $this->request->getPut();

            $itemModel = $this->getModelData("me", $class_id);

            $messages = array(
                'success' => "Lesson order updated.",
                'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
            );

            if ($itemModel->setLessonOrder($data['position'])) {
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

}

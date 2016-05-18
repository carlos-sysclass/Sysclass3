<?php
namespace Sysclass\Modules\Questions;
/**
 * Module Class File
 * @filesource
 */

use Sysclass\Models\Courses\Departament,
    Sysclass\Models\Courses\Questions\Question,
    Sysclass\Models\Courses\Questions\Type as QuestionType,
    Sysclass\Models\Courses\Questions\Difficulty as QuestionDifficulty;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/questions")
 */
class QuestionsModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable , \IBlockProvider
{
    protected $_modelRoute = "questions";
    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, "Questions", "View")) {

            $count = Question::count("active = 1");

            return array(
                'content' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Questions'),
                        'icon'  => 'fa fa-question',
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
                'icon'  => 'fa fa-question',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Questions")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Question"));
                break;
            }
            case "edit/{identifier}" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Question"));
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
                    'text'      => $this->translate->translate('New Question'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus'
                )
            )
        );

        return $actions[$request];
    }

    public function registerBlocks() {
        return array(
            'questions.list' => function($data, $self) {
                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");
                //$self->putComponent("select2");
                //$self->putComponent("bootstrap-editable");

                //$block_context = $self->getConfig("blocks\\blocks.questions.list\\context");
                //$self->putItem("questions_list_block_context", $block_context);

                $self->putModuleScript("blocks.questions.list");
                //$self->setCache("blocks.questions.list", $block_context);

                $self->putSectionTemplate("questions-list", "blocks/questions.list");

                $self->putBlock('questions.select.dialog');
                $self->putBlock('questions.create.dialog');

                return true;
            },
            'questions.create.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("wysihtml5");
                //$self->putComponent("select2");
                $self->putComponent("bootstrap-switch");


                $items = $self->model("courses/areas/collection")->addFilter(array(
                    'active' => 1
                ))->getItems();

                $self->putitem("knowledge_areas", $items);

                $items = $self->model("questions/types")->getItems();
                $self->putItem("questions_types", $items);

                $items =  $self->model("questions/difficulties")->getItems();
                $self->putItem("questions_difficulties", $items);


                //$block_context = $self->getConfig("blocks\\questions.select.dialog\\context");
                //$self->putItem("questions_select_block_context", $block_context);

                $self->putModuleScript("dialogs.questions.create");
                $self->putModuleScript("views.form.questions");
                //$self->setCache("dialogs.questions.select", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/create");

                return true;
            },
            'questions.select.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("data-tables");
                $self->putComponent("select2");
                //$self->putComponent("bootstrap-editable");

                $block_context = $self->getConfig("blocks\\questions.select.dialog\\context");
                $self->putItem("questions_select_block_context", $block_context);

                $self->putModuleScript("dialogs.questions.select");
                $self->setCache("dialogs.questions.select", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/questions.select");

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
        $items = Departament::find("active = 1");
        $this->putitem("knowledge_areas", $items->toArray());

        $items = QuestionType::find();
        $this->putItem("questions_types", $items->toArray());

        $items = QuestionDifficulty::find();
        $this->putItem("questions_difficulties", $items->toArray());

        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{identifier}")
     */
    public function editPage($identifier)
    {
        $items = Departament::find("active = 1");
        $this->putitem("knowledge_areas", $items->toArray());

        $items = QuestionType::find();
        $this->putItem("questions_types", $items->toArray());

        $items = QuestionDifficulty::find();
        $this->putItem("questions_difficulties", $items->toArray());


        parent::editPage($identifier);
    }


    public function getDatatableItemOptions() {
        if ($this->_args['model'] == 'lesson-content') {
            $options['select'] = array(
                'icon'  => 'icon-check',
                'class' => 'btn-sm btn-primary'
            );

            return $options;

        } else {
            return parent::getDatatableItemOptions();
        }
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/:model/:id
     */
    /*
    public function getItemAction($model, $id) {
        if ($model == "me") {
            $modelRoute = $this->_modelRoute;
        } else {
            return $this->invalidRequestError();
        }
        $editItem = $this->model($modelRoute)->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS

        return $editItem;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/:model
     */
    /*
    public function addItemAction($model)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $modelRoute = $this->_modelRoute;
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($modelRoute);
            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                if ($_GET['object'] == "1") {
                    $response = $this->createAdviseResponse(
                        $this->translate->translate("Question created with success"),
                        "success"
                    );

                    return array_merge($itemModel->getItem($data['id']), $response);

                } else {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        $this->translate->translate("Question created with success"),
                        "success"
                    );

                }
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
     * @url PUT /item/:model/:id
     */
    /*
    public function setItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $modelRoute = $this->_modelRoute;
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($modelRoute);

            if ($itemModel->setItem($data, $id) !== FALSE) {

                $modelData = $this->model($modelRoute)->getItem($id);
                $data = array_merge($data, $modelData);

                $response = $this->createAdviseResponse($this->translate->translate("Question updated with success"), "success");
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
     * @url DELETE /item/:model/:id
     */
    /*
    public function deleteItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($this->_modelRoute);
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Question removed with success"), "success");
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
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:data
     */
    /*
    public function getItemsAction($model, $type)
    {
        if ($model == "me") {
            $modelRoute = $this->_modelRoute;
            $optionsRoute = "edit";
        } elseif ($model == "lesson-content") {
            $modelRoute = $this->_modelRoute;

        } else {
            return $this->invalidRequestError();
        }


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
                if ($model == "me") {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'icon-edit',
                            'link'  => $baseLink . $optionsRoute . "/" . $item['id'],
                            'class' => 'btn-sm btn-primary'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                } elseif ($model == "lesson-content") {
                    $items[$key]['options'] = array(
                        'select'  => array(
                            'icon'  => 'icon-check',
                            'class' => 'btn-sm btn-primary'
                        )
                    );
                }
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


}

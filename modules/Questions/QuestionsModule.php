<?php
namespace Sysclass\Modules\Questions;
/**
 * Module Class File
 * @filesource
 */

use Sysclass\Models\Content\Department,
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
                        'icon'  => 'fa fa-question-circle',
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
                'icon'  => 'fa fa-question-circle',
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
                $breadcrumbs[] = array('text'   => $this->translate->translate("New question"));
                break;
            }
            case "edit/{identifier}" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit question"));
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
                    'text'      => $this->translate->translate('New question'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus-square'
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

                $self->putComponent("underscore-string");

                

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
                $self->putComponent("wysihtml");
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
        $items = Department::find("active = 1");
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
        $items = Department::find("active = 1");
        $this->putitem("knowledge_areas", $items->toArray());

        $items = QuestionType::find();
        $this->putItem("questions_types", $items->toArray());

        $items = QuestionDifficulty::find();
        $this->putItem("questions_difficulties", $items->toArray());


        parent::editPage($identifier);
    }


    /**
     * [ add a description ]
     *
     * @Get("/form/create")
     */
    public function formCreatePage($identifier)
    {
        $items = Department::find("active = 1");
        $this->putitem("knowledge_areas", $items->toArray());

        $items = QuestionType::find();
        $this->putItem("questions_types", $items->toArray());

        $items = QuestionDifficulty::find();
        $this->putItem("questions_difficulties", $items->toArray());

        $this->handleDefaultRequest();

        /*

        $model_info = $this->model_info['me'];

        if ($this->isResourceAllowed("create", $model_info)) {
            $this->display($this->template);
        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
        */
    }


    public function getDatatableItemOptions($model = "me") {
        if ($this->_args['model'] == 'lesson-content') {
            $options['select'] = array(
                'icon'  => 'icon-check',
                'class' => 'btn-sm btn-primary'
            );

            return $options;

        } else {
            return parent::getDatatableItemOptions($model);
        }
    }

}

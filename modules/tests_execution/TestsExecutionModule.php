<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class TestsExecutionModule extends SysclassModule implements IBlockProvider
{
    public function vksprintf($str, $args)
    {
        if (is_object($args)) {
            $args = get_object_vars($args);
        }
        $map = array_flip(array_keys($args));
        $new_str = preg_replace_callback(
            '/(^|[^%])%([a-zA-Z0-9_-]+)\$/',
            function($match) use ($map) {
                //var_dump($map[$match[2]]);
                //if (is_array($map[$match[2]])) {
                //    return $match[1].'%'.(json_encode($map[$match[2]]) + 1).'$';
                //} else {
                    return $match[1].'%'.($map[$match[2]] + 1).'$';
                //}
            },
            $str
        );
        foreach ($args as $key => $value) {
            if (is_array($value)) {
                $args[$key] = json_encode($value);
            }
        }
        return vsprintf($new_str, $args);
    }

    /* IBlockProvider */
    public function registerBlocks()
    {
        return array(
            'test_execution.list.table' => function ($data, $self) {
                // APLLY FILTER BASED ON $data['filter'] AND $data['context']

                // TODO: TEST FOR EMPTY KEYS IN $data
                $filter = array();
                foreach($data['filter'] as $key => $value) {
                    $filter[$key] = $self->vksprintf($value, $data['context']);
                }

                $block_context = $self->getConfig("blocks\\test_execution.list.table\context");

                $block_context['ajax_source'] = $self->vksprintf(
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
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        /*
        } elseif ($model ==  "coordinator") {
            $modelRoute = "users/collection";
            $optionsRoute = "edit-instructor";

            $itemsCollection = $this->model($modelRoute);
            $itemsData = $itemsCollection->addFilter(array(
                'can_be_coordinator' => true
            ))->getItems();

        } elseif ($model ==  "seasons") {

            $courses = filter_var($filter, FILTER_DEFAULT);

            if (!is_array($courses)) {
                $courses = json_decode($courses, true);
            }
            //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

            $itemsData = $this->model("course/seasons")->addFilter(array(
                'active'    => 1,
                'course_id' => $courses
            ), array("operator" => "="))->getItems();

            //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
        } elseif ($model ==  "classes") {

            $courses = filter_var($filter, FILTER_DEFAULT);

            if (!is_array($courses)) {
                $courses = json_decode($courses, true);
            }
            $modelRoute = "courses/classes/collection";
            $itemsCollection = $this->model($modelRoute);

            $itemsData = $itemsCollection->addFilter(array(
                'active'    => 1
            ), array("operator" => "="))->getItems();

            //$items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
        */
        if ($model ==  "me") {
            $modelRoute = "tests/execution";
            $optionsRoute = "edit";

            $filter = filter_var($filter, FILTER_DEFAULT);

            if (!is_array($filter)) {
                $filter = json_decode($filter, true);
            }

            $itemsCollection = $this->model($modelRoute);
            $itemsData = $itemsCollection->addFilter($filter)->getItems();

        } else {
            return $this->invalidRequestError();
        }

        if ($type === 'datatable') {

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
}

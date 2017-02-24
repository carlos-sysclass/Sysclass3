<?php
class LessonsModel extends BaseUnitsModel implements ISyncronizableModel {

    public function init()
    {
        $this->lesson_type =  "lesson";

        parent::init();
    }

    protected function parseItem($item)
    {
        $item['info'] = json_decode($item['info'], true);

        if ($this->getUserFilter()) {
            $progress = $this->model("lessons/progress")->clear()->addFilter(array(
                'user_id'       => $this->getUserFilter(),
                'lesson_id'    => $item['id']
            ))->getItems();

            $item['progress'] = reset($progress);
        }

        return $item;
    }

    public function getItems()
    {
        $data = parent::getItems();

        // LOAD INSTRUCTORS
        foreach($data as $key => $item) {
            $data[$key] = $this->parseItem($item);
        }
        return $data;
    }

    public function getItem($identifier)
    {
        $item = parent::getItem($identifier);

        return $this->parseItem($item);
    }

    public function loadContentFiles($id, $type = null) {
        $filehelper = $this->helper("file/wrapper");
        $path = $filehelper->getUnitPath($id, $type);

        return $filehelper->listFiles($path);

    }

    protected function resetContentOrder($class_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'class_id' => $class_id
        ));
    }

    public function setContentOrder($class_id, array $order_ids) {
        $this->resetContentOrder($class_id);
        foreach($order_ids as $index => $lesson_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $lesson_id,
                'class_id' => $class_id
            ));
        }

        return true;

    }
    /*
    public function recalculateProgress($lesson_id) {
        $progressAwareTypes = array('file');

        $contents = $this->model("lessons/content")->debug()->addFilter(array(
            'lesson_id' => $lesson_id,
            'content_type' => $progressAwareTypes
        ))->getItems();

        $progressItens = \array_column($contents, 'progress');

        if (array_sum($progressItens) == count($progressItens)) {
            $this->setItem(array('progress' => 1), $lesson_id);
            return true;
        }

        return false;

    }
    */
}

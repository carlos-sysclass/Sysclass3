<?php
class LessonsModel extends BaseLessonsModel implements ISyncronizableModel {

    public function init()
    {
        $this->lesson_type =  "lesson";

        parent::init();
    }

    public function loadContentFiles($id, $type = null) {
        $filehelper = $this->helper("file/wrapper");
        $path = $filehelper->getLessonPath($id, $type);

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
    public function recalculateProgress($lesson_id) {
        $progressAwareTypes = array('file');

        $contents = $this->model("lessons/content")->debug()->addFilter(array(
            'lesson_id' => $lesson_id,
            'content_type' => $progressAwareTypes
        ))->getItems();

        $progressItens = array_column($contents, 'progress');

        if (array_sum($progressItens) == count($progressItens)) {
            $this->setItem(array('progress' => 1), $lesson_id);
            return true;
        }

        return false;

    }
}

<?php
class UnitsContentProgressModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_lessons_content_progress";
        $this->id_field = "id";
        $this->mainTablePrefix = "lcp";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT
            lcp.id,
            lcp.user_id,
            lcp.content_id,
            lcp.factor,
            lc.lesson_id as 'content#lesson_id'
        FROM `mod_lessons_content_progress` lcp
        LEFT JOIN mod_lessons_content lc ON (lcp.content_id = lc.id)";

//        $this->order = array("-lc.`position` DESC");

        parent::init();

    }

    /*
    protected function parseItem($item)
    {
        $item['info'] = json_decode($item['info'], true);

        if ($item['content_type'] == 'exercise') {
            // LOAD QUESTIONS
            $innerModel = $this->model("lessons/content/exercise");
            $item['exercise'] = $innerModel->clear()->addFilter(array(
                'content_id' => $item['id']
            ))->getItems();
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
    */

    public function addOrSetItem($data) {
        $data['user_id'] = $this->getUserFilter();

        $items = $this->clear()->addFilter(array(
            'user_id'       => $data['user_id'],
            'content_id'    => $data['content_id']
        ))->getItems();

        if (count($items) > 0) {
            $result = parent::setItem($data, array(
                'user_id'       => $data['user_id'],
                'content_id'    => $data['content_id']
            ));

            $progress_id = $items[0]['id'];

        } else {
            $result = parent::addItem($data);

            $progress_id = $result;
        }
        $progress = $this->clear()->getItem($progress_id);

        if (floatval($data['factor']) >= 1) {
            $this->model("lessons/progress")
                ->setUserFilter($this->getUserFilter())
                ->recalculateProgress($progress['content']['lesson_id']);
        }
        return $progress;
    }

    public function addItem($data) {
        $progress = $this->addOrSetItem($data);
        return $progress['id'];
    }

    public function setItem($data, $identifier) {
        $progress = $this->addOrSetItem($data);
        return count($progress) > 0;
    }

}

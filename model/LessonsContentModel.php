<?php
class LessonsContentModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_lessons_content";
        $this->id_field = "id";
        $this->mainTablePrefix = "lc";
        //$this->fieldsMap = array();

        $this->selectSql = "
            SELECT
                lc.`id`,
                lc.`lesson_id`,
                lc.`parent_id`,
                lc.`content_type`,
                lc.`title`,
                lc.`info`,
                lc.`language_code`,
                lc.`position`,
                lc.`active`,
                lc.`main`,
                lf.id as 'file#id',
                lf.upload_type as 'file#upload_type',
                lf.`name` as 'file#name',
                lf.`type` as 'file#type',
                lf.size as 'file#size',
                lf.url as 'file#url',
                lf.active as 'file#active'
            FROM `mod_lessons_content` lc
            LEFT JOIN `mod_lessons_content_files` lcf ON (lc.id = lcf.content_id)
            LEFT JOIN `mod_dropbox` lf ON (lf.id = lcf.file_id)
		";

        $this->order = array("-lc.`position` DESC");

        parent::init();

    }


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

        if ($this->getUserFilter()) {
            $progress = $this->model("lessons/content/progress")->clear()->addFilter(array(
                'user_id'       => $this->getUserFilter(),
                'content_id'    => $item['id']
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

    /*
    public function getItem($identifier)
    {
        $item = parent::getItem($identifier);

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
        foreach($data as $key => $item) {
            if ($item['content_type'] == 'exercise') {
                // LOAD QUESTIONS
                $innerModel = $this->model("lessons/content/exercise");
                $data[$key]['exercise'] = $innerModel->addFilter(array(
                    'content_id' => $item['id']
                ))->getItems();
            }
        }
        return $data;
    }
    */
    public function addItem($data) {
        $identifier = parent::addItem($data);

        $type = $data['content_type'];
        if ($type == "subtitle" || $type == "subtitle-translation") {
            $type = "file";
        }
        if (in_array($type, array('file', 'text', 'exercise')) && array_key_exists($type, $data)) {
            $innerModel = $this->model("lessons/content/" . $type);

            if ($type == "file") {
                $innerData = array(
                    'content_id'    => $identifier,
                    'file_id'       => $data[$type]['id']
                );

                $innerModel->addItem($innerData);
            } elseif ($type == "exercise" && is_array($data[$type])) {
                foreach($data[$type] as $item) {
                    $innerData = array(
                        'content_id'    => $identifier,
                        'question_id'   => $item['question_id']
                    );
                    $innerModel->addOrSetItem($innerData);
                }
            }
        }

        // TODO: SAVE EXERCISES SENT!
        return $identifier;
    }

    public function setItem($data, $identifier) {
        parent::setItem($data, $identifier);

        // IF THE PROGRESS IS SET, SEND TO PARENT MODELS TO RECALCULATE
        //
        /*
        if (floatval($data['progress']) >= 1) {
            $this->model("lessons")->recalculateProgress($data['lesson_id']);
        }
        */

        $type = $data['content_type'];
        if ($type == "subtitle" || $type == "subtitle-translation") {
            $type = "file";
        }
        if (in_array($type, array('file', 'text', 'exercise')) && array_key_exists($type, $data)) {
            $innerModel = $this->model("lessons/content/" . $type);

            if ($type == "file") {
                /*
                $innerData = array(
                    'content_id'    => $identifier,
                    'file_id'       => $data[$type]['id']
                );

                $innerModel->addItem($innerData);
                */
            } elseif ($type == "exercise" && is_array($data[$type])) {
                $innerModel->delete(array(
                    'content_id'    => $identifier
                ));

                foreach($data[$type] as $item) {
                    $innerData = array(
                        'content_id'    => $identifier,
                        'question_id'   => $item['question_id']
                    );
                    $innerModel->addOrSetItem($innerData);
                }
            }
        }


        // TODO: SAVE EXERCISES SENT!
        return $identifier;
    }

    protected function resetContentOrder($lesson_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'lesson_id' => $lesson_id
        ));
    }

    public function setContentOrder($lesson_id, array $order_ids) {
        $this->resetContentOrder($lesson_id);

        foreach($order_ids as $index => $content_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $content_id,
                'lesson_id' => $lesson_id
            ));
        }

        return true;

    }
}

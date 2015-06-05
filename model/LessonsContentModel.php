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

    public function addItem($data) {
        $id = parent::addItem($data);

        $type = $data['content_type'];
        if ($type == "subtitle") {
            $type = "file";
        }
        if (in_array($type, array('file', 'text', 'exercise')) && array_key_exists($type, $data)) {
            $innerModel = $this->model("lessons/content/" . $type);
            $innerData = array(
                'content_id'    => $id,
                'file_id'       => $data[$type]['id']
            );
            $innerModel->addItem($innerData);
        }
        return $id;
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

<?php
/**
 * @deprecated 3.2.0
 */
class AdvertisingContentModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_advertising_content";
        $this->id_field = "id";
        $this->mainTablePrefix = "ac";
        //$this->fieldsMap = array();

        $this->selectSql = "
            SELECT
                ac.`id`,
                ac.`advertising_id`,
                ac.`content_type`,
                ac.`title`,
                ac.`info`,
                ac.`language_code`,
                ac.`position`,
                ac.`active`,
                af.id as 'file#id',
                af.upload_type as 'file#upload_type',
                af.`name` as 'file#name',
                af.`type` as 'file#type',
                af.size as 'file#size',
                af.url as 'file#url',
                af.active as 'file#active'
            FROM `mod_advertising_content` ac
            LEFT JOIN `mod_advertising_content_files` acf ON (ac.id = acf.content_id)
            LEFT JOIN `mod_dropbox` af ON (af.id = acf.file_id)
		";

        $this->order = array("-ac.`position` DESC");

        parent::init();

    }


    protected function parseItem($item)
    {
        $info = json_decode($item['info'], true);
        if (!is_null($info)) {
            $item['info'] = $info;
        }
        /*
        if ($item['content_type'] == 'exercise') {
            // LOAD QUESTIONS
            $innerModel = $this->model("lessons/content/exercise");
            $item['exercise'] = $innerModel->clear()->addFilter(array(
                'content_id' => $item['id']
            ))->getItems();
        }
        */
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
        if (in_array($type, array('file', 'text')) && array_key_exists($type, $data)) {
            $innerModel = $this->model("advertising/content/" . $type);

            if ($type == "file") {
                $innerData = array(
                    'content_id'    => $identifier,
                    'file_id'       => $data[$type]['id']
                );

                $innerModel->addItem($innerData);
            }
        }

        // TODO: SAVE EXERCISES SENT!
        return $identifier;
    }

    public function setItem($data, $identifier) {
        parent::setItem($data, $identifier);

        $type = $data['content_type'];
        if (in_array($type, array('file', 'text')) && array_key_exists($type, $data)) {
            $innerModel = $this->model("advertising/content/" . $type);

            if ($type == "file") {
                /*
                $innerData = array(
                    'content_id'    => $identifier,
                    'file_id'       => $data[$type]['id']
                );

                $innerModel->addItem($innerData);
                */
            }
        }

        // TODO: SAVE EXERCISES SENT!
        return $identifier;
    }

    protected function resetContentOrder($entity_id) {
        $this->setItem(array(
            'position' => -1
        ), array(
            'advertising_id' => $entity_id
        ));
    }

    public function setContentOrder($entity_id, array $order_ids) {
        $this->resetContentOrder($entity_id);

        foreach($order_ids as $index => $content_id) {
            $this->setItem(array(
                'position' => $index + 1
            ), array(
                'id' => $content_id,
                'advertising_id' => $entity_id
            ));
        }

        return true;

    }
}

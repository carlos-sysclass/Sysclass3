<?php
class ExtratoModel extends AbstractSysclassModel implements ISyncronizableCollection {

    public function init()
    {
        $this->table_name = "mod_courses";
        $this->id_field = "id";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT
            c.`id`,
            c.`permission_access_mode`,
            c.`ies_id`,
            c.`name`,
            c.`description`,
            c.`area_id`,
            c.`coordinator_id`,
            c.`active`,
            c.`archive`,
            c.`price`
            /*
            `created`,
            `start_date`,
            `end_date`,
            `options`,
            `metadata`,
            `info`,

            `currency`,
            `enable_registration`,
            `price_registration`,
            `enable_presencial`,
            `price_presencial`,
            `enable_web`,
            `price_web`,
            `show_catalog`,
            `publish`,
            `directions_ID`,
            `languages_NAME`,
            `reset`,
            `certificate_expiration`,
            `max_users`,
            `rules`,
            `terms`,
            `instance_source`,
            `supervisor_LOGIN`,
            `has_grouping`,
            `has_student_selection`,
            `has_periods`,
            */
        FROM `mod_courses` c";

        parent::init();

    }

    protected function parseItem($item) {
        if (count($item) == 0) {
            return $item;
        }

        $userModel =  $this->model("users/collection");

        $item['coordinator_id'] = json_decode($item['coordinator_id'], true);

        if (is_array($item['coordinator_id'])) {
            $item['coordinators'] = $userModel->clear()->addFilter(array(
                'can_be_coordinator' => true,
                'id'    =>  $item['coordinator_id']
            ))->getItems();
        } else {
            $item['class']['coordinators'] = array();
        }

        if ($this->getUserFilter()) {
            $this->model("courses/progress")
                ->setUserFilter($this->getUserFilter())
                ->recalculateProgress($item['id']);

            $progress = $this->model("courses/progress")->clear()->addFilter(array(
                'user_id'       => $this->getUserFilter(),
                'course_id'    => $item['id']
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
    public function getFullItem($identifier) {

        $data = $this->getItem($identifier);
        if (count($data) == 0) {
            return $data;
        }

        // GET CLASSES
        $data['classes'] = $this->model("roadmap/classes")->addFilter(array(
            'course_id' => $identifier
        ))->getItems();

        return $data;
    }
    */
    public function addItem($data)
    {
        $data['coordinator_id'] = json_encode($data['coordinator_id']);
        return parent::addItem($data);
    }

    public function setItem($data, $identifier)
    {
        $data['coordinator_id'] = json_encode($data['coordinator_id']);
        return parent::setItem($data, $identifier);
    }
}



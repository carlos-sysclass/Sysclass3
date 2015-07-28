<?php
class GradesModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_grades";
        $this->id_field = "id";
        $this->mainTablePrefix = "g";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, description, grades, active FROM mod_grades g";

        parent::init();

    }
    protected function parseItem($item) {
        $item['grades'] = json_decode($item['grades'], true);

        return $item;
    }

    public function getItems()
    {
        $data = parent::getItems();

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

    public function addItem($data)
    {
        $data['grades'] = json_encode($data['grades']);
        return parent::addItem($data);
    }

    public function setItem($data, $identifier)
    {
        $data['grades'] = json_encode($data['grades']);
        return parent::setItem($data, $identifier);
    }

}

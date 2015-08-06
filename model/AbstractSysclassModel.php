<?php
abstract class AbstractSysclassModel extends ModelManager implements ISyncronizableCollection  {
    protected $contextUserId = null;

    public function filterCollection($data, $filter) {
        $filter = trim(mb_strtolower($filter), '||');
        if (!empty($filter)) {
            foreach ($data as $key => $value) {
                $imploded_string = implode(",", $value); //Instead of checking each row value one-by-one, check it all at once
                if (strpos(mb_strtolower($imploded_string), $filter) === false) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    public function getUserFilter($user_id) {
        return $this->contextUserId;
    }

    public function setUserFilter($user_id) {
        $this->contextUserId = $user_id;

        return $this;
    }
}

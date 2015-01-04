<?php
abstract class AbstractSysclassModel extends ModelManager implements ISyncronizableCollection  {

    public function filterCollection($data, $filter) {
        $filter = trim(mb_strtolower($filter), '||');
        if ($filter) {
            foreach ($data as $key => $value) {
                $imploded_string = implode(",", $value); //Instead of checking each row value one-by-one, check it all at once
                if (strpos(mb_strtolower($imploded_string), $filter) === false) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

}

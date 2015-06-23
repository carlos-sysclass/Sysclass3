<?php
class TestsModel extends BaseLessonsModel implements ISyncronizableModel {

    public function init()
    {
        $this->lesson_type =  "test";

        parent::init();
    }


}

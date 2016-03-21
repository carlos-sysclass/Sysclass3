<?php
namespace Sysclass\Models\Courses\Contents;

use Phalcon\Mvc\Model;

class Progress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content_progress");

    }

    public function save($data = NULL, $whiteList = NULL) {
        if (is_null($this->user_id)) {
            $user = $this->getDI()->get("user");
            if ($user) {
                $this->user_id = $user->id;
            } else {
                return false;
            }
        }

        return parent::save($data, $whiteList);
    }

    public function afterSave() {
        $evManager = $this->getDI()->get("eventsManager");
        $evManager->fire("unit:progress", $this, $this->toArray());
    }



        

    /*
    public function metaData()
    {
        print_r($this->getModelsMetaData());
        exit;
    }
    */
}


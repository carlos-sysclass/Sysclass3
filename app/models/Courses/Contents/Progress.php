<?php
namespace Sysclass\Models\Courses\Contents;

use Phalcon\Mvc\Model,
    Sysclass\Models\Courses\LessonProgress;

class Progress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content_progress");


        $this->belongsTo("content_id", "Sysclass\\Models\\Courses\\Contents\\Content", "id",  array('alias' => 'Content'));

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

    public function updateProgress() {
        // GET RELATED UNIT, AND CALL AN UPDATE
        // 
        $content = $this->getContent();

        $lessonProgress = LessonProgress::findFirst(array(
            'conditions' => 'user_id = ?0 AND lesson_id = ?1',
            'bind' => array($this->user_id, $content->lesson_id)
        ));

        $messages = $lessonProgress->updateProgress();

        $statuses = array_column($messages, 'status');

        return array(
            'status' => !in_array(false, $statuses, true),
            'messages' => $messages
        );
    }


        

    /*
    public function metaData()
    {
        print_r($this->getModelsMetaData());
        exit;
    }
    */
}


<?php
namespace Sysclass\Models\Content\Progress;

use Phalcon\Mvc\Model,
    Sysclass\Models\Content\Progress\Unit as UnitProgress;

class Content extends Model
{
    public $updateLog = "";
    public function initialize()
    {
        $this->setSource("mod_lessons_content_progress");


        $this->belongsTo("content_id", "Sysclass\\Models\\Courses\\Contents\\Content", "id",  array('alias' => 'Content'));

    }

    public function beforeValidation() {



    }

    public function createOrUpdate() {
        if (is_null($this->user_id)) {
            $user = $this->getDI()->get("user");
            if ($user) {
                $this->user_id = $user->id;
            } else {
                return false;
            }
        }

        // CHECK IF EXISTS
        $exists = self::findFirst([
            'conditions' => 'user_id = ?0 AND content_id = ?1',
            'bind' => [$this->user_id, $this->content_id]
        ]);
        if ($exists) {
            $this->id = $exists->id;
            return $this->update();
        } else {
            return $this->create();
        }


    }
    /*
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
    */

    public function afterSave() {
        //$evManager = $this->getDI()->get("eventsManager");
        //$evManager->fire("unit:progress", $this, $this->toArray());

        $this->updateLog = $this->updateProgress();
    }

    public function updateProgress() {
        // GET RELATED UNIT, AND CALL AN UPDATE
        // 
        $content = $this->getContent();

        $lessonProgress = UnitProgress::findFirst(array(
            'conditions' => 'user_id = ?0 AND lesson_id = ?1',
            'bind' => array($this->user_id, $content->lesson_id)
        ));

        if (!$lessonProgress) {
            $lessonProgress = new UnitProgress();
            $lessonProgress->user_id = $this->user_id;
            $lessonProgress->lesson_id = $content->lesson_id;
            $lessonProgress->save();
        }

        $messages = $lessonProgress->updateProgress();


        $messages[] = array(
            'type' => 'success',
            'message' => sprintf('Progress for unit #%s for user #%s updated.', $this->id, $this->user_id),
            'status' => true,
            'entity' => 'content',
            'data' => $this->toArray()
        );

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


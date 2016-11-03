<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class UnitContent extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content");

        $this->belongsTo(
            "lesson_id",
            "Sysclass\Models\Content\Unit",
            "id",
            array("alias" => 'Unit')
        );
        
		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Courses\Contents\ContentFile",
            "content_id", "file_id",
            "Sysclass\Models\Dropbox\File",
            "id",
            array('alias' => 'Files')
        );

        $this->hasOne(
            "id",
            "Sysclass\Models\Courses\Contents\Progress",
            "content_id",
            array('alias' => 'Progress')
        );

    }

    public function toFullContentArray() {
        // GRAB FILES AND OTHER INFO
        $item = $this->toArray();
        $files = $this->getFiles(array(
            'conditions' => 'Sysclass\Models\Courses\Contents\ContentFile.active = 1',
            'limit' => '1'
        ));
        $file = $files->getFirst();
        if ($file) {
            $item['file'] = $files->getFirst()->toArray();
        } else {
            $item['file'] = array();
        }
        

        return $item;
    }

    public function getFullTree() {
        $result = $this->toFullContentArray();
        $result['info'] = json_decode($result['info']);
        
        $user_id = $this->getDI()->get("user")->id;

        $progress = $this->getProgress(array(
            'conditions' => "user_id = ?0",
            'bind' => array($user_id)
        ));

        if ($progress) {
            $result['progress'] = $progress->toArray();   
            $result['progress']['factor'] = floatval($result['progress']['factor']);
        } else {
            $result['progress'] = array(
                'factor' => 0
            );
        }

        return $result;
    }
}


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
    }
    public function toFullContentArray() {
        // GRAB FILES AND OTHER INFO
        $item = $this->toArray();
        $files = $this->getFiles(array(
            'conditions' => 'Sysclass\Models\Courses\Contents\ContentFile.active = 1',
            'limit' => '1'
        ));
        $item['file'] = $files->getFirst()->toArray();

        return $item;
    }

    public function getFullTree() {
        $result = $this->toFullContentArray();
        $result['info'] = json_decode($result['info']);
        return $result;
    }
}


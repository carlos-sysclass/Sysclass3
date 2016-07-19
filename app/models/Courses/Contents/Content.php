<?php
namespace Sysclass\Models\Courses\Contents;

use Plico\Mvc\Model;

class Content extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content");

        $this->belongsTo(
            "lesson_id",
            "Sysclass\Models\Courses\Unit",
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
}


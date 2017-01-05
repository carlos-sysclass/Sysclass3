<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class ContentFile extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content_files");

        $this->belongsTo(
            "content_id",
            "Sysclass\Models\Content\UnitContent",
            "id",
            array("alias" => 'Content')
        );

        $this->belongsTo(
            "file_id",
            "Sysclass\Models\Dropbox\File",
            "id",
            array("alias" => 'File')
        );


    }

    public function addOrUpdate() {
        $exists = self::findFirst([
            'conditions' => "file_id = ?0 AND content_id =?1",
            'bind' => [$this->file_id, $this->content_id]
        ]);
        if ($exists) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
   
}


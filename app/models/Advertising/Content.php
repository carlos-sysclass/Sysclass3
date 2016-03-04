<?php
namespace Sysclass\Models\Advertising;

use Plico\Mvc\Model;

class Content extends Model
{
    public function initialize()
    {
        $this->setSource("mod_advertising_content");

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Advertising\\ContentFile",
            "content_id", "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'Files')
        );

    }

    public function afterSave() {
        $identifier = $this->id;

        $type = $this->content_type;
        if (in_array($type, array('file', 'text'))) {
            $classname = sprintf("Sysclass\\Models\\Advertising\\Content%s", ucfirst($type));
            if (class_exists($classname)) {
            //$innerModel = $this->model("advertising/content/" . $type);
                $innerModel = new $classname;

                $fileinfo = json_decode($this->info);

                $innerModel->assign(array(
                    'content_id'    => $identifier,
                    'file_id'       => $fileinfo->id
                ));

                $innerModel->save();
            }
        }
    }
}


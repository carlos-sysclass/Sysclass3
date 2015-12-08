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
    /*
    public function toFullArray($manyAliases = null) {
        var_dump($manyAliases);
        $result = parent::toFullArray($manyAliases);
        print_r($this->getFiles()->toArray());
        print_r($result);
        exit;
    }
    */

}


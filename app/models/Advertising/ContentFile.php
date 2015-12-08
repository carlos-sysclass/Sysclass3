<?php
namespace Sysclass\Models\Advertising;

use Phalcon\Mvc\Model;

class ContentFile extends Model
{
    public function initialize()
    {
        $this->setSource("mod_advertising_content_files");

    }

}


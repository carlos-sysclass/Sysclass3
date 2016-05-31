<?php
namespace Sysclass\Models\Organizations;

use Plico\Mvc\Model;

class Organization extends Model
{
    public function initialize()
    {
        $this->setSource("mod_institution");


        $this->belongsTo("logo_id", "Sysclass\\Models\\Dropbox\\File", "id",  array('alias' => 'logo'));
    }

}

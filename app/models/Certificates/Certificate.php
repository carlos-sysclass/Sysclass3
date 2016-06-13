<?php
namespace Sysclass\Models\Certificates;

use Plico\Mvc\Model;

class Certificate extends Model
{
    public function initialize()
    {
        $this->setSource("mod_certificate");
        
        $this->belongsTo(
            "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array(
                'alias' => 'User',
            )
        );
    }
    
}

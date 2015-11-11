<?php
/**
 * @deprecated 3.1.0 Use Sysclass\Models\Calendar\Event instead
 */
namespace Sysclass\Models\Announcements;

use Phalcon\Mvc\Model;

class Announcement extends Model
{
    public function initialize()
    {
        $this->setSource("mod_news");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
    }

}

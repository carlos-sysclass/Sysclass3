<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Sysclass\Models\Users\User;

class UserAvatar extends Model
{
    public function initialize()
    {
        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));

        $this->belongsTo("file_id", "Sysclass\\Models\\Dropbox\\File", "id",  array('alias' => 'File', 'reusable' => true));
    }
}

<?php
namespace Sysclass\Models\Payments;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\Resource,
    Sysclass\Models\Acl\RolesUsers;

class Payment extends Model
{
    public function initialize()
    {
        $this->setSource("mod_payment");

        $this->hasOne("id", "Sysclass\\Models\\Payments\\PaymentTypes", "payment_id",  array('alias' => 'itens'));        

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'user'));

         //$this->skipAttributesOnCreate(array('active'));

        //$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'group'));
        /*
        $this->belongsTo("language_id", "Sysclass\\Models\\I18n\\Language", "id",  array('alias' => 'language'));

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserAvatar", "user_id",  array('alias' => 'avatar'));

        $this->hasMany("id", "Sysclass\\Models\\Users\\Settings", "user_id",  array('alias' => 'settings'));

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Enrollments\\CourseUsers",
            "user_id", "course_id",
            "Sysclass\\Models\\Courses\\Course",
            "id",
            array('alias' => 'Courses')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Users\\UserAvatar",
            "user_id", "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'Avatars', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesUsers",
            "user_id", "role_id",
            "Sysclass\Models\Acl\Role",
            "id",
            array('alias' => 'UserRoles', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Users\UsersGroups",
            "user_id", "group_id",
            "Sysclass\Models\Users\Group",
            "id",
            array('alias' => 'UserGroups')
        );
        */

    }
}

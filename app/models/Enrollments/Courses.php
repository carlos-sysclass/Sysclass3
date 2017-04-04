<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User,
    Sysclass\Models\Enrollments\CoursesRestrictGroups,
    Phalcon\Db\Column,
    Phalcon\Mvc\Model\MetaData,
    Phalcon\Mvc\Model\Message as Message;

class Courses extends Model
{
    protected $assignedData = null;

    public function initialize()
    {
        $this->setSource("mod_enroll_courses");

        $this->belongsTo("enroll_id", "Sysclass\\Models\\Enrollments\\Enroll", "id",  array('alias' => 'Enroll'));
        $this->belongsTo("course_id", "Sysclass\\Models\\Content\\Program", "id",  array('alias' => 'Course'));

        $this->hasMany("id", "Sysclass\\Models\\Enrollments\\CoursesRestrictGroups", "enroll_course_id",  array('alias' => 'Enrollgroups'));

        $this->hasManyToMany(
            "id", 
            "Sysclass\\Models\\Enrollments\\CoursesRestrictGroups", 
            "enroll_course_id", "group_id",
            "Sysclass\\Models\\Users\\Group", 
            "id",
            ['alias' => 'Groups']
        );
    }

    public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
        $this->assignedData = $data;

        return parent::assign($data, $dataColumnMap, $whiteList);
    }

    public function afterSave() {
        // SAVE THE LINKED TEST
        if (array_key_exists('enrollgroups', $this->assignedData) && is_array($this->assignedData['enrollgroups'])) {

            $this->getEnrollgroups()->delete();

            foreach ($this->assignedData['enrollgroups'] as $group) {
                $restrictGroup = new CoursesRestrictGroups();
                $restrictGroup->assign([
                    'group_id' => $group['group_id']
                ]);
                $restrictGroup->enroll_course_id = $this->id;
                $restrictGroup->save();
            }
        }
    }

    public function isAvaliable(User $user = null) {
        if ($this->signup_active) {
            if (is_null($user)) {
                $user = $this->getDI()->get('user');
            }
            if (!$user) {
                return false;
            }

            $groups =  $this->getGroups();

            if ($groups->count() == 0) {
                return true;
            }

            foreach($groups as $group) {
                if ($group->hasUser($user)) {
                    return true;
                }
            }
        }
        return false;
    }

    /*
    public function userCanEnroll($user_id, $course_id) {
        $depinj = DI::getDefault();
        $translator = $depinj->get("translate");

        if (!$this->signup_active) {
            $message = new Message(
                $translator->translate("This enrollment options ins not available"
                ),
                null,
                "warning"
            );

            $this->appendMessage($message);

            return false;
        }

    }
    */
    /*
    public function metaData()
    {
        return array(
            // Every column in the mapped table
            MetaData::MODELS_ATTRIBUTES => array(
                'id', 'enroll_id', 'course_id'
            ),

            // Every column part of the primary key
            MetaData::MODELS_PRIMARY_KEY => array(
                'enroll_id', 'course_id'
            ),

            // Every column that isn't part of the primary key
            MetaData::MODELS_NON_PRIMARY_KEY => array(
                'id'
            ),

            // Every column that doesn't allows null values
            MetaData::MODELS_NOT_NULL => array(
                'enroll_id', 'course_id'
            ),

            // Every column and their data types
            MetaData::MODELS_DATA_TYPES => array(
                'id'   => Column::TYPE_INTEGER,
                'enroll_id' => Column::TYPE_INTEGER,
                'course_id' => Column::TYPE_INTEGER
            ),

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => array(
                'id'   => true,
                'enroll_id' => true,
                'course_id' => true
            ),

            // The identity column, use boolean false if the model doesn't have
            // an identity column
            MetaData::MODELS_IDENTITY_COLUMN => false,

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => array(
                'id'   => Column::BIND_PARAM_INT,
                'enroll_id' => Column::BIND_PARAM_INT,
                'course_id' => Column::BIND_PARAM_INT
            ),

            // Fields that must be ignored from INSERT SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => array(
            ),

            // Fields that must be ignored from UPDATE SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => array(
                'id'
            ),

            // Default values for columns
            MetaData::MODELS_DEFAULT_VALUES => array(
            ),

            // Fields that allow empty strings
            MetaData::MODELS_EMPTY_STRING_VALUES => array(
            )
        );
    }
    */
    /*
    public function beforeValidationOnCreate() {
        if (is_null($this->token)) {
            $random = new \Phalcon\Security\Random();
            $this->token = $random->uuid();
        }
        
        $count = self::count(array(
            'conditions' => "user_id = ?0 AND course_id = ?1",
            'bind' => array($this->user_id, $this->course_id)
        ));
        if ($count > 0) {
            $message = new Message(
                "Already registered. Please, try again.",
                null,
                "warning"
            );
            $this->appendMessage($message);
        }
        return $count == 0;

    }

    public function enroll() {

    }
    */
}

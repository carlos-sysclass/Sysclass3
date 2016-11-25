<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\Db\Adapter\Pdo,
    Phalcon\Mvc\Model\Message as Message;

class Fields extends Model
{
    protected static $_translateFields = array(
        'label'
    );

    public function initialize()
    {
        $this->setSource("mod_enroll_fields");

        $this->belongsTo("enroll_id", "Sysclass\\Models\\Enrollments\\Enroll", "id",  array('alias' => 'Enroll'));

        $this->belongsTo("field_id", "Sysclass\\Models\\Forms\\Fields", "id",  array('alias' => 'Field'));

        $this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\FieldsOptions",
            "enroll_field_id",
            array('alias' => 'Options')
        );
    }

    public function toArray() {
    	return $this->toAdditionalArray(array('Field', 'Options'), parent::toArray());
    }

    public static function setOrder($enroll_id, $order) {
        $di = \Phalcon\DI::getDefault();

        $db = $di->get('db');
        //$db->begin();

        $sql = "UPDATE mod_enroll_fields 
            SET position = :position WHERE enroll_id = :enroll_id AND id = :field_id";
        $statement = $db->prepare($sql);

        $status = array();

        foreach($order as $position => $field_id) {

            $status[] = $db->executePrepared($statement, array(
                'position' => $position+1,
                'enroll_id' => $enroll_id,
                'field_id' => $field_id
            ), array(
                \PDO::PARAM_INT,
                \PDO::PARAM_INT,
                \PDO::PARAM_INT
            ));

        }

        //$db->commit();

        return $status;

    }

}

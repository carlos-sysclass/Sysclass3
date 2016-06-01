<?php
namespace Sysclass\Models\Courses\Questions;

use Plico\Mvc\Model;

class Question extends Model
{
    public function initialize()
    {
        $this->setSource("mod_questions");

        $this->belongsTo("area_id", "Sysclass\\Models\\Courses\\Departament", "id",  array('alias' => 'Departament'));

        $this->belongsTo("type_id", "Sysclass\\Models\\Courses\\Questions\\Type", "id",  array('alias' => 'Type'));
        
        $this->belongsTo("difficulty_id", "Sysclass\\Models\\Courses\\Questions\\Difficulty", "id",  array('alias' => 'Difficulty'));

    }

    public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
        if (array_key_exists($data['type_id'], $data) && is_array($data[$data['type_id']])) {
            $data = array_merge($data, $data[$data['type_id']]);
        }
        // ENCODE 'JSONED' FIELDS
        $data['options'] = json_encode($data['options']);
        return parent::assign($data, $dataColumnMap, $whiteList);
    }

    public function toArray() {
    	$this->options = json_decode($this->options);

		return parent::toArray();
    }

}

<?php
namespace Sysclass\Models\Courses\Questions;

use Plico\Mvc\Model;

class Question extends Model {
	public function initialize() {
		$this->setSource("mod_questions");

		$this->belongsTo("area_id", "Sysclass\\Models\\Content\\Department", "id", array('alias' => 'Department', 'reusable' => true));

		$this->belongsTo("type_id", "Sysclass\\Models\\Courses\\Questions\\Type", "id", array('alias' => 'Type', 'reusable' => true));

		$this->belongsTo("difficulty_id", "Sysclass\\Models\\Courses\\Questions\\Difficulty", "id", array('alias' => 'Difficulty', 'reusable' => true));

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
		if (!is_array($this->options)) {
			$this->options = json_decode($this->options, true);
		}
		return $this->toFullArray(array(
			'Department',
			'Type',
			'Difficulty',
		), parent::toArray());
	}

	public function shuffleOptions() {
		if ($this->type_id == "simple_choice" || $this->type_id == "multiple_choice") {
			if (!is_array($this->options)) {
				$this->options = json_decode($this->options, true);
			}

			shuffle($this->options);
		}
		return $this;
	}

}

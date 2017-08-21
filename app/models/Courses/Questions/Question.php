<?php
namespace Sysclass\Models\Courses\Questions;

use Plico\Mvc\Model;
use Sysclass\Models\Content\Questions\QuestionFile;

class Question extends Model {

	protected $assignedData = null;
	public function initialize() {
		$this->setSource("mod_questions");

		$this->belongsTo("area_id", "Sysclass\\Models\\Content\\Department", "id", array('alias' => 'Department', 'reusable' => true));

		$this->belongsTo("type_id", "Sysclass\\Models\\Courses\\Questions\\Type", "id", array('alias' => 'Type', 'reusable' => true));

		$this->belongsTo("difficulty_id", "Sysclass\\Models\\Courses\\Questions\\Difficulty", "id", array('alias' => 'Difficulty', 'reusable' => true));

		$this->hasMany(
			"id",
			"Sysclass\Models\Content\Questions\QuestionFile",
			"question_id",
			['alias' => 'QuestionFiles']
		);

		$this->hasManyToMany(
			"id",
			"Sysclass\Models\Content\Questions\QuestionFile",
			"question_id", "file_id",
			"Sysclass\Models\Dropbox\File",
			"id",
			array('alias' => 'Files')
		);

	}

	public function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL) {
		if (array_key_exists($data['type_id'], $data) && is_array($data[$data['type_id']])) {
			$data = array_merge($data, $data[$data['type_id']]);
		}
		// ENCODE 'JSONED' FIELDS
		$data['options'] = json_encode($data['options']);

		$this->assignedData = $data;

		return parent::assign($data, $dataColumnMap, $whiteList);
	}

	public function afterSave() {
		// SAVE THE LINKED TEST
		if (array_key_exists('files', $this->assignedData) && is_array($this->assignedData['files'])) {

			$ids = [];

			foreach ($this->assignedData['files'] as $file) {
				$questionFileModel = new QuestionFile();

				$questionFileModel->assign([
					'question_id' => $this->id,
					'file_id' => $file['id'],
					'active' => 1,
				]);

				$questionFileModel->addOrUpdate($file);

				$ids[] = $file['id'];
			}
			foreach ($this->getQuestionFiles() as $questionFile) {
				if (!in_array($questionFile->file_id, $ids)) {
					$questionFile->delete();
				}
			}
		}
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

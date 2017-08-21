<?php
namespace Sysclass\Models\Content\Questions;

use Plico\Mvc\Model;

class QuestionFile extends Model {
	public function initialize() {
		$this->setSource("mod_questions_files");

		$this->belongsTo(
			"question_id",
			"Sysclass\Models\Courses\Questions\Questions",
			"id",
			array("alias" => 'Question')
		);

		$this->belongsTo(
			"file_id",
			"Sysclass\Models\Dropbox\File",
			"id",
			array("alias" => 'File')
		);

	}

	public function addOrUpdate($data) {
		$exists = self::findFirst([
			'conditions' => "file_id = ?0 AND question_id =?1",
			'bind' => [$this->file_id, $this->question_id],
		]);
		if ($exists) {
			$status = $this->update();
		} else {
			$status = $this->create();
		}

		// UPDATE LOCALE FILE DATA, IF NEEDED
		if ($data['locale_code']) {
			$file = $this->getFile();
			if ($file->locale_code != $data['locale_code']) {
				$file->locale_code = $data['locale_code'];
				$file->save();
			}
		}
		return $status;
	}

}

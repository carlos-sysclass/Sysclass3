<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class ProgramPrice extends Model {
	public function initialize() {
		$this->setSource("mod_programs_prices");

		$this->belongsTo(
			"program_id",
			"Sysclass\Models\Content\Program",
			"id",
			array(
				'alias' => 'Program',
			)
		);
	}
}

<?php
/**
 * @deprecated
 */
class CourseSeasonsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		/*
		$this->table_name = "lessons";
		$this->id_field = "id";
		$this->mainTablePrefix = "l";

		$this->selectSql = sprintf('
			SELECT %1$s.id, lc.courses_ID, %1$s.permission_access_mode, %1$s.name, %1$s.created, %1$s.archive, %1$s.active, previous_lessons_ID
			FROM lessons %1$s
			LEFT OUTER JOIN lessons_to_courses lc ON (%1$s.id = lc.lessons_ID)
			LEFT OUTER JOIN courses c ON (lc.courses_ID = c.id)
		', $this->mainTablePrefix);

		// TODO REVIEW THESE FIELDS, BASECAUSE THEY WILL REDIRECTED TO ANOTHER MODULES/MODELS
		$this->fieldsMap = array(
			'course_id'			=> 'lc.courses_ID',
			'prev_lesson_id'	=> 'lc.previous_lessons_ID'
		);

		$this->order = array();
		*/
 		parent::init();
	}
	public function getItems() {
		return array(
			array(
				"id"						=> "1",
				"permission_access_mode"	=> "3",
				"name"						=> "Semester #1",
				"created"					=> "1339705411",
				"archive"					=> "0",
				"active"					=> "1",
				"course_id"					=> "47",
				"classes"					=> array("188"),
				'max_classes'				=> 4
			),
			array(
				"id"						=> "2",
				"permission_access_mode"	=> "4",
				"name"						=> "Semester #2",
				"created"					=> "1339705411",
				"archive"					=> "0",
				"active"					=> "1",
				"course_id"					=> "47",
				"classes"					=> array(),
				'max_classes'				=> 4
			),
			array(
				"id"						=> "3",
				"permission_access_mode"	=> "4",
				"name"						=> "Semester #3",
				"created"					=> "1339705411",
				"archive"					=> "0",
				"active"					=> "1",
				"course_id"					=> "47",
				"classes"					=> array("190"),
				'max_classes'				=> 4
			),
		);
	}
}

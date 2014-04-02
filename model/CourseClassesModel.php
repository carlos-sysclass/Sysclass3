<?php 
/**
  * This must be used as a "proxy design pattern" to libraries/lesson.class and some parts of libraries/user.class and libraries/course.class
 */
class CourseClassesModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
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
		/*
		l.ies_id,   
		l.info,
		l.price, 
		l.show_catalog, 
		l.directions_ID, 
		l.options, 
		l.metadata, 
		l.course_only, 
		l.certificate, 
		l.instance_source,
		l.originating_course
		l.max_users, 
		l.publish, 
		l.metadata,
		l.from_timestamp,
		l.to_timestamp, 
		l.share_folder, 
		l.shift, 
		l.languages_NAME, 
		l.duration, 
		*/
		$this->fieldsMap = array(
			'course_id'			=> 'lc.courses_ID',
			'prev_lesson_id'	=> 'lc.previous_lessons_ID'
		);

		$this->order = array();
			 
 		parent::init();
	}
	public function getItems() {
		$items = parent::getItems();
		// APPLY SORT BASED ON prev_lesson_id
		// var_dump($items);
    	$previous = 0; //Previous is only used when no previous_lessons_ID is set
    	$courseLessons = $previousValues = array();
    	foreach ($items as $value) {
    		$courseLessons[$value['id']] = $value;
    		$previousValues[$value['id']] = $value['prev_lesson_id'];
    		$value['prev_lesson_id'] !== false ? $previousLessons[$value['prev_lesson_id']] = $value : $previousLessons[$previous] = $value;
    		$previous = $value['id'];
    	}

    	if (array_sum($previousValues)) { //The special case where all previous values are 0, which is checked by array_sum, means that there is no specific ordering
    		//Sorting algorithm, based on previous_lessons_ID. The algorithm is copied from MagesterContentTree :: reset() and is the same with the one applied for content. It is also used in questions order
    		$node = $count = 0;
    		$nodes = array(); //$count is used to prevent infinite loops
    		while (sizeof($previousLessons) > 0 && isset($previousLessons[$node]) && $count++ < 1000) {
    			$nodes[$previousLessons[$node]['id']] = $previousLessons[$node];
    			$newNode = $previousLessons[$node]['id'];
    			unset($previousLessons[$node]);
    			$node = $newNode;
    		}
    	} else {
    		$nodes = $items;
    	}

		return $nodes;
	}
}

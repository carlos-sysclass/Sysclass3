<?php
/**
 * @deprecated
 */
class CourseLessonsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function getItem($id) {
		return new MagesterLesson($id);
	}
}

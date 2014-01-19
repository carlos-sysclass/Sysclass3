<?php 
class CourseCourseModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function getItem($id) {
		return new MagesterCourse($id);
	}

}

<?php
/**
 * @deprecated
 */
class CourseUnitsModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function getItem($id) {
		return new MagesterUnit($id);
	}
}

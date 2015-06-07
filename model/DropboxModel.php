<?php
/**
 * Dropbox Model File
 * @filesource
 */
/**
 * Provides functions to manipulate files in a backend-agnostic way.
 * @package Sysclass\Models
 */
class DropboxModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_dropbox";
        $this->id_field = "id";
        $this->mainTablePrefix = "d";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT
                `id`,
                `upload_type`,
                `name`,
                `type`,
                `size`,
                `url`,
                `active`
            FROM `mod_dropbox` d
		";

        parent::init();

    }
    /**
     * [getFileContents description]
     * @param  array|int $id The file id or file array contents
     * @return string     All the file contents
     * @throws FileBackendNotFoundException
     */
    public function getFileContents($id) {
        if (is_array($id)) {
            $data = $id;
        } else {
            $data = $this->getItem($id);
        }
        /**
         * For now, the backend is hard-coded, but will be a value from database
         * @var string
         */
        $backend = "local";
        //$backend = $data['backend'];

        // THE BACKEND MUST BE LOADED LIKE A SERVICE, A HELPER, A PLUGIN OR A MODULE
        $fileHelper = $this->helper("file/backend/" . $backend);
        if ($fileHelper) {
            return $fileHelper->getFileContents($data);
        } else {
            throw new FileBackendNotFoundException("The file backend {$backend} wasn't found");
        }

    }
/*
    public function addItem($item) {
        $id = parent::addItem($item);
        // INJECT INTO
        $roadmap = $this->model("roadmap/courses/classes/collection");
        if (is_numeric($id) && is_numeric($item['course_id'])) {
            //$roadmap->removeClassInAllCourses($id);
            $roadmap->addClassInCourse($item['course_id'], $id);
        }
        return $id;
    }

    public function setItem($item, $id) {
        $result = parent::setItem($item, $id);
        $roadmap = $this->model("roadmap/courses/classes/collection");

        if (is_numeric($id) && is_numeric($item['course_id'])) {
            //$roadmap->removeClassInAllCourses($id);
            $roadmap->addClassInCourse($item['course_id'], $id);
        }
        return $result;
    }
*/
}

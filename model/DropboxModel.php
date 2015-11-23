<?php
/**
 * Dropbox Model File
 * @filesource
 */
/**
 * Provides functions to manipulate files in a backend-agnostic way.
 * @package Sysclass\Models
 */
class DropboxModel extends AbstractSysclassModel implements ISyncronizableModel
{
    public function init()
    {
        $this->table_name = "mod_dropbox";
        $this->id_field = "id";
        $this->mainTablePrefix = "d";
        //$this->fieldsMap = array();

        $this->selectSql = "
        SELECT
        `id`,
        `owner_id`,
        `upload_type`,
        `name`,
        `filename`,
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
    public function getFileContents($identifier)
    {
        if (is_array($identifier)) {
            $data = $identifier;
        } else {
            $data = $this->getItem($identifier);
        }
        /**
         * For now, the backend is hard-coded, but will be a value from database
         * @var string
         */
        $backend = "local";
        //$backend = $data['backend'];

        // THE BACKEND MUST BE LOADED LIKE A SERVICE, A HELPER, A PLUGIN OR A MODULE
        $fileHelper = $this->getBackend($backend);
        return $fileHelper->getFileContents($data);
    }
    /*
    protected function generateRandomFilename()
    {
        $helper = $this->helper("uuid");
        return $helper::get();
    }
    */
    public function createFile($filestream, $template = null)
    {
        if (is_numeric($template)) {
            $data = $this->getItem($template);
        }
        if (is_array($template)) {
            $data = $template;
            $pathinfo = pathinfo($data['name']);
            $data['name']     = $data['name'];
            unset($data['id']);
        } else {
            $data = array(
                'upload_type'   => 'default',
                'name'          => 'file',
                //'filename'      => $this->generateRandomFilename(),
                 // TRY TO DETECT MIME-TYPE
                'type'          => "text/plain"
            );
        }
        /**
         * For now, the backend is hard-coded, but will be a value from database
         * @var string
         */
        $backend = "local";
        $fileHelper = $this->getBackend($backend);
        $fileinfo = $fileHelper->createFile($data, $filestream);

        $fileinfo['id'] = $this->addItem($fileinfo);
        return $fileinfo;
    }

    public function updateFile($filestream, $template = null)
    {
        if (is_numeric($template)) {
            $data = $this->getItem($template);
        }
        if (is_array($template)) {
            $data = $template;
            $pathinfo = pathinfo($data['name']);
            $data['name']     = $data['name'];
            //unset($data['id']);
        } else {
            $data = array(
                'upload_type'   => 'default',
                'name'          => 'file',
                //'filename'      => $this->generateRandomFilename(),
                 // TRY TO DETECT MIME-TYPE
                'type'          => "text/plain"
            );
        }
        /**
         * For now, the backend is hard-coded, but will be a value from database
         * @var string
         */
        $backend = "local";
        $fileHelper = $this->getBackend($backend);
        $fileinfo = $fileHelper->createFile($data, $filestream);

        $id = $this->setItem($fileinfo, $data['id']);

        return $fileinfo;
    }


    public function copyFile($identifier, $dest = null)
    {
        if (is_array($identifier)) {
            $data = $identifier;
        } else {
            $data = $this->getItem($identifier);
        }
        /**
         * For now, the backend is hard-coded, but will be a value from database
         * @var string
         */
        $backend = "local";
        $fileHelper = $this->getBackend($backend);
        return $fileHelper->copyFile($data, $dest);
    }

    protected function getBackend($backend)
    {
        // THE BACKEND MUST BE LOADED LIKE A SERVICE, A HELPER, A PLUGIN OR A MODULE
        $fileHelper = $this->helper("file/backend/" . $backend);
        if ($fileHelper) {
            return $fileHelper;
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

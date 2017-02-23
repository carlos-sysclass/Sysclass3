<?php
/**
 * File Wrapper Helper File
 * @filesource
 */
/**
 * Provides functions to manipulate files in a backend-agnostic way.
 * @package Sysclass\Helpers
 */

class FileWrapperHelper {
    /**
     * Use as Interface Method
     */

    public function getPublicPath($type = null)
    {
        //$plicolib = PlicoLib::instance();

        $di = Phalcon\DI::getDefault();
        $environment = $di->get("environment");

        $path = $environment["path/files/public"] . "/";

        if (!is_null($type)) {
            $path .= $type;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public function getPublicUrl($type = null) {
        //$plicolib = PlicoLib::instance();

        $di = Phalcon\DI::getDefault();
        $environment = $di->get("environment");

        $path = $environment["http/fqdn"] . "/files";

        if (!is_null($type)) {
            $path .= "/" . $type;
        }

        return $path;
    }

    public function getUnitPath($unit_id, $type = null) {
        $plicolib = PlicoLib::instance();

        $path = $plicolib->get("path/files/public") . "/units/" . $unit_id;

        if (!is_null($type)) {
            $path .= "/" . $type;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }
    public function getUnitUrl($unit_id, $type = null) {
        $plicolib = PlicoLib::instance();

        $path = $plicolib->get("http/fqdn") . "/files/units/" . $unit_id;

        if (!is_null($type)) {
            $path .= "/" . $type;
        }

        return $path;
    }

    protected function getUserPath($username) {
        $plicolib = PlicoLib::instance();

        $privatepath = $plicolib->get("path/files/private") . "/users/" . $username;

        if (!is_dir($privatepath)) {
            mkdir($privatepath, 0777, true);
        }
        return $privatepath;
    }

    public function getFullPath($username, $file_name) {
        $file_path = $this->getUserPath($username);
        return $full_name = $file_path . "/" . $file_name;
    }

    public function getFilesize($username, $file_name) {
        $file_path = $this->getUserPath($username, $file_name);
        return filesize($file_path);
    }

    public function uploadObjectByPath($username, $file_name, $file_path) {
        $file_data = file_get_contents($file_path);

        return $this->uploadObjectByData($username, $file_name, $file_data);
    }
    public function uploadObjectByData($username, $file_name, $file_data) {
        // TODO CHECK FOR BACKEND TO SEND THE FILE TO THEM

        $base_name = pathinfo($file_name, PATHINFO_FILENAME);
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        $file_name = uniqid(null, true) . "." . $ext;

        $file_path = $this->getUserPath($username);
        $full_name = $file_path . "/" . $file_name;
        $fh = fopen($full_name, "w");
        fwrite($fh, $file_data);
        fclose($fh);

        chmod($full_name, 0777);

        return array(
            'user'  => $username,
            'file'  => $file_name
        );
    }

    public function listFiles($path) {
        $realpath = realpath($path);

        $plicolib = PlicoLib::instance();
        $privatepath = $plicolib->get("path/files/public");
        $publicpath = $plicolib->get("path/files/private");

        if (strpos($realpath, $privatepath) !== FALSE || strpos($realpath, $publicpath) !== FALSE) {
            return $this->fileTreeToArray($realpath);
        }
        return array();
    }

    protected function fileTreeToArray($dir) {

       $result = array();

       $cdir = scandir($dir);
       foreach ($cdir as $key => $value)
       {
          if (!in_array($value,array(".","..")))
          {
             if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
             {
                $result[$value] = $this->fileTreeToArray($dir . DIRECTORY_SEPARATOR . $value);
             }
             else
             {
                $result[] = $value;
             }
          }
       }

       return $result;
    }

}

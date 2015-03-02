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

}

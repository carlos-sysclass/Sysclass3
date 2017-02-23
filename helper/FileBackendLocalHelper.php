<?php
/**
 * File "Local Backend" Helper File
 * @filesource
 */
/**
 * Provides functions to manipulate files in local backend
 *
 * The interface IFileBackendInterface isn't not created yet, this file will be the based to it.
 * @package Sysclass\Helpers
 */
class FileBackendLocalHelper extends AbstractFileBackend /* implements IFileBackendInterface */
{
    /**
     * Use as Interface Method
     *
     */
    //public function getFileContents($)
    public function getFileContents($fileinfo)
    {
        if ($this->fileExists($fileinfo)) {
            $type = $fileinfo['upload_type'];
            $fullpath = realpath($this->getPublicPath($type) . "/" . $fileinfo['name']);
            return file_get_contents($fullpath);
        }
        return false;
    }
    /**
     * Use as Interface Method
     *
     */
    public function fileExists($fileinfo)
    {
        $type = $fileinfo['upload_type'];
        $fullpath = realpath($this->getPublicPath($type) . "/" . $fileinfo['filename']);
        return file_exists($fullpath);
    }
    /**
     * Use as Interface Method
     *
     */
    public function createFile($fileinfo, $filestream)
    {
        //var_dump($fileinfo, $filestream);
        $pathinfo = pathinfo($data['filename']);
        $suffix = $pathinfo['extension'];
        if (is_null($suffix)) {
            // TRY TO DETECT FROM type (mime-type)
            $suffix = $this->getExtensionFromMimeType($fileinfo['type']);
        }

        $fileinfo['filename'] = @isset($fileinfo['filename']) ? $fileinfo['filename'] : $this->generateRandomFilename($suffix);
        $fileinfo['upload_type'] = @isset($fileinfo['upload_type']) ? $fileinfo['upload_type'] : "default";

        while ($this->fileExists($fileinfo)) {
            $fileinfo['filename'] = $this->generateRandomFilename($suffix);
        };

        $fullname = $this->getPublicPath($fileinfo['upload_type']) . "/" . $fileinfo['filename'];

        $handler = fopen($fullname, "w");
        if (fwrite($handler, $filestream) === false) {
            throw new Exception("File isn't writable", 1);
        }
        fclose($handler);

        //$fileinfo = pathinfo($fullname);
        if (!@isset($fileinfo['type'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $fileinfo = finfo_file($finfo, $fullname);
            $fileinfo['type'] = $fileinfo;
        }
        $fileinfo['size'] = mb_strlen($filestream);

        $fileinfo['url'] = $this->getPublicUrl($fileinfo['upload_type']) . "/" . $fileinfo['filename'];

        return $fileinfo;
    }

    /**
     * Use as Interface Method
     *
     */
    public function copyFile($fileinfo, $dest = null)
    {
        throw new Exception("Function not implemented yet");
        exit;
        $type = $fileinfo['upload_type'];

        $fullpath = realpath($this->getPublicPath($type) . "/" . $fileinfo['name']);
        var_dump($fullpath);
        exit;
        return file_exists($fullpath);
    }


    public function getPublicPath($type = null)
    {
        $di = \Phalcon\DI::getDefault();
        $environment = $di->get("environment");

        $path = $environment["path/files/public"] . "/";

        if (!is_null($type)) {
            $path .= "/" . $type;
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public function getPublicUrl($type = null)
    {
        $plicolib = PlicoLib::instance();

        $path = $plicolib->get("http/fqdn") . "/files";

        if (!is_null($type)) {
            $path .= "/" . $type;
        }

        return $path;
    }

    public function getLessonPath($unit_id, $type = null)
    {
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
    public function getLessonUrl($unit_id, $type = null)
    {
        $plicolib = PlicoLib::instance();

        $path = $plicolib->get("http/fqdn") . "/files/units/" . $unit_id;

        if (!is_null($type)) {
            $path .= "/" . $type;
        }

        return $path;
    }

    protected function getUserPath($username)
    {
        $plicolib = PlicoLib::instance();

        $privatepath = $plicolib->get("path/files/private") . "/users/" . $username;

        if (!is_dir($privatepath)) {
            mkdir($privatepath, 0777, true);
        }
        return $privatepath;
    }

    public function getFullPath($username, $file_name)
    {
        $file_path = $this->getUserPath($username);
        return $full_name = $file_path . "/" . $file_name;
    }

    public function getFilesize($username, $file_name)
    {
        $file_path = $this->getUserPath($username, $file_name);
        return filesize($file_path);
    }

    public function uploadObjectByPath($username, $file_name, $file_path)
    {
        $file_data = file_get_contents($file_path);

        return $this->uploadObjectByData($username, $file_name, $file_data);
    }
    public function uploadObjectByData($username, $file_name, $file_data)
    {
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

    public function listFiles($path)
    {
        $realpath = realpath($path);

        $plicolib = PlicoLib::instance();
        $privatepath = $plicolib->get("path/files/public");
        $publicpath = $plicolib->get("path/files/private");

        if (strpos($realpath, $privatepath) !== false || strpos($realpath, $publicpath) !== false) {
            return $this->fileTreeToArray($realpath);
        }
        return array();
    }

    protected function fileTreeToArray($dir)
    {
        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = $this->fileTreeToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}

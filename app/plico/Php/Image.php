<?php
namespace Plico\Php;

use Phalcon\Mvc\User\Component;

class Image extends Component
{
    protected static $cfg;

    protected static $cache_dir = "images/";

    public function resize($src, $coords, $width, $height) {
        $img_r = \imagecreatefromstring($src);

        //imagejpeg($img_r, "/var/www/sysclass/develop/current/files/image/TESTE.jpeg", 90);

        $dst_r = \imagecreatetruecolor($width, $height);

        \imagecopyresampled(
            $dst_r,
            $img_r,
            0,
            0,
            intval($coords['x']),
            intval($coords['y']),
            $width,
            $height,
            intval($coords['w']),
            intval($coords['h'])
        );

        return $dst_r;
    }

    public function saveAsPng($resource, $file_path, $quality = 9) {
        $file_info = \pathinfo($file_path);
        $full_path = $file_info['dirname'] . "/" . $file_info['filename'] . ".png";
        $result = \imagepng($resource, $full_path, $quality);

        return ($result) ? $full_path : FALSE;
    }

    public function saveAsJpeg($resource, $file_path, $quality = 90) {
        $file_info = \pathinfo($file_path);
        $full_path = $file_info['dirname'] . "/" . $file_info['filename'] . ".jpeg";
        $result = \imagejpeg($resource, $full_path, $quality);

        return ($result) ? $full_path : FALSE;
    }

    public static function getCached($cache_slug, $as_stream) {
        // GET CACHE DIRECTORY FROM CONFIGURATION, SAVE THE FILE AND RETURN
        $environment = \Phalcon\DI::getDefault()->get('environment');
        $cache_dir = $environment['path/cache'] . self::$cache_dir;

        $full_path = $cache_dir . $cache_slug . ".png";

        if (file_exists($full_path)) {
            if ($as_stream) {
                return file_get_contents($full_path);
            } else {
                return $full_path;
            }
        }
    }    

    public function cache($resource, $cache_slug, $as_stream, $type = "png") {
        // GET CACHE DIRECTORY FROM CONFIGURATION, SAVE THE FILE AND RETURN

        $cache_dir = $this->environment['path/cache'] . self::$cache_dir;

        $full_path = $cache_dir . $cache_slug . ".png";

        $result = \imagepng($resource, $full_path, $quality);

        if ($result) {
            return self::getCached($cache_slug, $as_stream);
        }
        return false;
        
    }
    
}
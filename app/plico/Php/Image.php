<?php
namespace Plico\Php;

use Phalcon\Mvc\User\Component;

class Image extends Component
{
    protected static $cfg;

    public function resize($src, $coords, $width, $height) {
        $img_r = imagecreatefromstring($src);

        //imagejpeg($img_r, "/var/www/sysclass/develop/current/files/image/TESTE.jpeg", 90);

        $dst_r = imagecreatetruecolor($width, $height );

        imagecopyresampled(
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
        $file_info = pathinfo($file_path);
        $full_path = $file_info['dirname'] . "/" . $file_info['filename'] . ".png";
        $result = imagepng($resource, $full_path, $quality);

        return ($result) ? $full_path : FALSE;
    }

    public function saveAsJpeg($resource, $file_path, $quality = 90) {
        $file_info = pathinfo($file_path);
        $full_path = $file_info['dirname'] . "/" . $file_info['filename'] . ".jpeg";

        $result = imagejpeg($resource, $full_path, $quality);

        return ($result) ? $full_path : FALSE;
    }
    
    /*
    public function __construct() {
    }
    */
}

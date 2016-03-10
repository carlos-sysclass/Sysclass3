<?php
namespace Sysclass\Models\Advertising;

use Phalcon\Mvc\Model;

class ContentFile extends Model
{
	protected static $imagesizes;
    public function initialize()
    {
        $this->setSource("mod_advertising_content_files");

        $this->belongsTo(
            "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'File')
        );

		self::$imagesizes = array(
			array(728, 90, 728/90), // HORIZONTAL BANNER
			array(300, 250, 300/250), // ALMOST SQUARE BANNER
			array(120, 600, 600/120 * -1) // VERTICAL BANNER
		);
    }
    public function beforeCreate() {
        // RESIZE THE FILE BASED ON ADSENSE TABLE (get the most closer size ratio)
        // 
        $file = $this->getFile();

        $storageService = $this->getDI()->get('storage');

        $imageinfo = $storageService->getImageFileInfo($file);

        $stream = $storageService->getFilestream($file);

        $imagesize = $this->getNearestImageSize($imageinfo['width'], $imageinfo['height']);

        //$imagesize
        //
        $coords = array(
        	'w' => $imageinfo['width'],
        	'h' => $imageinfo['height'],
        	'x' => 0,
        	'y' => 0,
        );

		$image = new \Plico\Php\Image();
        $croped = $image->resize($stream, $coords, $imagesize['width'], $imagesize['height']);

        //var_dump($coords, $imagesize['width'], $imagesize['height']);

        $file_path = $storageService->getFullFilePath($file);
        $file_full_path = $image->saveAsPng($croped, $file_path);
        
        if ($file_full_path) {

            $path_info = pathinfo($file_full_path);

            $file->name = $path_info['basename'];
            $file->filename = $path_info['basename'];
            $file->type = "image/png";

            $file->size = filesize($file_full_path);

            $file->url = $storageService->getFullFileUrl($file);
            $file->save();
        }
    }

	/**
	 * 
	 */
	/**
	 * Calculte the nearest image size based on passed parameters
	 * @param  int $width  Width of the image
	 * @param  int $height height of the image
	 * @return array int         An array containing the width and height
	 */
    protected function getNearestImageSize($width, $height) {
    	if ($width > $height) {
    		$imageratio = $width / $height;
    	} else {
    		$imageratio = $height / $width * -1;
    	}

    	foreach(self::$imagesizes as $size) {
    		$ratio = $size[2];
    		//var_dump($imageratio, $ratio);
    		$diffs[] = abs($imageratio - $ratio);
    	}

    	//var_dump(array_keys($diffs, min($diffs)));
    	$minvalue = min($diffs);
    	$index = array_search($minvalue, $diffs);

    	return array(
    		'width' => self::$imagesizes[$index][0],
    		'height' => self::$imagesizes[$index][1]
    	);
    }

}


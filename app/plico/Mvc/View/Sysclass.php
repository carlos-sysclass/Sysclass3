<?php
namespace Plico\Mvc\View;

//use \Phalcon\Mvc\View;
use \Phalcon\Mvc\View\Simple as View;

class Sysclass extends View
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setVar($key, $value, $nocache = false)
    {
        $this->_viewParams[$key] = $value;
        $this->_viewParams["_" . $key] = $nocache;
    }

	public function render($path, $params) {
        if (strpos($path, "/") === 0) {
            $pathinfo = pathinfo($path);

            $this->setViewsDir($pathinfo['dirname'] . "/");
            $path = $pathinfo['basename'];
        } else {

            //var_dump($this->getViewsDir() . $path);
            //exit;
        }

        return parent::render($path, $params);
	}

}

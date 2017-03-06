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
    /*
    public function setVar($key, $value, $nocache = false)
    {
        $this->_viewParams[$key] = $value;
        $this->_viewParams["_" . $key] = $nocache;
    }
    */
	public function render($path, $params) {
        if (strpos($path, "/") === 0) {
            $pathinfo = pathinfo($path);

            $this->setViewsDir($pathinfo['dirname'] . "/");
            $path = $pathinfo['basename'];
        } else {

            //var_dump($this->getViewsDir() . $path);
            //exit;
        }

        // GETTING FLASH MESSAGES
        $flashSession = $this->getDI()->get("flashSession");
        $messages = $flashSession->getMessages(null, true);
        $flashSession->clear();


        $params['messages'] = array();
        foreach($messages as $type => $items) {
            foreach($items as $msg) {
                $params['messages'][$type] = $msg;
            }
        }



        return parent::render($path, $params);
	}

    public function exists($path) {
        if (strpos($path, "/") === 0) {
            $pathinfo = pathinfo($path);

            $this->setViewsDir($pathinfo['dirname'] . "/");
            $path = $pathinfo['basename'];
        } else {

            //var_dump($this->getViewsDir() . $path);
            //exit;
        }

        $full_path = $this->getViewsDir() . $path;

        return file_exists($full_path);
    }

}

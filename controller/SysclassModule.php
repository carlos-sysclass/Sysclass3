<?php 
abstract class SysclassModule extends AbstractSysclassController
{   
    protected $module_id = null;
    protected $module_folder = null;
    public function __construct() {
        
    }
    
    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="")
    {
        $plico = PlicoLib::instance();
        $class_name = get_class($this);
        $this->module_id = strtolower(str_replace("Module", "", $class_name));
        $this->module_folder = $plico->get("path/modules") . $this->module_id;

        $baseUrl = $plico->get('module/base_path') . "/" . $this->module_id;
        if (is_null($url)) {
            $url = $baseUrl;
        }
        if (empty($basePath)) {
            $basePath = $baseUrl . "/";
        }
        parent::init($url, $method, $format, $root, $basePath);
    }
    protected function putModuleScript($script)
    {
        return parent::putModuleScript($this->getBasePath() . "js/" . $script);

    }
    /**
     * Module Entry Point
     *
     * @url GET /js
     * @url GET /js/:filename
     */
    public function jsWrapperAction($filename = null)
    {
        if ($filename == 0 || $this->getRequestedFormat() == RestFormat::JAVASCRIPT) {
            header("Content-Type: application/javascript");
            if ($filename === 0) {
                $filename = $this->module_id;
            }

            $jsFileName = $this->module_folder . "/scripts/" . $filename . ".js";
            if (file_exists($jsFileName)) {
                echo file_get_contents($jsFileName);
            }
        }
        exit;
    }

    protected function display($template=NULL)
    {
        return parent::display($this->template($template));
    }
    protected function template($template=NULL) {
        return $this->module_folder . "/templates/" . $template;
    }
    protected function beforeDisplay() {
        parent::beforeDisplay();

        $this->putItem("module_basepath", $this->getBasePath());
        $this->putItem("module_fullpath", $this->module_folder);
        $this->putItem("module_tplpath", $this->module_folder. "/templates/");
    }

}

<?php 
abstract class AbstractModule extends PageController
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
    public function putModuleScript($script, $data = null)
    {
        if (!is_null($data)) {
            $this->setCache($script, $data);
        }
        return parent::putModuleScript($this->getBasePath() . "js/" . $script);
    }
    protected function putCrossModuleScript($module, $script)
    {
        return parent::putModuleScript($this->module($module)->getBasePath() . "js/" . $script);

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
                $sendData = $this->getCache($filename);

                if (!is_null($sendData)) {
                    $var_name = str_replace(".", "_", $filename);
                    echo sprintf("var %s = %s;\n", $var_name, json_encode($sendData));
                }

                echo file_get_contents($jsFileName);
            }
        }
        exit;
    }

    protected function baseDisplay($template=NULL)
    {
        return parent::display($template);
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

    public function putSectionTemplate($key, $tpl) {
        return parent::putSectionTemplate($key, $this->template($tpl));
    }
    
    protected function putCrossSectionTemplate($module, $key, $tpl)
    {
        if (is_null($key)) {
            $key = $module;
        }
        return parent::putSectionTemplate($key, $this->module($module)->template($tpl));
    }

}

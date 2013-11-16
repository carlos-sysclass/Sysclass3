<?php 
abstract class SysclassModule extends AbstractSysclassController
{   
    protected $module_folder = null;
    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="")
    {
        $plico = PlicoLib::instance();
        $class_name = get_class($this);
        $this->module_folder = $plico->get("path/modules") .  strtolower(str_replace("Module", "", $class_name));

        $baseUrl = $plico->get('module/base_path') . "/" . strtolower(str_replace("Module", "", $class_name));
        if (is_null($url)) {
            $url = $baseUrl;
        }
        if (empty($basePath)) {
            $basePath = $baseUrl;
        }

        parent::init($url, $method, $format, $root, $basePath);
    }
    /**
     * Module Entry Point
     *
     * @url GET /
     */
    public function defaultPage()
    {
        // DASHBOARD PAGE
        //require_once 'control_panel.php';
        //$this->putScript("scripts/portlet-draggable");
        $this->display('default.tpl');
    }
    protected function display($template=NULL)
    {
        return parent::display($this->template($template));
    }
    protected function template($template=NULL) {
        return $this->module_folder . "/templates/" . $template;
    }


}

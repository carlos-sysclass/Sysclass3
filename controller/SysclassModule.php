<?php 
abstract class SysclassModule extends AbstractSysclassController
{   
    protected $module_folder = null;
    public function init($url, $method, $format, $root=NULL, $basePath="")
    {
        parent::init($url, $method, $format, $root, $basePath);

        $plico = PlicoLib::instance();
        $class_name = get_class($this);
        $this->module_folder = $plico->get("path/modules") .  strtolower(str_replace("Module", "", $class_name));
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
        $template = $this->module_folder . "/templates/" . $template;
        return parent::display($template);
    }


}

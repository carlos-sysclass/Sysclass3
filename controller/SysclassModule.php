<?php
abstract class SysclassModule extends BaseSysclassModule
{
    protected $module_id = null;
    protected $module_folder = null;
    protected $module_request;
    public function __construct() {

    }
    /*
    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="", $urlMatch = null)
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

        $this->module_request = str_replace("module/" . $this->module_id . "/", "", $url);

        $urlMatch = str_replace("module/" . $this->module_id . "/", "", $urlMatch);

        parent::init($url, $method, $format, $root, $basePath, $urlMatch);

        $this->context['module_id']         = $this->module_id;
        $this->context['module_request']    = $this->module_request;
        $this->context['module_folder']     = $this->module_folder;
    }
    */


}

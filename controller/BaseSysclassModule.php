<?php
abstract class BaseSysclassModule extends AbstractSysclassController
{
    protected $module_id = null;
    protected $module_folder = null;
    protected $module_request;

    protected $clientContext;

    public function __construct() {

    }
    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="", $urlMatch = null)
    {
        $plico = PlicoLib::instance();
        $class_name = get_class($this);
        $this->module_id = $plico->CamelDiscasefying(str_replace("Module", "", $class_name));
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

        //$this->createContext($this->module_id);
    }

    protected function createContext($module_id = null) {
        $plico = PlicoLib::instance();

        if (is_null($module_id)) {
            $class_name = get_class($this);
            $this->module_id = strtolower(str_replace("Module", "", $class_name));
        } else {
            $this->module_id = $module_id;
        }

        $this->module_folder = $plico->get("path/modules") . $this->module_id;

        $this->module_request = str_replace($this->getBasePath(), "", $this->context['urlMatch']);

        $urlMatch = str_replace("module/" . $this->module_id . "/", "", $urlMatch);

        $this->context['module_id']         = $this->module_id;
        $this->context['module_request']    = $this->module_request;
        $this->context['module_folder']     = $this->module_folder;

        $this->context['basePath'] = sprintf("/module/%s/", $this->module_id);

        $this->module_request = str_replace($this->getBasePath(), "", $this->context['module_request']);
        $this->context['module_request']    = $this->module_request;

        $this->loadConfigFile();

    }

    protected function loadConfigFile() {
        $filename = $this->getModuleFolder() . "/config.yml";

        $this->context['*config*'] = null;

        if (file_exists($filename)) {
            $this->context['*config*'] = yaml_parse_file($filename);
        }

    }

    public function getConfig($path = null) {
        if (is_null($path)) {
            return $this->context['*config*'];
        }
        $data = $this->context['*config*'];

        $keys = explode("\\", $path);

        foreach($keys as $key) {
            $data = $data[$key];
        }
        return $data;

    }

    protected function injectObjects($page = null, $override_route = null) {
        if (is_null($override_route)) {
            $override_route = $this->getMatchedUrl();
        }

        $config = $this->getConfig("crud\\routes\\" . $override_route);
        $baseconfig = $this->getConfig('crud\base_route');

        $config = array_replace_recursive($baseconfig, $config);

        // PROCESS ENTRIES
        foreach($config['components'] as $component) {
            $this->putComponent($component);
        }

        foreach($config['blocks'] as $block) {
            $this->putBlock($block);
        }

        if (!is_null($page)) {
            $this->setCache("crud_config/" . $this->module_id, $this->clientContext);
            $this->putModuleScript("scripts/crud.config", null, null);
            $this->putModuleScript("scripts/crud.models", null, null);
            $this->putModuleScript("scripts/crud.view." . $page, null, null);
        }


        foreach($config['stylesheets'] as $stylesheet) {
            $this->putCss($stylesheet);
        }

        foreach($config['base_scripts'] as $script) {
            $this->putScript($script);
        }

        foreach($config['scripts'] as $script) {
            $this->putModuleScript($script);
        }

        $this->putItem("module_id", $this->module_id);

        foreach($config['variables'] as $name => $value) {
            $this->putItem($name, self::$t->translate($value));
        }

        $this->putItem("module_context", $config['context']);

        $this->template = $config['template'];

        //$this->clientContext = $config;
    }

    protected function createClientContext($operation, $data = null, $override_route = null) {
        if (is_null($override_route)) {
            $override_route = $this->getMatchedUrl();
        }
        $config = $this->getConfig("crud\\routes\\" . $override_route);

        if ($config === FALSE) {
            return false;
        }


        $this->clientContext = $config['context'];
        $this->clientContext['module_id'] = $this->context['module_id'];
        $this->clientContext['route'] = $this->getMatchedUrl();
        $this->clientContext['override_route'] = $override_route;

        if (array_key_exists("model-prefix", $config)) {
            $this->clientContext['model-prefix'] = $config['model-prefix'];
        }
        if (is_array($data)) {
            $this->clientContext = array_replace_recursive($this->clientContext, $data);
        }

        if (array_key_exists("override-route", $config)) {
            $this->injectObjects($config["override-route"]);
        } else {
            $this->injectObjects($operation, $override_route);
        }

        //$this->injectObjects($operation);

        return true;
    }


    public function getModuleFolder() {
        return $this->getContext('module_folder');
    }
    public function getModuleRequest() {
        return $this->getContext('module_request');
    }


    public function putModuleScript($script, $data = null, $path_prefix = "js/")
    {
        if (!is_null($data)) {
            $this->setCache($script, $data);
        }
        return parent::putModuleScript($this->getBasePath() . $path_prefix . $script);
    }
    protected function putCrossModuleScript($module, $script)
    {
        return parent::putModuleScript($this->module($module)->getBasePath() . "js/" . $script);

    }
    /**
     * [ add a description ]
     *
     * @url GET /js
     * @url GET /js/:filename
     */
    public function jsWrapperAction($module, $filename = null)
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

    /**
     * [ add a description ]
     *
     * @url GET /scripts/:filename
     */
    public function jsCrudWrapperAction($module, $filename = null)
    {
        $cacheKey = "crud_config/" . $this->module_id;

        $plicolib = PlicoLib::instance();

        //$this->createContext($module);
        if ($this->getRequestedFormat() == RestFormat::JAVASCRIPT) {
            header("Content-Type: application/javascript");

            $jsFileName = $plicolib->get("path/app/www") . "/assets/default/scripts/" . $filename . ".js";
            if (file_exists($jsFileName)) {
                $sendData = $this->getCache($cacheKey);

                if (!is_null($sendData)) {
                    $var_name = "crud_config";
                    echo sprintf("var %s = %s;\n", $var_name, json_encode($sendData));

                    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                    header("Cache-Control: post-check=0, pre-check=0", false);
                    header("Pragma: no-cache");
                }
                echo file_get_contents($jsFileName);
            }
        }
        exit;
    }

    protected function display($template=NULL)
    {
        return parent::display($this->template($template));
    }
    protected function fetch($template=NULL)
    {
        return parent::fetch($this->template($template));
    }
    protected function template($template=NULL, $suffix = false) {
        return $this->module_folder . "/templates/" . $template . (($suffix) ? '.tpl' : '');
    }
    protected function beforeDisplay() {
        parent::beforeDisplay();

        $this->putItem("module_basepath", $this->getBasePath());
        $this->putItem("module_fullpath", $this->module_folder);
        $this->putItem("module_tplpath", $this->module_folder. "/templates/");
    }

    public function putSectionTemplate($key, $tpl) {
        if (is_null($key)) {
            $key = $this->module_id;
        }
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

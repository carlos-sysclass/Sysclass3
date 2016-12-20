<?php
//use Phalcon\Mvc\View\Simple as View;
use Plico\Mvc\View\Sysclass as View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\View\Engine\Smarty as SmartyEngine;

$di->set('view', function() use ($environment, $di) {

    $view = new View();

    $view->setDI($di);

    $view->setViewsDir(REAL_PATH . '/themes/default/templates/');

    $view->registerEngines(array(
        '.tpl' => function($view, $di) use ($environment) {

            $smarty = new SmartyEngine($view, $di);

            $smarty->setOptions(array(
                'force_compile'     => true,
                'debugging'         => true,
                'caching'           => false,
                'cache_lifetime'    => 120,
                //'template_dir'      => $view->getViewsDir(),
                //'compile_dir'       => __DIR__ . "/../../cache/view/smarty/compiled",
                'error_reporting'   => ($environment->run->debug) ? error_reporting() ^ E_WARNING : error_reporting(),
                'escape_html'       => true,
                //'_file_perms'       => 0666,
                //'_dir_perms'        => 0777,
                'compile_check'     => true,
            ));


            $themesPath = array(
                REAL_PATH . "/themes/"
            );
            
            if (!is_null($environment->view->theme)) {
                $system_theme = $environment->view->theme;
            }

            $smartyTemplates = array();
            $smartyPlugins = array();

            //$smartyPlugins[] = $plico->get('path/lib') . "smarty/plugins/";

            foreach ($themesPath as $theme) {
                if (isset($system_theme)) {
                    $smartyTemplates[]  = sprintf($theme . '%s/templates/', $system_theme);    
                    $smartyPlugins[]    = sprintf($theme . '%s/plugins/', $system_theme);
                }
                $smartyTemplates[]  = $theme . 'default/templates/';
                $smartyPlugins[]    = $theme . 'default/plugins/';
            }

            $smarty->getSmarty()->setTemplateDir($smartyTemplates);
            $smarty->getSmarty()->setPluginsDir($smartyPlugins);

            $smarty->getSmarty()->setCompileDir(__DIR__ . "/../../cache/view/smarty/compiled");
            $smarty->getSmarty()->setCacheDir(__DIR__ . "/../../cache/view/smarty/cache");

            return $smarty;
        },
        '.email' => function ($view, $di) {
            $volt = new VoltEngine($view, $di);
            
            $volt->setOptions(array(
                'compiledPath' => __DIR__ . "/../../cache/view/volt/compiled/",
                //'compileAlways'     => true, // performance decrease
            ));
            // Set some options here
            return $volt;
        },
        '.cert' => function ($view, $di) {
            $volt = new VoltEngine($view, $di);
            
            $volt->setOptions(array(
                'compiledPath' => __DIR__ . "/../../cache/view/volt/compiled/",
                //'compileAlways'     => true, // performance decrease
            ));


            $volt->getCompiler()->addFunction('strftime', 'strftime');
            /*
            $volt->getCompiler()->addFunction(
                'strftime',
                function($key, $params) {
                    foreach($params as $param) {
                        $values[] = $param['expr']['value'];
                    }
                    print_r($params);
                    exit;
                    return "\\strftime({$format}, {$timestamp})";
                }
            );
            */

            // Set some options here
            return $volt;
        },
        '.volt' => function ($view, $di) {
            $volt = new VoltEngine($view, $di);
            
            $volt->setOptions(array(
                'compiledPath' => __DIR__ . "/../../cache/view/volt/compiled/",
                //'compileAlways'     => true, // performance decrease
            ));


            $volt->getCompiler()->addFunction('strftime', 'strftime');
            /*
            $volt->getCompiler()->addFunction(
                'strftime',
                function($key, $params) {
                    foreach($params as $param) {
                        $values[] = $param['expr']['value'];
                    }
                    print_r($params);
                    exit;
                    return "\\strftime({$format}, {$timestamp})";
                }
            );
            */

            // Set some options here
            return $volt;
        }
    ));

    return $view;
});


$di->setShared("assets", function() use ($di) {
    $assets = new Plico\Assets\Manager(array(
        "sourceBasePath" => REAL_PATH . "/www/",
        "targetBasePath" => REAL_PATH . "/www/"
    ));
    //$assets->setDI($di);
    //$url->setBasePath("/var/www/local.sysclass.com/current/www");
    return $assets;
});

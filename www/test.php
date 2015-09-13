<?php

$di = new Phalcon\DI();

$di->setShared("escaper", function() {
    $escaper = new \Phalcon\Escaper();
    return $escaper;
});

$di->set('assets', function () {
    return new Phalcon\Assets\Manager();
}, true);

$di->set('url', function () {
    return new Phalcon\Mvc\Url();
}, true);

$di['assets']->collection('footer')
    ->join(true)
    ->setTargetPath("/var/www/local.sysclass.com/current/www/css");

$di['assets']->collection('footer')->addCss('/assets/default/plugins/font-awesome/css/font-awesome.min.css');
$di['assets']->collection('footer')->addCss('/assets/default/plugins/font-awesome-more/css/font-awesome-corp.cs');

$di['assets']->outputJs('footer');

/*
PHP Fatal error:  Uncaught exception 'Phalcon\\Di\\Exception' with message 'Service 'escaper' wasn't found in the dependency injection container' in /var/www/local.sysclass.com/current/www/test.php:15\n
Stack trace:\n#0 [internal function]: Phalcon\\Di->get('escaper', NULL)\n#1 [internal function]: Phalcon\\Di->getShared('escaper')\n#2 [internal function]: Phalcon\\Tag::getEscaperService()\n#3 [internal function]: Phalcon\\Tag::getEscaper(Array)\n#4 [internal function]: Phalcon\\Tag::renderAttributes('<script', Array)\n#5 [internal function]: Phalcon\\Tag::javascriptInclude(Array, true)\n#6 [internal function]: Phalcon\\Assets\\Manager->output(Object(Phalcon\\Assets\\Collection), Array, 'js')\n#7 /var/www/local.sysclass.com/current/www/test.php(15): Phalcon\\Assets\\Manager->outputJs('footer')\n#8 {main}\n  thrown in /var/www/local.sysclass.com/current/www/test.php on line 15
*/

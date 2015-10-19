<?php
use 
    Phalcon\Session\Adapter\Files as Session,
    Sysclass\Services\I18n\Translator,
    Phalcon\Flash\Direct as FlashDirect,
    Phalcon\Flash\Session as FlashSession;

$di->setShared("url", function() use ($di) {
    $url = new Phalcon\Mvc\Url();
    $url->setDI($di);
    $url->setBasePath(realpath(__DIR__ . "/../../www"));

    return $url;
});

$di->setShared("escaper", function() {
    $escaper = new \Phalcon\Escaper();
    return $escaper;
});

$di->setShared('stringsHelper', function () {
    $strings = new \Plico\Php\Helpers\Strings();
    // Set a global encryption key
    //$crypt->setKey();
    return $strings;
});

$session = new Session(array('uniqueId' => 'SYSCLASS'));
if (!$session->isStarted()) {
    $session->start();
}
$di->setShared('session', $session);

$di->setShared('translate', function () use ($di) {

    $translator = new Translator(!$di->get("request")->isAjax());
    $user = $di->get("user");

    if ($user) {
        $userlanguage = $user->getLanguage();

        if ($userlanguage) {
            $language_code = $userlanguage->code;
            $translator->setSource($userlanguage->code);
            return $translator;
        }
    }



    // TRY TO GET FROM A COOKIE OR FROM HTTP ACCEPTED LANGUAGE
    $locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    $language_code = Locale::getPrimaryLanguage($locale);
    //$languageModel = Sysclass\Models\I18n\Language::findFirstByCode($language_code);

    $translator->setSource($language_code);
    
    return $translator;
});




// Register the flash service with custom CSS classes
$di->set('flashSession', function () {
    $flash = new flashSession(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );

    return $flash;
});
$di->set('flash', function () {
    $flash = new FlashDirect(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );

    return $flash;
});

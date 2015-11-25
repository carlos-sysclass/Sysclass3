<?php
use 
    Phalcon\Session\Adapter\Files as Session,
    Sysclass\Services\I18n\Translator,
    Sysclass\Services\Mail\Adapter as MailAdapter,
    Sysclass\Services\Debug\Adapter as DebugAdapter,
    Plico\Php\Helpers\Strings as stringsHelper,
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
    $strings = new stringsHelper();
    // Set a global encryption key
    //$crypt->setKey();
    return $strings;
});

$session = new Session(array('uniqueId' => 'SYSCLASS'));
if (!$session->isStarted()) {
    $session->start();
}
$di->setShared('session', $session);



if (APP_TYPE === "WEB") {
    $di->setShared('translate', function () use ($di) {

        $translator = new Translator(!$di->get("request")->isAjax());

        //if ($di->get("session")->has("session_language")) {
        //    $translator->setSource($di->get("session")->get("session_language"));
            //var_dump($di->get("session")->get("session_language"));
            //exit;
        //} else {
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
        //}
     
        return $translator;
    });
} elseif (APP_TYPE === "CONSOLE" || APP_TYPE === "WEBSOCKET") {
    $di->setShared('translate', function () use ($di) {
        $translator = new Translator();

        $translator->setSource("en");

        // TODO: MAKE A WAY THE SELECT THE USER 
        /*
        $user = $di->get("user");

        if ($user) {
            $userlanguage = $user->getLanguage();

            if ($userlanguage) {
                $language_code = $userlanguage->code;
                $translator->setSource($userlanguage->code);
                return $translator;
            }
        }
        */

        return $translator;
    });
}

$di->setShared('queue', function () {
    $queue = new Sysclass\Services\Queue\Adapter();

    return $queue;
});

$di->setShared('mail', function () {
    require_once REAL_PATH . "/vendor/swiftmailer/swiftmailer/lib/swift_required.php";

    $mail = new MailAdapter();

    return $mail;
});


// GET DATA FROM SYSTEM RELEASE ENVIRONMENT DATABASE
$di->setShared("debug", function() use ($di) {
    $debug = new DebugAdapter();

    return $debug;
});


//Kint::enabled(  )



// Register the flash service with custom CSS classes
$di->setShared('flashSession', function () {
    $flash = new flashSession(/*
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    */);

    $flash
        ->setImplicitFlush(false)
        ->setAutomaticHtml(true);

    return $flash;
});
$di->setShared('flash', function () {
    $flash = new FlashDirect(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );

    $flash
        ->setImplicitFlush(false)
        ->setAutomaticHtml(true);


    return $flash;
});

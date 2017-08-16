<?php
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Security\Random;
use Phalcon\Session\Adapter\Files as Session;
use Plico\Php\Helpers\Arrays as arrayHelper;
use Plico\Php\Helpers\Strings as stringsHelper;
use Sysclass\Services\Debug\Adapter as DebugAdapter;
use Sysclass\Services\I18n\Translator;
use Sysclass\Services\Loader\Adapter as LoaderAdapter;
use Sysclass\Services\Mail\Adapter as MailAdapter;
use Sysclass\Services\Notifications\Manager as NotificationManager;
use Sysclass\Services\Utils\YmlParser;

/**
 * Component to provide module/model/controller cold loading (used when manually loading is need)
 */
$di->setShared("loader", function () use ($di, $environment) {
	$loader = new LoaderAdapter();
	return $loader;
});

$di->setShared("notification", function () use ($di, $environment) {
	$notification = new NotificationManager();
	return $notification;
});

$di->setShared("url", function () use ($di, $environment) {
	$url = new Phalcon\Mvc\Url();
	$url->setDI($di);
	$url->setBasePath(realpath(REAL_PATH . "/www"));

	return $url;
});

$di->setShared("resourceUrl", function () use ($di, $environment) {
	$url = new Plico\Mvc\ResourceUrl(array(
		$environment->view->theme,
		'default',
	));
	$url->setDI($di);
	$url->setBasePath(realpath(REAL_PATH . "/www/assets/"));
	$url->setBaseUri("/assets");
	$url->setStaticBaseUri("/assets");

	return $url;
});

$di->setShared("random", function () {
	$random = new Random();
	return $random;
});

$di->setShared("escaper", function () {
	$escaper = new \Phalcon\Escaper();
	return $escaper;
});

$di->setShared('stringsHelper', function () {
	$strings = new stringsHelper();
	// Set a global encryption key
	//$crypt->setKey();
	return $strings;
});

$di->set('arrayHelper', function () {
	$array = new arrayHelper();
	// Set a global encryption key
	//$crypt->setKey();
	return $array;
}, true);

//$di->setShared('session', $session);

$di->set('session', function () {
	$session = new Session(array('uniqueId' => 'SYSCLASS'));

	if (!$session->isStarted()) {
		$session->start();
	}

	return $session;
}, true);

$di->set('yaml', function () {
	$parser = new Sysclass\Services\Utils\YmlParser();
	// Set a global encryption key
	//$crypt->setKey();
	return $parser;
}, true);

$di->set('xml', function () {
	$parser = new Sysclass\Services\Utils\XmlParser();
	// Set a global encryption key
	//$crypt->setKey();
	return $parser;
}, true);

$di->set('sqlParser', function () {
	$parser = new Sysclass\Services\Utils\QueryBuilderParser();
	// Set a global encryption key
	//$crypt->setKey();

	$parser->initialize();
	return $parser;
}, true);

$di->set('payments', function () use ($eventsManager) {
	$payment = new Sysclass\Services\Payments\Adapter();
	// Set a global encryption key
	$payment->setEventsManager($eventsManager);

	$payment->initialize();
	return $payment;
}, true);

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

$di->setShared('messagebus', function () use ($eventsManager) {
	$messagebus = new Sysclass\Services\MessageBus\Manager();
	$messagebus->setEventsManager($eventsManager);
	return $messagebus;
});

$di->setShared('mail', function () {
	require_once REAL_PATH . "/vendor/swiftmailer/swiftmailer/lib/swift_required.php";

	$mail = new MailAdapter();

	return $mail;
});

// GET DATA FROM SYSTEM RELEASE ENVIRONMENT DATABASE
$di->setShared("debug", function () use ($di) {
	$debug = new DebugAdapter();

	return $debug;
});

//Kint::enabled(  )

// Register the flash service with custom CSS classes
$di->setShared('flashSession', function () {
	$flash = new flashSession( /*
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
			'error' => 'alert alert-danger',
			'success' => 'alert alert-success',
			'notice' => 'alert alert-info',
			'warning' => 'alert alert-warning',
		)
	);

	$flash
		->setImplicitFlush(false)
		->setAutomaticHtml(true);

	return $flash;
});
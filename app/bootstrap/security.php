<?php
use Sysclass\Services\Authentication\Exception as AuthenticationException;


// TODO: Load Autentication Backends, based on configuration
$di->set("authentication", function() use ($eventsManager) {
    $authentication = new Sysclass\Services\Authentication\Adapter();
    $authentication->setEventsManager($eventsManager);
    return $authentication;
});

$di->setShared("user", function() use ($di, $eventsManager) {
    try {
        $user = $di->get("authentication")->checkAccess();

        return $user;
    } catch (AuthenticationException $e) {
        return false;
    }
});

$di->setShared("acl", function() use ($di, $eventsManager) {
    // GET CURRENT USER
    $user = $di->get("user");

    if ($user) {
        // CREATE THE ACL
        $acl = Sysclass\Acl\Adapter::getDefault($user);
        // Bind the eventsManager to the ACL component
        $acl->setEventsManager($eventsManager);
    
        return $acl;
    }
    return false;
});

$di->set('crypt', function () {
    $crypt = new \Phalcon\Crypt();
    // Set a global encryption key
    //$crypt->setKey();
    return $crypt;
}, true);
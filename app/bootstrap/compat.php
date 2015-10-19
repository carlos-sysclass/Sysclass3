<?php
// FOR BACKWARD COMPATILITY
$db_dsn = sprintf(
    '%s://%s:%s@%s/%s?persist',
    $environment->database->dbtype,
    $environment->database->dbuser,
    $environment->database->dbpass,
    $environment->database->dbhost,
    $environment->database->dbname
);

$plicoLib = PLicoLib::instance();

$plicoLib->set('db_dsn', $db_dsn);
$plicoLib->set('db/charset', 'utf8');

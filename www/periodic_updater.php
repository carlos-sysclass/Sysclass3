<?php
/**
 * Periodic updater
 *
 * This page is used to periodically revive the current user, as well as check for unread messages etc
 *
 * @package SysClass
 */
session_cache_limiter('none');
$sid = session_id();
if (empty($sid)) session_start();

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$path = "../libraries/";
$loadLanguage = false;
/** Configuration file.*/
require_once $path."configuration.php";
if (!isset($_SESSION['s_login']) || !sC_checkParameter($_SESSION['s_login'], 'login')) {
    echo "No active session found";
    exit;
}

try {

    $currentUser = MagesterUserFactory::factory($_SESSION['s_login']);
    //var_dump($currentUser->isLoggedIn());
    //var_dump($GLOBALS['configuration']['autologout_time']);

    $onlineUsers = MagesterUser::getUsersOnline($GLOBALS['configuration']['autologout_time'] * 60);

    if ($_SESSION['timestamp']) {
        $entity = getUserTimeTarget($_SERVER['HTTP_REFERER']);
        sC_updateTableData(
            "user_times",
            array(
                'timestamp_now' => time(),
                'time' => $_SESSION['time'] + time() - $_SESSION['timestamp']
            ),
            "session_id = '".session_id()."' and users_LOGIN='".$_SESSION['s_login']."' and entity='".current($entity)."' and entity_id='".key($entity)."'"
        );
    }

    $messages = sC_getTableData(
        "f_personal_messages pm, f_folders ff",
        "count(*)",
        "pm.users_LOGIN='".$_SESSION['s_login']."' and viewed='no' and f_folders_ID=ff.id and ff.name='Incoming'"
    );
    $messages = $messages[0]['count(*)'];

    echo json_encode(array("messages" => $messages, "online" => $onlineUsers));

} catch (Exception $e) {
    handleAjaxExceptions($e);
}

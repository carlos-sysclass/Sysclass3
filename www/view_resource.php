<?php
/**
 * View Resource
 *
 * This file offers the user the ability to access many SysClass resources, in a unified way
 * Usage:
 * view_resource.php&type=<type>&id=<identifier>
 *
 * @package SysClass
 * @version 3.5.0
 */

//General initialization and parameters
session_cache_limiter('none');
session_start();

$path = "../libraries/";

/** Configuration file.*/
include_once $path."configuration.php";

if (isset($_SESSION['s_login']) && $_SESSION['s_password']) {
    try {
        $currentUser = MagesterUserFactory :: factory($_SESSION['s_login']);
    } catch (MagesterException $e) {                                                //The user is not valid
        $message = $e -> getMessage().' ('.$e -> getCode().')';
        eF_redirect("index.php?message=".urlencode($message)."&message_type=failure");
        exit;
    }
} else {
    setcookie('c_request', $_SERVER['REQUEST_URI'], time() + 300);
    eF_redirect("index.php?message=".urlencode(_RESOURCEREQUESTEDREQUIRESLOGIN)."&message_type=failure");
    exit;
}

try {
    switch ($_GET['type']) {
        case 'content':
            if (!($currentUser instanceof MagesterLessonUser)) {
                throw new Exception(_YOUCANNOTACCESSREQUESTEDRESOURCE);
            }
            $unit        = new MagesterUnit($_GET['id']);
            $userLessons = $currentUser -> getLessons();
            if (!$unit['options']['indexed']) {
                throw new Exception(_RESOURCEISNOTACCESSIBLEFROMOUTSIDE);
            }
            if (!$unit['active']) {
                throw new Exception(_RESOURCEISNOTAVAILABLE);
            }
            if (in_array($unit['lessons_ID'], array_keys($userLessons))) {
                $userLessons[$unit['lessons_ID']] == 'professor' ? $userType = 'professor' : $userType = 'student';
                setcookie('c_request', $userType.".php?lessons_ID=".$unit['lessons_ID']."&view_unit=".$unit['id'], time() + 300);
                echo "<script>top.location='".$userType."page.php'</script>";
            } else {
                throw new Exception(_CANNOTACCESSRESOURCE);
            }
            break;
        case 'file':
            $file = new MagesterFile($_GET['id']);
            if ($file['shared']) {
                $userLessons = $currentUser -> getLessons();
                if (in_array($file['shared'], array_keys($userLessons))) {
                    eF_redirect("view_file.php?file=".$_GET['id']);
                } else {
                    throw new Exception(_CANNOTACCESSRESOURCE);
                }
            } else {
                throw new Exception(_RESOURCEISNOTACCESSIBLEFROMOUTSIDE);
            }
            break;
        case 'lesson':
            if (!($currentUser instanceof MagesterLessonUser)) {
                throw new Exception(_YOUCANNOTACCESSREQUESTEDRESOURCE);
            }            
            $userLessons = $currentUser -> getLessons();
            if (in_array($_GET['id'], array_keys($userLessons))) {
                $lesson = new MagesterLesson($_GET['id']);
                $userLessons[$lesson -> lesson['id']] == 'professor' ? $userType = 'professor' : $userType = 'student';
                setcookie('c_request', $userType.".php?ctg=control_panel&lessons_ID=".$lesson -> lesson['id'], time() + 300);
                echo "<script>top.location='".$userType."page.php'</script>";
            } else {
                throw new Exception(_CANNOTACCESSRESOURCE);
            }
            break;
        default: break;
    }
} catch (Exception $e) {
    eF_redirect("".$currentUser -> user['user_type'].".php?message=".$e -> getMessage());
}

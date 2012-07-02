<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

if (!$_student_) {
    $showTest = new MagesterTest($_GET['view_unit'], true);
    if (isset($_GET['print'])) {
        $testString = $showTest -> toHTML($showTest -> toHTMLQuickForm(), false, true);
    } else {
        $testString = $showTest -> toHTML($showTest -> toHTMLQuickForm(), false);
    }

    $smarty -> assign("T_TEST", $testString);
} else {
    $test = new MagesterTest($currentUnit['id'], true);
    $status = $test -> getStatus($currentUser, $_GET['show_solved_test']);
    $form = new HTML_QuickForm("test_form", "post", basename($_SERVER['PHP_SELF']).'?view_unit='.$_GET['view_unit'], "", 'onsubmit = "$(\'submit_test\').disabled=true;"', true);
    

 switch ($status['status']) {
        case 'incomplete':
        	//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
        	
            if (!$testInstance = unserialize($status['completedTest']['test'])) {
                throw new MagesterTestException(_TESTCORRUPTEDASKRESETEXECUTION, MagesterTestException::CORRUPTED_TEST);
            }
            if ($testInstance -> time['pause'] && isset($_GET['resume'])) {
                $testInstance -> time['pause'] = 0;
                $testInstance -> time['resume'] = time();
                //unset($testInstance -> currentQuestion);
                $testInstance -> save();
            }
            $remainingTime = $testInstance -> options['duration'] - $testInstance -> time['spent'] - (time() - $testInstance -> time['resume']);

            $nocache = false;
            if ($form -> isSubmitted() || ($testInstance -> options['duration'] && $remainingTime < 0) || $status['status'] == 'incomplete') {
                $nocache = true;
            }
            $testString = $testInstance -> toHTMLQuickForm($form, false, false, false, $nocache);
            $testString = $testInstance -> toHTML($testString, $remainingTime);

			/*
            if ($testInstance -> options['duration'] && $remainingTime < 0) {
                $values = $form -> exportValues();
                $testInstance -> complete($values['question']);
                if ($testInstance -> completedTest['status'] == 'failed') {
                    $currentUser -> setSeenUnit($currentUnit, $currentLesson, 0);
                } else {
                    $currentUser -> setSeenUnit($currentUnit, $currentLesson, 1);
                }
                eF_redirect(basename($_SERVER['PHP_SELF'])."?view_unit=".$_GET['view_unit']);
                exit; //<-- This exit is necessary here, otherwise test might be counted twice
            }
            */
            $smarty -> assign("T_TEST_UNDERGOING", true);
            //$testUndergoing = true;
            //pr($remainingTime);
            
            break;
        case 'completed':case 'passed':case 'failed':case 'pending':
/*
        	/** @todo RETIRAR O "IF" E MONTAR ESQUEMA PARA BUSCAR A PÁGINA DE RESULTADO, BASEADO NO unit_id, E CASO EXISTA, DIRECIONAR PARA A PÁGINA CORRENTE */
        	if ($currentUnit['id'] == 2) {
				//eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=module&op=module_xcms&action=load_xpage&xpage_id=4");
        		
        		// PASS USER AUTO-LOGIN
//        		header("Location: http://idiompro.com/ingles-online/nivel?hash=" . $currentUser->user['autologin']);
        		header("Location: http://idiompro.com/ingles-online/wp-content/themes/breath/SysClass/nivelamento.score.send.php?hash=" . $currentUser->user['autologin']);
        		
        		//echo "http://idiompro.com/nivel?hash=" . $currentUser->user['autologin'];
        		//eF_redirect("http://idiompro.com/nivel?hash=" . $currentUser->user['autologin']);
        		//exit; //<-- This exit is necessary here, otherwise test might be counted twice
        	}
        	
            if (!$testInstance = unserialize($status['completedTest']['test'])) {
                throw new MagesterTestException(_TESTCORRUPTEDASKRESETEXECUTION, MagesterTestException::CORRUPTED_TEST);
            }

   //$url          = basename($_SERVER['PHP_SELF']).'?ctg=content&view_unit='.$_GET['view_unit'];
   $testString = $testInstance -> toHTMLQuickForm($form, false, true);
   $testString = $testInstance -> toHTMLSolved($testString, false);

   //Added for test redirect option
   //$currentStatus not needed because he can not jump to previous execution
   $status = $testInstance -> getStatus($currentUser -> user['login']);
   $smarty -> assign("T_TEST_STATUS", $status);

            if (isset($_GET['test_analysis'])) {
                require_once 'charts/php-ofc-library/open-flash-chart.php';

                list($parentScores, $analysisCode) = $testInstance -> analyseTest();

                $smarty -> assign("T_CONTENT_ANALYSIS", $analysisCode);
                $smarty -> assign("T_TEST_DATA", $testInstance);

                $status = $testInstance -> getStatus($currentUser -> user['login']);
                $smarty -> assign("T_TEST_STATUS", $status);

                if (isset($_GET['display_chart'])) {
                    $url = basename($_SERVER['PHP_SELF']).'?ctg=content&view_unit='.$currentUnit['id'].'&test_analysis=1&selected_unit='.$_GET['selected_unit'].'&show_chart=1&show_solved_test='.$_GET['show_solved_test'];
                    echo $testInstance -> displayChart($url);
                    exit;
                } elseif (isset($_GET['show_chart'])) {
                    echo $testInstance -> calculateChart($parentScores);
                    exit;
                }
            }

            break;
        default:
            if (isset($_GET['confirm'])) {
                //The user specified himself the size of the test
                if ($test -> options['user_configurable']) {
                    //Get the size of the test, so that we can verify that the value specified is at most equal to it
                 $test -> getQuestions(); //This way the test's questions are populated, and we will be needing this information
                 $test -> options['random_pool'] && $test -> options['random_pool'] <= sizeof($test -> questions) ? $questionsNumber = $test -> options['random_pool'] : $questionsNumber = sizeof($test -> questions);

                 //Assigning the 'user_configurable' value to the 'random_pool' option gives us a test instance with the appropriate number of questions
                 if (is_numeric($_GET['user_configurable']) && $_GET['user_configurable'] <= $questionsNumber && $_GET['user_configurable'] > 0) {
                        $test -> options['random_pool'] = $_GET['user_configurable'];
                 } else if (!isset($_GET['user_configurable']) || !$_GET['user_configurable']) {
                     eF_redirect(basename($_SERVER['PHP_SELF'])."?view_unit=".$_GET['view_unit'].'&message='.urlencode(_MUSTSPECIFYQUESTIONNUMBER));
                     exit;
                 } else if ($_GET['user_configurable'] > $questionsNumber || $_GET['user_configurable'] <= 0) {
                     eF_redirect(basename($_SERVER['PHP_SELF'])."?view_unit=".$_GET['view_unit'].'&message='.urlencode(_MUSTSPECIFYVALUEFROM.' 1 '._TO.' '.$questionsNumber));
                     exit;
                 } else {
                     eF_redirect(basename($_SERVER['PHP_SELF'])."?view_unit=".$_GET['view_unit'].'&message='.urlencode(_INVALIDFIELDDATA));
                     exit;
                 }
                }
                $testInstance = $test -> start($currentUser -> user['login']);
                eF_redirect(basename($_SERVER['PHP_SELF'])."?view_unit=".$_GET['view_unit']);
                exit;
            } else {
                $testInstance = $test;
                $test -> getQuestions(); //This way the test's questions are populated, and we will be needing this information
                $testInstance -> options['random_pool'] && $testInstance -> options['random_pool'] <= sizeof($testInstance -> questions) ? $questionsNumber = $testInstance -> options['random_pool'] : $questionsNumber = sizeof($testInstance -> questions);
            }
            break;
    }

    if (isset($_GET['ajax']) || isset($_POST['ajax'])) {
        $testInstance -> handleAjaxActions();
    }

    //Calculate total questions. If it's already set, then we are visiting an unsolved test, and the questions number is already calculated (and may be different that the $testInstance -> questions size)
    if (!isset($questionsNumber)) {
        $questionsNumber = sizeof($testInstance -> questions);
    }

    //$smarty -> assign("T_REMAINING_TIME", $remainingTime);
    $smarty -> assign("T_TEST_QUESTIONS_NUM", $questionsNumber);
    $smarty -> assign("T_TEST_DATA", $testInstance);
    $smarty -> assign("T_TEST", $testString);
    $smarty -> assign("T_TEST_STATUS", $status);

    if (!$status['status'] || ($status['status'] == 'incomplete' && $testInstance -> time['pause'])) { //If the user hasn't confirmed he wants to do the test, display confirmation buttons
		$smarty -> assign("T_SHOW_CONFIRMATION", true);
    } else { //The user confirmed he wants to do the test, so display it
        $form -> addElement('hidden', 'time_start', $timeStart); //This element holds the time the test started, so we know the remaining time even if the user left the system
        if ($currentUnit['ctg_type'] !== 'feedback') {
   $form -> addElement('submit', 'submit_test', _SUBMITTEST, 'class = "flatButton" id = "submit_test" onclick = "return checkQuestions()"');
  } else {
   $form -> addElement('submit', 'submit_test', _SUBMITFEEDBACK, 'class = "flatButton"  id = "submit_test" onclick = "return checkQuestions()"');
  }
        if ($testInstance -> options['pause_test']) {
            $form -> addElement('submit', 'pause_test', _PAUSETEST, 'class = "flatButton"');
        }

        if ($form -> isSubmitted() && $form -> validate()) {

            $values = $form -> exportValues();

            $submitValues = $form -> getSubmitValues();

            foreach($testInstance -> questions as $id => $question) {
                $submitValues['question_time'][$id] || $submitValues['question_time'][$id] === 0 ? $question -> time = $submitValues['question_time'][$id] : null;
            }

            if (isset($values['pause_test'])) {
                $testInstance -> pause($values['question'], $_POST['goto_question']);
                eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=content&type=tests");
            } else {
                //Set the unit as "seen"
                $testInstance -> complete($values['question']);
                if ($testInstance -> completedTest['status'] == 'failed') {
                    $currentUser -> setSeenUnit($currentUnit, $currentLesson, 0);
                } else {
                 $currentUser -> setSeenUnit($currentUnit, $currentLesson, 1);
                }
                eF_redirect("".basename($_SERVER['PHP_SELF'])."?view_unit=".$_GET['view_unit']);
            }
        }

        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $form -> accept($renderer);
        $smarty -> assign('T_TEST_FORM', $renderer -> toArray());
        //$smarty -> assign('T_TEST_COMPLETED_ID', $testInstance->completedTest['id']);
    }
}

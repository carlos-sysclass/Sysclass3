<?php
/**
 * Platform index page
 *
 * This is the index page, allowing for logging in, registering new users,
 * contacting and resetting password
 *
 * @package SysClass
 * @version 3.6.0
 */
$_TEST_ID	= 60;
$_USERNAME	= "mr.debug";
$_COMPLETE_TEST_ID	= 880;

session_cache_limiter('none');
session_start();
//print_r($_SESSION);
$path = "../libraries/";

/** The configuration file.*/
require_once $path."configuration.php";

//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

$currentTest = new EfrontTest($_TEST_ID);
$doneTests = EfrontStats :: getDoneTestsPerTest(false, $currentTest -> test['id']);
unset($doneTests[$currentTest -> test['id']]['average_score']);

$currentDoneTests = $doneTests[$currentTest -> test['id']];

foreach($currentDoneTests as $username => $testStatus) {
	$_USERNAME = $username;
	
	foreach($testStatus as $testStatusId => $testStatusData) {
		if (is_numeric($testStatusId)) {
			$_COMPLETE_TEST_ID = $testStatusId;

			//$completetest60 = new EfrontCompletedTest($currentTest, $_USERNAME);
			$currentStatus  = $currentTest -> getStatus($_USERNAME, $_COMPLETE_TEST_ID, true);
			$efrontTest = unserialize($currentStatus['completedTest']['test']);
			
			if (!$efrontTest) {
				echo sprintf(
					"ERRO: unserialize error => USUÁRIO: %s => COMPLETE_TEST_ID: %s <br />",
					$_USERNAME, $_COMPLETE_TEST_ID
				);
				continue;
			}
			if (count($efrontTest->questions) <= 7) {
				echo sprintf(
					"ERRO: 7 questões somente => USUÁRIO: %s => COMPLETE_TEST_ID: %s <br />",
					$_USERNAME, $_COMPLETE_TEST_ID
				);
				continue;
			}
			
			unset($efrontTest->questions[1038]);
			
			$score_total = 0;
			foreach($efrontTest->questions as $id => $question) {
				$results = $question -> correct();
		//		echo $id. ' => ' . $results['score'] . ' => ' . $efrontTest -> getQuestionWeight($id) . '<br/>';
				$score_total += $results['score'] * $efrontTest -> getQuestionWeight($id);
				 
				$question->score = round($results['score'] * 100, 2);
			}
			$score_total > 1 ? $efrontTest -> completedTest['score'] = 100 : $efrontTest -> completedTest['score'] = round($score_total * 100, 2); //Due to roundings, overall score may go slightly above 100. so, truncate it to 100
			
			$efrontTest->save();
			
			echo sprintf(
				"STATUS: TESTE CORRIGIDO! => USUÁRIO: %s => COMPLETE_TEST_ID: %s <br />",
				$_USERNAME, $_COMPLETE_TEST_ID
			);
		}	
	}
	
}
exit;

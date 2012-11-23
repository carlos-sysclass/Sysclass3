<?php
header("Location: index.php");
exit;
session_cache_limiter('nocache');
session_start(); //This causes the double-login problem, where the user needs to login twice when already logged in with the same browser

$path = "../libraries/";
//Automatically redirect to installation page if configuration file is missing
/** Configuration file */
require_once $path."configuration.php";

header("content-type: text/plain");


$TestID		= 192;
//$QuestionID = 1533;

$test = new MagesterTest($TestID);
$testQuestions = $test->getQuestions();

$completedTests = eF_getTableData("completed_tests", "*", "tests_ID=" . $TestID);

foreach($completedTests as $testData) {
	$showTest = unserialize($testData['test']);

	$logData = array(
			'ID_TESTE' => $testData['tests_ID'],
			'NOME'		=> "Segurança da Informação - Gestão de Segurança - Prova on line",
			'ID' => $testData['id'],
			'LOGIN' => $testData['users_LOGIN'],
			'OLD_SCORE' => $showTest -> completedTest['score'],
	);

	$showTest -> completedTest['score'] = 0;

	foreach($showTest->questions as $QuestionID => $question) {
		if ($QuestionID == 2326) {
			$showTest->questions[$QuestionID]->answer = unserialize($testQuestions[$QuestionID]['answer']);
		}

		$results = $showTest->questions[$QuestionID]->correct();
		$showTest->questions[$QuestionID] -> score = round($results['score'] * 100, 2);

		$showTest->questions[$QuestionID] -> results = $results['correct'];
		$showTest -> completedTest['score'] += $showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID); //the total test score
		$showTest->questions[$QuestionID] -> scoreInTest = round($showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID), 3); //Score in test is the question score, weighted with the question's weight in the test

	}

	if ($showTest -> completedTest['score'] != $logData['OLD_SCORE']) {
		$showTest->save();

		$logData['NEW_SCORE'] = $showTest -> completedTest['score'];
		$diff[] = $logData;
	}

	//echo "\n\n";
}




/*
$TestID		= 436;
//$QuestionID = 1533;

$test = new MagesterTest($TestID);
$testQuestions = $test->getQuestions();

$completedTests = eF_getTableData("completed_tests", "*", "tests_ID=" . $TestID);

foreach($completedTests as $testData) {
	$showTest = unserialize($testData['test']);
	
	$logData = array(
		'ID_TESTE' => $testData['tests_ID'],
		'NOME'		=> "Segurança da Informação - Gestão de Segurança - Prova on line",
		'ID' => $testData['id'],
		'LOGIN' => $testData['users_LOGIN'],
		'OLD_SCORE' => $showTest -> completedTest['score'],
	);

	$showTest -> completedTest['score'] = 0;

	foreach($showTest->questions as $QuestionID => $question) {
		//$showTest->questions[$QuestionID]->answer = unserialize($testQuestions[$QuestionID]['answer']);

		if (!array_key_exists($QuestionID, $testQuestions)) {
		    // QUESTION REMOVED FROM TEST
		    unset($showTest->questions[$QuestionID]);
            continue;
		}

		if ($QuestionID == 6172) {
			if ($showTest->questions[$QuestionID]->answer[0] == 4) {
				$showTest->questions[$QuestionID]->answer = unserialize($testQuestions[$QuestionID]['answer']);
				$showTest->questions[$QuestionID]->options = unserialize($testQuestions[$QuestionID]['options']);
			}
		}

		if ($QuestionID == 7830) {
			if ($showTest->questions[$QuestionID]->userAnswer == 4) {
				$showTest->questions[$QuestionID]->userAnswer = 2;
				echo "<br />";
			}
		}
		
		$results = $showTest->questions[$QuestionID]->correct();
		$showTest->questions[$QuestionID] -> score = round($results['score'] * 100, 2);
		
		$showTest->questions[$QuestionID] -> results = $results['correct'];
		$showTest -> completedTest['score'] += $showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID); //the total test score
		$showTest->questions[$QuestionID] -> scoreInTest = round($showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID), 3); //Score in test is the question score, weighted with the question's weight in the test
		
	}

	if ($showTest -> completedTest['score'] != $logData['OLD_SCORE']) {
		$showTest->save();
		
		$logData['NEW_SCORE'] = $showTest -> completedTest['score'];
		$diff[] = $logData;
	}
	echo "\n\n";
}
*/


/* BUSCAR TESTS BY UNIT */
/* COMO RECALCULAR */
/*
$TestID		= 208;

$test = new MagesterTest($TestID);
$testQuestions = $test->getQuestions();

$completedTests = eF_getTableData("completed_tests", "*", "tests_ID=" . $TestID);

foreach($completedTests as $testData) {
	$showTest = unserialize($testData['test']);

	$logData = array(
			'ID_TESTE' => $testData['tests_ID'],
			'NOME'		=> "Segurança da Informação - Gestão de Segurança - Prova Presencial",
			'ID' => $testData['id'],
			'LOGIN' => $testData['users_LOGIN'],
			'OLD_SCORE' => $showTest -> completedTest['score'],
	);

	$showTest -> completedTest['score'] = 0;

	foreach($showTest->questions as $QuestionID => $question) {
		
		if ($QuestionID == 7869) {
			$showTest->questions[$QuestionID]->answer = unserialize($testQuestions[$QuestionID]['answer']);
		}
		
		if ($QuestionID == 7861) {
			$showTest->questions[$QuestionID]->answer = unserialize($testQuestions[$QuestionID]['answer']);
		}
		
		if ($QuestionID == 7855) {
			$showTest->questions[$QuestionID]->answer = unserialize($testQuestions[$QuestionID]['answer']);
		}
		
		$results = $showTest->questions[$QuestionID]->correct();
		$showTest->questions[$QuestionID] -> score = round($results['score'] * 100, 2);

		$showTest->questions[$QuestionID] -> results = $results['correct'];
		$showTest -> completedTest['score'] += $showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID); //the total test score
		$showTest->questions[$QuestionID] -> scoreInTest = round($showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID), 3); //Score in test is the question score, weighted with the question's weight in the test

	}

	if ($showTest -> completedTest['score'] != $logData['OLD_SCORE']) {
		$showTest->save();

		$logData['NEW_SCORE'] = $showTest -> completedTest['score'];
		$diff[] = $logData;
	}
	echo "\n\n";
}


*/
/* BUSCAR TESTS BY UNIT */
/* COMO RECALCULAR */
/*
$TestID		= 207;
//$QuestionID = 1533;

$test = new MagesterTest($TestID);
$testQuestions = $test->getQuestions();

$completedTests = eF_getTableData("completed_tests", "*", "tests_ID=" . $TestID);

foreach($completedTests as $testData) {
	$showTest = unserialize($testData['test']);

	$logData = array(
			'ID_TESTE' => $testData['tests_ID'],
			'NOME'		=> "Segurança da Informação - Introdução Forense - Prova Presencial",
			'ID' => $testData['id'],
			'LOGIN' => $testData['users_LOGIN'],
			'OLD_SCORE' => $showTest -> completedTest['score'],
	);

	$showTest -> completedTest['score'] = 0;

	foreach($showTest->questions as $QuestionID => $question) {
		if ($QuestionID == 8880) {
			if ($showTest->questions[$QuestionID]->answer[0] != $showTest->questions[$QuestionID]->userAnswer) {
				$showTest->questions[$QuestionID]->userAnswer = $showTest->questions[$QuestionID]->answer[0];
				
			}
			echo "\n";
		}

		$results = $showTest->questions[$QuestionID]->correct();
		$showTest->questions[$QuestionID] -> score = round($results['score'] * 100, 2);

		$showTest->questions[$QuestionID] -> results = $results['correct'];
		$showTest -> completedTest['score'] += $showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID); //the total test score
		$showTest->questions[$QuestionID] -> scoreInTest = round($showTest->questions[$QuestionID] -> score * $showTest -> getQuestionWeight($QuestionID), 3); //Score in test is the question score, weighted with the question's weight in the test

	}

	if ($showTest -> completedTest['score'] != $logData['OLD_SCORE']) {
		$showTest->save();

		$logData['NEW_SCORE'] = $showTest -> completedTest['score'];
		$diff[] = $logData;
	}
	echo "\n\n";
}
*/

echo implode(";", array_keys($diff[0]));
echo "\n";
foreach($diff as $item) {
	echo implode(";", $item);
	echo "\n";
}

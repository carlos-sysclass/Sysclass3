<?php
/**
 * Platform boleto page
 *
 * Esta página permite a visualização de um boleto através de um hash
 *
 * @package SysClass
 * @version 3.0.0
 */

session_cache_limiter('nocache');
session_start(); //This causes the double-login problem, where the user needs to login twice when already logged in with the same browser

$path = "../libraries/";
//Automatically redirect to installation page if configuration file is missing
/** Configuration file */
require_once $path."configuration.php";









/*

//- ARRUMAR CLASSES
//	- COLOCAR TUDO NA TABELA USER_TO_COURSES E EXCLUIR TABELA USER_TO_CLASSES
	$userToClasses = eF_getTableData("users_to_classes uc 
	LEFT JOIN classes c ON (uc.classes_ID = c.id)
	LEFT JOIN users u ON (uc.users_ID = u.id)
	", "*");
	
	//var_dump($userToClasses);
	
//	fields: users_ID 	classes_ID 	active 	archive 	user_type
	//var_dump($userToClasses);
	foreach($userToClasses as $classeUser) {
		// 
		$user2CourseCount = eF_countTableData("users_to_courses", "*", 
			sprintf("users_LOGIN = '%s' AND courses_ID = %d", $classeUser['login'], $classeUser['courses_ID']) 
		);

		if ($user2CourseCount[0]['count'] == 1) {
			$user2Course = eF_getTableData("users_to_courses", "*", 
				sprintf("users_LOGIN = '%s' AND courses_ID = %d", $classeUser['login'], $classeUser['courses_ID']) 
			);
			if ($user2Course[0]['class_id'] == 0) {
				// UPDATE
				eF_updateTableData(
					"users_to_courses", 
					array('classe_id' => $classeUser['classes_ID']), 
					sprintf("users_LOGIN = '%s' AND courses_ID = %d", $classeUser['login'], $classeUser['courses_ID'])
				);
				eF_deleteTableData(
					"users_to_classes",
				 	sprintf("users_ID = %d AND classes_ID = %d", $classeUser['users_ID'], $classeUser['classes_ID'])
				);
				$settedUsers[] = $classeUser['login'];
			} elseif ($user2Course[0]['class_id'] != $classeUser['classes_ID']) {
				// DIFERENTE, COM VALORES DIFERENTES EM CADA UM. VERIFICAR MANUALMENTE QUAL DEVE SER ACERTADO
					
				$errorSetUsers[] = $classeUser['login'];
			} elseif ($user2Course[0]['class_id'] == $classeUser['classes_ID']) {
				// usuário setados
				$alreadySetUsers[] = $classeUser['login'];
			}
		} else {
			$notFoundUsers[] = $classeUser['login'];
			// ONLY DELETE
			eF_deleteTableData(
				"users_to_classes",
			 	sprintf("users_ID = %d AND classes_ID = %d", $classeUser['users_ID'], $classeUser['classes_ID'])
			);
		} 
	}
	// CLEARING users_to_courses WITH NO COURSE
	var_dump(eF_deleteTableData("users_to_courses", "courses_ID NOT IN (SELECT id FROM courses)")); 

	
	echo "\nUSUÀRIOS COM ERRO:\n";
	echo implode("\n", $settedUsers);
	echo "\nUSUÀRIOS COM ERRO:\n";
	echo implode("\n", $errorSetUsers);
	echo "\nUSUÀRIOS OK:\n";
	echo implode("\n", $alreadySetUsers);
	echo "\nUSUÀRIOS NF:\n";
	echo implode("\n", $notFoundUsers);
*/
/*
- ARRUMAR MATRICULAS
	- REGISTRAR MATRICULA DE ALUNOS ANTIGOS
- ARRUMAR PAGAMENTOS
	- LIGAR O PAGAMENTO SOMENTE A MATRICULA 
	- INCLUIR LISTA DE BOLETOS PARA O ALUNO
*/
?>

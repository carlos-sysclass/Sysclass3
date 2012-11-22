<?php
/**
 * Platform boleto page
 *
 * Esta página permite a visualização de um boleto através de um hash
 *
 * @package SysClass
 * @version 3.0.0
 */

set_time_limit(300);
 
session_cache_limiter('nocache');
session_start(); //This causes the double-login problem, where the user needs to login twice when already logged in with the same browser

$path = "../libraries/";
//Automatically redirect to installation page if configuration file is missing
/** Configuration file */
require_once $path."configuration.php";

include_once($path . 'localization.class.php');

$filenames = array(
	dirname(__FILE__) . '/alunos.adm.turmaA.txt',
	dirname(__FILE__) . '/alunos.adm.turmaB.txt'
);

$courses = array(8,8);
$classes = array(1,2);


foreach ($filenames as $index => $filename) {
	$handle =  fopen($filename, 'r');
	$students 	= array();
	//$keys		= array('nome', 'email', 'telefone', 'instituicao_formacao', '_data_nascimento', 'localidade', 'empregabilidade', 'escolaridade');
	$keys		= array('registro', 'nome', 'email', 'telefone', 'endereco', 'bairro', 'cidade');
	
	while ($student_raw = fgets($handle)) {
		
		$student_raw = str_replace("\n", "", $student_raw);
		$student_array = explode("\t", $student_raw);
		
		$students[] = array_combine($keys, $student_array);
		
	}
	
	
	$array_states = localization::getStateList();
	
	foreach ($students as &$student) {
		// CLEAN, MERGE, PROCESS STUDENT DATA
		
		//1. break name
		list($student['name'], $student['surname']) = explode(" ", $student['nome'], 2);
		/*
		$datetime = date_create_from_format('m/d/Y', $student['_data_nascimento']);
		$student['data_nascimento'] = $datetime->format('Y-m-d');
		*/
		//$student['cidade'] = 'Não informado';
		$student['uf'] = 'PR';
		/*
		foreach($array_states as $sigla => $nome) {
			if (stripos($student['localidade'], '-' . $sigla) !== FALSE) {
				$student['cidade'] = str_replace('-' . $sigla, "", $student['localidade']);
				$student['uf'] = $sigla;
				break;
			} 
		}
		*/
		$student['email'] = trim($student['email']);
		
		//$student['email'] = "kucaniz@arcanjoweb.com";
		
		//unset($student['_data_nascimento']);
		unset($student['nome']);
		unset($student['localidade']);
	}
	
	$courseID 	= $courses[$index];
	$classeID	= $classes[$index];
	$course = new MagesterCourse($courseID);
	
	foreach ($students as &$student) {
		$insert['name'] = $_GET['name'];
		$insert['surname'] = $_GET['surname'];
	
		// GENERATE LOGIN AND PASSWORD BASED ON NAME AND SURNAME
		$student['login']		= MagesterUser::generateNewLogin($student['name'], $student['surname']);
		$student['password']	= MagesterUser::generateMD5Password();
		$student['languages_NAME'] = 'portuguese';
		$student['user_type'] = 'student';
		$student['user_types_ID'] = 0;
		$student['active'] = 1;
		
		
		$insert['login']			= $student['login'];
		$insert['password']			= $student['password'];
		$insert['email']			= $student['email'];
		//$insert['email']			= 'kucaniz@arcanjoweb.com';
		$insert['name']				= $student['name'];
		$insert['surname']			= $student['surname'];
		$insert['languages_NAME']	= $student['languages_NAME'];
		$insert['user_type']		= $student['user_type'];
		$insert['user_types_ID']	= $student['user_types_ID'];
		$insert['active']			= $student['active'];
		
		$user = MagesterUser :: createUser($insert);
		
		
	
		$details = array(
			'registro'				=> $student['registro'],
			'telefone'				=> $student['telefone'],
			//'instituicao_formacao'	=> $student['instituicao_formacao'],
			//'empregabilidade'		=> $student['empregabilidade'],
			//'escolaridade'			=> $student['escolaridade'],
			//'data_nascimento'		=> $student['data_nascimento'],
			'endereco'				=> $student['endereco'],
			'bairro'				=> $student['bairro'],
			'cidade'				=> $student['cidade'],
			'uf'					=> $student['uf'],
			'ies_id'				=> 1,
			'polo_id'				=> 0
			/*
			'cep'				=> $values['cep'],
			'endereco'			=> $values['endereco'],
			'numero'			=> $values['numero'],
			'complemento'		=> $values['complemento'],
			'bairro'			=> $values['bairro'],
			'cidade'			=> $values['cidade'],
			 */
	    );
		MagesterUserDetails :: injectDetails($student['login'], $details);
		
		
		$currentCourseClass = current($courseClassesKey);
		
		// MATRICULAR USUÀRIO NO CURSO
		$course -> addUsers($student['login'], 'student', true, 'Web');
		$course -> setUserCourseClass($student['login'], $course->course['id'], $classeID);
		
		var_dump($student);
		
		$inserted[] = $student;
	}
}

var_dump($inserted);
exit;

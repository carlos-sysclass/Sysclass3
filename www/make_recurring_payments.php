<?php

/**

 * Cron job script

 *

 * This script is used by a cron manager to periodically send the top X unsent email messages

 * from the notifications table.

 * @package SysClass

 * @version 3.6.0

 */
//This is needed in order to make cron jobs able to run the file
$path = "/home/sysclass/root/libraries/";
$_SERVER['HTTP_HOST'] = 'sysclass.com';
$_SERVER['SERVER_NAME'] = 'sysclass.com';

$DO_NOT_REDIRECT = true;

$_GET['debug'] = 10;
ini_set("display_errors", 1);

require_once $path."configuration.php";


$dir = getcwd();
chdir(dirname(__FILE__));
session_cache_limiter('none'); //Initialize session
session_start();
// GET A LIST OF TODAY'S PAYMENTS



$paymentMaker = MagesterUserFactory::factory("admin");
$paymentMaker->login("fep7_58A$");
global $currentUser;
$currentUser = $paymentMaker;
$modules = eF_loadAllModules(true);

//var_dump($modules);


// MAKE ONE-BY-ONE
//foreach() :
	$negociationID 	= 2022;
	$invoice_index 	= 1;
	$token 			= '6wt+ok86hbjNBM6qngiSSfzJkJxyhUerKp0DIjkd4Hs=';
	$bandeira		= 'visa';
	
	// CREATE AN AUTO-LOGIN AND CIELO RETURN PAGE, ON SYSCLASS
	$cieloPayment = $modules['module_xpay_cielo']->initPaymentProccess($negociationID, $invoice_index,
		array(
			'bandeira' 		=> $bandeira,
			'parcelas' 		=> 1,
			'return_data' 	=> true,
			'token'			=> $token
			//'return_url' 	=> $return_url,
			//'gerar_token'	=> true // ONLY TRUE IF RECORRENTE
		)
	);
	
	var_dump($cieloPayment);

//endforeach;
chdir($dir);

//debug(false);
exit;

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
$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] = "sysclass.com";


$dir = getcwd();
chdir(dirname(__FILE__));
$debug_TimeStart = microtime(true); //Debugging timer - initialization
session_cache_limiter('none'); //Initialize session
session_start();
$path = "../libraries/"; //Define default path
/** The configuration file.*/
require_once $path."configuration.php";
$debug_InitTime = microtime(true) - $debug_TimeStart; //Debugging timer - time spent on file inclusion
$lowest_possible_time = time() - 21600; // last acceptable time - pending 6 hours in the queue to be sent

// GET A LIST OF TODAY'S PAYMENTS

$modules = eF_loadAllModules(true);



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

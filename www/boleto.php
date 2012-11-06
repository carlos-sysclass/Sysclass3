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

$sha1_access = $_GET['id'];

// GET IF HASH EXISTS IN TABLE
$existsCount = eF_countTableData(
	// table 
	"module_pagamento_invoices", 	
	// fields
	"invoices_sha_access",
  	// where clause
  	"invoices_sha_access = '" . $sha1_access . "'"
);
$totalcount = $existsCount[0]['count'];

$filename = sprintf(
	G_ROOTPATH . 'boletos/%s.html',
	$sha1_access
);

if ($totalcount > 0  && file_exists($filename)) {
	$boleto_html = file_get_contents($filename);
	echo $boleto_html;
} else {
	// ERRO. MOSTRAR NA PÁGINA
	eF_redirect("index.php?message=Não existe o boleto específicado&message_type=failure");
}
exit;
?>

<?php
$user_domains = array(
	'ultbr'		=> 'ult.com.br',
	'SysClass'	=> 'SysClass.com',
	'maguser'	=> 'magester.net',
);
$username = null;
$result = sscanf(__FILE__, "/home/%[a-z]/cron.php", $username);

if (array_key_exists($username, $user_domains)) {
	$domain_name = $user_domains[$username];
} else {
	exit;	
}


$start = strpos(dirname(__FILE__), "subdomains/");
if ($start !== FALSE) {
	$start += strlen("subdomains/");

	$sub = substr(dirname(__FILE__), $start);
	$_SERVER['HTTP_HOST'] = $sub . '.' . $domain_name;
	include(dirname(__FILE__) . '/www/send_notifications.php');
	exit;
}

$start = strpos(dirname(__FILE__), "sub-domains/");
if ($start !== FALSE) {
	$start += strlen("sub-domains/");

	$sub = substr(dirname(__FILE__), $start);
	$_SERVER['HTTP_HOST'] = $sub . '.' . $domain_name;
	include(dirname(__FILE__) . '/www/send_notifications.php');
	exit;
}
if (isset($domain_name)) {
	$_SERVER['HTTP_HOST'] = $domain_name;
	include(dirname(__FILE__) . '/www/send_notifications.php');
	exit;
}
exit;
?>
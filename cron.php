<?php
$home = "/var/zpanel/hostdata/sysclass/public_html";
$user_domains = array(
	'ultbr'		=> 'ult.com.br',
	'sysclass_com'	=> 'sysclass.com',
	'maguser'	=> 'magester.net',
);
$username = null;
$result = sscanf(__FILE__, $home . "/%[a-z_]/cron.php", $username);


if (array_key_exists($username, $user_domains)) {
	$domain_name = $user_domains[$username];
} else {
	exit;
}

$start = strpos(dirname(__FILE__), "subdomains/");
if ($start !== false) {
	$start += strlen("subdomains/");

	$sub = substr(dirname(__FILE__), $start);
	$_SERVER['SERVER_NAME'] =  $_SERVER['HTTP_HOST'] = $sub . '.' . $domain_name;
	include(dirname(__FILE__) . '/www/send_notifications.php');
	exit;
}

$start = strpos(dirname(__FILE__), "sub-domains/");
if ($start !== false) {
	$start += strlen("sub-domains/");

	$sub = substr(dirname(__FILE__), $start);
	$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] = $sub . '.' . $domain_name;

	include(dirname(__FILE__) . '/www/send_notifications.php');
	exit;
}
if (isset($domain_name)) {
	$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] = $domain_name;

	if ($_SERVER['SERVER_NAME'] == 'sysclass.com') {
		$_SERVER['HTTPS'] = 'on';
	}
	include(dirname(__FILE__) . '/www/send_notifications.php');
	include(dirname(__FILE__) . '/www/disable_expired_users.php');
	exit;
}
exit;

<?php
require_once(dirname(__FILE__) . "/super.itau.xpay_boleto_FBarCode.php");
if (!function_exists('xpay_boleto_itau_fajar_FBarCode')) { 
	function xpay_boleto_itau_fajar_FBarCode($params, &$smarty)
	{
		return xpay_boleto_super_itau_FBarCode($params, $smarty);
	} //Fim da função
}

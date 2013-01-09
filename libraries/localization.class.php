<?php
/**
 * Localization Class file
 *
 * @package SysClass
 * @version 3.0
 */

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}

/**
 *
 * @author Andre Kucaniz
 *
 */
class localization
{
	public static function getStateList()
	{
		return array(
			"AC"	=> "Acre",
			"AL"	=> "Alagoas",
			"AM"	=> "Amazonas",
			"AP"	=> "Amapá",
			"BA"	=> "Bahia",
			"CE"	=> "Ceará",
			"DF"	=> "Distrito Federal",
			"ES"	=> "Espírito Santo",
			"GO"	=> "Goiás",
			"MA"	=> "Maranhão",
			"MG"	=> "Minas Gerais",
			"MS"	=> "Mato Grosso do Sul",
			"MT"	=> "Mato Grosso",
			"PA"	=> "Pará",
			"PB"	=> "Paraíba",
			"PE"	=> "Pernambuco",
			"PI"	=> "Piauí",
			"PR"	=> "Paraná",
			"RJ"	=> "Rio de Janeiro",
			"RN"	=> "Rio Grande do Norte",
			"RO"	=> "Rondônia",
			"RR"	=> "Roraima",
			"RS"	=> "Rio Grande do Sul",
			"SE"	=> "Sergipe",
			"SC"	=> "Santa Catarina",
			"SP"	=> "São Paulo",
			"TO"	=> "Tocantins"
		);
	}
	public static function getStateNameById($stateId)
	{
		$stateList = self::getStateList();

		if (array_key_exists($stateId, $stateList)) {
			return $stateList[$stateId];
		}
		return "";
	}
}

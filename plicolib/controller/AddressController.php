<?php 
/**
 * @package PlicoLib\Controllers\Utilities
 */
class AddressController extends PageController
{
	
	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url GET /cep/search/:cep
	 */
	public function searchCep($cep)
	{
//		echo 'http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string';
		$resultado = file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string');
		if (!$resultado) {
			$resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
		}

		parse_str(utf8_encode(urldecode($resultado)), $retorno);

		return $retorno;

	}

	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url POST /cep/search
	 */
	public function searchCepPostAction()
	{
		$cep = $_POST['cep'];

		return $this->searchCep($cep);
	}


}

<?php
//require 'errorHandling.php';
//require_once 'pedido.php';
//require_once 'logger.php';

define('VERSAO', "1.2.0");

//session_start();
/*
if(!isset($_SESSION["pedidos"]))
{
	$_SESSION["pedidos"] = new ArrayObject();
}
*/
// CONSTANTES
if (defined("XPAY_CIELO_DEV") && XPAY_CIELO_DEV) {
	define("ENDERECO_BASE", "https://qasecommerce.cielo.com.br");
} else {
	define("ENDERECO_BASE", "https://ecommerce.cbmp.com.br");
}

define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");

// Envia requisiçãoo
function httprequest($paEndereco, $paPost)
{
	# error_reporting( E_ALL);ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);
	//Uncomment this to get a full list of errors
	$sessao_curl = curl_init();

	curl_setopt($sessao_curl, CURLOPT_URL, $paEndereco);

	curl_setopt($sessao_curl, CURLOPT_FAILONERROR, true);

	//  CURLOPT_SSL_VERIFYPEER
	//  verifica a validade do certificado
	curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, true);
	//  CURLOPPT_SSL_VERIFYHOST
	//  verifica se a identidade do servidor bate com aquela informada no certificado
	curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, 2);

	//  CURLOPT_SSL_CAINFO
	//  informa a localização do certificado para verificação com o peer
	
	if (defined("XPAY_CIELO_DEV") && XPAY_CIELO_DEV) {
		curl_setopt($sessao_curl, CURLOPT_CAINFO, dirname(__FILE__) . "/../ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt");
	} else {
		curl_setopt($sessao_curl, CURLOPT_CAINFO, dirname(__FILE__) . "/../ssl/verisign.crt");
	}
	curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 3);

	//  CURLOPT_CONNECTTIMEOUT
	//  o tempo em segundos de espera para obter uma conexão
	curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);

	//  CURLOPT_TIMEOUT
	//  o tempo máximo em segundos de espera para a execução da requisição (curl_exec)
	curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 400);


	//CURLOPT_HTTPHEADER 	An array of HTTP header fields to set, in the format
	//curl_setopt($sessao_curl, CURLOPT_HTTPHEADER, array('Accept-Charset: ISO-8859-1'));
	//curl_setopt($sessao_curl, CURLOPT_ENCODING, "UTF-8");


	//  CURLOPT_RETURNTRANSFER
	//  TRUE para curl_exec retornar uma string de resultado em caso de sucesso, ao
	//  invés de imprimir o resultado na tela. Retorna FALSE se há problemas na requisição
	curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($sessao_curl, CURLOPT_POST, true);
	curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $paPost );

	$fp = fopen(dirname(__FILE__).'/../logs/curlerror.txt', 'w');
	curl_setopt($sessao_curl, CURLOPT_VERBOSE, 1);
	curl_setopt($sessao_curl, CURLOPT_STDERR, $fp);

	$resultado = curl_exec($sessao_curl);

	if ($resultado) {
		curl_close($sessao_curl);
		return $resultado;
	} else {
		return curl_error($sessao_curl);
	}
}

// Monta URL de retorno
function ReturnURL()
{
	$pageURL = 'http';

	if ($_SERVER["SERVER_PORT"] == 443) // protocolo https
	{
		$pageURL .= 's';
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"]. substr($_SERVER["REQUEST_URI"], 0);
	}
	// ALTERNATIVA PARA SERVER_NAME -> HOST_HTTP
	
	
	$queryString = $_SERVER['QUERY_STRING'];
	$queryArray = explode("&", $queryString);
	$queryItens = array();
	foreach($queryArray as $item) {
		list($queryKey, $queryValue)  = explode("=", $item);
		
		$queryItens[$queryKey] = $queryValue;
	}

	$file = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

	//$ReturnURL = str_replace($file, "retorno.php", $pageURL);
	$ReturnURL = str_replace($_SERVER['QUERY_STRING'], "module/module_xpay_cielo/return_payment", $pageURL);
	
	$ReturnURL = str_replace("?", "?route=" , $ReturnURL);

	return $ReturnURL;
}

function VerificaErro($vmPost, $vmResposta)
{
	$error_msg = null;

	try
	{
		if(stripos($vmResposta, "SSL certificate problem") !== false)
		{
			throw new Exception("CERTIFICADO INVÁLIDO - O certificado da transação não foi aprovado", "099");
		}
		
		$objResposta = simplexml_load_string($vmResposta, null, LIBXML_NOERROR);
		if($objResposta == null)
		{
			throw new Exception("HTTP READ TIMEOUT - o Limite de Tempo da transação foi estourado", "099");
		}
	}
	catch (Exception $ex)
	{
		$error_msg = "     Codigo do erro: " . $ex->getCode() . "\n";
		$error_msg .= "     Mensagem: " . $ex->getMessage() . "\n";

		/*
		// Gera p�gina HTML
		echo '<html><head><title>Erro na transação</title></head><body>';
		echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transação!</span>' . '<br />';
		echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
		echo '' . $error_msg . '<br /><br />';
		//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
		echo '<p><center>';
		echo '<input type="button" value="Retornar" onclick="javascript:if(window.opener!=null){window.opener.location.reload();' .
				'window.close();}else{window.location.href=' . "'index.php';" . '}" />';
		echo '</center></p></body></html>';
		$error_msg .= "     XML de envio: " . "\n" . $vmPost;
		
		*/
		
		// Dispara o erro
		trigger_error($error_msg, E_USER_ERROR);
		
		return array(
			'message'		=> $ex->getMessage(),
			'message_type'	=> 'failure'
		);
	}

	if($objResposta->getName() == "erro")
	{
		$error_msg = "     C�digo do erro: " . $objResposta->codigo . "\n";
		$error_msg .= "     Mensagem: " . utf8_decode($objResposta->mensagem) . "\n";
		// Gera p�gina HTML
		echo '<html><head><title>Erro na transação</title></head><body>';
		echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transação!</span>' . '<br />';
		echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
		echo '' . $error_msg . '<br /><br />';
		//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
		echo '<p><center>';
		echo '<input type="button" value="Retornar" onclick="javascript:if(window.opener!=null){window.opener.location.reload();' .
				'window.close();}else{window.location.href=' . "'index.php';" . '}" />';
		echo '</center></p></body></html>';
		$error_msg .= "     XML de envio: " . "\n" . $vmPost;

		// Dispara o erro
		trigger_error($error_msg, E_USER_ERROR);
	}
}



	class Logger_Model {
		private $log_file = "/../logs/xml.log";
		private $fp = null;
		
		public function __construct() {
			$this->log_file = dirname(__FILE__) . "/../logs/xml.log";
		}
		
		public function logOpen()
		{
			$this->fp = fopen($this->log_file, 'a');
		}
		 
		public function logWrite($strMessage, $transacao)
		{
			if(!$this->fp)
				$this->logOpen();
			
			$path = $_SERVER["REQUEST_URI"];
			$data = date("Y-m-d H:i:s:u (T)");
			
			$log = "***********************************************" . "\n";
			$log .= $data . "\n";
			$log .= "DO ARQUIVO: " . $path . "\n"; 
			$log .= "OPERAÇÃO: " . $transacao . "\n";
			$log .= $strMessage . "\n\n"; 

			fwrite($this->fp, $log);
		}
	}
	
	class Pedido_Model
	{
		private $logger;
				
		public $dadosEcNumero;
		public $dadosEcChave;
		
		public $dadosPortadorNumero;
		public $dadosPortadorVal;
		public $dadosPortadorInd;
		public $dadosPortadorCodSeg;
		public $dadosPortadorNome;
		
		public $dadosPedidoNumero;
		public $dadosPedidoValor;
		public $dadosPedidoMoeda = "986";
		public $dadosPedidoData;
		public $dadosPedidoDescricao;
		public $dadosPedidoIdioma = "PT";
		
		public $formaPagamentoBandeira;
		public $formaPagamentoProduto;
		public $formaPagamentoParcelas;
		
		public $urlRetorno;
		public $autorizar;
		public $capturar;
		public $gerarToken;
		
		public $campoLivre;
		
		public $tid;
		public $status;
		public $urlAutenticacao;
		
		//const ENCODING = "ISO-8859-1";
		const ENCODING = "UTF-8";
		
		function __construct()
		{
			// cria um logger
			$this->logger = new Logger_Model();
		}
		
		// Geradores de XML
		private function XMLHeader()
		{
			return '<?xml version="1.0" encoding="' . self::ENCODING . '" ?>'; 
		}
		
		private function XMLDadosEc()
		{
			$msg = '<dados-ec>' . "\n      " .
						'<numero>'
							. $this->dadosEcNumero . 
						'</numero>' . "\n      " .
						'<chave>'
							. $this->dadosEcChave .
						'</chave>' . "\n   " .
					'</dados-ec>';
							
			return $msg;
		}
		
		private function XMLDadosPortador()
		{
			$msg = '<dados-portador>' . "\n      " . 
						'<numero>' 
							. $this->dadosPortadorNumero .
						'</numero>' . "\n      " .
						'<validade>'
							. $this->dadosPortadorVal .
						'</validade>' . "\n      " .
						'<indicador>'
							. $this->dadosPortadorInd .
						'</indicador>' . "\n      " .
						'<codigo-seguranca>'
							. $this->dadosPortadorCodSeg .
						'</codigo-seguranca>' . "\n   ";
			
			// Verifica se Nome do Portador foi informado
			if($this->dadosPortadorNome != null && $this->dadosPortadorNome != "")
			{
				$msg .= '   <nome-portador>'
							. $this->dadosPortadorNome .
						'</nome-portador>' . "\n   " ;
			}
			
			$msg .= '</dados-portador>';
			
			return $msg;
		}
		
		private function XMLDadosCartao()
		{
			$msg = '<dados-cartao>' . "\n      " . 
						'<numero>' 
							. $this->dadosPortadorNumero .
						'</numero>' . "\n      " .
						'<validade>'
							. $this->dadosPortadorVal .
						'</validade>' . "\n      " .
						'<indicador>'
							. $this->dadosPortadorInd .
						'</indicador>' . "\n      " .
						'<codigo-seguranca>'
							. $this->dadosPortadorCodSeg .
						'</codigo-seguranca>' . "\n   ";

			// Verifica se Nome do Portador foi informado				
			if($this->dadosPortadorNome != null && $this->dadosPortadorNome != "")
			{
				$msg .= '   <nome-portador>'
							. $this->dadosPortadorNome .
						'</nome-portador>' . "\n   " ;
			}
			
			$msg .= '</dados-cartao>';
			
			return $msg;
		}
		
		private function XMLDadosPedido()
		{
			$this->dadosPedidoData = date("Y-m-d") . "T" . date("H:i:s");
			$msg = '<dados-pedido>' . "\n      " .
						'<numero>'
							. $this->dadosPedidoNumero . 
						'</numero>' . "\n      " .
						'<valor>'
							. $this->dadosPedidoValor .
						'</valor>' . "\n      " .
						'<moeda>'
							. $this->dadosPedidoMoeda .
						'</moeda>' . "\n      " .
						'<data-hora>'
							. $this->dadosPedidoData .
						'</data-hora>' . "\n      ";
			if($this->dadosPedidoDescricao != null && $this->dadosPedidoDescricao != "")
			{
				$msg .= '<descricao>'
					. $this->dadosPedidoDescricao .
					'</descricao>' . "\n      ";
			}
			$msg .= '<idioma>'
						. $this->dadosPedidoIdioma .
					'</idioma>' . "\n   " .
					'</dados-pedido>';
							
			return $msg;
		}
		
		private function XMLFormaPagamento()
		{
			$msg = '<forma-pagamento>' . "\n      " .
						'<bandeira>' 
							. $this->formaPagamentoBandeira .
						'</bandeira>' . "\n      " .
						'<produto>'
							. $this->formaPagamentoProduto .
						'</produto>' . "\n      " .
						'<parcelas>'
							. $this->formaPagamentoParcelas .
						'</parcelas>' . "\n   " .
					'</forma-pagamento>';
							
			return $msg;
		}
		 
		private function XMLUrlRetorno()
		{
			$msg = '<url-retorno>' . $this->urlRetorno . '</url-retorno>';
			
			return $msg;
		}
		
		private function XMLAutorizar()
		{
			$msg = '<autorizar>' . $this->autorizar . '</autorizar>';
			
			return $msg;
		}
		
		private function XMLCapturar()
		{
			$msg = '<capturar>' . $this->capturar . '</capturar>';
			
			return $msg;
		}
		private function XMLGerarToken()
		{
			$msg = '<gerar-token>' . $this->gerarToken . '</gerar-token>';
				
			return $msg;
		}
		private function XMLCampoLivre() {
			$msg = '<campo-livre>' . $this->campoLivre . '</campo-livre>';
			
			return $msg;
		}
		
		// Envia Requisição
		public function Enviar($vmPost, $transacao)
		{
			$this->logger->logWrite("ENVIO: " . $vmPost, $transacao);
	
			// ENVIA REQUISIÇÃO SITE CIELO
			$vmResposta = httprequest(ENDERECO, "mensagem=" . $vmPost);
			
			$this->logger->logWrite("RESPOSTA: " . $vmResposta, $transacao);
			
			$error_status = VerificaErro($vmPost, $vmResposta);
			
			return simplexml_load_string($vmResposta);
		}
		
		// Requisições
	public function RequisicaoTransacao($incluirPortador)
	{
		$msg = $this->XMLHeader() . "\n" . '<requisicao-transacao id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n" . $this->XMLDadosEc() . "\n";
		if($incluirPortador == true)
		{
					//$msg .=	$this->XMLDadosPortador() . "\n   ";
		}
							
		$msg .=		  $this->XMLDadosPedido() . "\n   "
			   		. $this->XMLFormaPagamento() . "\n   "
			   		. $this->XMLUrlRetorno() . "\n   "
			   		. $this->XMLAutorizar() . "\n"
			   		. $this->XMLCapturar() . "\n "
			   		. $this->XMLCampoLivre() . "\n"
			   		. $this->XMLGerarToken() . "\n " ;
			
		$msg .= '</requisicao-transacao>';
		
		$objResposta = $this->Enviar($msg, "Transacao");
		return $objResposta;
	}
		
		public function RequisicaoTid()
		{
			$msg = $this->XMLHeader() . "\n" .
				   '<requisicao-tid id="' . md5(date("YmdHisu")) . '" versao ="' . VERSAO . '">' . "\n   "
				        . $this->XMLDadosEc() . "\n   " 
				        . $this->XMLFormaPagamento() . "\n" .
				   '</requisicao-tid>';
				        
			$objResposta = $this->Enviar($msg, "Requisicao Tid");
			return $objResposta;
		}
		
		public function RequisicaoAutorizacaoPortador()
		{
			$msg = $this->XMLHeader() . "\n" .
				   '<requisicao-autorizacao-portador id="' . md5(date("YmdHisu")) . '" versao ="' . VERSAO . '">' . "\n"
				   		. '<tid>' . $this->tid . '</tid>' . "\n   "
				        . $this->XMLDadosEc() . "\n   " 
				        . $this->XMLDadosCartao() . "\n   "
				        . $this->XMLDadosPedido() . "\n   "
				        . $this->XMLFormaPagamento() . "\n   "
				        . '<capturar-automaticamente>' . $this->capturar . '</capturar-automaticamente>' . "\n" .
				   '</requisicao-autorizacao-portador>';
			
			$objResposta = $this->Enviar($msg, "Autorizacao Portador");
			return $objResposta;
		}
		
		public function RequisicaoAutorizacaoTid()
		{
			$msg = $this->XMLHeader() . "\n" .
				 '<requisicao-autorizacao-tid id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n  "
				 	. '<tid>' . $this->tid . '</tid>' . "\n  "
				 	. $this->XMLDadosEc() . "\n" .
				 '</requisicao-autorizacao-tid>';
				 	
			$objResposta = $this->Enviar($msg, "Autorizacao Tid");
			return $objResposta;
		}
		
		public function RequisicaoCaptura($PercentualCaptura = null, $anexo = null)
		{
			$msg = $this->XMLHeader() . "\n" .
				    '<requisicao-captura id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n   "
				   	. '<tid>' . $this->tid . '</tid>' . "\n   "
				   	. $this->XMLDadosEc() . "\n   ";
			
			if (!is_null($PercentualCaptura)) {
				$msg = '<valor>' . $PercentualCaptura . '</valor>' . "\n";
			}
			if($anexo != null && $anexo != "")
			{
				$msg .=	'   <anexo>' . $anexo . '</anexo>' . "\n";
			}
			$msg .= '</requisicao-captura>';
			
			$objResposta = $this->Enviar($msg, "Captura");
			return $objResposta;
		}
		
		public function RequisicaoCancelamento()
		{
			$msg = $this->XMLHeader() . "\n" . 
				   '<requisicao-cancelamento id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n   "
				    . '<tid>' . $this->tid . '</tid>' . "\n   "
				    . $this->XMLDadosEc() . "\n" .
				   '</requisicao-cancelamento>';
			
			$objResposta = $this->Enviar($msg, "Cancelamento");
			return $objResposta;
		}
		
		public function RequisicaoConsulta()
		{
			$msg = $this->XMLHeader() . "\n" .
				   '<requisicao-consulta id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n   "
				    . '<tid>' . $this->tid . '</tid>' . "\n   "
				    . $this->XMLDadosEc() . "\n" .
				   '</requisicao-consulta>';
			
			$objResposta = $this->Enviar($msg, "Consulta");
			return $objResposta;
		}
		
		
		// Transforma em string
		public function ToString()
		{
			$msg = $this->XMLHeader() .
				   '<objeto-pedido>'
				    . '<tid>' . $this->tid . '</tid>'
				    . '<status>' . $this->status . '</status>'
				   	. $this->XMLDadosEc()
				   	. $this->XMLDadosPedido()
				   	. $this->XMLFormaPagamento() .
				   '</objeto-pedido>';
				   	
			return $msg;
		}
		
		public function FromString($Str)
		{
			$DadosEc = "dados-ec";
			$DadosPedido = "dados-pedido";
			$DataHora = "data-hora";
			$FormaPagamento = "forma-pagamento";
			
			$XML = simplexml_load_string($Str);
			
			$this->tid = $XML->tid;
			$this->status = $XML->status;
			$this->dadosEcChave = $XML->$DadosEc->chave;
			$this->dadosEcNumero = $XML->$DadosEc->numero;
			$this->dadosPedidoNumero = $XML->$DadosPedido->numero;
			$this->dadosPedidoData = $XML->$DadosPedido->$DataHora;
			$this->dadosPedidoValor = $XML->$DadosPedido->valor;
			$this->formaPagamentoProduto = $XML->$FormaPagamento->produto;
			$this->formaPagamentoParcelas = $XML->$FormaPagamento->parcelas;
		}
		
		// Traduz cógigo do Status
		public function getStatus()
		{
			switch($this->status)
			{
				case "0": $status = "Criada";
						break;
				case "1": $status = "Em andamento";
						break;
				case "2": $status = "Autenticada";
						break;
				case "3": $status = "Não autenticada";
						break;
				case "4": $status = "Autorizada";
						break;
				case "5": $status = "Não autorizada";
						break;
				case "6": $status = "Capturada";
						break;
				case "8": $status = "Não capturada";
						break;
				case "9": $status = "Cancelada";
						break;
				case "10": $status = "Em autenticação";
						break;
				default: $status = "n/a";
						break;
			}
			
			return $status;
		}
		
	}
?>

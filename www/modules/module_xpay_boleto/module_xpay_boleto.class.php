<?php
require_once (dirname(__FILE__) . '/../module_xpay/module_xpay_submodule.interface.php');

class module_xpay_boletoException extends Exception
{
	const DIFERENT_REGISTERTYPE = 10001;
}

class module_xpay_boleto extends MagesterExtendedModule implements IxPaySubmodule, ICronable {
	
	//protected static $subModules = null;
	
/*	
	protected $conf = array(
		// Opção => Autorizar transação autenticada e não-autenticada
		'authorization'					=> 2,	
		'auto_capture'					=> "false",
		// [A - Débito, 1- Crédito, 2 - loja, 3 - Administradora]
		'payment_subdivision_method'	=> 3
	);
*/ 
	public function loadConfig() {
		$return_root = $this->moduleBaseDir . "retorno/";
		self::$_CONFIG = array(
			'on_overdue'		=> 'refresh', // CAN BE 'refresh, block or none'
			'partial_payment'	=> false, // CAN BE 'refresh, block or none'
			'paths'			=> array(
				'return_root'		=> $return_root,
				'return_proc'		=> $return_root . "proc/",
				'return_error'		=> $return_root . "error/",
				'return_instance'	=> $return_root . "%s/"
			),
			'cron_file_limit'	=> 5
		);
		return self::$_CONFIG;
	}
	
	public function getName() {
		return "XPAY_BOLETO";
	}
	
	public function getPermittedRoles() {
		return array("administrator", "student");
	}
	
	public function callCronEventAction() {
		$totalProcessed = $this->onCronEvent(array(
			'exec_limit' => false			
		));
		
		if ($totalProcessed > 0) {
			eF_redirect(sprintf(
				$this->moduleBaseUrl . "&action=send_return_file&message=%s&message_type=%s",
				sprintf(__XPAY_BOLETO_X_FILE_RETURNS, $totalProcessed),
				"success"
			));
		} else {
			eF_redirect(sprintf(
				$this->moduleBaseUrl . "&action=send_return_file&message=%s&message_type=%s",
				__XPAY_BOLETO_NO_FILE_QUEUE,
				"warning"
			));
		}
		exit;
	} 
	/* ICronable INTERFACE FUNCTIONS */
	public function onCronEvent(array $contraints) {
		// PROCCESS QUEUE LIST FILES
		$queueList = $this->getOnQueueFilesList();
		if ($contraints['exec_limit'] === FALSE) {
			$maxFiles =  null;
		} elseif (is_numeric($contraints['exec_limit']) && $contraints['exec_limit'] > 0) {
			$maxFiles =  $contraints['exec_limit'];
		} else {
			$maxFiles =  $this->getConfig()->cron_file_limit;
		}

		$count = 0;

		foreach($queueList as $methodFiles) {
			foreach($methodFiles['files'] as $file) {
//				var_dump($file['name']);
				$fileProcPath = $file['fullpath'];
				$status = $this->analyzeReturnFile($fileProcPath);
				// WITH RESULT, REFRESH PAID ITENS
				if ($this->importFileStatusToSystem($file['method_index'], $status)) {

					// MOVE FILE TO YOUR OWN PATH
					$finalPath = sprintf($this->getConfig()->paths['return_instance'], $file['method_index']);
					rename($fileProcPath, $finalPath . $file['name']);
				}
				
				$count++;
				if (is_numeric($maxFiles) && $maxFiles > 0 && $count >= $maxFiles) {
					break;
				}
			}
		}
		return $count; 
	}
	
	private function importFileStatusToSystem($instance_id, $fileStatus) {
		$xpayModule = $this->loadModule("xpay");
		
		//$registro $fileStatus['batch'][0]['registros']
		foreach($fileStatus['batch'] as $lote) {
			foreach($lote['registros'] as $registro) {
				$boletoTransaction = array(
					'instance_id' 			=> $instance_id,
					'nosso_numero'			=> $registro['nosso_numero']['parseddata'],
					'data_pagamento' 		=> date("Y-m-d", $registro['data_ocorrencia']['parseddata']),
					'ocorrencia_id'			=> '???',
					'liquidacao_id'			=> '???',
					'valor_titulo'			=> $registro['valor_titulo']['parseddata'],
					'valor_abatimento' 		=> $registro['valor_abatimento']['parseddata'],
					'valor_desconto'		=> $registro['valor_desconto']['parseddata'],
					'valor_juros_multa' 	=> $registro['valor_juros_multa']['parseddata'],
					'valor_outros_creditos'	=> $registro['valor_outros_creditos']['parseddata'],
					'valor_tarifas'			=> $registro['valor_tarifas']['parseddata'],
					'valor_pago'			=> $registro['valor_pago']['parseddata'],
					'valor_total'	 		=> $registro['valor_total']['parseddata'],
					'tag'					=> json_encode($status),
					'filename'				=> $fileFullPath
				);
				//var_dump($boletoTransaction);
				
				// STEP 1 - CHECK WITH IS ALREADY IMPORTED
				list($countReturn) = ef_countTableData(
					"module_xpay_boleto_transactions", 
					"id", 
					sprintf("nosso_numero = '%s'", $registro['nosso_numero']['parseddata'])
				);
				if (intval($countReturn['count']) == 0) {
					
					$boletoTransID = eF_insertTableData("module_xpay_boleto_transactions", $boletoTransaction);
					
					$paid_items = array(
						'transaction_id'	=> $boletoTransID,
						'method_id' 		=> 'boleto',
						'paid' 				=> $registro['valor_pago']['parseddata'],
						'start_timestamp' 	=> $registro['data_ocorrencia']['parseddata']
					);
					
					$paidID = ef_insertTableData(
						"module_xpay_paid_items",
						$paid_items
					);
					
					// GRAB NEGOCIATION ID, INVOICE_INDEX FORM "nosso_numero"
					$values = sscanf($boletoTransaction['nosso_numero'], "%03d%03d%05d%04d", $course_id, $invoice_index, $user_id, $negociation_id);
//					list($course_id, $invoice_index, $user_id, $negociation_id) = $values;
//					var_dump($boletoTransaction['nosso_numero']);
//					var_dump($values);
//					var_dump($course_id, $invoice_index, $user_id, $negociation_id);
					
					$negociation = $xpayModule->_getNegociationByContraints(array(
						'negociation_id'	=> $negociation_id,
						'user_id'			=> $user_id,
						'course_id'			=> $course_id	
					));
					
					if (count($negociation) > 0) {
						// ENCONTROU A NEGOCIAÇÃO
						//var_dump($negociation);
						$paidInvoice = $xpayModule->_getNegociationInvoiceByIndex($negociation['id'], $invoice_index);
						
						if (count($paidInvoice) > 0) {
							// ENCONTROU A FATURA
							$item = array(
								'negociation_id'	=> $negociation['id'],
								'invoice_index'		=> $invoice_index,
								'paid_id'			=> $paidID
							);
							
							if ($registro['valor_pago']['parseddata'] < $paidInvoice['valor'] && !$config['partial_payment']) {
								$item['full_value']	= $paidInvoice['valor'];
							}

							ef_insertTableData(
								"module_xpay_invoices_to_paid",
								$item
							);
						}
						// BUSCAR A FATURA AGORA
					}
				}
			}
		}
		return true;
	}
	
	private function analyzeReturnFile($fullFileName) {
		// CHECK FILE INTEGRITY AND IMPORT INFORMATION
		// STEPS:
		// 1. MOSTRAR TODOS OS BOLETOS ENCONTRADOS NO SISTEMA, E MARCAR COM O STATUS VINDO DO ARQUIVO.
		// 2. MOSTRAR OS BOLETOS NÃO ENCONTRADOS, PARA RESOLUÇÂO OU REGISTRO
		// 3. MOSTRAR O STATUS DA POSSÍVEL IMPORTAÇÃO
		// 1. Carregar todos os boletos encontrados no arquivo, e marcar quais foram encontrados no sistema (ordenar por itens não encontrados).
		$lines = file($fullFileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		//$handle = fopen($destDir . $retFileName, 'r');
		
		//var_dump($lines);
	
		//echo $result;
	
		$result = array();
	
		// BEGIN PROCESS FILE
		// HEADER FILE
		foreach($lines as $key => $line) {
			if ($line{7} === "0") {
				$result['header'] = $this->processReturnFileHeader($line);
			} elseif ($line{7} === "1") {
				$openBatch = array(
					'header'	=> null,
					'footer'	=> null,
					'registros' => array(),
					'registrosT' => array(),
					'registrosU' => array()
				);
				$openBatch['header'] = $this->processReturnFileBatchHeader($line);
			} elseif ($line{7} === "3") {
				if ($line{13} == "T") {
					$openBatch['registrosT'][] = $this->processReturnFileTransactionTSegment($line);
				} elseif ($line{13} == "U") {
					$openBatch['registrosU'][] = $this->processReturnFileTransactionUSegment($line);
				}
			} elseif ($line{7} === "5") {
				$openBatch['footer'] = $this->processReturnFileBatchFooter($line);
				$result['batch'][] = $openBatch;
			} elseif ($line{7} === "9") {
				$result['footer'] = $this->processReturnFileFooter($line);
			} else {
				//var_dump($line{7});
			}
		}
		foreach($result['batch'] as $lote_index => $lote) {
			// MERGE "T" AND "U" SEGMENTS
			$keys = array_keys($lote['registrosT']);
			foreach($keys as $key) {
				$result['batch'][$lote_index]['registros'][] = array_merge($lote['registrosT'][$key], $lote['registrosU'][$key]);
			}
		}

		return $result;
	}
	
	private function processReturnFileHeader($fileLine) {
		if ($fileLine{7} != 0) {
			throw new module_xpay_boletoException(_XPAY_BOLETO_REGISTERTYPEISDIFERENT, module_xpay_boletoException::DIFERENT_REGISTERTYPE);
		}
		/*
		array(21) {
			"tipo" "cod_retorno" "retorno" "cod_servico" "servico" "" [6]=> string(12) "complemento1" [7]=> string(5) "conta" [8]=> string(3) "dac" [9]=> string(12) "complemento2" [10]=> string(12) "nome_empresa" [11]=> string(9) "cod_banco" [12]=> string(10) "nome_banco" [13]=> string(12) "data_geracao" [14]=> string(9) "densidade" [15]=> string(23) "nro_seq_arquivo_retorno" [16]=> string(13) "uni_densidade" [17]=> string(12) "data_credito" [18]=> string(12) "complemento3" [19]=> string(23) "nro_sequencial_registro" [20]=> string(9) "CAIXA1111" }

			
			
			
			-uni_densidade
			
			data_credito
			nro_sequencial_registro
			servico
			conta
			*/
		$headerParameters = array(
			'cod_banco'			=> array(
				'label'			=> 'Banco',
				'description'	=> 'Código do Banco na Compensação',
				'type'			=> 'int',
				'size'			=> 3
			),
			'cod_servico'		=> array(
				'label'			=> 'Lote',
				'description'	=> 'Lote de Serviço',
				'type'			=> 'int',
				'size'			=> 4
			),
			'tipo'					=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Registro',
				'type'			=> 'int',
				'size'			=> 1
			),
			'uso_exclusivo_cnab1'				=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 9
			),
			'tipo_inscricao'					=> array(
				'label'			=> 'Tipo de Inscrição',
				'description'	=> 'Tipo de Inscrição da Empresa',
				'type'			=> 'int',
				'size'			=> 1
			),
			'nro_inscricao'					=> array(
				'label'			=> 'Número de Inscrição',
				'description'	=> 'Número de Inscrição da Empresa',
				'type'			=> 'int',
				'size'			=> 14
			),
			// ZEROS                   	031 032  	9(02)	“00”
			'uso_exclusivo_caixa1'				=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo CAIXA',
				'type'			=> 'int',
				'size'			=> 20
			),
			'agencia'						=> array(
				'label'			=> 'Agência Código',
				'description'	=> 'Agência Mantenedora da Conta',
				'type'			=> 'int',
				'size'			=> 5
			),
			// DAC                     	038 038  	9(01)	""
			'dac'						=> array(
				'label'			=> 'DV da Conta',
				'description'	=> 'Dígito Verificador da Agência',
				'type'			=> 'int',
				'size'			=> 1
			),
			// DAC                     	038 038  	9(01)	""
			'codigo_cedente'						=> array(
				'label'			=> 'Código Cedente',
				'description'	=> 'Código do Convênio no Banco',
				'type'			=> 'int',
				'size'			=> 6
			),				
			'uso_exclusivo_caixa2'				=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo CAIXA',
				'type'			=> 'int',
				'size'			=> 7
			),
			'uso_exclusivo_caixa3'				=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo CAIXA',
				'type'			=> 'int',
				'size'			=> 1
			),
			'nome_empresa'				=> array(
				'label'			=> 'Nome da Empresa',
				'description'	=> 'Nome da Empresa',
				'type'			=> 'string',
				'size'			=> 30
			),
			'nome_banco'					=> array(
				'label'			=> 'Nome do Banco',
				'description'	=> 'Nome do Banco',
				'type'			=> 'string',
				'size'			=> 30
			),
			'uso_exclusivo_cnab2'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 10
			),
			'cod_retorno'				=> array(
				'label'			=> 'Código',
				'description'	=> 'Código Remessa / Retorno',
				'type'			=> 'int',
				'size'			=> 1
			),
			// DENSIDADE               	101 105  	9(05)	""
			'data_geracao'			=> array(
				'label'			=> 'Data de Geração',
				'description'	=> 'Data de Geração do Arquivo',
				'type'			=> 'date',
				'size'			=> 8
			),
			'hora_geracao'			=> array(
				'label'			=> 'Hora de Geração',
				'description'	=> 'Hora de Geração do Arquivo',
				'type'			=> 'time',
				'size'			=> 6
			),
			// No SEQ. ARQUIVO RET.    	109 113  	9(05)	""
			'nro_seq_arquivo_retorno'	=> array(
				'label'			=> 'Sequência (NSA)',
				'description'	=> 'Número Seqüencial do Arquivo',
				'type'			=> 'int',
				'size'			=> 6
			),
			'layout_arquivo'	=> array(
				'label'			=> 'Layout do Arquivo',
				'description'	=> 'No da Versão do Layout do Arquivo',
				'type'			=> 'int',
				'size'			=> 3
			),
			'densidade'				=> array(
				'label'			=> 'Densidade',
				'description'	=> 'Densidade de Gravação do Arquivo',
				'type'			=> 'int',
				'size'			=> 5
			),
			// DATA DE CRÉDITO         	114 119  	9(06)	DDMMAA
			'uso_exclusivo_caixa4'				=> array(
				'label'			=> 'Reservado Banco',
				'description'	=> 'Para Uso Reservado do Banco',
				'type'			=> 'string',
				'size'			=> 20
			),
			// BRANCOS					120 394 	X(275)	""
			'uso_exclusivo_empresa'				=> array(
				'label'			=> 'Reservado Empresa',
				'description'	=> 'Para Uso Reservado do Empresa',
				'type'			=> 'string',
				'size'			=> 20
			),
			'versao_app'	=> array(
				'label'			=> 'Versão Aplicativo',
				'description'	=> 'Versão Aplicativo CAIXA',
				'type'			=> 'string',
				'size'			=> 4
			),
			'uso_exclusivo_cnab3'	=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 25
			)
		);
		$sizes = array();
		$size = 0;
		foreach($headerParameters as $param) {
			$sizes[] = $param['size'];

		}
	
		$headerData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$headerData = array_combine(array_keys($headerParameters), $headerData);
	
		$result = array();
	
		foreach($headerParameters as $headerName => $headerItem) {
			$toMerge = $this->coercionDataByType($headerData[$headerName], $headerItem['type'], $headerItem['format']);
			$result[$headerName] = array_merge($headerItem, $toMerge);
		}
	
		return $result;
	
	}
	private function processReturnFileBatchHeader($fileLine) {
		if ($fileLine{7} != 1) {
			throw new module_xpay_boletoException(_XPAY_BOLETO_REGISTERTYPEISDIFERENT, module_xpay_boletoException::DIFERENT_REGISTERTYPE);
		}
		$headerParameters = array(
			'cod_banco'			=> array(
				'label'			=> 'Banco',
				'description'	=> 'Código do Banco na Compensação',
				'type'			=> 'int',
				'size'			=> 3
			),
			'cod_servico'		=> array(
				'label'			=> 'Lote',
				'description'	=> 'Lote de Serviço',
				'type'			=> 'int',
				'size'			=> 4
			),
			'tipo'					=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Registro',
				'type'			=> 'int',
				'size'			=> 1
			),
			'tipo_operacao'			=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Operação',
				'type'			=> 'int',
				'size'			=> 1
			),
			'tipo_servico'			=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Serviço',
				'type'			=> 'int',
				'size'			=> 2
			),
			'uso_exclusivo_cnab1'				=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'int',
				'size'			=> 2
			),
			'layout_lote'	=> array(
				'label'			=> 'Layout do Lote',
				'description'	=> 'No da Versão do Layout do Lote',
				'type'			=> 'int',
				'size'			=> 3
			),
			'uso_exclusivo_cnab2'				=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 1
			),
			'tipo_inscricao'					=> array(
				'label'			=> 'Tipo de Inscrição',
				'description'	=> 'Tipo de Inscrição da Empresa',
				'type'			=> 'int',
				'size'			=> 1
			),
			'nro_inscricao'					=> array(
				'label'			=> 'Número de Inscrição',
				'description'	=> 'Número de Inscrição da Empresa',
				'type'			=> 'int',
				'size'			=> 15
			),
			'codigo_convenio'						=> array(
				'label'			=> 'Código Cedente',
				'description'	=> 'Código do Convênio no Banco',
				'type'			=> 'int',
				'size'			=> 6
			),
				
				
			'uso_exclusivo_caixa1'				=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo CAIXA',
				'type'			=> 'int',
				'size'			=> 14
			),
			'agencia'						=> array(
				'label'			=> 'Agência Código',
				'description'	=> 'Agência Mantenedora da Conta',
				'type'			=> 'int',
				'size'			=> 5
			),
			'dac'						=> array(
				'label'			=> 'DV da Conta',
				'description'	=> 'Dígito Verificador da Agência',
				'type'			=> 'int',
				'size'			=> 1
			),
			'codigo_cedente'						=> array(
				'label'			=> 'Código Cedente',
				'description'	=> 'Código do Convênio no Banco',
				'type'			=> 'int',
				'size'			=> 6
			),
			'codigo_modelo'						=> array(
				'label'			=> 'Código Cedente',
				'description'	=> 'Código do Convênio no Banco',
				'type'			=> 'int',
				'size'			=> 7
			),				
			'uso_exclusivo_caixa2'				=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo CAIXA',
				'type'			=> 'int',
				'size'			=> 1
			),
			'nome_empresa'				=> array(
				'label'			=> 'Nome da Empresa',
				'description'	=> 'Nome da Empresa',
				'type'			=> 'string',
				'size'			=> 30
			),
			'mensagem1'				=> array(
				'label'			=> 'Mensagem 1',
				'description'	=> 'Mensagem 1',
				'type'			=> 'string',
				'size'			=> 40
			),
			'mensagem2'				=> array(
				'label'			=> 'Mensagem 2',
				'description'	=> 'Mensagem 2',
				'type'			=> 'string',
				'size'			=> 40
			),
			'nro_retorno'				=> array(
				'label'			=> 'Número Retorno',
				'description'	=> 'Número Remessa/Retorno',
				'type'			=> 'int',
				'size'			=> 8
			),
			'data_gravacao'			=> array(
				'label'			=> 'Data de Geração',
				'description'	=> 'Data de Geração do Arquivo',
				'type'			=> 'date',
				'size'			=> 8
			),
			'data_credito'			=> array(
				'label'			=> 'Data de Geração',
				'description'	=> 'Data de Geração do Arquivo',
				'type'			=> 'date',
				'size'			=> 8
			),				
			'uso_exclusivo_cnab3'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 33
			)
		);
		$sizes = array();
		$size = 0;
		foreach($headerParameters as $param) {
			$sizes[] = $param['size'];
			
			$size += $param['size'];
		
		}
		
		$headerData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$headerData = array_combine(array_keys($headerParameters), $headerData);
		
		$result = array();
		
		foreach($headerParameters as $headerName => $headerItem) {
			$toMerge = $this->coercionDataByType($headerData[$headerName], $headerItem['type'], $headerItem['format']);
			$result[$headerName] = array_merge($headerItem, $toMerge);
		}
		
		return $result;
	}
	private function processReturnFileTransactionTSegment($fileLine) {
		if ($fileLine{7} != 3) {
			throw new module_xpay_boletoException(_XPAY_BOLETO_REGISTERTYPEISDIFERENT, module_xpay_boletoException::DIFERENT_REGISTERTYPE);
		}
		
		$transParameters = array(
			'cod_banco'			=> array(
				'label'			=> 'Banco',
				'description'	=> 'Código do Banco na Compensação',
				'type'			=> 'int',
				'size'			=> 3
			),
			'cod_servico'		=> array(
				'label'			=> 'Lote',
				'description'	=> 'Lote de Serviço',
				'type'			=> 'int',
				'size'			=> 4
			),
			'tipo'					=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Registro',
				'type'			=> 'int',
				'size'			=> 1
			),
			'nro_sequencial_registro'	=> array(
				'label'			=> 'Sequencial do Registro',
				'description'	=> 'Número Seqüencial Registro no Lote',
				'type'			=> 'int',
				'size'			=> 5
			),
			'codigo_segmento'	=> array(
				'label'			=> 'Código Segmento',
				'description'	=> 'Código Segmento do Registro Detalhe',
				'type'			=> 'string',
				'size'			=> 1
			),
			'uso_exclusivo_cnab1'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 1
			),
			'cod_movimento'		=> array(
				'label'			=> 'Código Movimento',
				'description'	=> 'Código de Movimento Retorno',
				'type'			=> 'string',
				'size'			=> 2
			),
			'agencia'						=> array(
				'label'			=> 'Agência Código',
				'description'	=> 'Agência Mantenedora da Conta',
				'type'			=> 'int',
				'size'			=> 5
			),
			'dac'						=> array(
				'label'			=> 'DV da Conta',
				'description'	=> 'Dígito Verificador da Agência',
				'type'			=> 'int',
				'size'			=> 1
			),
			'codigo_cedente'						=> array(
				'label'			=> 'Código Cedente',
				'description'	=> 'Código do Convênio no Banco',
				'type'			=> 'int',
				'size'			=> 6
			),
			'uso_exclusivo_caixa1'		=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo Caixa',
				'type'			=> 'int',
				'size'			=> 3
			),
			'numero_banco'			=> array(
				'label'			=> 'Número do Banco',
				'description'	=> 'Número do Banco de Sacados',
				'type'			=> 'int',
				'size'			=> 3
			),
			'uso_exclusivo_caixa2'		=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo Caixa',
				'type'			=> 'int',
				'size'			=> 4
			),
			'modalidade_nosso_numero'		=> array(
				'label'			=> 'Modalidade Nosso Número',
				'description'	=> 'Modalidade Nosso Número',
				'type'			=> 'int',
				'size'			=> 2
			),
			'nosso_numero'		=> array(
				'label'			=> 'Modalidade Nosso Número',
				'description'	=> 'Identificação do Título no Banco',
				'type'			=> 'string',
				'size'			=> 15
			),
			'uso_exclusivo_caixa4'		=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo Caixa',
				'type'			=> 'int',
				'size'			=> 1
			),
			'cod_carteira'		=> array(
				'label'			=> 'Código da Carteira',
				'description'	=> 'Código da Carteira',
				'type'			=> 'int',
				'size'			=> 1
			),
			'meu_numero'		=> array(
				'label'			=> 'Número do Documento',
				'description'	=> 'Número do Documento de Cobrança',
				'type'			=> 'string',
				'size'			=> 11
			),
			'uso_exclusivo_caixa3'		=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo Caixa',
				'type'			=> 'int',
				'size'			=> 4
			),
			'data_vencimento'		=> array(
				'label'			=> 'Data de Vencimento',
				'description'	=> 'Data de Vencimento do Título',
				'type'			=> 'date',
				'size'			=> 8
			),
			'valor_titulo'		=> array(
				'label'			=> 'Valor do Título',
				'description'	=> 'Valor Nominal do Título',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'banco_receptor'		=> array(
				'label'			=> 'Código do Banco',
				'description'	=> 'Código do Banco',
				'type'			=> 'int',
				'size'			=> 3
			),
			'agencia_receptor'		=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Código da Agência Cobr/Receb',
				'type'			=> 'int',
				'size'			=> 5
			),
			'dv_agencia_receptor'		=> array(
				'label'			=> 'Dígito Verificador da Agência',
				'description'	=> 'Dígito Verificador da Agência Cobr/Rec',
				'type'			=> 'int',
				'size'			=> 1
			),
			'uso_empresa'		=> array(
				'label'			=> 'Identificação do Título na Empresa',
				'description'	=> 'Identificação do Título na Empresa',
				'type'			=> 'string',
				'size'			=> 25
			),
			'cod_moeda'		=> array(
				'label'			=> 'Código da Moeda',
				'description'	=> 'Código da Moeda',
				'type'			=> 'int',
				'size'			=> 2
			),
			'sacado_tipo_inscricao'		=> array(
				'label'			=> 'Tipo de Inscrição',
				'description'	=> 'Tipo de Inscrição',
				'type'			=> 'int',
				'size'			=> 1
			),
			'sacado_inscricao'		=> array(
				'label'			=> 'CPF/CNPJ',
				'description'	=> 'Número de Inscrição',
				'type'			=> 'int',
				'size'			=> 15
			),
			'sacado'		=> array(
				'label'			=> 'Nome',
				'description'	=> 'Nome do Sacado',
				'type'			=> 'string',
				'size'			=> 40
			),
			'uso_exclusivo_cnab2'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 10
			),
			'valor_tarifas'		=> array(
				'label'			=> 'Valor da Tarifa',
				'description'	=> 'Valor da Tarifa / Custas',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'motivo_ocorrencia'		=> array(
				'label'			=> 'Identificação de Ocorrências',
				'description'	=> 'Identificação para Rejeições, Tarifas, Custas, Liquidação e Baixas',
				'type'			=> 'string',
				'size'			=> 10
			),
			'uso_exclusivo_cnab3'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 17
			)
		);
				
		$sizes = array();
		foreach($transParameters as $param) {
			$sizes[] = $param['size'];
			
			$size += $param['size'];
		}
		
		$transData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$transData = array_combine(array_keys($transParameters), $transData);
		
		$result = array();
		
		foreach($transParameters as $transName => $transItem) {
			$toMerge = $this->coercionDataByType($transData[$transName], $transItem['type'], $transItem['format']);
			$result[$transName] = array_merge($transItem, $toMerge);
		}
		
		return $result;
	}
	private function processReturnFileTransactionUSegment($fileLine) {
		if ($fileLine{7} != 3) {
			throw new module_xpay_boletoException(_XPAY_BOLETO_REGISTERTYPEISDIFERENT, module_xpay_boletoException::DIFERENT_REGISTERTYPE);
		}
	
		$transParameters = array(
			'cod_banco'			=> array(
				'label'			=> 'Banco',
				'description'	=> 'Código do Banco na Compensação',
				'type'			=> 'int',
				'size'			=> 3
			),
			'cod_servico'		=> array(
				'label'			=> 'Lote',
				'description'	=> 'Lote de Serviço',
				'type'			=> 'int',
				'size'			=> 4
			),
			'tipo'					=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Registro',
				'type'			=> 'int',
				'size'			=> 1
			),
			'nro_sequencial_registro'	=> array(
				'label'			=> 'Sequencial do Registro',
				'description'	=> 'Número Seqüencial Registro no Lote',
				'type'			=> 'int',
				'size'			=> 5
			),
			'codigo_segmento'	=> array(
				'label'			=> 'Código Segmento',
				'description'	=> 'Código Segmento do Registro Detalhe',
				'type'			=> 'string',
				'size'			=> 1
			),
			'uso_exclusivo_cnab1'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 1
			),
			'cod_movimento'		=> array(
				'label'			=> 'Código Movimento',
				'description'	=> 'Código de Movimento Retorno',
				'type'			=> 'string',
				'size'			=> 2
			),
				// VALOR PRINCIPAL          254 266 9(11)V9(2)
			'valor_juros_multa'					=> array(
				'label'			=> 'Valor Total',
				'description'	=> 'Juros / Multa / Encargos',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_desconto'					=> array(
				'label'			=> 'Valor do Desconto',
				'description'	=> 'Valor do Desconto Concedido',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_abatimento'					=> array(
				'label'			=> 'Valor do Abat.',
				'description'	=> 'Valor do Abat. Concedido/Cancel.',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_iof'					=> array(
				'label'			=> 'Valor IOF',
				'description'	=> 'Valor do IOF Recolhido',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_pago'					=> array(
				'label'			=> 'Valor Pago',
				'description'	=> 'Valor Pago pelo Sacado',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_total'					=> array(
				'label'			=> 'Valor Líquido',
				'description'	=> 'Valor Líquido a ser Creditado',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_outros_debitos'					=> array(
				'label'			=> 'Valor Despesas',
				'description'	=> 'Valor de Outras Despesas',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'valor_outros_creditos'					=> array(
				'label'			=> 'Valor Créditos',
				'description'	=> 'Valor de Outros Créditos',
				'type'			=> 'float15',
				'size'			=> 15
			),
			'data_ocorrencia'		=> array(
				'label'			=> 'Data da Ocorrência',
				'description'	=> 'Data da Ocorrência',
				'type'			=> 'date',
				'size'			=> 8
			),
			'data_credito'		=> array(
				'label'			=> 'Data do Crédito',
				'description'	=> 'Data da Efetivação do Crédito',
				'type'			=> 'date',
				'size'			=> 8
			),
			'numero_banco'			=> array(
				'label'			=> 'Número do Banco',
				'description'	=> 'Número do Banco de Sacados',
				'type'			=> 'int',
				'size'			=> 3
			),
			'nome_banco'			=> array(
				'label'			=> 'Nome do Banco',
				'description'	=> 'Nome do Banco de Sacados',
				'type'			=> 'string',
				'size'			=> 20
			),
			'ajuste_vencimento'		=> array(
				'label'			=> 'Ajuste Vencimento',
				'description'	=> 'ID Ajuste Vencimento',
				'type'			=> 'int',
				'size'			=> 1
			),
			'ajuste_emissao'		=> array(
				'label'			=> 'Ajuste Emissão',
				'description'	=> 'ID Ajuste Emissão',
				'type'			=> 'int',
				'size'			=> 1
			),
			'modelo_bloqueto'		=> array(
				'label'			=> 'ID Modelo Bloqueto - Banco Sacados',
				'description'	=> 'ID Modelo Bloqueto - Banco Sacados',
				'type'			=> 'int',
				'size'			=> 2
			),
			'via_entrega'		=> array(
				'label'			=> 'Via Entrega',
				'description'	=> 'ID Via Entrega / Distribuição',
				'type'			=> 'int',
				'size'			=> 1
			),
				
			'especie_titulo'		=> array(
				'label'			=> 'Espécie Título',
				'description'	=> 'ID Espécie Título',
				'type'			=> 'int',
				'size'			=> 2
			),
			'aceite'		=> array(
				'label'			=> 'Aceite',
				'description'	=> 'ID Aceite',
				'type'			=> 'string',
				'size'			=> 1
			),
			'codigo_sacado'		=> array(
				'label'			=> 'Código Sacado Banco',
				'description'	=> 'Código do Sacado no Banco',
				'type'			=> 'string',
				'size'			=> 15
			),
			'uso_exclusivo_caixa1'		=> array(
				'label'			=> 'Uso Exclusivo',
				'description'	=> 'Uso Exclusivo Caixa',
				'type'			=> 'string',
				'size'			=> 11
			),
			'uso_exclusivo_cnab2'		=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 30
			)
		);
	
		$sizes = array();
		foreach($transParameters as $param) {
			$sizes[] = $param['size'];
				
			$size += $param['size'];
		}
	
		$transData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$transData = array_combine(array_keys($transParameters), $transData);
	
		$result = array();
	
		foreach($transParameters as $transName => $transItem) {
			$toMerge = $this->coercionDataByType($transData[$transName], $transItem['type'], $transItem['format']);
			$result[$transName] = array_merge($transItem, $toMerge);
		}
	
		return $result;
	}
/*
	private function processReturnFileOptional($fileLine) {
		if ($fileLine{0} != 4) {
			throw new module_pagamento_boletoException(_PAGAMENTO_REGISTERTYPEISDIFERENT, module_pagamentoException::DIFERENT_REGISTERTYPE);
		}
	
		return array();
	}
*/	
	private function processReturnFileBatchFooter($fileLine) {
		if ($fileLine{7} != 5) {
			throw new module_xpay_boletoException(_XPAY_BOLETO_REGISTERTYPEISDIFERENT, module_xpay_boletoException::DIFERENT_REGISTERTYPE);
		}
	
		$footerParameters = array(
			'cod_banco'			=> array(
				'label'			=> 'Banco',
				'description'	=> 'Código do Banco na Compensação',
				'type'			=> 'int',
				'size'			=> 3
			),
			'cod_servico'		=> array(
				'label'			=> 'Lote',
				'description'	=> 'Lote de Serviço',
				'type'			=> 'int',
				'size'			=> 4
			),
			'tipo'					=> array(
				'label'			=> 'Registro',
				'description'	=> 'Tipo de Registro',
				'type'			=> 'int',
				'size'			=> 1
			),
			'uso_exclusivo_cnab1'				=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 9
			),
			'qtde_registros'	=> array(
				'label'			=> 'Quantidade de Registros',
				'description'	=> 'Quantidade de Registros no Lote',
				'type'			=> 'int',
				'size'			=> 6
			),
			'qtde_cobr_simples'						=> array(
				'label'			=> 'Quantidade em Carteiras Simples',
				'description'	=> 'Quantidade dos Títulos em Carteiras Simples',
				'type'			=> 'int',
				'size'			=> 6
			),
			'valor_total_simples'						=> array(
				'label'			=> 'Valor Total em Carteiras Simples',
				'description'	=> 'Valor Total dos Títulos em Carteiras Simples',
				'type'			=> 'float17',
				'size'			=> 17
			),
			'qtde_cobr_caucionada'	=> array(
				'label'			=> 'Quantidade em Carteiras Caucionada',
				'description'	=> 'Quantidade dos Títulos em Carteiras Caucionada',
				'type'			=> 'int',
				'size'			=> 6
			),
			'valor_total_caucionada'	=> array(
				'label'			=> 'Valor Total em Carteiras Caucionada',
				'description'	=> 'Valor Total dos Títulos em Carteiras Caucionada',
				'type'			=> 'float17',
				'size'			=> 17
			),
			'qtde_cobr_descontada'						=> array(
				'label'			=> 'Quantidade em Carteiras Descontada',
				'description'	=> 'Quantidade dos Títulos em Carteiras Descontada',
				'type'			=> 'int',
				'size'			=> 6
			),
			'valor_total_descontada'						=> array(
				'label'			=> 'Valor Total em Carteiras Descontada',
				'description'	=> 'Valor Total dos Títulos em Carteiras Descontada',
				'type'			=> 'float17',
				'size'			=> 17
			),
			'uso_exclusivo_cnab2'				=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 31
			),
			'uso_exclusivo_cnab3'				=> array(
				'label'			=> 'CNAB',
				'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
				'type'			=> 'string',
				'size'			=> 117
			)
		);
	
		$sizes = array();
		foreach($footerParameters as $param) {
			$sizes[] = $param['size'];
			$size += $param['size'];
		}
		$footerData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$footerData = array_combine(array_keys($footerParameters), $footerData);
	
		$result = array();
	
		foreach($footerParameters as $footerName => $footerItem) {
			$toMerge = $this->coercionDataByType($footerData[$footerName], $footerItem['type'], $footerItem['format']);
	
			$result[$footerName] = array_merge($footerItem, $toMerge);
		}
	
		return $result;
	}
	private function processReturnFileFooter($fileLine) {
		if ($fileLine{7} != 9) {
			throw new module_xpay_boletoException(_XPAY_BOLETO_REGISTERTYPEISDIFERENT, module_xpay_boletoException::DIFERENT_REGISTERTYPE);
		}
	
		$footerParameters = array(
				'cod_banco'			=> array(
						'label'			=> 'Banco',
						'description'	=> 'Código do Banco na Compensação',
						'type'			=> 'int',
						'size'			=> 3
				),
				'cod_servico'		=> array(
						'label'			=> 'Lote',
						'description'	=> 'Lote de Serviço',
						'type'			=> 'int',
						'size'			=> 4
				),
				'tipo'					=> array(
						'label'			=> 'Registro',
						'description'	=> 'Tipo de Registro',
						'type'			=> 'int',
						'size'			=> 1
				),
				'uso_exclusivo_cnab1'				=> array(
						'label'			=> 'CNAB',
						'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
						'type'			=> 'string',
						'size'			=> 9
				),
				// NÚMERO SEQÜENCIAL		395 400    	9(06)
				'qtde_lotes'	=> array(
						'label'			=> 'Quantidade de Lotes',
						'description'	=> 'Quantidade de Lotes do Arquivo',
						'type'			=> 'int',
						'size'			=> 6
				),
				'qtde_registros'	=> array(
						'label'			=> 'Quantidade de Registros',
						'description'	=> 'Quantidade de Registros do Arquivo',
						'type'			=> 'int',
						'size'			=> 6
				),
				'uso_exclusivo_cnab2'				=> array(
						'label'			=> 'CNAB',
						'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
						'type'			=> 'string',
						'size'			=> 6
				),				
				'uso_exclusivo_cnab3'				=> array(
						'label'			=> 'CNAB',
						'description'	=> 'Uso Exclusivo FEBRABAN / CNAB',
						'type'			=> 'string',
						'size'			=> 205
				)
		);
	
		$sizes = array();
		foreach($footerParameters as $param) {
			$sizes[] = $param['size'];
//			$size += $param['size'];
		}
	
		$footerData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$footerData = array_combine(array_keys($footerParameters), $footerData);
	
		$result = array();
	
		foreach($footerParameters as $footerName => $footerItem) {
			$toMerge = $this->coercionDataByType($footerData[$footerName], $footerItem['type'], $footerItem['format']);
				
			$result[$footerName] = array_merge($footerItem, $toMerge);
		}
	
		return $result;
	}
	
	private function chunkSplitLineBySizes($string, array $sizes) {
		$start = 0;
		$result = array();
		foreach($sizes as $size) {
			$result[] = substr($string, $start, $size);
			$start += $size;
		}
		return $result;
	}
	
	private function coercionDataByType($fieldData, $fieldType, $fieldFormat = null) {
		$result = array(
			'originaldata' => $fieldData,
			'parseddata' 	=> '',
			'formatteddata'	=> ''
		);
	
		switch($fieldType) {
			case 'null' : {
				$result['parseddata'] = $result['formatteddata'] = null;
				break;
			}
			case 'string' : {
				$result['parseddata'] = $result['formatteddata'] = trim($fieldData);
				break;
			}
			case 'int' : {
				$result['parseddata'] = intval($fieldData);
				if (is_null($fieldFormat)) {
					$fieldFormat = "%d";
				}
				$result['formatteddata'] = sprintf($fieldFormat, $fieldData);
	
				break;
			}
			case 'date6' : 
			case 'date' : {
				$sizes = array(2,2, $fieldType == 'date6' ? 2 : 4);
				
				$splited = $this->chunkSplitLineBySizes($fieldData, $sizes);
				$isoDate = $splited[2] . '-' .$splited[1] . '-' . $splited[0];
				$result['parseddata'] = strtotime($isoDate);
				if (is_null($fieldFormat)) {
					$fieldFormat = 'd/m/Y';
				}
				$result['formatteddata'] = date($fieldFormat, $result['parseddata']);
				break;
			}
				
			case 'float13' :
			case 'float14' :
			case 'float15' :
			case 'float17' : {
				$sizes = array(
					$fieldType == 'float13' ? 11 : (
						$fieldType == 'float14' ? 12 : (
							$fieldType == 'float15' ? 13 : (
								15
							)
						)
					), 2);
	
				//$sizes = ($fieldType == 'float14' ? array(12,2) : array(11,2));
				$splited = $this->chunkSplitLineBySizes($fieldData, $sizes);
				$strFloat = $splited[0] . '.' . $splited[1];
	
				$result['parseddata'] = floatval($strFloat);
	
				if (is_null($fieldFormat)) {
					$fieldFormat = 'R$ %1.2f';
				}
				$result['formatteddata'] = str_replace(".", ",", sprintf($fieldFormat, $result['parseddata']));
				break;
			}
				
			default : {
				$result['parseddata'] = $result['formatteddata'] = $fieldData;
				break;
			}
		}
	
		return $result;
	}
	
	/* IxPaySubmodule INTERFACE FUNCTIONS */
	public static function getInstance() {
		$currentUser = self::getCurrentUser();
	
		$defined_moduleBaseUrl 	= G_SERVERNAME . $currentUser -> getRole() . ".php" . "?ctg=module&op=" . __CLASS__;
		$defined_moduleFolder 	= __CLASS__;
	
		return new self($defined_moduleBaseUrl , $defined_moduleFolder);
	}
	
	public function getPaymentInstances() {
		return array(
			//'title'		=> __XPAY_PAYPAL_DO_PAYMENT,
			'baselink'	=> $this->moduleBaseLink,
			'default'	=> 'cef_sigcb',
			'options'	=> array (
				"cef_sigcb"	=> array(
					"name" 			=> "Boleto Bancário",
					"fullname" 		=> "Boleto Caixa Extensão",
					"image_name"	=> "boleto",
					"config"		=> array($this, "getPaymentInstanceConfig") // CAN BE A CALLBACK 
				),
				"itau_pos"	=> array(
					"name" 			=> "Boleto Bancário",
					"fullname" 		=> "Boleto Itaú Pós Graduação",
					"image_name"	=> "boleto",
					"active"		=> false,
					"config"		=> array($this, "getPaymentInstanceConfig") // CAN BE A CALLBACK 
				),
			)
		);
	}
	public function getPaymentInstancesIndexes() {
		$payment_types = $this->getPaymentInstances();
		return array_keys($payment_types['options']);
	}
	public function getPaymentInstanceConfig($instance_id, array $overrideOptions) {
		// RETURN ALL BOLETO METHOD DATA, BASED ON INSTANCE ID
		//$instance_id = end(explode(":", $instance_id));
		
		if (file_exists($this->moduleBaseDir . "config/" . $instance_id . ".inc")) {
			$__METHOD_SUPER_OPTIONS = $overrideOptions;
			
			$config = require($this->moduleBaseDir . "config/" . $instance_id . ".inc");
			return $config;
		}
		return false;
	}
	public function initPaymentProccess($negociation_id, $invoice_index, array $data) {
		$payInstances = $this->getPaymentInstances();
		
		if (!array_key_exists('option', $data) || !in_array($data['option'], array_keys($payInstances['options']))) {
			$indexOpt = $payInstances['default'];
		} else {
			$indexOpt = $data['option'];
		}
		
		$invoiceData = $this->getParent()->_getNegociationInvoiceByIndex($negociation_id, $invoice_index);

		$payInstance = $payInstances['options'][$indexOpt];
		
		/** @todo IMPLEMENT THIS THROUGH RULES */
		//	IF invoice_index = 0 AND data_vencimento IS NULL THEN
		//		data_vencimento = today + 5 days
		//	IF invoice_index = 0 AND data_vencimento > today THEN
		//		data_vencimento = today + 5 days
		//	IF invoice_index = 0 AND data_vencimento < today THEN
		//		DO NOT CHANGE!!!
		//	IF invoice_index > 0 AND data_vencimento > today THEN
		// 		multa
		$today = new DateTime("today");
		
		$datavencimento = date_create_from_format("Y-m-d H:i:s", $invoiceData['data_vencimento']);
		if (!$datavencimento) {
			$datavencimento = date_create_from_format("Y-m-d", $invoiceData['data_vencimento']);
		}
		
		if ($invoice_index == 0) {
			if ($datavencimento === FALSE || $datavencimento > $today) {
				$tmp = new DateTime("today");
				$datavencimento = $tmp->add(new DateInterval("P5D"));
			}
		}
		
		if (!$datavencimento) {
			return false;
		}
		// Composição Nosso Numero - CEF SIGCB
		// course_id (3)
		$invoiceOptions["nosso_numero1"]	= sprintf("%03d", substr($invoiceData['course_id'], 0, 3));
		// invoice_index (3)
		$invoiceOptions["nosso_numero2"]	= sprintf("%03d", substr($invoiceData['invoice_index'], 0, 3));
		// user_id (5) + negociation_id(4)
		$invoiceOptions["nosso_numero3"] 	= sprintf("%05d%04d", substr($invoiceData['user_id'], 0, 5), substr($invoiceData['negociation_id'], 0, 4));
		
		$invoiceOptions["numero_documento"] = $invoiceData['invoice_id'];	// Num do pedido ou do documento
		
		
		if ($datavencimento < $today && $this->getConfig()->on_overdue == 'refresh') {
			$invoiceOptions["data_vencimento"] = $today->format("d/m/Y"); // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		} elseif ($datavencimento < $today && $this->getConfig()->on_overdue == 'block') {
			return false;
		} else {
			$invoiceOptions["data_vencimento"] = $datavencimento->format("d/m/Y"); // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		}
//		$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
//		$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
		$invoiceOptions["valor_boleto"] = number_format($invoiceData['full_price'] - $invoiceData['paid'], 2, ",", ""); 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

		
		$invoicePayer = $this->getParent()->_getNegociationPayerByNegociationID($negociation_id);
		
		//var_dump($invoicePayer);
		
		// DADOS DO SEU CLIENTE
		$invoiceOptions["sacado"] 		= $invoicePayer['name'] . " " . $invoicePayer['surname'];
		$invoiceOptions["endereco1"] 	= sprintf("%s , %s %s / %s", $invoicePayer['endereco'], $invoicePayer['numero'], $invoicePayer['complemento'], $invoicePayer['bairro']); ;
		$invoiceOptions["endereco2"] 	= sprintf("%s / %s", $invoicePayer['cidade'], $invoicePayer['uf']);
		
		$invoiceOptions["instrucoes1"] = "NÃO RECEBER APÓS O VENCIMENTO";
//		$dadosboleto["instrucoes2"] = "";
//		$dadosboleto["instrucoes3"] = "";
//		$dadosboleto["instrucoes4"] = "";
		
		//var_dump($invoiceOptions);
		
		if (is_callable($payInstance['config'])) {
			$methodConfig = call_user_func($payInstance['config'], $indexOpt, $invoiceOptions);
		} else {
			$methodConfig = $payInstance['config'];
		}
		/*
		var_dump($methodConfig);
		exit;
		*/
		// INJECT USER DATA, INVOICE DATA, ETC...
		echo $boletoHTML = $this->loadPaymentInvoiceFromTpl($indexOpt, $methodConfig);
		exit;
	}
	
	private function loadPaymentInvoiceFromTpl($paymentIndex, $paymentConfig) {
		$smarty = $this->getSmartyVar();
		
		$invoiceFile = sprintf(
			"%stemplates/layouts/%s.tpl", 
			$this->moduleBaseDir,
			$paymentIndex 
		);

		$smartyFunctionsFile = sprintf(
				"%sfunctions/smarty/%s.xpay_boleto_FBarCode.php",
				$this->moduleBaseDir,
				$paymentIndex
		);
		require($smartyFunctionsFile);
		
		$smarty->register_function(
			sprintf('xpay_boleto_%s_FBarCode', $paymentIndex),
			sprintf('xpay_boleto_%s_FBarCode', $paymentIndex)
		);

		$this->assignSmartyModuleVariables();
		
		/* CUSTOM FIELDS */
		$index = "03";
		//$paymentConfig["numero_documento"] = "0000000" . $index;
		//$paymentConfig["valor_boleto"] = "1," . $index;
		$smarty->assign("T_" . strtoupper($this->getName()) . "_CFG", $paymentConfig);
		
		$boletoHTML = $smarty -> fetch($invoiceFile);
		return $boletoHTML;
	}
	public function paymentCanBeDone($payment_id, $invoice_id) {
		return true;
	}
	/*
	public function getInvoiceStatusById($payment_id, $invoice_id) {
		
	}
	*/
	public function sendReturnFileAction() {
		$smarty = $this->getSmartyVar();

		// MAKE UPLOAD FORM
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
		$form = $this->getUploadInvoicesForm();
		
		if ($this->processUploadInvoicesForm()) {
			$form = $this->populateUploadInvoicesForm($form);
		} else {
			$form = $this->getUploadInvoicesForm();
		}
		$smarty = $this->getSmartyVar();
		
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
		$formRender = $renderer -> toArray();
		
		$smarty -> assign('T_XPAY_BOLETO_FILE_FORM', $formRender);
		
		$smarty -> assign('T_XPAY_BOLETO_FILE_QUEUE', $this->getOnQueueFilesList());
		
		return true;
	}
	
	/** FORMULÁRIOS DE UPLOAD */
	private function getUploadInvoicesForm() {
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
	
		$form = new HTML_QuickForm(__CLASS__ . "_upload_invoices", "post", $_SERVER['REQUEST_URI'], "", null, true);
	
		$paymentInstanceList = array();
		$paymentInstances = $this->getPaymentInstances();

		foreach($paymentInstances['options'] as $key => $instance) {
			if ($instance['active'] !== FALSE) {
				$paymentInstanceList[$key] = $instance['fullname'];
			}
		}
		
		$form -> addElement('text', 'file_title', __XPAY_BOLETO_TITLE, 'class = "large"');
		$form -> addElement('select', 'instance_type', __XPAY_INSTANCE_TYPE, $paymentInstanceList);
		$form -> addElement('file', 'file_upload', __XPAY_BOLETO_RETURN_FILE, 'class = "large"');
		$form -> setMaxFileSize(FileSystemTree :: getUploadMaxSize() * 1024); //getUploadMaxSize returns size in KB
	
		//FileSystemTree :: getUploadMaxSize() * 1024
	
		$form -> addElement('submit', 'submit_apply', __SEND);
	
		return $this->populateUploadInvoicesForm($form);
	}
	private function populateUploadInvoicesForm($form = null) {
		if (is_null($form)) {
			$form = $this->getUploadInvoicesForm();
		}
		if ($form -> isSubmitted() && $form -> validate()) {
			$instance_type = $form->exportValue('instance_type');
		} else {
			$payInstances = $this->getPaymentInstances();
			$instance_type = $payInstances['default'];
		}	
		$form->setDefaults(array(
			'instance_type' => $instance_type
		));
		
		return $form;
	}
	private function processUploadInvoicesForm() {
		
		$this->_createReturnDirectories();
		/*
		$iesData = $this->getCurrentIes();
		$iesID = $iesData['id'];
		*/
		$form = $this->getUploadInvoicesForm();
		if ($form -> isSubmitted() && $form -> validate()) {
			if (!is_null($form->exportValue('submit_apply'))) {
				$xpayModule = $this->loadModule("xpay");
				
				$file =& $form->getElement('file_upload');
				
				$instance_type = $form->exportValue('instance_type');
				if (!in_array($instance_type, $this->getPaymentInstancesIndexes())) {
					$payInstances = $this->getPaymentInstances();
					$instance_type = $payInstances['default'];
				}
				//$fullfilepath = sprintf($this->getConfig()->paths['return_instance'], $instance_type);
				
				$fullProcPath = sprintf($this->getConfig()->paths['return_proc'], $instance_type);
				$fileData = $file->getValue();
				$originalFileName = str_replace(
					array("/", "."), 
					"", 
					basename($fileData['name'], "RET")
				);
				
				$count = 1;
				do {
					$retnumbers = sprintf("%d-%s-%04d", date("Ymd"), $instance_type, $count);
					$retModule10 = $xpayModule->_module10($retnumbers);
					$retFileName = sprintf("%s-%s-%s-%04d-%d.ret", date("Y_m_d"), $instance_type, $originalFileName, $count, $retModule10);
					$count++;
				} while(file_exists($fullProcPath . $retFileName));
	
				var_dump($fullProcPath, $retFileName);
				
				if ($file->moveUploadedFile($fullProcPath, $retFileName)) {
					$fullFilePath = $fullProcPath . $retFileName;
					
					// NOW IS ON QUEUE, WILL BE PROCESSED LATER
					$this->setMessageVar(__XPAY_BOLETO_SEND_FILE_ON_QUEUE, "success");
					/*
					// SEND MESSAGE
					$returnStatus = $this->processReturnFile($destDir . 'proc/' . $retFileName);
	
					// GET PAYMENT TYPE ID FROM FILE
					$agencia	= $returnStatus['header']['agencia']['parseddata'];
					$conta 		= $returnStatus['header']['conta']['parseddata'];
	
					$paymentTypes = $this->parent->getPaymentTypes();
					$selectedPaymentType = null;
					foreach($paymentTypes as $paymentType) {
						$payTypeAgencia = $paymentType['tag']['agencia'];
						$payTypeCC = $paymentType['tag']['conta_corrente']['conta'];
	
						if ($agencia == $payTypeAgencia && $conta == $payTypeCC) {
							$selectedPaymentType = $paymentType;
							break;
						}
					}
					if (!is_null($selectedPaymentType)) {
						// MOVE $destDir . 'proc/' . $retFileName TO $destDir . $selectedPaymentType['payment_type_id']. '/' . $retFileName;
						rename(
								$destDir . 'proc/' . $retFileName,
								$returnFileName = $destDir . $selectedPaymentType['payment_type_id']. '/' . $retFileName
						);
					} else {
						rename(
								$destDir . 'proc/' . $retFileName,
								$returnFileName = $destDir . 'unknown/' . $retFileName
						);
					}
					$this->moduleBaseUrl = empty($this->moduleBaseUrl) ? '/' . $_SESSION['s_type']. ".php?ctg=module&op=" . __CLASS__ : $this->moduleBaseUrl;
	
					$url = $this->moduleBaseUrl . "&action=check_processed_file&filename=" . urlencode($returnFileName);
	
					eF_redirect($url);
					exit;
					*/
				} else {
					$this->setMessageVar(__XPAY_BOLETO_SEND_FILE_ERROR, "error");
				}
				
				return $this->populateUploadInvoicesForm($form);
			} else {
				$this->setMessageVar(_UNDEFINEDERROR, 'warning');
			}
		} else {
			return false;
		}
	}
	
	private function _createReturnDirectories() {
		// CREATE THE FOLLOWING STRUCTURE
		$array_dirs = array(
			'return_root',
			'return_proc',
			'return_error',
			'return_instance'				
		);
		$paymentIndexes = $this->getPaymentInstancesIndexes();
		
		foreach($array_dirs as $filepath) {
			foreach($paymentIndexes as $index) {
				$fullfilepath = sprintf($this->getConfig()->paths[$filepath], $index);
				if (file_exists($fullfilepath) && !is_dir(!file_exists($fullfilepath))) {
					continue;
				}
				if (!file_exists($fullfilepath)) {
//					var_dump($fullfilepath);
//					echo '<br />';
						
					mkdir($fullfilepath, 0777, true);
				}
			}
		}
	}
	private function getOnQueueFilesList() {
		$paymentTypes = $this->getPaymentInstances();
		$paymentIndexes = $this->getPaymentInstancesIndexes();
		
		$queueList = array();
				
		foreach($paymentIndexes as $instance_type) {
			$fullProcPaths[] = sprintf($this->getConfig()->paths['return_proc'], $instance_type);
			$queueList[$instance_type] = array(
				'name'	=> $paymentTypes['options'][$instance_type]['fullname'],
				'files'	=> array(),
				'size'	=> 0
			);
		}

		$fullProcPaths = array_unique($fullProcPaths);

		foreach($fullProcPaths as $procPath) {
			$files = scandir($procPath, 1);
			
			foreach($files as $file) {
				//var_dump($file);
				//$fileStruct = sscanf($file, "%s_%s_%s-%s-%s-%s-%s.ret", $date, $method, $name, $count, $module10);
				if ($file == "." || $file == "..") {
					continue;
				}
				$fileTmp = reset(explode(".", $file));
				list($date, $method, $name, $count, $module10) = explode("-", $fileTmp);
				
				$file_stat = stat($procPath . $file);
				
				$fileStruct = array(
					'fullpath'	=> $procPath . $file,
					'name'		=> $name . "-" . $count . ".ret",
					'method_index'	=> $method,
					'method_name'	=> $paymentTypes['options'][$method]['fullname'],
					'timestamp' => $file_stat['mtime'],
					'size'		=> sprintf("%.2fKb", ($file_stat['size'] / 1024))
				);
				$queueList[$method]['files'][] = $fileStruct;
				$queueList[$method]['size'] += $file_stat['size'];
						
				/*
				echo
				"<li class=\"file ext_$ext\">
				<a href=\"#\" rel=\"" . htmlentities($absCurrentDir . $file) . "\">" .
				"<div class=\"filepart fileprefix\">" . $mapFolderNames[$middleFolder] . "</div>" .
				"<div class=\"filepart filename\">" . htmlentities($file) . "</div>" .
				"<div class=\"filepart filetime\">" .  . "</div>" .
				"<div class=\"filepart filesize\">" .  . "</div>" .
				"</a>
				</li>";
				*/
			}
		}
		
		foreach($queueList as $index => $value) {
			$queueList[$index]['size'] = sprintf("%.2fKb", ($value['size'] / 1024));
		}
		
		return $queueList;
	}
}
?>
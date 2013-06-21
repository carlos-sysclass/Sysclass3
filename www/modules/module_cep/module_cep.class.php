<?php
class module_cep extends MagesterModule
{
    // Mandatory functions required for module function
    public function getName()
    {
        return _CEP;
    }

    public function getPermittedRoles()
    {
        return array("administrator","professor" ,"student");
    }

	// Optional functions
    // What should happen on installing the module
    public function onInstall()
    {
        sC_executeNew("drop table if exists module_cep_logradouros");
        $a = sC_executeNew("CREATE TABLE IF NOT EXISTS `module_cep_logradouros` (
  			`id` mediumint(8) NOT NULL auto_increment,
			`cep` varchar(9) NOT NULL,
			`tipo_logradouro` varchar(250) NOT NULL,
			`logradouro` varchar(250) NOT NULL,
			`bairro` varchar(150) NOT NULL,
			`cidade` varchar(150) NOT NULL,
			`uf` varchar(2) NOT NULL,
			`last_update` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			PRIMARY KEY  (`id`),
			KEY `cep_index` (`cep`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        MagesterConfiguration :: setValue("module_cep_webservice_url", "http://cep.republicavirtual.com.br/web_cep.php");
        MagesterConfiguration :: setValue("module_cep_webservice_format", "xml");

        return $a;
    }

    public function onUninstall()
    {
        $a = sC_executeNew("drop table module_cep_logradouros;");
        $b = sC_deleteTableData("configuration", "name IN ('module_cep_webservice_url', 'module_cep_webservice_format')");

        return $a && $b;
    }

    public function getModule()
    {
    	$cep = $_GET['cep'];
    	// SANITIZE CEP
		$cep	= preg_replace('/\D/', '', $cep);

    	$resultCep = sC_getTableData("module_cep_logradouros", "*", sprintf("cep = '%s'", $cep));

    	if (count($resultCep) > 0) {
    		// @todo: CHECK FOR OLD RECORDS
    		$cepData = $resultCep[0];
    	} else {

			$configOptions = MagesterConfiguration::getValues();

	    	$format = $configOptions['module_cep_webservice_format'];

	    	$url = sprintf(
	    		$configOptions['module_cep_webservice_url'] . '?cep=%1$s&formato=%2$s',
	    		$cep,
	    		$format
	    	);

	    	if ($stream = fopen($url, 'r')) {

				$output = stream_get_contents($stream);
				fclose($stream);

				$xml = simplexml_load_string($output);

				$status = (int) $xml->resultado;

				if ($status == 1) {
					$cepData = array(
						'cep'				=> $cep,
						'uf'				=> (string) $xml->uf,
						//'estado'			=> (string) $xml->uf,
						'cidade'			=> (string) $xml->cidade,
						'bairro'			=> (string) $xml->bairro,
						'tipo_logradouro'	=> (string) $xml->tipo_logradouro,
						'logradouro'		=> (string) $xml->logradouro
					);
					// INSERE INFORMAÇÕES NO BANCO DE DADOS, PARA CONSULTA FUTURA
					sC_insertTableData("module_cep_logradouros", $cepData);
				} else { // ERROR

					// CHECK THE maguser_cep BASE
					$cep = $_GET['cep'];

					$resultCep = sC_getTableData("module_cep_logradouros_base", "*", sprintf("cep = '%s'", $cep));

					if (count($resultCep) > 0) {
						$cepData = $resultCep[0];

						$cepData = array(
							'cep'				=> preg_replace('/\D/', '', $cep),
							'uf'				=> $cepData['uf'],
							'cidade'			=> $cepData['cidade'],
							'bairro'			=> $cepData['bairro'],
							'tipo_logradouro'	=> $cepData['tp_logradouro'],
							'logradouro'		=> $cepData['logradouro'],
						);
						// INSERE INFORMAÇÕES NO BANCO DE DADOS, PARA CONSULTA FUTURA
						$countCep = sC_countTableData("module_cep_logradouros", "*", sprintf("cep = '%s'", preg_replace('/\D/', '', $cep)));

						if ($countCep > 0) {
							sC_updateTableData("module_cep_logradouros", $cepData, sprintf("cep = '%s'", preg_replace('/\D/', '', $cep)));
						} else {
							sC_insertTableData("module_cep_logradouros", $cepData);
						}

					} else {
						$cepData = array(
							"error" 	=> true,
							"message"	=> "Cep não encontrado"
						);
					}
				}
	    	}
    	}
    	// ADDITIONAL FIELDS
    	$cepData['endereco']	= $cepData['tipo_logradouro'] . " " . $cepData['logradouro'];

		$prefix = "";
		if ($_GET['prefix']) {
			$prefix = str_replace("cep", "", $_GET['prefix']);
		}

  		switch ($_GET['output']) {
  			case "xml" : {
  				$result = "<result>";
  				foreach ($cepData as $key => $value) {
  					$result .= sprintf('<%1$s>%2$s</%1$s>', $key, $value);

  				}
  				$result .= "</result>";
  				break;
  			}
  			case "json" :
  			default : {
  				$items = array();
  				foreach ($cepData as $key => $value) {
  					$items[$prefix . $key] = $value;
  				}
  				$result = json_encode($items);
  			}
  		}

    	echo $result;
    	exit;
    }
    public function getModuleJS()
    {
   		return $this->moduleBaseDir."module_cep.js";
    }
}

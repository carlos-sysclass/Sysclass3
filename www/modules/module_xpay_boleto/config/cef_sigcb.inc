<?php
function digitoVerificador_nossonumero($numero) {
	$resto2 = modulo_11($numero, 9, 1);
	$digito = 11 - $resto2;
	if ($digito == 10 || $digito == 11) {
		$dv = 0;
	} else {
		$dv = $digito;
	}
	return $dv;
}

function digitoVerificador_cedente($numero) {
	$resto2 = modulo_11($numero, 9, 1);
	$digito = 11 - $resto2;
	if ($digito == 10 || $digito == 11) $digito = 0;
	$dv = $digito;
	return $dv;
}

function digitoVerificador_barra($numero) {
	$resto2 = modulo_11($numero, 9, 1);
	if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
		$dv = 1;
	} else {
		$dv = 11 - $resto2;
	}
	return $dv;
}


// FUN��ES
// Algumas foram retiradas do Projeto PhpBoleto e modificadas para atender as particularidades de cada banco

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		 retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}

function fator_vencimento($data) {
	if ($data != "") {
		$data = explode("/",$data);
		$ano = $data[2];
		$mes = $data[1];
		$dia = $data[0];
		return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
	} else {
		return "0000";
	}
}

function _dateToDays($year,$month,$day) {
	$century = substr($year, 0, 2);
	$year = substr($year, 2, 2);
	if ($month > 2) {
		$month -= 3;
	} else {
		$month += 9;
		if ($year) {
			$year--;
		} else {
			$year = 99;
			$century --;
		}
	}
	return ( floor((  146097 * $century)    /  4 ) +
			floor(( 1461 * $year)        /  4 ) +
			floor(( 153 * $month +  2) /  5 ) +
			$day +  1721119);
}

function modulo_10($num) {
	$numtotal10 = 0;
	$fator = 2;

	// Separacao dos numeros
	for ($i = strlen($num); $i > 0; $i--) {
		// pega cada numero isoladamente
		$numeros[$i] = substr($num,$i-1,1);
		// Efetua multiplicacao do numero pelo (falor 10)
		$temp = $numeros[$i] * $fator;
		$temp0=0;
		foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){
			$temp0+=$v;
		}
		$parcial10[$i] = $temp0; //$numeros[$i] * $fator;
		// monta sequencia para soma dos digitos no (modulo 10)
		$numtotal10 += $parcial10[$i];
		if ($fator == 2) {
			$fator = 1;
		} else {
			$fator = 2; // intercala fator de multiplicacao (modulo 10)
		}
	}

	// v�rias linhas removidas, vide fun��o original
	// Calculo do modulo 10
	$resto = $numtotal10 % 10;
	$digito = 10 - $resto;
	if ($resto == 0) {
		$digito = 0;
	}

	return $digito;

}

function modulo_11($num, $base=9, $r=0)  {
	$soma = 0;
	$fator = 2;

	/* Separacao dos numeros */
	for ($i = strlen($num); $i > 0; $i--) {
		// pega cada numero isoladamente
		$numeros[$i] = substr($num,$i-1,1);
		// Efetua multiplicacao do numero pelo falor
		$parcial[$i] = $numeros[$i] * $fator;
		// Soma dos digitos
		$soma += $parcial[$i];
		if ($fator == $base) {
			// restaura fator de multiplicacao para 2
			$fator = 1;
		}
		$fator++;
	}

	/* Calculo do modulo 11 */
	if ($r == 0) {
		$soma *= 10;
		$digito = $soma % 11;
		if ($digito == 10) {
			$digito = 0;
		}
		return $digito;
	} elseif ($r == 1){
		$resto = $soma % 11;
		return $resto;
	}
}

function monta_linha_digitavel($codigo) {

	// Posi��o 	Conte�do
	// 1 a 3    N�mero do banco
	// 4        C�digo da Moeda - 9 para Real
	// 5        Digito verificador do C�digo de Barras
	// 6 a 9   Fator de Vencimento
	// 10 a 19 Valor (8 inteiros e 2 decimais)
	// 20 a 44 Campo Livre definido por cada banco (25 caracteres)

	// 1. Campo - composto pelo c�digo do banco, c�digo da mo�da, as cinco primeiras posi��es
	// do campo livre e DV (modulo10) deste campo
	$p1 = substr($codigo, 0, 4);
	$p2 = substr($codigo, 19, 5);
	$p3 = modulo_10("$p1$p2");
	$p4 = "$p1$p2$p3";
	$p5 = substr($p4, 0, 5);
	$p6 = substr($p4, 5);
	$campo1 = "$p5.$p6";

	// 2. Campo - composto pelas posi�oes 6 a 15 do campo livre
	// e livre e DV (modulo10) deste campo
	$p1 = substr($codigo, 24, 10);
	$p2 = modulo_10($p1);
	$p3 = "$p1$p2";
	$p4 = substr($p3, 0, 5);
	$p5 = substr($p3, 5);
	$campo2 = "$p4.$p5";

	// 3. Campo composto pelas posicoes 16 a 25 do campo livre
	// e livre e DV (modulo10) deste campo
	$p1 = substr($codigo, 34, 10);
	$p2 = modulo_10($p1);
	$p3 = "$p1$p2";
	$p4 = substr($p3, 0, 5);
	$p5 = substr($p3, 5);
	$campo3 = "$p4.$p5";

	// 4. Campo - digito verificador do codigo de barras
	$campo4 = substr($codigo, 4, 1);

	// 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
	// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
	// tratar de valor zerado, a representacao deve ser 000 (tres zeros).
	$p1 = substr($codigo, 5, 4);
	$p2 = substr($codigo, 9, 10);
	$campo5 = "$p1$p2";

	return "$campo1 $campo2 $campo3 $campo4 $campo5";
}

function geraCodigoBanco($numero) {
	$parte1 = substr($numero, 0, 3);
	$parte2 = modulo_11($parte1);
	return $parte1 . "-" . $parte2;
}


// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "";
$dadosboleto["demonstrativo2"] = "";
$dadosboleto["demonstrativo3"] = "";

// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"] = "";
$dadosboleto["instrucoes2"] = "";
$dadosboleto["instrucoes3"] = "";
$dadosboleto["instrucoes4"] = "";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS DA SUA CONTA - CEF
$dadosboleto["agencia"] = "1316"; // Num da agencia, sem digito
$dadosboleto["conta"] = "1200"; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = "4"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - CEF
$dadosboleto["conta_cedente"] = "318992"; // Código Cedente do Cliente, com 6 digitos (Somente N�meros)
$dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

// SEUS DADOS
$dadosboleto["identificacao"] = "ULT - União Latino-Americana de Tecnologia";
$dadosboleto["cpf_cnpj"] = "01.139.174/0001-00";
$dadosboleto["endereco"] = "Rua Coronel Dulcídio, 517 Lj. 79 - Batel";
$dadosboleto["cidade_uf"] = "Curitiba / Paraná";
$dadosboleto["cedente"] = "Braswell do Brasil";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";

// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//


// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
//$taxa_boleto = 2.95;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$valor_cobrado = "0,01"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado, 2, ',', '');


// Composição Nosso Numero - CEF SIGCB
$dadosboleto["nosso_numero1"] = "111"; // tamanho 3
$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
$dadosboleto["nosso_numero2"] = "222"; // tamanho 3
$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
$dadosboleto["nosso_numero3"] = "333333333"; // tamanho 9

$dadosboleto["numero_documento"] = sprintf("%08d", mt_rand(0, 99999999));	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] 		= "Andre Kucaniz";
$dadosboleto["endereco1"] 	= "Av Presidente Getúlio Vargas, 4547 Ap 90 / Água Verde";
$dadosboleto["endereco2"] 	= "Curitiba / Paraná";


$dadosboleto = array_merge(
		$dadosboleto,
		$__METHOD_SUPER_OPTIONS
);

$codigobanco = "104";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//agencia : 4 digitos
$agencia = formata_numero($dadosboleto["agencia"],4,0);
//conta : 5 digitos
$conta = formata_numero($dadosboleto["conta"],5,0);
//dv da conta
$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
//carteira : 2 caracteres
$carteira = $dadosboleto["carteira"];

//conta cedente (sem dv) com 6 digitos
$conta_cedente = formata_numero($dadosboleto["conta_cedente"],6,0);
//dv da conta cedente
$conta_cedente_dv = digitoVerificador_cedente($conta_cedente);

//campo livre (sem dv) � 24 digitos
$campo_livre = $conta_cedente . $conta_cedente_dv . formata_numero($dadosboleto["nosso_numero1"],3,0) . formata_numero($dadosboleto["nosso_numero_const1"],1,0) . formata_numero($dadosboleto["nosso_numero2"],3,0) . formata_numero($dadosboleto["nosso_numero_const2"],1,0) . formata_numero($dadosboleto["nosso_numero3"],9,0);
//dv do campo livre
$dv_campo_livre = digitoVerificador_nossonumero($campo_livre);
$campo_livre_com_dv ="$campo_livre$dv_campo_livre";

//nosso n�mero (sem dv) � 17 digitos
$nnum = formata_numero($dadosboleto["nosso_numero_const1"],1,0).formata_numero($dadosboleto["nosso_numero_const2"],1,0).formata_numero($dadosboleto["nosso_numero1"],3,0).formata_numero($dadosboleto["nosso_numero2"],3,0).formata_numero($dadosboleto["nosso_numero3"],9,0);
//nosso n�mero completo (com dv) com 18 digitos
$nossonumero = $nnum . digitoVerificador_nossonumero($nnum);

// 43 numeros para o calculo do digito verificador do codigo de barras
$dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campo_livre_com_dv", 9, 0);
// Numero para o codigo de barras com 44 digitos
$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campo_livre_com_dv";

$agencia_codigo = $agencia." / ". $conta_cedente ."-". $conta_cedente_dv;

$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;



return $dadosboleto;
?>

<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
<head>
<title>{$T_XPAY_BOLETO_CFG.identificacao}</title>
<meta charset="UTF-8" content="text/html" http-equiv="Content-Type">
<style type=text/css>
{literal}
.cp {
	font: bold 10px Arial;
	color: black
}
.ti {
	font: 9px Arial, Helvetica, sans-serif
}
.ld {
	font: bold 15px Arial;
	color: #000000
}
.ct {
	FONT: 9px "Arial Narrow";
	COLOR: #000033
}
.cn {
	FONT: 9px Arial;
	COLOR: black
}
.bc {
	font: bold 20px Arial;
	color: #000000
}
.ld2 {
	font: bold 12px Arial;
	color: #000000
}
{/literal}
</style>
</head>

<body text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
	<table width=666 cellspacing=0 cellpadding=0 border=0>
		<tr>
			<td valign=top class=cp>
				<div ALIGN="CENTER">Instruções de Impressão</div>
			</TD>
		</TR>
		<tr>
			<td valign=top class=cp>
				<div ALIGN="left">
					<p></p>
					<li>Imprima em impressora jato de tinta (ink jet) ou laser em
						qualidade normal ou alta (Não use modo econômico).<br>

					</li>
					<li>Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e
						margens mínimas à esquerda e à direita do formulário.<br>

					</li>
					<li>Corte na linha indicada. Não rasure, risque, fure ou dobre
						a região onde se encontra o código de barras.<br>

					</li>
					<li>Caso não apareça o código de barras no final, clique em F5
						para atualizar esta tela.</li>
					<li>Caso tenha problemas ao imprimir, copie a seqüencia
						numérica abaixo e pague no caixa eletrônico ou no internet
						banking:<br> <br> <span class="ld2">
							&nbsp;&nbsp;&nbsp;&nbsp;Linha Digitável: &nbsp;{$T_XPAY_BOLETO_CFG.linha_digitavel}<br>
							&nbsp;&nbsp;&nbsp;&nbsp;Valor: &nbsp;&nbsp;R$ {$T_XPAY_BOLETO_CFG.valor_boleto}<br>
					</span>

					</li>
				</div>
			</td>
		</tr>
	</table>
	<br>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tbody>
			<tr>
				<td class=ct width=666><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/6.png" width=665
					border=0 alt=""></td>
			</tr>
			<tr>
				<td class=ct width=666>
					<div align=right>
						<b class=cp>Recibo do Sacado</b>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table width=666 cellspacing=5 cellpadding=0 border=0>
		<tr>
			<td width=41></TD>
		</tr>
	</table>
	<table width=666 cellspacing=5 cellpadding=0 border=0 align=Default>
		<tr>
			<td width="113"><img
				SRC="{$T_XPAY_BOLETO_BASELINK}images/layouts/logo_empresa.png"
				alt=""></td>
			<td class=ti width=455>{$T_XPAY_BOLETO_CFG.identificacao}
				{if isset($T_XPAY_BOLETO_CFG.cpf_cnpj)}
					<br>{$T_XPAY_BOLETO_CFG.cpf_cnpj}
				{else}
				{/if}
				<br>
				{$T_XPAY_BOLETO_CFG.endereco}<br />
				{$T_XPAY_BOLETO_CFG.cidade_uf}<br />
			</td>
			<td align=RIGHT width=150 class=ti>&nbsp;</td>
		</tr>
	</table>
	<br>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tr>
			<td class=cp width=150><span class="campo"
				style="margin-left: 15px;"> <font class="bc">Banco
						Itaú</font> <!-- 
 <IMG
      src="{$T_XPAY_BOLETO_BASELINK}images/layouts/logoitau.jpg" width="150" height="40"
      border=0></span>
 -->

			</span></td>
			<td width=3 valign=bottom><img height=22
				src="{$T_XPAY_BOLETO_BASELINK}images/layouts/3.png" width=2
				border=0 alt=""></td>
			<td class=cpt width=58 valign=bottom>
				<div align=center>
					<font class=bc>{$T_XPAY_BOLETO_CFG.codigo_banco_com_dv}
					</font>
				</div>
			</td>
			<td width=3 valign=bottom><img height=22
				src="{$T_XPAY_BOLETO_BASELINK}images/layouts/3.png" width=2
				border=0 alt=""></td>
			<td class=ld align=right width=453 valign=bottom><span class=ld>
					<span class="campotitulo"> {$T_XPAY_BOLETO_CFG.linha_digitavel}
				</span>
			</span></td>
		</tr>
		<tbody>
			<tr>
				<td colspan=5><img height=2
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=666
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=268 height=13>Cedente</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=156 height=13>Agência/Código do
					Cedente</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=34 height=13>Espécie</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=53 height=13>Quantidade</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=120 height=13>Nosso número</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=268 height=12><span class="campo">{$T_XPAY_BOLETO_CFG.cedente}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=156 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.agencia_codigo}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=34 height=12><span class="campo">{$T_XPAY_BOLETO_CFG.especie}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=53 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.quantidade}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=120 height=12><span
					class="campo"> {$T_XPAY_BOLETO_CFG.nosso_numero}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=268 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=268
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=156 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=156
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=34 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=34
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=53 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=53
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=120 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=120
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top colspan=3 height=13>Número do documento</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=132 height=13>CPF/CNPJ</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=134 height=13>Vencimento</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>Valor documento</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top colspan=3 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.numero_documento}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=132 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.cpf_cnpj}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=134 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.data_vencimento}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=180 height=12><span
					class="campo"> {$T_XPAY_BOLETO_CFG.valor_boleto}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=113 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=113
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=72 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=72
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=132 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=132
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=134 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=134
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=1 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=119 height=13
					style="white-space: nowrap;">(-) Desconto/Abatimentos</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=112 height=13>(-) Outras deduções</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=113 height=13>(+) Mora / Multa</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=113 height=13>(+) Outros
					acréscimos</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>(=) Valor cobrado</td>
			</tr>
			<tr>
				<td class=cp valign=top width=1 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=119 height=12></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=112 height=12></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=113 height=12></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=113 height=12></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=180 height=12></td>
			</tr>
			<tr>
				<td valign=top width=1 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=1
					border=0 alt=""></td>
				<td valign=top width=119 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=119
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=112 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=112
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=113 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=113
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=113 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=113
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=659 height=13>Sacado</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=659 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.sacado}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=659 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=659
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct width=7 height=12></td>
				<td class=ct width=564>Demonstrativo</td>
				<td class=ct width=7 height=12></td>
				<td class=ct width=88>Autenticação mecânica</td>
			</tr>
			<tr>
				<td width=7></td>
				<td class=cp width=564><span class="campo"> {$T_XPAY_BOLETO_CFG.demonstrativo1}<br>
						{$T_XPAY_BOLETO_CFG.demonstrativo2}<br> {$T_XPAY_BOLETO_CFG.demonstrativo3}<br>
				</span></td>
				<td width=7></td>
				<td width=88></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tbody>
			<tr>
				<td width=7></td>
				<td width=500 class=cp><br> <br> <br></td>
				<td width=159></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tr>
			<td class=ct width=666></td>
		</tr>
		<tbody>
			<tr>
				<td class=ct width=666>
					<div align=right>Corte na linha pontilhada</div>
				</td>
			</tr>
			<tr>
				<td class=ct width=666><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/6.png" width=665
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tr>
			<td class=cp width=150><span class="campo"
				style="margin-left: 15px;"> <font class="bc">Banco
						Itaú</font> <!-- 
  <IMG
      src="{$T_XPAY_BOLETO_BASELINK}images/layouts/logoitau.jpg"" width="150" height="40"
      border=0></span>
 -->

			</span></td>
			<td width=3 valign=bottom><img height=22
				src="{$T_XPAY_BOLETO_BASELINK}images/layouts/3.png" width=2
				border=0 alt=""></td>
			<td class=cpt width=58 valign=bottom>
				<div align=center>
					<font class=bc>{$T_XPAY_BOLETO_CFG.codigo_banco_com_dv}
					</font>
				</div>
			</td>
			<td width=3 valign=bottom><img height=22
				src="{$T_XPAY_BOLETO_BASELINK}images/layouts/3.png" width=2
				border=0 alt=""></td>
			<td class=ld align=right width=453 valign=bottom><span class=ld>
					<span class="campotitulo"> {$T_XPAY_BOLETO_CFG.linha_digitavel}
				</span>
			</span></td>
		</tr>
		<tbody>
			<tr>
				<td colspan=5><img height=2
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=666
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=472 height=13>Local de pagamento</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>Vencimento</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=472 height=12>Até o vencimento,
					preferencialmente no Itaú. Após o vencimento, somente no Itaú.</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=180 height=12><span
					class="campo"> {$T_XPAY_BOLETO_CFG.data_vencimento}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=472 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=472
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=472 height=13>Cedente</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>Agência/Código
					cedente</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=472 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.cedente}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=180 height=12><span
					class="campo"> {$T_XPAY_BOLETO_CFG.agencia_codigo}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=472 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=472
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=113 height=13>Data do documento</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=133 height=13>N<u>o</u> documento
				</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=62 height=13>Espécie doc.</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=34 height=13>Aceite</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=102 height=13>Data processamento</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>Nosso número</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=113 height=12>
					<div align=left>
						<span class="campo"> {$T_XPAY_BOLETO_CFG.data_documento}
						</span>
					</div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=133 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.numero_documento}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=62 height=12>
					<div align=left>
						<span class="campo"> {$T_XPAY_BOLETO_CFG.especie_doc}
						</span>
					</div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=34 height=12>
					<div align=left>
						<span class="campo"> {$T_XPAY_BOLETO_CFG.aceite}
						</span>
					</div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=102 height=12>
					<div align=left>
						<span class="campo"> {$T_XPAY_BOLETO_CFG.data_processamento}
						</span>
					</div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=180 height=12><span
					class="campo"> {$T_XPAY_BOLETO_CFG.nosso_numero}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=113 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=113
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=133 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=133
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=62 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=62
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=34 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=34
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=102 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=102
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top COLSPAN="3" height=13>Uso do banco</td>
				<td class=ct valign=top height=13 width=7><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=83 height=13>Carteira</td>
				<td class=ct valign=top height=13 width=7><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=53 height=13>Espécie</td>
				<td class=ct valign=top height=13 width=7><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=103 height=13>Quantidade</td>
				<td class=ct valign=top height=13 width=7><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=92 height=13>Valor Documento</td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>(=) Valor documento</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td valign=top class=cp height=12 COLSPAN="3">
					<div align=left></div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=83>
					<div align=left>
						<span class="campo"> {$T_XPAY_BOLETO_CFG.carteira}
						</span>
					</div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=53>
					<div align=left>
						<span class="campo"> {$T_XPAY_BOLETO_CFG.especie}
						</span>
					</div>
				</td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=103><span class="campo"> {$T_XPAY_BOLETO_CFG.quantidade}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=92><span class="campo"> {$T_XPAY_BOLETO_CFG.valor_unitario}
				</span></td>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top align=right width=180 height=12><span
					class="campo"> {$T_XPAY_BOLETO_CFG.valor_boleto}
				</span></td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=75
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=31 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=31
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=83 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=83
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=53 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=53
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=103 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=103
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=92 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=92
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tbody>
			<tr>
				<td align=right width=10>
					<table cellspacing=0 cellpadding=0 border=0 align=left>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=1 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td valign=top width=468 rowspan=5><font class=ct>Instruções
						(Texto de responsabilidade do cedente)</font><br> <br> <span
					class=cp> <font class=campo>
							{$T_XPAY_BOLETO_CFG.instrucoes1}<br />
							{$T_XPAY_BOLETO_CFG.instrucoes2}<br />
							{$T_XPAY_BOLETO_CFG.instrucoes3}<br />
							{$T_XPAY_BOLETO_CFG.instrucoes4}
							</font> <br> <br>
				</span></td>
				<td align=right width=188>
					<table cellspacing=0 cellpadding=0 border=0>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=ct valign=top width=180 height=13>(-) Desconto /
									Abatimentos</td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=cp valign=top align=right width=180 height=12></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=7 border=0 alt=""></td>
								<td valign=top width=180 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=180 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td align=right width=10>
					<table cellspacing=0 cellpadding=0 border=0 align=left>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=1 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align=right width=188>
					<table cellspacing=0 cellpadding=0 border=0>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=ct valign=top width=180 height=13>(-) Outras
									deduções</td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=cp valign=top align=right width=180 height=12></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=7 border=0 alt=""></td>
								<td valign=top width=180 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=180 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td align=right width=10>
					<table cellspacing=0 cellpadding=0 border=0 align=left>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=1 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align=right width=188>
					<table cellspacing=0 cellpadding=0 border=0>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=ct valign=top width=180 height=13>(+) Mora /
									Multa</td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=cp valign=top align=right width=180 height=12></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=7 border=0 alt=""></td>
								<td valign=top width=180 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=180 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td align=right width=10>
					<table cellspacing=0 cellpadding=0 border=0 align=left>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=1 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align=right width=188>
					<table cellspacing=0 cellpadding=0 border=0>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=ct valign=top width=180 height=13>(+) Outros
									acréscimos</td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=cp valign=top align=right width=180 height=12></td>
							</tr>
							<tr>
								<td valign=top width=7 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=7 border=0 alt=""></td>
								<td valign=top width=180 height=1><img height=1
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png"
									width=180 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td align=right width=10>
					<table cellspacing=0 cellpadding=0 border=0 align=left>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align=right width=188>
					<table cellspacing=0 cellpadding=0 border=0>
						<tbody>
							<tr>
								<td class=ct valign=top width=7 height=13><img height=13
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=ct valign=top width=180 height=13>(=) Valor
									cobrado</td>
							</tr>
							<tr>
								<td class=cp valign=top width=7 height=12><img height=12
									src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png"
									width=1 border=0 alt=""></td>
								<td class=cp valign=top align=right width=180 height=12></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 width=666 border=0>
		<tbody>
			<tr>
				<td valign=top width=666 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=666
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=659 height=13>Sacado</td>
			</tr>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=659 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.sacado}
				</span></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=cp valign=top width=7 height=12><img height=12
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=659 height=12><span class="campo">
						{$T_XPAY_BOLETO_CFG.endereco1}
				</span></td>
			</tr>
		</tbody>
	</table>
	<table cellspacing=0 cellpadding=0 border=0>
		<tbody>
			<tr>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=cp valign=top width=472 height=13><span class="campo">
						{$T_XPAY_BOLETO_CFG.endereco2}
				</span></td>
				<td class=ct valign=top width=7 height=13><img height=13
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/1.png" width=1
					border=0 alt=""></td>
				<td class=ct valign=top width=180 height=13>Cód. baixa</td>
			</tr>
			<tr>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=472 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=472
					border=0 alt=""></td>
				<td valign=top width=7 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=7
					border=0 alt=""></td>
				<td valign=top width=180 height=1><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/2.png" width=180
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
	<table cellSpacing=0 cellPadding=0 border=0 width=666>
		<tbody>
			<tr>
				<td class=ct width=7 height=12></td>
				<td class=ct width=409>Sacador/Avalista</td>
				<td class=ct width=250>
					<div align=right>
						Autenticação mecânica - <b class=cp>Ficha de Compensação</b>
					</div>
				</td>
			</tr>
			<tr>
				<td class=ct colspan=3></td>
			</tr>
		</tbody>
	</table>
	<table cellSpacing=0 cellPadding=0 width=666 border=0>
		<tbody>
			<tr>
				<td vAlign=bottom align=left height=50>
					{xpay_boleto_itau_fajar_FBarCode
						barcode=$T_XPAY_BOLETO_CFG.codigo_barras
						module_link=$T_XPAY_BOLETO_BASELINK 
					}
				</td>
			</tr>
		</tbody>
	</table>
	<table cellSpacing=0 cellPadding=0 width=666 border=0>
		<tr>
			<td class=ct width=666></td>
		</tr>
		<tbody>
			<tr>
				<td class=ct width=666>
					<div align=right>Corte na linha pontilhada</div>
				</td>
			</tr>
			<tr>
				<td class=ct width=666><img height=1
					src="{$T_XPAY_BOLETO_BASELINK}images/layouts/6.png" width=665
					border=0 alt=""></td>
			</tr>
		</tbody>
	</table>
</body>
</html>

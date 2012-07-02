<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Comprovante de Transação</title>x
<style>
{literal}
.todo {
	width:400px;
	padding:20px;
	font-size:12px;
	color:#4b4b4b;
}
.logo-container {
	text-align: center;
	margin-bottom: 10px;
}
.spanleft {
	float:left;
	margin: 10px 0px 0px 10px;
}
.spanright {
	float:right;
	margin: 10px 10px 0px 0px;
}
.radius {
	-moz-border-radius: 2px; 
	-webkit-border-radius: 2px; 
	border-radius: 2px;
	border: 1px solid #c7c7c7;
	height:35px;
	background: -moz-linear-gradient(top, #f9f9f9 0%, #e9e9e9 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f9f9f9), color-stop(100%,#e9e9e9)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* IE10+ */
	background: linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0 ); /* IE6-9 */
	box-shadow: 0 1px 2px #ccc;
	-moz-box-shadow: 0 1px 2px #ccc;
	-webkit-box-shadow: 0 1px 2px #ccc;
	margin-top:1px;
	margin-bottom:5px;
}
.radius-total {
	-moz-border-radius: 2px; 
	-webkit-border-radius: 2px; 
	border-radius: 2px;
	border: 1px solid #000;
    
	height:35px;
	
	background: -moz-linear-gradient(top, #5a5a5a 0%, #2e2e2e 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#5a5a5a), color-stop(100%,#2e2e2e)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #5a5a5a 0%,#2e2e2e 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #5a5a5a 0%,#2e2e2e 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #5a5a5a 0%,#2e2e2e 100%); /* IE10+ */
	background: linear-gradient(top, #5a5a5a 0%,#2e2e2e 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5a5a5a', endColorstr='#2e2e2e',GradientType=0 ); /* IE6-9 */
	box-shadow: 0 1px 2px #ccc;
	-moz-box-shadow: 0 1px 2px #ccc;
	-webkit-box-shadow: 0 1px 2px #ccc;
	margin-top:1px;
	margin-bottom:5px;
	color:#fff;
}
.notification { 
	border: 1px solid #98d81e; 
	border-radius: 3px; 
	display: block; 
	margin-bottom: 5px; 
	overflow: hidden; 
	padding: 10px 0 10px 0; 
	position: relative; 
	z-index: 1; 
	zoom: 1; 
	-moz-border-radius: 3px; 
	-webkit-border-radius: 3px; 
	border-radius: 3px;
	background:#e8ffbc;
/*	width:431px; */
	height:35px;
}
.notification p	{ 
	color: #333333; 
	line-height: 16px;
	text-align:center;
}
div.message_success {
    background: none repeat scroll 0 0 #e9ffbc;
    border-color: #98d81f;
}
div.message_warning {
    background: none repeat scroll 0 0 #ffe8b2;
    border-color: #e9b640;
}
div.message_information {
    background: none repeat scroll 0 0 #bbd7ff;
    border-color: #4c8de9;
}
div.message_failure {
    background: none repeat scroll 0 0 #ffc2c7;
    border-color: #fc7079;
}


.radius img {margin-right: -6px; margin-top: -7px;}
.event-confnormal { 
	-moz-border-radius: 0px 2px 2px 0px; 
	-webkit-border-radius: 0px 2px 2px 0px; 
	border-radius: 2px 2px 2px 2px;
	border: 1px solid #c7c7c7;
    float: left;
	background: -moz-linear-gradient(top, #f9f9f9 0%, #e9e9e9 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f9f9f9), color-stop(100%,#e9e9e9)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* IE10+ */
	background: linear-gradient(top, #f9f9f9 0%,#e9e9e9 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0 ); /* IE6-9 */
	box-shadow: 0 1px 2px #ccc;
	-moz-box-shadow: 0 1px 2px #ccc;
	-webkit-box-shadow: 0 1px 2px #ccc;
	min-width:95px;
	margin-top:20px;
	
}

.event-confnormal:hover { 
	background: -moz-linear-gradient(top, #e9e9e9 0%,#f9f9f9 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e9e9e9), color-stop(100%,#f9f9f9)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #e9e9e9 0%,#f9f9f9 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #e9e9e9 0%,#f9f9f9 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #e9e9e9 0%,#f9f9f9 100%); /* IE10+ */
	background: linear-gradient(top, #e9e9e9 0%,#f9f9f9 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0 ); /* IE6-9 */
}

.event-confnormal img { float:left; margin-right:8px; margin-left: -6px; margin-top: 2px;}

/*Hack CSS para o Google Chrome e Safari*/   
@media screen and (-webkit-min-device-pixel-ratio:0){
   .event-confnormal img { float:left; margin-right:8px; margin-left: -5px; margin-top: -1px;}
}

.event-confnormal span {
    display: block;
    padding: 0;
	padding:8px 0 8px 0;
	float:left;
	font-size:11px;
	color:#848484;
	min-width:44px;
}
.imgs_impress { background: url("/themes/sysclass3/images/icon_28x28.png") 0px -1769px; }
.imgs_seto { background: url("/themes/sysclass3/images/icon_28x28.png") 0px -60px; }
{/literal}
</style>
</head>

<body>
<div class="todo">
	<div class="logo-container" style="text-align: center;">
    	<img src="themes/sysclass3/images/logo.png" />
    </div>

	<div class="notification message_{$T_XPAY_CIELO_MESSAGE_TYPE}">
    	<p><strong>{$T_XPAY_CIELO_MESSAGE}</strong></p>
    </div>
	<div class="radius">
    	<span class="spanleft">ID Transação:</span>
        <span class="spanright"><strong>{$T_XPAY_CIELO_TRANS.tid}</strong></span>
    </div>

	<div class="radius">
    	<span class="spanleft">Descrição:</span>
        <span class="spanright">{$T_XPAY_CIELO_TRANS.descricao|eF_truncate:40}</span>
    </div>

   	<div class="radius">
    	<span class="spanleft">Valor:</span>
        <span class="spanright">#filter:currency-{$T_XPAY_CIELO_TRANS.valor}#</span>
    </div>
    <div class="radius">
    	<span class="spanleft">Data / Hora:</span>
        <span class="spanright">#filter:date-{$T_XPAY_CIELO_TRANS.data}# / #filter:time-{$T_XPAY_CIELO_TRANS.data}#</span>

    </div>
    <div class="radius">
    	<span class="spanleft">Bandeira:</span>
        <span class="spanright"><img src="{$T_XPAY_CIELO_BASELINK}images/{$T_XPAY_CIELO_TRANS.bandeira}.png" /></span>
    </div>
    <div class="radius">
    	<span class="spanleft">Acréscimo / Descontos:</span>
        <span class="spanright">#filter:currency:{$T_XPAY_CIELO_TRANS.total_reajuste}#</span>
    </div>
    <div class="radius-total">
    	<span class="spanleft">Total:</span>
        <span class="spanright">#filter:currency-{$T_XPAY_CIELO_TRANS.valor_total}#</span>
    </div>
	<button name="configurar" type="button" class="event-confnormal" value="configurar" onclick="window.parent.jQuery('#xpay-cielo-modal').dialog('close');" >
    	<img src="images/transp.png" class="imgs_seto" width="29" height="29" />
    <span>Fechar</span>

    </button>
    {if $T_XPAY_CIELO_TRANS.status == 6}
    <button name="configurar" type="button" class="event-confnormal" value="configurar" style="float:right;" onclick="window.print();">
    	<img src="images/transp.png" class="imgs_impress" width="29" height="29" />
    	<span>imprimir</span>
    </button>
    {/if}
</div>
</body>
</html>

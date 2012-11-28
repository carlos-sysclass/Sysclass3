{capture name="t_xpay_cielo_return"}
	<div class="todo">
		<div class="radius">
	    	<span class="spanleft">ID Transação:</span>
	        <span class="spanright"><strong>{$T_XPAY_CIELO_TRANS.tid}</strong></span>
	    </div>
	
		<div class="radius">
	    	<span class="spanleft">Descrição:</span>
	        <span class="spanright">
	        	{if $smarty.get.output == 'dialog'}
	        		{$T_XPAY_CIELO_TRANS.descricao|eF_truncate:50}
	        	{else}
	        		{$T_XPAY_CIELO_TRANS.descricao|eF_truncate:100}
	        	{/if}
	        </span>
	    </div>
	    <div class="radius">
	    	<span class="spanleft">Status:</span>
	        <span class="spanright">{$T_XPAY_CIELO_STATUS}</span>
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
	    {if $T_XPAY_CIELO_RETURN_LINK}
		    <button name="configurar" type="button" class="event-confnormal" value="configurar" style="float:right; margin: 5px;" onclick="window.location.href = '{$T_XPAY_CIELO_RETURN_LINK}';">
		    	<img src="images/transp.png" class="imgs_impress" width="29" height="29" />
		    	<span>Retornar</span>
		    </button>
		    &nbsp;&nbsp;&nbsp;
	    {/if}
	    
	    {if $T_XPAY_CIELO_TRANS.status == 6}
		    <button name="configurar" type="button" class="event-confnormal" value="configurar" style="float:right; margin: 5px;" onclick="window.print();">
		    	<img src="images/transp.png" class="imgs_impress" width="29" height="29" />
		    	<span>imprimir</span>
		    </button>
	    {/if}
	</div>
{/capture}

{eF_template_printBlock
	title 			= $smarty.const.__XPAY_CIELO_RETURN
	data			= $smarty.capture.t_xpay_cielo_return
}

<?php /* Smarty version 2.6.26, created on 2012-06-08 14:17:17
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay/templates/includes/print.negociation.summary.tpl */ ?>
<table class="style1 invoice-summary">
	<thead>
		<tr>
			<th colspan="11"><?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['username']; ?>
 &raquo; <?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['course']; ?>
</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Preço Base</td>
			<td rowspan="2" class="invoice-summary-sign">+</td>
			<td>Acréscimos</td>
			<td rowspan="2" class="invoice-summary-sign">-</td>
			<td>Descontos</td>
			<td rowspan="2" class="invoice-summary-sign">=</td>
			<td>Valor Final</td>
			<td rowspan="2" class="invoice-summary-sign">-</td>
			<td>Valor Pago</td>
			<td rowspan="2" class="invoice-summary-sign">=</td>
			<td>Saldo</td>
		</tr>
		<tr>
			<td>#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['base_price']; ?>
#</td>
			<td>#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['acrescimo']; ?>
#</td>
			<td>#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['desconto']; ?>
#</td>
			<td>#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['full_price']; ?>
#</td>
			<td>#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['paid']; ?>
#</td>
			<td>#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['full_price']-$this->_tpl_vars['T_XPAY_STATEMENT']['paid']; ?>
#</td>
		</tr>
	</tbody>
</table>	
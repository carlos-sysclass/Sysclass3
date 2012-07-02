<?php /* Smarty version 2.6.26, created on 2012-06-08 14:14:37
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/includes/last_payments.list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/includes/last_payments.list.tpl', 43, false),)), $this); ?>
<table id="xpay-view-to-send-invoices-list-table" class="style1 <?php echo $this->_tpl_vars['T_XPAY_TABLE_CLASS']; ?>
">
	<thead>
		<tr>
			<th style="text-align: center;">Aluno</th>
			<th style="text-align: center;">Data</th>
			<th style="text-align: center;">Parcela</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;"><?php echo @__OPTIONS; ?>
</th>
		</tr>
	</thead>
	<tbody>
		<?php $_from = $this->_tpl_vars['T_XPAY_LAST_PAYMENTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['invoice']):
?>
			<tr class="<?php if ($this->_tpl_vars['invoice']['locked']): ?>locked<?php endif; ?>">
				<td><a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=view_user_course_statement&xuser_id=<?php echo $this->_tpl_vars['invoice']['user_id']; ?>
&xcourse_id=<?php echo $this->_tpl_vars['invoice']['course_id']; ?>
"><?php echo $this->_tpl_vars['invoice']['name']; ?>
 <?php echo $this->_tpl_vars['invoice']['surname']; ?>
</a></td>
				<td align="center">#filter:date-<?php echo $this->_tpl_vars['invoice']['data_pagamento']; ?>
#</td>
				<td align="center">
					<?php if ($this->_tpl_vars['invoice']['invoice_index'] == 0): ?>
						Matrícula
					<?php else: ?>
						<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
/<?php echo $this->_tpl_vars['invoice']['total_parcelas']; ?>

					<?php endif; ?>
				</td>
				<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['valor']; ?>
#</td>
			 	<td align="center">
			 		<div>
			 			<!--
						<a class="form-icon" href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=view_paid_item&paid_id=<?php echo $this->_tpl_vars['invoice']['paid_id']; ?>
">
							<img src="images/others/transparent.gif" class="sprite16 sprite16-view">
						</a>
						-->
					</div>
			 	</td>
			</tr>
				<!-- 
			<tr>
			 	<td colspan="7" align="center"><?php echo @__XPAY_NO_INVOICES_FOUND; ?>
</td>
			</tr>
		-->
		<?php endforeach; endif; unset($_from); ?>
	</tbody>
	<!-- 
	<?php if (count($this->_tpl_vars['T_XPAY_STATEMENT']['invoices']) > 0): ?>
		<tfoot>
			<tr>
				<th>&nbsp;</th>
				<th style="text-align: center;"><?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['invoices_count']; ?>
</th>
				<th style="text-align: center;">&nbsp;</th>
				<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['valor']; ?>
#</th>
				<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['total_reajuste']; ?>
#</th>
				<th style="text-align: center;" class="xpay-paid">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['paid']; ?>
#</th>
				<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['valor']+$this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['total_reajuste']-$this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['paid']; ?>
#</th>
				<th style="text-align: center;">&nbsp;</th>
			</tr>
		</tfoot>
	<?php endif; ?>
	 -->
</table>
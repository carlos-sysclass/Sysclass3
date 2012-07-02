<?php /* Smarty version 2.6.26, created on 2012-06-08 15:11:20
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/simulate_due_balance_negociation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'implode', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/simulate_due_balance_negociation.tpl', 10, false),array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/simulate_due_balance_negociation.tpl', 94, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/simulate_due_balance_negociation.tpl', 110, false),)), $this); ?>
<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XPAY_BASEDIR'])."templates/includes/print.negociation.summary.tpl", 'smarty_include_vars' => array('T_XPAY_STATEMENT' => $this->_tpl_vars['T_XPAY_STATEMENT'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

		<?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['javascript']; ?>

	<div class="xpay-invoice-params-selection form-container" id="xpay-invoice-params-selection" title="<?php echo @__XPAY_CREATE_NEW_NEGOCIATION; ?>
">
		<form <?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['attributes']; ?>
>
			<?php echo implode($this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['hidden']); ?>

			<div align="left">
				<label><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['saldo_total']['label']; ?>
:</label>
				<span><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['saldo_total']['html']; ?>
</span>
			</div>
			<div align="left">
				<label><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['taxa_matricula']['label']; ?>
:</label>
				<span><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['taxa_matricula']['html']; ?>
</span>
			</div>
			<div align="left">
				<label><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['total_parcelas']['label']; ?>
:</label>
				<span><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['total_parcelas']['html']; ?>
</span>
			</div>
			<div>
				<label><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['vencimento_1_parcela']['label']; ?>
:</label>
				<span><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['vencimento_1_parcela']['html']; ?>
</span>
			</div>
			<!-- 		
			<div>
				<button class="form-button icon-save" type="<?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['submit_invoice_params']['type']; ?>
" value="<?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['submit_invoice_params']['value']; ?>
">
					<img width="29" height="29" src="images/transp.png">
					<span><?php echo $this->_tpl_vars['T_XPAY_INVOICE_PARAMS_FORM']['submit_invoice_params']['label']; ?>
</span>
				</button>	
			</div>
			 -->
		 </form>
	</div>
	
	<div class="clear"></div>
	<div class="grid_24" style="margin-bottom: 20px;" align="right">
		<button class="form-button icon-add openInvoiceNegociationDialog" type="button">
			<img width="29" height="29" src="images/transp.png">
			<span><?php echo @__XPAY_CREATE_NEW_NEGOCIATION; ?>
</span>
		</button>
		<?php if ($this->_tpl_vars['T_XPAY_NEGOCIATION_IS_SUGESTED']): ?>
		<button class="form-button icon-save saveNegociation" type="button">
			<img width="29" height="29" src="images/transp.png">
			<span><?php echo @__XPAY_SAVE_NEGOCIATION; ?>
</span>
		</button>
		<?php endif; ?>
		
	</div>
	<?php if ($this->_tpl_vars['T_XPAY_NEGOCIATION_IS_SUGESTED']): ?>
	<table class="style1">
		<thead>
			<tr>
				<th style="text-align: center;">Descrição</th>
<!-- 				<th style="text-align: center;">Parcela</th>  -->
				<th style="text-align: center;">Vencimento</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
	 			<th style="text-align: center;">Pago</th>	
				<th style="text-align: center;">Saldo Devedor</th>
				<th style="text-align: center;"><?php echo @__OPTIONS; ?>
</th>
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['T_XPAY_STATEMENT']['sugested_invoices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['invoice']):
?>
				<tr class="<?php if (( $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste'] ) <= $this->_tpl_vars['invoice']['paid']): ?>xpay-paid<?php endif; ?> <?php if ($this->_tpl_vars['invoice']['locked']): ?>locked<?php endif; ?>">
					<td><?php echo $this->_tpl_vars['invoice']['description']; ?>
</td>
				 	<td align="center">#filter:date-<?php echo $this->_tpl_vars['invoice']['data_vencimento']; ?>
#</td>
				 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['valor']; ?>
#</td>
				 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['total_reajuste']; ?>
#</td>
				 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['paid']; ?>
#</td>
				 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste']-$this->_tpl_vars['invoice']['paid']; ?>
#</td>
				 	<td align="center">
				 		<div>
			 				<?php if ($this->_tpl_vars['invoice']['locked'] == 0 && ( $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste'] ) > $this->_tpl_vars['invoice']['paid']): ?>
			 				<!-- 
						 		<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=edit_user_course_statement&xuser_id=<?php echo $this->_tpl_vars['statement']['user_id']; ?>
&xcourse_id=<?php echo $this->_tpl_vars['statement']['course_id']; ?>
&negociation_index=<?php echo $this->_tpl_vars['statement']['negociation_index']; ?>
" class="form-icon">
									<img src="images/others/transparent.gif" class="sprite16 sprite16-edit">
								</a>
							 -->
							<?php endif; ?>
						</div>
				 	</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
				 	<td colspan="7" align="center"><?php echo @__XPAY_NO_INVOICES_FOUND; ?>
</td>
				</tr>
			<?php endif; unset($_from); ?>
		</tbody>
		
		<?php if (count($this->_tpl_vars['T_XPAY_STATEMENT']['sugested_invoices']) > 0): ?>
			<tfoot>
				<tr>
					<th>&nbsp;</th>
					<th style="text-align: center;">&nbsp;</th>
					<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['valor']; ?>
#</th>
					<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['total_reajuste']; ?>
#</th>
					<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['paid']; ?>
#</th>
					<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['balance']; ?>
#</th>
					<th style="text-align: center;">&nbsp;</th>
				</tr>
			</tfoot>
		<?php endif; ?>
	</table>
	<?php endif; ?>
<?php $this->_smarty_vars['capture']['t_xpay_simulate_statement'] = ob_get_contents(); ob_end_clean(); ?>
<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_SIMULATE_NEGOCIATION,'data' => $this->_smarty_vars['capture']['t_xpay_simulate_statement']), $this);?>

	
<?php /* Smarty version 2.6.26, created on 2012-06-08 14:17:17
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/view_user_course_statement.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/view_user_course_statement.tpl', 81, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/view_user_course_statement.tpl', 98, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['T_XPAY_STATEMENT'] )): ?>
	<?php ob_start(); ?>
		<div class="headerTools">
			<?php if ($this->_tpl_vars['T_XPAY_IS_ADMIN']): ?>	
			<span>
				<img 
					src = "images/others/transparent.png"
					class="imgs_cont sprite16 sprite16-go_into"
					border = "0"
				/>				
				<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=simulate_due_balance_negociation&xuser_id=<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['user_id']; ?>
&xcourse_id=<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['course_id']; ?>
">Simular (re)negociação</a>
			</span>
			<?php endif; ?>
			<span>
				<img 
					src = "images/others/transparent.png"
					class="imgs_cont sprite16 sprite16-arrow_right"
					border = "0"
				/>				
				<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=do_payment&negociation_id=<?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['id']; ?>
">Realizar Pagamento</a>
			</span>
		</div>
		<div class="clear"></div>
		
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XPAY_BASEDIR'])."templates/includes/print.negociation.summary.tpl", 'smarty_include_vars' => array('T_XPAY_STATEMENT' => $this->_tpl_vars['T_XPAY_STATEMENT'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<table class="style1">
			<thead>
				<tr>
					<th style="text-align: center;">ID</th>
					<th style="text-align: center;">Parcela</th>
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
				<?php $_from = $this->_tpl_vars['T_XPAY_STATEMENT']['invoices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['invoice']):
?>
					<tr class="<?php if (( $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste'] ) <= $this->_tpl_vars['invoice']['paid']): ?>xpay-paid<?php endif; ?><?php if ($this->_tpl_vars['invoice']['locked']): ?>locked<?php endif; ?>">
					 	<td align="center"><?php echo $this->_tpl_vars['invoice']['invoice_id']; ?>
</td>
					 	<td align="center"><?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
</td>
					 	<td align="center">
					 		<?php if ($this->_tpl_vars['invoice']['data_vencimento']): ?>
					 			#filter:date-<?php echo $this->_tpl_vars['invoice']['data_vencimento']; ?>
#
					 		<?php else: ?>
					 			n/a
					 		<?php endif; ?>
					 	</td>
					 	<td align="center">
					 	#filter:currency:<?php echo $this->_tpl_vars['invoice']['valor']; ?>
#</td>
					 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['total_reajuste']; ?>
#</td>
					 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['paid']; ?>
#</td>
					 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste']-$this->_tpl_vars['invoice']['paid']; ?>
#</td>
					 	<td align="center">
					 		<div>
					 		<?php if ($this->_tpl_vars['invoice']['full_price'] > $this->_tpl_vars['invoice']['paid']): ?>
					 		<!-- 
								<a class="form-icon" href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=do_payment&negociation_id=<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
&invoice_index=<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
&popup=1" onclick = "eF_js_showDivPopup('<?php echo @__XPAY_PRINT_INVOICE; ?>
', 2);" target = "POPUP_FRAME">
									<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
								</a>
							 -->
								<a class="form-icon" href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=do_payment&negociation_id=<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
&invoice_index=<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
">
									<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
								</a>
								
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
		</table>
	<?php $this->_smarty_vars['capture']['t_xpay_view_statement'] = ob_get_contents(); ob_end_clean(); ?>
	<?php if ($this->_tpl_vars['T_XPAY_IS_ADMIN']): ?>
		<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_VIEW_USER_COURSE_STATEMENT,'data' => $this->_smarty_vars['capture']['t_xpay_view_statement']), $this);?>

	<?php else: ?>
		<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_VIEW_MY_COURSE_STATEMENT,'data' => $this->_smarty_vars['capture']['t_xpay_view_statement']), $this);?>

	<?php endif; ?>
<?php endif; ?>
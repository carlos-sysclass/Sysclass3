<?php /* Smarty version 2.6.26, created on 2012-06-08 14:15:03
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/view_to_send_invoices_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/view_to_send_invoices_list.tpl', 63, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/view_to_send_invoices_list.tpl', 81, false),)), $this); ?>
<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XPAY_BASEDIR'])."/templates/includes/options.links.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
	<table id="xpay-view-to-send-invoices-list-table" class="style1">
		<thead>
			<tr>
				<th style="text-align: center;">Vencimento</th>
				<th style="text-align: center;">Parcela</th>
				<th style="text-align: center;">Usuário</th>
				<th style="text-align: center;">Curso</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
				<th style="text-align: center;"><?php echo @__OPTIONS; ?>
</th>
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['T_XPAY_LIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['invoice']):
?>
				<tr class="<?php if ($this->_tpl_vars['invoice']['locked']): ?>locked<?php endif; ?>">
					<td align="center">#filter:date-<?php echo $this->_tpl_vars['invoice']['data_vencimento']; ?>
#</td>
					<td align="center"><?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
/<?php echo $this->_tpl_vars['invoice']['invoice_count']; ?>
</td>
					<td align="center">
						<a href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=view_user_course_statement&xuser_id=<?php echo $this->_tpl_vars['invoice']['user_id']; ?>
&xcourse_id=<?php echo $this->_tpl_vars['invoice']['course_id']; ?>
">
							<?php echo $this->_tpl_vars['invoice']['username']; ?>

						</a>
					</td>
					<td align="center"><?php echo $this->_tpl_vars['invoice']['course']; ?>
</td>
					
				 	<!-- <td align="center"><?php echo $this->_tpl_vars['invoice']['invoice_id']; ?>
</td>  -->
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
				 		<?php if ($this->_tpl_vars['invoice']['full_price'] > $this->_tpl_vars['invoice']['paid']): ?>
				 			<?php if ($this->_tpl_vars['invoice']['sending']): ?>
					 			<input type="checkbox" name="invoices_to_send" value="<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
:<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
" onclick="xPayUpdateSentInvoiceStatus(<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
, <?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
, this);" checked="checked" />
					 		<?php elseif ($this->_tpl_vars['invoice']['sent_count'] > 0): ?>
								<a class="form-icon" href="javascript: xPayMailInvoicesAdviseAction('<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
', '<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
');" title="Reenviar E-mail!"><img src="images/others/transparent.gif" class="sprite16 sprite16-mail" border="0"></a>
					 		<?php else: ?>
					 			<input type="checkbox" name="invoices_to_send" value="<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
:<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
" onclick="xPayUpdateSentInvoiceStatus(<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
, <?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
, this);" />
					 		<?php endif; ?>
				 			<!-- 
							<a class="form-icon" href="<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=print_invoice&negociation_id=<?php echo $this->_tpl_vars['invoice']['negociation_id']; ?>
&invoice_index=<?php echo $this->_tpl_vars['invoice']['invoice_index']; ?>
" onclick = "eF_js_showDivPopup('<?php echo @__XPAY_PRINT_INVOICE; ?>
', 3);" target = "POPUP_FRAME">
								<img src="images/others/transparent.gif" class="sprite16 sprite16-printer">
							</a>
							 -->
						<?php endif; ?>
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
<?php $this->_smarty_vars['capture']['t_xpay_view_send_list'] = ob_get_contents(); ob_end_clean(); ?>

<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_VIEW_TO_SEND_INVOICES_LIST,'options' => $this->_tpl_vars['T_VIEW_TO_SEND_INVOICES_LIST_OPTIONS'],'data' => $this->_smarty_vars['capture']['t_xpay_view_send_list']), $this);?>

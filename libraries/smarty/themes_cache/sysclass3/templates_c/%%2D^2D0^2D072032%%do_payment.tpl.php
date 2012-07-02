<?php /* Smarty version 2.6.26, created on 2012-06-05 14:12:23
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/do_payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/do_payment.tpl', 79, false),)), $this); ?>
<?php if ($this->_tpl_vars['T_XPAY_STATEMENT']): ?>
	<?php ob_start(); ?>
		<?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['javascript']; ?>

		<form <?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['hidden']; ?>

			<table class="style1">
				<thead>
					<tr>
						<th style="text-align: center;">Selecionar</th>
						<th style="text-align: center;">Identificação</th>
						<th style="text-align: center;">Vencimento</th>
						<th style="text-align: center;">Valor</th>
						<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
						<th style="text-align: center;">Pago</th>
						<th style="text-align: center;">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th colspan="6">Curso: <strong><?php echo $this->_tpl_vars['T_XPAY_STATEMENT']['course']; ?>
</strong></th>
					</tr>
					<?php $_from = $this->_tpl_vars['T_XPAY_STATEMENT']['invoices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['invoice']):
?>
					<tr class="<?php if (( $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste'] ) <= $this->_tpl_vars['invoice']['paid']): ?>xpay-paid<?php endif; ?><?php if ($this->_tpl_vars['invoice']['locked']): ?>locked<?php endif; ?>">
						<td align="center">
							<?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['invoice_indexes'][$this->_tpl_vars['invoice']['invoice_index']]['html']; ?>

						</td>
					 	<td align="center"><?php echo $this->_tpl_vars['invoice']['invoice_id']; ?>
</td>
					 	<td align="center">#filter:date-<?php echo $this->_tpl_vars['invoice']['data_vencimento']; ?>
#</td>
					 	<td align="center">#filter:currency-<?php echo $this->_tpl_vars['invoice']['valor']; ?>
#</td>
					 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['total_reajuste']; ?>
#</td>
					 	<td align="center">#filter:currency:<?php echo $this->_tpl_vars['invoice']['paid']; ?>
#</td>
					 	<td align="center">#filter:currency-<?php echo $this->_tpl_vars['invoice']['valor']+$this->_tpl_vars['invoice']['total_reajuste']; ?>
#</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2">Total:</th>
						<th>&nbsp;</th>
						<th style="text-align: center;">#filter:currency-<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['valor']; ?>
#</th>
						<th style="text-align: center;">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['total_reajuste']; ?>
#</th>
						<th style="text-align: center;" class="xpay-paid">#filter:currency:<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['paid']; ?>
#</th>
						<th style="text-align: center;">#filter:currency-<?php echo $this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['valor']+$this->_tpl_vars['T_XPAY_STATEMENT_TOTALS']['total_reajuste']; ?>
#</th>
					</tr>
				</tfoot>
			</table>
			
			<div class="form-field clear">
				<label class="clear" for="textfield"><?php echo @__XPAY_PAYMENT_METHOD; ?>
<span class="required">*</span></label>
			</div>
			
			<?php $_from = $this->_tpl_vars['T_XPAY_METHODS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pay_module_key'] => $this->_tpl_vars['pay_module']):
?>
				<div class="form-field clear">
					<?php if ($this->_tpl_vars['pay_module']['title']): ?>
						<label class="clear" for="textfield"><?php echo $this->_tpl_vars['pay_module']['title']; ?>
</label>
					<?php endif; ?>
					<?php $_from = $this->_tpl_vars['pay_module']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pay_index'] => $this->_tpl_vars['pay_method']):
?>
						<!--  <input type="radio" value="" name="xpay_methods" class="xpay_methods"> -->
						<?php $this->assign('input_name', ($this->_tpl_vars['pay_module_key']).":".($this->_tpl_vars['pay_index'])); ?>
						<?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['pay_methods'][$this->_tpl_vars['input_name']]['html']; ?>

					<?php endforeach; endif; unset($_from); ?>
				</div>
			<?php endforeach; endif; unset($_from); ?>
	<!-- 
			<div class="form-field clear buttons">
				<button class="" type="submit" name="<?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['xpay_submit']['name']; ?>
" value="<?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['xpay_submit']['value']; ?>
">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-go_into"
						border = "0"
					/>							
					<span><?php echo $this->_tpl_vars['T_XPAY_METHOD_FORM']['xpay_submit']['value']; ?>
</span>
				</button>
			</div>
	-->
		</form>
	<?php $this->_smarty_vars['capture']['t_xpay_do_payment'] = ob_get_contents(); ob_end_clean(); ?>
	
	<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_DO_PAYMENT,'data' => $this->_smarty_vars['capture']['t_xpay_do_payment']), $this);?>

<?php endif; ?>
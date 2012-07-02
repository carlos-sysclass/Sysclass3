<?php /* Smarty version 2.6.26, created on 2012-06-08 14:14:37
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/show_payments_summary.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/actions/show_payments_summary.tpl', 18, false),)), $this); ?>
<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XPAY_BASEDIR'])."/templates/includes/last_payments.list.tpl", 'smarty_include_vars' => array('T_XPAY_LAST_PAYMENTS' => $this->_tpl_vars['T_XPAY_LAST_PAYMENTS'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<div style="margin-top: 20px;" align="right">
		<button class="form-button icon-list" type="button" name="xpayViewAllPayments" onclick="window.location.href = '<?php echo $this->_tpl_vars['T_XPAY_BASEURL']; ?>
&action=view_last_paid_invoices'">
			<img width="29" height="29" src="images/transp.png">
			<span><?php echo @__XPAY_ALL_LAST_PAYMENTS; ?>
</span>
		</button>		
	</div>
<?php $this->_smarty_vars['capture']['t_xpay_last_payments_widget'] = ob_get_contents(); ob_end_clean(); ?>

<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XPAY_BASEDIR'])."/templates/includes/options.links.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


	<div class="grid_12">
	<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_LAST_PAYMENTS,'data' => $this->_smarty_vars['capture']['t_xpay_last_payments_widget']), $this);?>

	</div>
<?php $this->_smarty_vars['capture']['t_xpay_summary_list'] = ob_get_contents(); ob_end_clean(); ?>

<?php echo smarty_function_eF_template_printBlock(array('title' => @__XPAY_SUMMARY_LIST,'data' => $this->_smarty_vars['capture']['t_xpay_summary_list']), $this);?>

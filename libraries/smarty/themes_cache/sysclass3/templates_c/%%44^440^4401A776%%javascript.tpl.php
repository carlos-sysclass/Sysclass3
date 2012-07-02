<?php /* Smarty version 2.6.26, created on 2012-06-05 14:12:23
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/includes/javascript.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'Mag_Json_Encode', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xpay//templates/includes/javascript.tpl', 2, false),)), $this); ?>
<script>
	$_xpay_mod_data = <?php echo smarty_function_Mag_Json_Encode(array('data' => $this->_tpl_vars['T_XPAY_MOD_DATA']), $this);?>
;
</script>
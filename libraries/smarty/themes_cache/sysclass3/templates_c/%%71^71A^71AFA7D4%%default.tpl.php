<?php /* Smarty version 2.6.26, created on 2012-06-12 15:32:46
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest/templates/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest/templates/default.tpl', 7, false),array('modifier', 'reset', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest/templates/default.tpl', 8, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest/templates/default.tpl', 14, false),)), $this); ?>
<?php if ($this->_tpl_vars['T_XREQUEST_MAIN_TEMPLATE']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XREQUEST_BASEDIR'])."/templates/".($this->_tpl_vars['T_XREQUEST_MAIN_TEMPLATE']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XREQUEST_BASEDIR'])."/templates/actions/".($this->_tpl_vars['T_XREQUEST_ACTION']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['T_XREQUEST_TEMPLATES'] && count($this->_tpl_vars['T_XREQUEST_TEMPLATES']) == 1): ?>
	<?php $this->assign('item', reset($this->_tpl_vars['T_XREQUEST_TEMPLATES'])); ?>
		
	<?php ob_start(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['item']['template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $this->_smarty_vars['capture'][$this->_tpl_vars['index']] = ob_get_contents(); ob_end_clean(); ?>
			
	<?php echo smarty_function_eF_template_printBlock(array('title' => $this->_tpl_vars['item']['title'],'data' => $this->_smarty_vars['capture'][$this->_tpl_vars['index']],'contentclass' => $this->_tpl_vars['item']['contentclass'],'class' => $this->_tpl_vars['item']['class'],'options' => $this->_tpl_vars['item']['options']), $this);?>

<?php elseif ($this->_tpl_vars['T_XREQUEST_TEMPLATES'] && count($this->_tpl_vars['T_XREQUEST_TEMPLATES']) > 1): ?>
	<?php ob_start(); ?>
		<?php $_from = $this->_tpl_vars['T_XREQUEST_TEMPLATES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['edit_user_iteration'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['edit_user_iteration']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
        $this->_foreach['edit_user_iteration']['iteration']++;
?>
			<?php ob_start(); ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['item']['template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php $this->_smarty_vars['capture'][$this->_tpl_vars['index']] = ob_get_contents(); ob_end_clean(); ?>
		
			<?php echo smarty_function_eF_template_printBlock(array('tabber' => $this->_tpl_vars['item']['title'],'title' => $this->_tpl_vars['item']['title'],'data' => $this->_smarty_vars['capture'][$this->_tpl_vars['index']],'class' => $this->_tpl_vars['item']['class'],'contentclass' => $this->_tpl_vars['item']['contentclass']), $this);?>

		<?php endforeach; endif; unset($_from); ?>
	<?php $this->_smarty_vars['capture']['t_enrollment_tabbers'] = ob_get_contents(); ob_end_clean(); ?>
	
	<?php echo smarty_function_eF_template_printBlock(array('title' => '_XREQUEST_TABS','data' => $this->_smarty_vars['capture']['t_enrollment_tabbers'],'tabs' => $this->_tpl_vars['T_XREQUEST_TEMPLATES']), $this);?>

<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XREQUEST_BASEDIR'])."/templates/includes/javascript.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
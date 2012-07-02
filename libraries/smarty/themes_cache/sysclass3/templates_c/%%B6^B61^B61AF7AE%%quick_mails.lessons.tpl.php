<?php /* Smarty version 2.6.26, created on 2012-06-05 16:16:54
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_quick_mails/templates/includes/quick_mails.lessons.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_quick_mails/templates/includes/quick_mails.lessons.tpl', 6, false),)), $this); ?>
<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_QUICK_MAILS_BASEDIR'])."/templates/includes/quick_mails.list.tpl", 'smarty_include_vars' => array('T_QUICK_MAILS_CONTACT_LIST' => $this->_tpl_vars['T_QUICK_MAILS_CONTACT_LIST'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_smarty_vars['capture']['quick_mail_lesson_contacts'] = ob_get_contents(); ob_end_clean(); ?>
<?php echo smarty_function_eF_template_printBlock(array('title' => @__QUICK_MAILS_CONTACTS,'data' => $this->_smarty_vars['capture']['quick_mail_lesson_contacts'],'contentclass' => 'blockContents','class' => ""), $this);?>
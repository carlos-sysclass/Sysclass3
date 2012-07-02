<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/blocks/xcms.calendar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eF_template_printCalendar', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/blocks/xcms.calendar.tpl', 9, false),)), $this); ?>
	<?php ob_start(); ?>
		<?php ob_start(); ?>
			<?php if ($_SESSION['s_type'] == 'administrator'): ?>
				<?php $this->assign('calendar_ctg', "users&edit_user=".($_GET['edit_user'])); ?>
			<?php else: ?>
				<?php $this->assign('calendar_ctg', 'personal'); ?>
			<?php endif; ?>
			<?php echo smarty_function_eF_template_printCalendar(array('ctg' => $this->_tpl_vars['calendar_ctg'],'events' => $this->_tpl_vars['T_CALENDAR_EVENTS'],'timestamp' => $this->_tpl_vars['T_VIEW_CALENDAR']), $this);?>

		
		<?php $this->_smarty_vars['capture']['t_calendar_code'] = ob_get_contents(); ob_end_clean(); ?>
					<?php $this->_smarty_vars['capture']['moduleCalendar'] = ob_get_contents(); ob_end_clean(); ?>
<?php echo $this->_smarty_vars['capture']['t_calendar_code']; ?>
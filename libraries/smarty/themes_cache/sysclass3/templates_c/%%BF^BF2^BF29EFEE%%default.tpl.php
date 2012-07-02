<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/default.tpl', 7, false),array('modifier', 'reset', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/default.tpl', 8, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/default.tpl', 14, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XCMS_BASEDIR'])."/templates/includes/javascript.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XCMS_BASEDIR'])."/templates/includes/xcms.options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['T_XCMS_MAIN_TEMPLATE']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XCMS_BASEDIR'])."/templates/".($this->_tpl_vars['T_XCMS_MAIN_TEMPLATE']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($this->_tpl_vars['T_XCMS_TEMPLATES'] && count($this->_tpl_vars['T_XCMS_TEMPLATES']) == 1): ?>
	<?php $this->assign('item', reset($this->_tpl_vars['T_XCMS_TEMPLATES'])); ?>

	<?php ob_start(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['item']['template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $this->_smarty_vars['capture'][$this->_tpl_vars['index']] = ob_get_contents(); ob_end_clean(); ?>
			
	<?php echo smarty_function_eF_template_printBlock(array('title' => $this->_tpl_vars['item']['title'],'sub_title' => $this->_tpl_vars['item']['sub_title'],'data' => $this->_smarty_vars['capture'][$this->_tpl_vars['index']],'contentclass' => $this->_tpl_vars['item']['contentclass'],'class' => $this->_tpl_vars['item']['class'],'options' => $this->_tpl_vars['item']['options'],'absoluteImagePath' => $this->_tpl_vars['item']['absoluteImagePath']), $this);?>

<?php elseif ($this->_tpl_vars['T_XCMS_TEMPLATES'] && count($this->_tpl_vars['T_XCMS_TEMPLATES']) > 1): ?>
		
		<?php echo $this->_tpl_vars['T_EDITED_PAGE']['layout']; ?>

		<div class="clearfix" id="layout_margin_top_rigth_index_student">
		<?php $_from = $this->_tpl_vars['T_XCMS_SECTIONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['section']):
?>
			<div class="<?php echo $this->_tpl_vars['section']['class']; ?>
">
				<?php $_from = $this->_tpl_vars['section']['blocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['blockname']):
?>
					<?php $this->assign('item', $this->_tpl_vars['T_XCMS_TEMPLATES'][$this->_tpl_vars['blockname']]); ?>
					<?php if ($this->_tpl_vars['item']['template']): ?>
						<?php ob_start(); ?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['item']['template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php $this->_smarty_vars['capture'][$this->_tpl_vars['index']] = ob_get_contents(); ob_end_clean(); ?>
						
						<?php echo smarty_function_eF_template_printBlock(array('title' => $this->_tpl_vars['item']['title'],'sub_title' => $this->_tpl_vars['item']['sub_title'],'data' => $this->_smarty_vars['capture'][$this->_tpl_vars['index']],'class' => $this->_tpl_vars['item']['class'],'contentclass' => $this->_tpl_vars['item']['contentclass'],'options' => $this->_tpl_vars['item']['options'],'link' => $this->_tpl_vars['item']['link'],'absoluteImagePath' => $this->_tpl_vars['item']['absoluteImagePath']), $this);?>

					<?php elseif ($this->_tpl_vars['item']['links']): ?>
						<?php echo smarty_function_eF_template_printBlock(array('title' => $this->_tpl_vars['item']['title'],'columns' => $this->_tpl_vars['item']['columns'],'links' => $this->_tpl_vars['item']['links']), $this);?>

					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</div>
		<?php endforeach; endif; unset($_from); ?>
		</div>
		<div class="clear">
		
		
<?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XCMS_BASEDIR'])."/templates/actions/".($this->_tpl_vars['T_XCMS_ACTION']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

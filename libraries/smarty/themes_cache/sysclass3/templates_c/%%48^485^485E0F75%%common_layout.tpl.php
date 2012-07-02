<?php /* Smarty version 2.6.26, created on 2012-06-13 14:19:39
         compiled from includes/common_layout.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'basename', 'includes/common_layout.tpl', 1, false),)), $this); ?>
<?php if (! $_GET['popup'] && ! $this->_tpl_vars['T_POPUP_MODE'] && basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/barra_topo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


	<!-- Content -->
	<section id="content" class="wrap">
		<div class="container_24">
			<?php if ($this->_tpl_vars['T_NO_HEADER_MODE']): ?>
				<div class="logo clear">
					<img 
						src="<?php echo $this->_tpl_vars['T_LOGO']; ?>
" 
						class="picture"
						title="<?php echo $this->_tpl_vars['T_CONFIGURATION']['site_name']; ?>
" 
						alt="<?php echo $this->_tpl_vars['T_CONFIGURATION']['site_name']; ?>
" 
						border="0"
						/>
				</div>
			<?php else: ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/header_code.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
	
			<?php if ($_SESSION['s_type'] != 'student' && ( ! $this->_tpl_vars['layoutClass'] || strpos ( $this->_tpl_vars['layoutClass'] , 'hideRight' ) !== false )): ?>
				<div class="grid_8">
					<?php echo $this->_smarty_vars['capture']['left_code']; ?>

				</div>
				<div class="grid_16">
					<?php echo $this->_smarty_vars['capture']['center_code']; ?>

				</div>
			<?php elseif ($_SESSION['s_type'] != 'student' && ( ! $this->_tpl_vars['layoutClass'] || strpos ( $this->_tpl_vars['layoutClass'] , 'hideLeft' ) !== false )): ?>
				<div class="grid_16">
					<?php echo $this->_smarty_vars['capture']['center_code']; ?>

				</div>
				<div class="grid_8">
					<?php echo $this->_smarty_vars['capture']['right_code']; ?>

				</div>
			<?php else: ?>
				<?php echo $this->_smarty_vars['capture']['center_code']; ?>

			<?php endif; ?>
		</div>
	</section>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/footer_code.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else: ?>
	<?php echo $this->_smarty_vars['capture']['center_code']; ?>

	<?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>		
			<?php endif; ?>
<?php endif; ?>
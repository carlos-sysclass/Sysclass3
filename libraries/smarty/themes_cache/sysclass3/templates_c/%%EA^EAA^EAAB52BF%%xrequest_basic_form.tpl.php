<?php /* Smarty version 2.6.26, created on 2012-06-12 15:33:24
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest/templates/includes/xrequest_basic_form.tpl */ ?>
<div class="grid_24 box border" style="margin-top: 15px;">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_MODULE_XREQUEST_BASEDIR'])."/templates/actions/xrequest_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<div class="clear"></div>


<div class="blockContents">
 	<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['javascript']; ?>

	<form <?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['hidden']; ?>

		<div class="grid_12">

			<label><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['name']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['name']['html']; ?>

			
			<br />
			<label><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['valor']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['valor']['html']; ?>

			<br />
			<label><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['dias_prazo']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['dias_prazo']['html']; ?>

			<br />
			<label><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['status']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['status']['html']; ?>

		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<button class="button_colour round_all" type="submit" name="<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['submit_XREQUEST']['name']; ?>
" value="<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['submit_XREQUEST']['value']; ?>
">
				<img width="24" height="24" src="/themes/<?php echo @G_CURRENTTHEME; ?>
/images/icons/small/white/bended_arrow_right.png">
				<span><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['submit_XREQUEST']['value']; ?>
</span>
			</button>
		</div>
		<div class="clear"></div>
	</form>
</div>
<?php /* Smarty version 2.6.26, created on 2012-06-08 14:38:08
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/includes/new_content.form.dialog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'implode', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/includes/new_content.form.dialog.tpl', 3, false),)), $this); ?>
<?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['javascript']; ?>

<form <?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['attributes']; ?>
>
	<?php echo implode($this->_tpl_vars['T_XCONTENT_SELECT_FORM']['hidden']); ?>

	<div class="blockContents" style="width: 100%;">
		<div>
			<label><?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['lesson_id']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['lesson_id']['html']; ?>

		</div>
		<div>
			<label><?php echo @__XCONTENT_CONTENT_NAME; ?>
:</label>
		</div>
		<div style="display: block" id="xcontent_content_tree_container">
		</div>
		<div>
			<label><?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['required']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['required']['html']; ?>

		</div>
		<div class="clear"></div>
		<div style="margin-top: 20px;" align="center">
			<button class="form-button" type="<?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['submit_schedule']['type']; ?>
" name="<?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['submit_schedule']['name']; ?>
" value="<?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['submit_schedule']['value']; ?>
">
				<img width="29" height="29" src="images/transp.png">
				<span><?php echo $this->_tpl_vars['T_XCONTENT_SELECT_FORM']['submit_schedule']['label']; ?>
</span>
			</button>
		</div>
	</div>
</form>
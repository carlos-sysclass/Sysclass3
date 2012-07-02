<?php /* Smarty version 2.6.26, created on 2012-06-06 14:40:42
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/new_schedule.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'implode', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/new_schedule.tpl', 4, false),array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/new_schedule.tpl', 23, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcontent//templates/actions/new_schedule.tpl', 52, false),)), $this); ?>
<?php ob_start(); ?>
 	<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['javascript']; ?>

	<form <?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['attributes']; ?>
>
		<?php echo implode($this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['hidden']); ?>

		<div class="grid_24">
			<label><?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['scope_id']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['scope_id']['html']; ?>

		</div>
		<!-- 
		<div class="grid_24">
			<label><?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['lesson_id']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['lesson_id']['html']; ?>

		</div>
		-->
		<div class="grid_12">
			<label><?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['start_date']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['start_date']['html']; ?>

		</div>
		<div class="grid_12">
			<label><?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['end_date']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['end_date']['html']; ?>

		</div>
		<?php if (count($this->_tpl_vars['T_XCONTENT_SCOPE_FIELDS']) > 0): ?>
			<?php $_from = $this->_tpl_vars['T_XCONTENT_SCOPE_FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
				<div class="grid_24">
					<label><?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM'][$this->_tpl_vars['field']]['label']; ?>
:</label>
					<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM'][$this->_tpl_vars['field']]['html']; ?>

				</div>
			<?php endforeach; endif; unset($_from); ?>			
		<?php endif; ?>
		<!-- 
		<?php if (isset ( $this->_tpl_vars['T_XCONTENT_HTML'] )): ?>
			<div class="grid_24">
				<label><?php echo @__XCONTENT_CONTENT_NAME; ?>
:</label>
				<span id="xcontent_content_tree_text"><?php echo @__SELECT_ONE_OPTION; ?>
</label>
			</div>
			<div class="grid_24">
				<?php echo $this->_tpl_vars['T_XCONTENT_HTML']; ?>

			</div>
		<?php endif; ?>
		 -->
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;" align="center">
			<button class="form-button" type="<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['submit_schedule']['type']; ?>
" name="<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['submit_schedule']['name']; ?>
" value="<?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['submit_schedule']['value']; ?>
">
				<img width="29" height="29" src="images/transp.png">
				<span><?php echo $this->_tpl_vars['T_XCONTENT_NEW_SCHEDULE_FORM']['submit_schedule']['label']; ?>
</span>
			</button>
		</div>
	</form>
<?php $this->_smarty_vars['capture']['t_xcontent_new_schedule_form'] = ob_get_contents(); ob_end_clean(); ?>

<?php echo smarty_function_eF_template_printBlock(array('title' => @__XCONTENT_NEW_SCHEDULE,'data' => $this->_smarty_vars['capture']['t_xcontent_new_schedule_form'],'contentclass' => 'blockContents'), $this);?>

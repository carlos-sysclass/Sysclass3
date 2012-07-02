<?php /* Smarty version 2.6.26, created on 2012-06-13 10:58:52
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse//templates/includes/javascript.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'Mag_Json_Encode', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse//templates/includes/javascript.tpl', 8, false),)), $this); ?>
<script>
	var getCoursesDatatableID	= '<?php echo @_XCOURSE_GETCOURSES_DATATABLE; ?>
';
//	var activeStates 		= ['<?php echo @_ACTIVATE; ?>
', '<?php echo @_DEACTIVATE; ?>
'];

	<?php if ($_GET['xcourse_id']): ?>
		var editCourse_ID = new Number('<?php echo $_GET['xcourse_id']; ?>
');
	<?php endif; ?>
	$_xcourse_mod_data = <?php echo smarty_function_Mag_Json_Encode(array('data' => $this->_tpl_vars['T_XCOURSE_MOD_DATA']), $this);?>
;
</script>
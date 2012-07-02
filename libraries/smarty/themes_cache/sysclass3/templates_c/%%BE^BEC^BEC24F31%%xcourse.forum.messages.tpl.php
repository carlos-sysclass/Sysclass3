<?php /* Smarty version 2.6.26, created on 2012-06-14 10:47:05
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.forum.messages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'eF_template_printForumMessages', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/blocks/xcourse.forum.messages.tpl', 5, false),)), $this); ?>
<ul class="xcontent_forum_lessons_list">
	<?php $_from = $this->_tpl_vars['T_FORUM_LESSON_MESSAGE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lesson_id'] => $this->_tpl_vars['lesson_messages']):
?>
		<li class="lesson_<?php echo $this->_tpl_vars['lesson_id']; ?>
">
			<div>
				<?php echo smarty_function_eF_template_printForumMessages(array('data' => $this->_tpl_vars['lesson_messages'],'forum_lessons_ID' => $this->_tpl_vars['lesson_id'],'limit' => 10), $this);?>

			</div>
		</li>
	<?php endforeach; endif; unset($_from); ?>
</ul>
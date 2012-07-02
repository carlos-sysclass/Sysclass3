<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:52
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcourse/templates/actions/load_lesson_top_links.tpl */ ?>
<?php $_from = $this->_tpl_vars['T_TOP_LINKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module_key'] => $this->_tpl_vars['link']):
?>
<a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" title="<?php echo $this->_tpl_vars['module_key']; ?>
" id="<?php echo $this->_tpl_vars['module_key']; ?>
_change_lesson_id">
	<button class="inputo-top-<?php echo $this->_tpl_vars['module_key']; ?>
" type="button">
	  	<img class="inputo-top-<?php echo $this->_tpl_vars['module_key']; ?>
-icon" src="images/others/transparent.png">
	</button>
</a>
<?php endforeach; endif; unset($_from); ?>
<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/blocks/xcms.news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xcms/templates/blocks/xcms.news.tpl', 4, false),)), $this); ?>
<?php if ($this->_tpl_vars['T_NEWS'] && $this->_tpl_vars['T_CURRENT_USER']->coreAccess['news'] != 'hidden' && $this->_tpl_vars['T_CONFIGURATION']['disable_news'] != 1): ?>
	<?php ob_start(); ?>
		<?php ob_start(); ?>
			<?php if (count($this->_tpl_vars['T_NEWS']) > 0): ?>
				<div id="comunicadosStudentContainer">
				<table class = "style1">
				<?php $_from = $this->_tpl_vars['T_NEWS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['news_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['news_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['news_list']['iteration']++;
?>
					<tr><td><?php echo $this->_foreach['news_list']['iteration']; ?>
. <a title = "<?php echo $this->_tpl_vars['item']['title']; ?>
" href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=news&view=<?php echo $this->_tpl_vars['item']['id']; ?>
&lessons_ID=all&popup=1" target = "POPUP_FRAME" onclick = "eF_js_showDivPopup('<?php echo @_ANNOUNCEMENT; ?>
', 1);"><?php echo $this->_tpl_vars['item']['title']; ?>
</a></td>
					<td class = "cpanelTime">#filter:user_login-<?php echo $this->_tpl_vars['item']['users_LOGIN']; ?>
#, <span title = "#filter:timestamp_time-<?php echo $this->_tpl_vars['item']['timestamp']; ?>
#"><?php echo $this->_tpl_vars['item']['time_since']; ?>
</span></td></tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
				</div>
			<?php else: ?>
				<div = "emptyCategory"><?php echo @_NOANNOUNCEMENTSPOSTED; ?>
</div>
			<?php endif; ?>
		<?php $this->_smarty_vars['capture']['t_news_code'] = ob_get_contents(); ob_end_clean(); ?>
			<?php $this->_smarty_vars['capture']['moduleNewsList'] = ob_get_contents(); ob_end_clean(); ?>
<?php endif; ?>
<?php echo $this->_smarty_vars['capture']['t_news_code']; ?>
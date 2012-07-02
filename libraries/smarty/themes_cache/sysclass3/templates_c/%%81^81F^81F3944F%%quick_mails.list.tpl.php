<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_quick_mails//templates/includes/quick_mails.list.tpl */ ?>
<?php ob_start(); ?>
<ul class="default-list <?php echo $this->_tpl_vars['T_QUICK_MAILS_CONTACT_CLASS']; ?>
">
	<?php $_from = $this->_tpl_vars['T_QUICK_MAILS_CONTACT_LIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['item']):
?>
		<li>
			<a  class="event-conf list-item" title="<?php echo $this->_tpl_vars['item']['title']; ?>
" href = "<?php echo $this->_tpl_vars['item']['href']; ?>
" onclick = "eF_js_showDivPopup('<?php echo $this->_tpl_vars['item']['title']; ?>
', 3);" target = "POPUP_FRAME">
				<img 
					src = "images/others/transparent.png"
					class="imgs_cont sprite<?php echo $this->_tpl_vars['item']['image']['size']; ?>
 sprite<?php echo $this->_tpl_vars['item']['image']['size']; ?>
-<?php echo $this->_tpl_vars['item']['image']['name']; ?>
"
					title = "<?php echo $this->_tpl_vars['item']['title']; ?>
" 
					alt = "<?php echo $this->_tpl_vars['item']['title']; ?>
"
					border = "0"
				/>
				
				<span><?php echo $this->_tpl_vars['item']['title']; ?>
</span>
				<div class="list-item-image">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-go_into"
						title = "<?php echo $this->_tpl_vars['item']['title']; ?>
" 
						alt = "<?php echo $this->_tpl_vars['item']['title']; ?>
"
						border = "0"
					/>
				</div>
			</a>
		</li>
	<?php endforeach; endif; unset($_from); ?>
</ul>
<!-- 
<div class="blockFooter" align="right">
		<img 
			src = "images/others/transparent.gif"
			class="sprite16 sprite16-conversation"
			border = "0"/>
		<a title="<?php echo @__QUICK_MAILS_ANOTHER_CONTACTS; ?>
" href = "<?php echo $this->_tpl_vars['T_QUICK_MAILS_BASEURL']; ?>
">
			<span><?php echo @__QUICK_MAILS_ANOTHER_CONTACTS; ?>
</span>
		</a>
</div>
 -->
<?php $this->_smarty_vars['capture']['t_inner_table_mail_code'] = ob_get_contents(); ob_end_clean(); ?>
<?php echo $this->_smarty_vars['capture']['t_inner_table_mail_code']; ?>
<?php /* Smarty version 2.6.26, created on 2012-06-12 15:49:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest//templates/includes/xrequest_menu.tpl */ ?>
<div class="grid_24 box border" style="margin-top: 15px;">
	<div class="headerTools">
		<?php if ($this->_tpl_vars['T_TYPE_USER'] == 'administrator'): ?> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="<?php echo @_NEWGROUP; ?>
" alt="<?php echo @_NEWGROUP; ?>
"> <a
			href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=get_xrequest"><?php echo @_REQUEST_LIST_TYPE; ?>
</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="<?php echo @_MODULE_XREQUEST_ADDTYPE; ?>
"
			alt="<?php echo @_MODULE_XREQUEST_ADDTYPE; ?>
"> <a
			href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=add_xrequest"><?php echo @_REQUEST_ADD_TYPE; ?>
</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="<?php echo @__XREQUEST_NEW_STATUS; ?>
"
			alt="<?php echo @_XREQUEST_NEW_STATUS; ?>
"> <a
			href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=status_xrequest"><?php echo @_XREQUEST_NEW_STATUS; ?>
</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="<?php echo @_XREQUEST_LIST_STATUS; ?>
"
			alt="<?php echo @_XREQUEST_LIST_STATUS; ?>
"> <a
			href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=get_xrequest_status"><?php echo @_XREQUEST_LIST_STATUS; ?>
</a>
	
		</span> <?php endif; ?> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="<?php echo @_REQUEST_LIST_PROTOCOL; ?>
"
			alt="<?php echo @_REQUEST_LIST_PROTOCOL; ?>
"> <a
			href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=get_xrequest_protocol"><?php echo @_REQUEST_LIST_PROTOCOL; ?>
</a>
	
		</span> <span> <img
			src="/themes/sysclass/images/icons/small/grey/users.png"
			title="<?php echo @_REQUEST_ADD_TYPE; ?>
"
			alt="<?php echo @_REQUEST_ADD_TYPE; ?>
"> <a
			href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=new_xrequest"><?php echo @_REQUEST_NEW_PROTOCOL; ?>
</a>
	
		</span>
	
	</div>
</div>
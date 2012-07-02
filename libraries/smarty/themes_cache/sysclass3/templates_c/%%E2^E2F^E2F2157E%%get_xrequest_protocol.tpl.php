<?php /* Smarty version 2.6.26, created on 2012-06-12 15:36:42
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest//templates/actions/get_xrequest_protocol.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest//templates/actions/get_xrequest_protocol.tpl', 21, false),)), $this); ?>
<?php echo @_REQUEST_LIST_PROTOCOL; ?>


<div class="grid_24 box border" style="margin-top: 15px;">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XREQUEST_BASEDIR'])."/templates/actions/xrequest_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div class="clear"></div>


<table class="sortedTable" style="width:100%">
	<tr>
		<td class="topTitle"><?php echo @_XREQUEST_PROTOCOL_ID; ?>
</td>
		<td class="topTitle"><?php echo @_USER; ?>
</td>
		<td class="topTitle"><?php echo @_XREQUEST_DATAOPEN; ?>
</td>
		<td class="topTitle"><?php echo @_XREQUEST_DATAMODIFICAD; ?>
</td>
		<td class="topTitle"><?php echo @_XREQUEST_TYPE; ?>
</td>
		<td class="topTitle"><?php echo @_XREQUEST_STATUS; ?>
</td>
		
	</tr>
<?php $_from = $this->_tpl_vars['T_REQUEST_PROTCOL_LIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ranges_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ranges_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['range']):
        $this->_foreach['ranges_loop']['iteration']++;
?>
	<tr id="row_<?php echo $this->_tpl_vars['range']['id']; ?>
" class="<?php echo smarty_function_cycle(array('values' => "oddRowColor, evenRowColor"), $this);?>
">
		<td><a href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=get_xrequest_protocol_unit&id=<?php echo $this->_tpl_vars['range']['id']; ?>
"><?php echo $this->_tpl_vars['range']['id']; ?>
</td>
		<td><?php echo $this->_tpl_vars['range']['user_name']; ?>
</td>
		<td><a href=""></a><?php echo $this->_tpl_vars['range']['data_open']; ?>
</td>
		
		<?php if ($this->_tpl_vars['range']['data_modificado'] == null): ?>
		<td><?php echo @_NODATAFOUND; ?>
</td>		
		<?php else: ?>
		<td><?php echo $this->_tpl_vars['range']['data_modificado']; ?>
</td>
		<?php endif; ?>
		
		<td><?php echo $this->_tpl_vars['range']['desc_type']; ?>
</td>
		<td><?php echo $this->_tpl_vars['range']['status']; ?>
</td>
	</tr>
<?php endforeach; else: ?>
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%"><?php echo @_NODATAFOUND; ?>
</td>
	</tr>
<?php endif; unset($_from); ?>
</table>
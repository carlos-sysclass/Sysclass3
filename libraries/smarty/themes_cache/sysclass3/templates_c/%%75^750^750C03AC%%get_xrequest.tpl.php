<?php /* Smarty version 2.6.26, created on 2012-06-12 15:50:30
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest//templates/actions/get_xrequest.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest//templates/actions/get_xrequest.tpl', 14, false),array('function', 'eF_template_printBlock', '/srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest//templates/actions/get_xrequest.tpl', 33, false),)), $this); ?>
<?php ob_start(); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['T_XREQUEST_BASEDIR'])."/templates/includes/xrequest_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="clear"></div>

<table class="sortedTable" style="width:100%">
	<tr>
		<td class="topTitle"><?php echo @_NAME; ?>
</td>
		<td class="topTitle"><?php echo @_MODULE_XREQUEST_VALOR_TYPE; ?>
</td>
		<td class="topTitle"><?php echo @_MODULE_XREQUEST_DIASPRAZO_TYPE; ?>
</td>
		<!-- <td class="topTitle centerAlign noSort"><?php echo @_OPERATIONS; ?>
</td>  -->
	</tr>
<?php $_from = $this->_tpl_vars['T_REQUEST_TYPES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ranges_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ranges_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['range']):
        $this->_foreach['ranges_loop']['iteration']++;
?>
	<tr id="row_<?php echo $this->_tpl_vars['range']['id']; ?>
" class="<?php echo smarty_function_cycle(array('values' => "oddRowColor, evenRowColor"), $this);?>
">
		<td><?php echo $this->_tpl_vars['range']['name']; ?>
</td>
		<td>R$ <?php echo $this->_tpl_vars['range']['price']; ?>
</td>
		<td><?php echo $this->_tpl_vars['range']['dias_prazo']; ?>
</td>
		<!-- 
		<td class="centerAlign">
			<a href="<?php echo $this->_tpl_vars['T_GRADEBOOK_BASEURL']; ?>
&edit_range=<?php echo $this->_tpl_vars['range']['id']; ?>
&popup=1" target="POPUP_FRAME" onclick="eF_js_showDivPopup('<?php echo @_GRADEBOOK_EDIT_RANGE; ?>
', 0)"><img src="<?php echo $this->_tpl_vars['T_GRADEBOOK_BASELINK']; ?>
images/edit.png" alt="<?php echo @_EDIT; ?>
" title="<?php echo @_EDIT; ?>
" border="0"></a>
			<a href="javascript:void(0)" onclick="if(confirm('<?php echo @_IRREVERSIBLEACTIONAREYOUSURE; ?>
')) deleteRange(this, <?php echo $this->_tpl_vars['range']['id']; ?>
);"><img src="<?php echo $this->_tpl_vars['T_GRADEBOOK_BASELINK']; ?>
images/delete.png" alt="<?php echo @_DELETE; ?>
" title="<?php echo @_DELETE; ?>
" border="0"></a>
		</td>
		 -->
	</tr>
<?php endforeach; else: ?>
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%"><?php echo @_NODATAFOUND; ?>
</td>
	</tr>
<?php endif; unset($_from); ?>
</table>
<?php $this->_smarty_vars['capture']['t_xrequest_body'] = ob_get_contents(); ob_end_clean(); ?>

<?php echo smarty_function_eF_template_printBlock(array('title' => @__XREQUEST_TYPES,'data' => $this->_smarty_vars['capture']['t_xrequest_body']), $this);?>
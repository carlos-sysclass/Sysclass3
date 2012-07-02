<?php /* Smarty version 2.6.26, created on 2012-06-04 14:50:47
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_billboard/templates/blocks/billboard.main_billboard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/srv/www/vhosts/local.sysclass.com/www/modules/module_billboard/templates/blocks/billboard.main_billboard.tpl', 1, false),)), $this); ?>
<?php if (count($this->_tpl_vars['T_BILLBOARD_DATA']) > 1): ?>
<!-- 
	<ul class="default-list">
		<li>
			<span style="float:left;"><a href="javascript: void(0);" class="billboard-main-previous"><?php echo @__PREVIOUS; ?>
</a></span>
			<span style="float:right;"><a href="javascript: void(0);" class="billboard-main-next"><?php echo @__NEXT; ?>
</a></span>
			<div style="text-align: center">&nbsp;</div>
		</li>
	</ul>
-->
<?php endif; ?>
<ul id="billboard-main-list">
	<?php $_from = $this->_tpl_vars['T_BILLBOARD_DATA']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['billboard']):
?>
		<li class="course_<?php echo $this->_tpl_vars['billboard']['course_id']; ?>
">
			<ul class="default-list">
				<li>
					<?php echo $this->_tpl_vars['billboard']['data']; ?>

				</li>
			</ul>
			<!-- 
			<div class="blockFooter">
				<span class="to-left">
					<img 
						src = "images/others/transparent.gif"
						class="sprite16 sprite16-calendar3"
						border = "0"/>
						&nbsp;
						<span>#filter:ext-date-<?php echo $this->_tpl_vars['T_BILLBOARD_DATA']['data_registro']; ?>
#</span>
				
				</span>
				<span class="to-right">
					<img 
						src = "images/others/transparent.gif"
						class="sprite16 sprite16-n_pointer"
						border = "0"/>
					<a title="__BILLBOARD_KNOWN_MORE" href = "<?php echo $this->_tpl_vars['T_BILLBOARD_BASEURL']; ?>
">
						<span><?php echo @__BILLBOARD_KNOWN_MORE; ?>
</span>
					</a>
				</span>
			</div>
			 -->
		</li>
	<?php endforeach; endif; unset($_from); ?>
</ul>



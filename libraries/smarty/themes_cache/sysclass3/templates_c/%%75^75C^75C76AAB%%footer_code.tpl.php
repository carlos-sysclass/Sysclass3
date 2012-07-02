<?php /* Smarty version 2.6.26, created on 2012-06-12 11:56:50
         compiled from includes/footer_code.tpl */ ?>
<?php if ($this->_tpl_vars['T_THEME_SETTINGS']->options['show_footer'] && $this->_tpl_vars['T_CONFIGURATION']['show_footer']): ?>
 <?php if ($this->_tpl_vars['T_CONFIGURATION']['additional_footer']): ?>
 	<div class="clear"></div>
 	<div id="footer" class="footer">
  		<?php echo $this->_tpl_vars['T_CONFIGURATION']['additional_footer']; ?>

  	</div>
 <?php else: ?>
  <div id="footer" class="footer">
   <a href = "<?php echo @_MAGESTERURL; ?>
"><?php echo @_MAGESTERNAME; ?>
</a> (version <?php echo @G_VERSION_NUM; ?>
) &bull; <?php echo $this->_tpl_vars['T_VERSION_TYPE']; ?>
 Edition &bull; <a href = "index.php?ctg=contact"><?php echo @_CONTACTUS; ?>
</a>
  </div>
 <?php endif; ?>
<?php endif; ?>
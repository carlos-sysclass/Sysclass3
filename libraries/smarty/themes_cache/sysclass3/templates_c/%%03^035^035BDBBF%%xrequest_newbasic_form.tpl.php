<?php /* Smarty version 2.6.26, created on 2012-06-12 15:36:46
         compiled from /srv/www/vhosts/local.sysclass.com/www/modules/module_xrequest/templates/includes/xrequest_newbasic_form.tpl */ ?>
<div class="grid_24 box border" style="margin-top: 15px;">
			<div class="headerTools">
            	                
                <span>
                    
                  	<img src = "/themes/sysclass/images/icons/small/grey/users.png" title = "<?php echo @_NEWGROUP; ?>
" alt = "<?php echo @_NEWGROUP; ?>
">
                    <a href="<?php echo $this->_tpl_vars['T_REQUEST_BASEURL']; ?>
<?php echo $this->_tpl_vars['T_TYPE_USER']; ?>
.php?ctg=module&op=module_xrequest&action=get_xrequest_protocol"><?php echo @_REQUEST_LIST_PROTOCOL; ?>
</a>	
                  
                </span>
                
                
			</div>
</div>
<div class="clear"></div>


<div class="blockContents">
 	<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['javascript']; ?>

	<form <?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['hidden']; ?>

		<div class="grid_12">
					
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['user_id']['html']; ?>

			
			
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['username']['html']; ?>

			<br />

			<label><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['type']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['type']['html']; ?>

			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['type']['error']; ?>

			<br />
			<br />
			<label><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['desc']['label']; ?>
:</label>
			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['desc']['html']; ?>

			<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['desc']['error']; ?>

			
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<button class="button_colour round_all" type="submit" name="<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['submit_XREQUEST']['name']; ?>
" value="<?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['submit_XREQUEST']['value']; ?>
">
				<img width="24" height="24" src="/themes/<?php echo @G_CURRENTTHEME; ?>
/images/icons/small/white/bended_arrow_right.png">
				<span><?php echo $this->_tpl_vars['T_MODULE_XREQUEST_BASIC_FORM']['submit_XREQUEST']['value']; ?>
</span>
			</button>
		</div>
		<div class="clear"></div>
	</form>
</div>
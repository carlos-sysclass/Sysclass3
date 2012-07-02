<?php /* Smarty version 2.6.26, created on 2012-06-04 14:49:34
         compiled from includes/blocks/login.tpl */ ?>
<!-- <a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=contact"><?php echo @_CONTACTUS; ?>
</a>  -->
<div class="login_corpo">
	<div class="login_top">
    	<img src="themes/sysclass3/images/login_logo.png" />
    </div>
    <div class="login_centro">
		<?php echo $this->_tpl_vars['T_LOGIN_FORM']['javascript']; ?>

		<form <?php echo $this->_tpl_vars['T_LOGIN_FORM']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['T_LOGIN_FORM']['hidden']; ?>

    		<input type="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['login']['type']; ?>
" class="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['login']['class']; ?>
" name="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['login']['name']; ?>
" id="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['login']['name']; ?>
" value="<?php echo @__USER_TEXT; ?>
" />
    		<input type="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['type']; ?>
" class="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['class']; ?>
" name="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['name']; ?>
" id="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['name']; ?>
" value="<?php echo @__PASS_TEXT; ?>
" />
    		<input type="text" class="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['class']; ?>
" name="_<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['name']; ?>
" id="_<?php echo $this->_tpl_vars['T_LOGIN_FORM']['password']['name']; ?>
" value="<?php echo @__PASS_TEXT; ?>
" />

            <button name="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['submit_login']['name']; ?>
" type="submit" class="event-conf" value="<?php echo $this->_tpl_vars['T_LOGIN_FORM']['submit_login']['value']; ?>
" >
                <img src="images/transp.png"  class="imgs_cont" width="29" height="29" />
                <span><?php echo $this->_tpl_vars['T_LOGIN_FORM']['submit_login']['value']; ?>
</span>
            </button>
        </form>
    </div>
    <div class="login_footer">
    <!-- 
    	<input type="checkbox" />
        <p style=" color: #848484; float: left;font-size: 11px; margin: 6px 0 0;">Lembrar</p>
 	-->
        <?php if ($this->_tpl_vars['T_CONFIGURATION']['password_reminder'] && ! $this->_tpl_vars['T_CONFIGURATION']['only_ldap']): ?>
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=reset_pwd"><?php echo @_FORGOTPASSWORD; ?>
</a>
			</p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['T_CONFIGURATION']['signup'] && ! $this->_tpl_vars['T_CONFIGURATION']['only_ldap']): ?>
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=signup"><?php echo @_DONTHAVEACCOUNT; ?>
</a>
			</p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['T_CONFIGURATION']['lessons_directory'] == 1): ?>
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=lessons"><?php echo @_LESSONSLIST; ?>
</a>
			</p>
		<?php endif; ?>
    </div>
    <div style="clear:both"></div>
</div>
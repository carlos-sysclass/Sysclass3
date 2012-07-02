<?php /* Smarty version 2.6.26, created on 2012-06-14 14:41:31
         compiled from includes/header_code.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'includes/header_code.tpl', 66, false),array('modifier', 'eF_truncate', 'includes/header_code.tpl', 68, false),)), $this); ?>
<?php echo '
<style type="text/css">
.menu .menu-dropdown-subtitle a {
/*  font-weight: bold; */
}
.menu-dropdown-subtitle-selected {

}
div.menu-dropdown-subitem {
    margin: 0 0 0 2%;
    display: none;
    width: 98%;
    float: left;
}

.menu div.menu-dropdown-subitem a:hover {
    color: #666666 !important;
}
</style>
'; ?>

<!-- Header -->
<header id="header">
<!--
	<h1 id="logo">
		<a href="#" title="SysClass">SysClass</a>
	</h1>
-->

	<!-- Menu -->
	
<div class="pagetop">
    <div class="head pagesize">
        <div class="pagetop">
            <div class="head pagesize">
                <div class="head_top">
                    <!-- Logo -->
                    <div class="logo clear">
                        <!--
                        <h1 id="logo">					    
                            <a href="#" title="SysClass">SysClass</a>
                        </h1>
                        -->
                        <a href = "<?php if ($_SESSION['s_login']): ?><?php echo $_SERVER['PHP_SELF']; ?>
<?php else: ?>index.php<?php endif; ?>">
                            <img 
                                src="themes/sysclass3/images/login_logo.png" 
                                class="picture" 
                                title="<?php echo $this->_tpl_vars['T_CONFIGURATION']['site_name']; ?>
" 
                                alt="<?php echo $this->_tpl_vars['T_CONFIGURATION']['site_name']; ?>
" 
                                border="0"
                            />
                        </a>
                    </div>
                    <!-- /end logo -->
                </div>
                <?php if ($_SESSION['s_type'] != 'administrator' || $_GET['ctg'] != ''): ?>
                <div class="menu dropdown_menu">
        			<ul class="clear" id="top-menu">
        				<li id="top-menu-home-link">
        					<a href="<?php echo @G_SERVERNAME; ?>
<?php echo $_SESSION['s_type']; ?>
.php">Home</a>
        				</li>
        				
        				<?php $_from = $this->_tpl_vars['T_MENU']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer_menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer_menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['menu_key'] => $this->_tpl_vars['menu']):
        $this->_foreach['outer_menu']['iteration']++;
?>
        						<li>
        							<a 
        								href="<?php if ($this->_tpl_vars['menu']['link']): ?><?php echo $this->_tpl_vars['menu']['link']; ?>
<?php else: ?>javascript: void(0);<?php endif; ?>" 
        								class="<?php if (count($this->_tpl_vars['menu']['options']) > 0): ?>has_dropdown<?php endif; ?>">
        								        								<?php echo ((is_array($_tmp=$this->_tpl_vars['menu']['title'])) ? $this->_run_mod_handler('eF_truncate', true, $_tmp, 30) : smarty_modifier_eF_truncate($_tmp, 30)); ?>

        							</a>
        							<?php if ($this->_tpl_vars['menu']['options']): ?>
        							<ul class="dropdown ui-accordion ui-widget ui-helper-reset" role="tablist"  id="mag_list_menu<?php echo $this->_tpl_vars['menu_key']; ?>
">
        								<?php $_from = $this->_tpl_vars['menu']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['options_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['options_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['option_id'] => $this->_tpl_vars['option']):
        $this->_foreach['options_list']['iteration']++;
?>
        									<?php if (isset ( $this->_tpl_vars['option']['html'] )): ?>
        										<li id="<?php echo $this->_tpl_vars['option']['id']; ?>
" class="ui-accordion-li-fix <?php echo $this->_tpl_vars['option']['class']; ?>
" <?php if ($this->_tpl_vars['menu_key'] == 1 && $_SESSION['s_type'] != 'administrator'): ?>name="lessonSpecific"<?php endif; ?>><?php echo $this->_tpl_vars['option']['html']; ?>
</li>
        									<?php else: ?>
        										<li id="<?php echo $this->_tpl_vars['option']['id']; ?>
" class = "ui-accordion-li-fix <?php echo $this->_tpl_vars['option']['class']; ?>
">
        											<a href = "<?php echo $this->_tpl_vars['option']['link']; ?>
" title="<?php echo $this->_tpl_vars['option']['title']; ?>
"><?php echo $this->_tpl_vars['option']['title']; ?>
</a>
        											
        											<?php if ($this->_tpl_vars['option']['subitens']): ?>
        												<?php $_from = $this->_tpl_vars['option']['subitens']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['suboptions_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['suboptions_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['suboption_id'] => $this->_tpl_vars['suboption']):
        $this->_foreach['suboptions_list']['iteration']++;
?>
        													<div id="<?php echo $this->_tpl_vars['suboption']['id']; ?>
" class = "menu-dropdown-subitem <?php echo $this->_tpl_vars['suboption']['class']; ?>
">
        														<a href = "<?php echo $this->_tpl_vars['suboption']['link']; ?>
" title="<?php echo $this->_tpl_vars['suboption']['title']; ?>
"><?php echo $this->_tpl_vars['suboption']['title']; ?>
</a>
        													</div>
        												<?php endforeach; endif; unset($_from); ?>
        											<?php endif; ?>
        											
        										</li>
        									<?php endif; ?>
        								<?php endforeach; endif; unset($_from); ?>
        							</ul>
        							<?php endif; ?>
        						</li>
        				<?php endforeach; endif; unset($_from); ?>
        				<!-- 
        				        				<li>
        					<a href="javascript: void(0); "><?php echo @_SWITCHACCOUNT; ?>
</a>
        					<ul class="dropdown">
        						        							<li><a href="javascript: changeAccount('');">#filter:login-#</a></li>
        						        					</ul>
        				</li>
        				        				 -->
        				<!-- 
        				<li>
        					<a href="javascript: void(0); "><?php echo @_MODULES; ?>
</a>
        					<ul class="dropdown">
        						<?php $_from = $this->_tpl_vars['T_BAR_ADDITIONAL_COURSE_LESSONS_MENU_TOP']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
        							<li><a href="<?php echo $this->_tpl_vars['item']['href']; ?>
"><?php echo $this->_tpl_vars['item']['name']; ?>
</a></li>
        						<?php endforeach; endif; unset($_from); ?>
        					</ul>
        				</li>
        				 -->
        			</ul>					
				</div>
                <?php endif; ?>
			</div>
		</div>
    </div>
</div>
	<!-- /end Menu -->


	<!-- BreadCrumb -->
	<!--<section id="breadcrumb">
		<p>Você está em: <a href="#" title="Home">Home &raquo; </a><a href="#" title="Configurações">Configurações &raquo; </a></p>
	</section>-->
	<!-- /end breadcrumb -->
	
            <div id="dialog-avatar" title="<?php echo @_MYPROFILE; ?>
 - <?php echo @_CHANGEAVATAR; ?>
" style="display:none">
                  
                   <fieldset class = "fieldsetSeparator">
                    avatar.php
                       <?php echo $this->_tpl_vars['T_AVATAR_FORM']['javascript']; ?>

                           <form <?php echo $this->_tpl_vars['T_AVATAR_FORM']['attributes']; ?>
>
                            <?php echo $this->_tpl_vars['T_AVATAR_FORM']['hidden']; ?>

                            <table class = "formElements">
                         
                             <tr><td class = "labelCell"><?php echo @_CURRENTAVATAR; ?>
:&nbsp;</td>
                              <td class = "elementCell">
                              <img src = "view_file.php?file=<?php echo $this->_tpl_vars['T_CURRENT_USER_AVATAR']['avatar']; ?>
" title="<?php echo @T_CURRENTAVATAR; ?>
" alt="<?php echo @_CURRENTAVATAR; ?>
" <?php if (isset ( $this->_tpl_vars['T_NEWWIDTH'] )): ?> width = "<?php echo $this->_tpl_vars['T_NEWWIDTH']; ?>
" height = "<?php echo $this->_tpl_vars['T_NEWHEIGHT']; ?>
"<?php endif; ?> /></td></tr>
                            <?php if (! isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] ) || $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] == 'change'): ?>
                             <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['delete_avatar']['label']; ?>
:&nbsp;</td>
                              <td class = "elementCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['delete_avatar']['html']; ?>
</td></tr>
                             <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['file_upload']['label']; ?>
:&nbsp;</td>
                              <td class = "elementCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['file_upload']['html']; ?>
</td></tr>
                             
                             <tr><td></td>
                              <td class = "elementCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['submit_upload_file']['html']; ?>
</td></tr>
                            <?php endif; ?>
                            </table>
                           </form>
                   </fieldset>
                  
            </div>

	
	
	<?php if ($this->_tpl_vars['T_CONFIGURATION']['updater_period']): ?><script> var updaterPeriod = '<?php echo $this->_tpl_vars['T_CONFIGURATION']['updater_period']; ?>
';</script><?php else: ?><script>var updaterPeriod = 100000;</script><?php endif; ?>
</header>
<!-- /end Header -->
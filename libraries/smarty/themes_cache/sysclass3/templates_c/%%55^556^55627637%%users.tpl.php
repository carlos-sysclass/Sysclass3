<?php /* Smarty version 2.6.26, created on 2012-06-05 14:06:40
         compiled from includes/users.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'includes/users.tpl', 57, false),array('function', 'eF_template_printBlock', 'includes/users.tpl', 107, false),)), $this); ?>

<?php if ($_GET['add_user'] || $_GET['edit_user']): ?>
                <?php ob_start(); ?>
                                <tr><td class = "moduleCell" id = "singleColumn">

                                        <table width = "100%">
                                            <tr><td class = "topAlign" width = "50%">
                                                    <?php if (isset ( $this->_tpl_vars['T_PERSONAL'] )): ?>
                                                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/personal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                </td></tr>
        <?php $this->_smarty_vars['capture']['moduleNewUser'] = ob_get_contents(); ob_end_clean(); ?>
<?php else: ?>

    <?php ob_start(); ?>



             <tr><td class = "moduleCell">
              <script>var activate = '<?php echo @_ACTIVATE; ?>
';var deactivate = '<?php echo @_DEACTIVATE; ?>
';</script>
                    <?php ob_start(); ?>
                            <?php if (! isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] ) || $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] == 'change'): ?>
                                <div class = "headerTools">
                                    <span>
                                        <img src = "images/16x16/add.png" title = "<?php echo @_NEWUSER; ?>
" alt = "<?php echo @_NEWUSER; ?>
">
                                        <a href = "administrator.php?ctg=users&add_user=1"><?php echo @_NEWUSER; ?>
</a>
                                    </span>
                                </div>
                                <div class="clear">
                                </div>
                                <?php $this->assign('_change_', 1); ?>
                            <?php endif; ?>
<!--ajax:usersTable-->

                                <table style = "width:100%" class = "sortedTable" size = <?php echo $this->_tpl_vars['T_USERS_SIZE']; ?>
 sortBy = "0" id = "usersTable" useAjax = "1" rowsPerPage = "20" url = "administrator.php?ctg=users&">
                                    <tr class = "topTitle">
                                        <td class = "topTitle" name = "login"><?php echo @_USER; ?>
</td>
                                        <td class = "topTitle" name = "user_type"><?php echo @_USERTYPE; ?>
</td>
                                        <td class = "topTitle centerAlign" name = "lessons_num"><?php echo @_LESSONS; ?>
</td>
                                        <td class = "topTitle centerAlign" name = "courses_num"><?php echo @_COURSES; ?>
</td>
                                        <td class = "topTitle centerAlign" name = "groups_num"><?php echo @_GROUPS; ?>
</td>
                                        <td class = "topTitle" name = "last_login"><?php echo @_LASTLOGIN; ?>
</td>
                                        <td class = "topTitle centerAlign" name = "active"><?php echo @_ACTIVE2; ?>
</td>
                                    <?php if (! isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['statistics'] ) || $this->_tpl_vars['T_CURRENT_USER']->coreAccess['statistics'] != 'hidden'): ?>
                                        <td class = "topTitle centerAlign noSort"><?php echo @_STATISTICS; ?>
</td>
                                    <?php endif; ?>
                                    <?php if (! isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] ) || $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] == 'change'): ?>
                                        <td class = "topTitle centerAlign"><?php echo @_OPERATIONS; ?>
</td>
                                    <?php endif; ?>
                                    </tr>
                            <?php $_from = $this->_tpl_vars['T_USERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['users_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['users_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['user']):
        $this->_foreach['users_list']['iteration']++;
?>
                                    <tr id="row_<?php echo $this->_tpl_vars['user']['login']; ?>
" class = "<?php echo smarty_function_cycle(array('values' => "oddRowColor, evenRowColor"), $this);?>
 <?php if (! $this->_tpl_vars['user']['active']): ?>deactivatedTableElement<?php endif; ?>">
                                            <td><a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=users&edit_user=<?php echo $this->_tpl_vars['user']['login']; ?>
" class = "editLink" <?php if (( $this->_tpl_vars['user']['pending'] == 1 )): ?>style="color:red;"<?php endif; ?>><span id="column_<?php echo $this->_tpl_vars['user']['login']; ?>
" <?php if (! $this->_tpl_vars['user']['active']): ?>style="color:red;"<?php endif; ?>>#filter:login-<?php echo $this->_tpl_vars['user']['login']; ?>
#</span></a></td>
                                            <td><?php if ($this->_tpl_vars['user']['user_types_ID']): ?><?php echo $this->_tpl_vars['T_ROLES'][$this->_tpl_vars['user']['user_types_ID']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['T_ROLES'][$this->_tpl_vars['user']['user_type']]; ?>
<?php endif; ?></td>
                                            <td class = "centerAlign"><?php echo $this->_tpl_vars['user']['lessons_num']; ?>
</td>
                                            <td class = "centerAlign"><?php echo $this->_tpl_vars['user']['courses_num']; ?>
</td>
                                            <td class = "centerAlign"><?php echo $this->_tpl_vars['user']['groups_num']; ?>
</td>
                                            <td><?php if ($this->_tpl_vars['user']['last_login']): ?>#filter:timestamp_time_nosec-<?php echo $this->_tpl_vars['user']['last_login']; ?>
#<?php else: ?><?php echo @_NEVER; ?>
<?php endif; ?></td>
                                            <td class = "centerAlign">
           <?php if (! ( $this->_tpl_vars['user']['user_type'] == 'administrator' && $this->_tpl_vars['user']['user_types_ID'] == 0 && $this->_tpl_vars['T_CURRENT_USER']->user['user_type'] == 'administrator' && $this->_tpl_vars['T_CURRENT_USER']->user['user_types_ID'] != 0 )): ?>
            <?php if ($this->_tpl_vars['user']['login'] != $_SESSION['s_login']): ?>
             <?php if ($this->_tpl_vars['user']['active'] == 1): ?>
              <img class = "ajaxHandle" src = "images/16x16/trafficlight_green.png" alt = "<?php echo @_DEACTIVATE; ?>
" title = "<?php echo @_DEACTIVATE; ?>
" <?php if ($this->_tpl_vars['_change_']): ?>onclick = "activateUser(this, '<?php echo $this->_tpl_vars['user']['login']; ?>
')"<?php endif; ?>>
             <?php else: ?>
              <img class = "ajaxHandle" src = "images/16x16/trafficlight_red.png" alt = "<?php echo @_ACTIVATE; ?>
" title = "<?php echo @_ACTIVATE; ?>
" <?php if ($this->_tpl_vars['_change_']): ?>onclick = "activateUser(this, '<?php echo $this->_tpl_vars['user']['login']; ?>
')"<?php endif; ?>>
             <?php endif; ?>
            <?php else: ?>
             <img class = "inactiveImage" src = "images/16x16/trafficlight_green.png" alt = "<?php echo @_ACTIVE; ?>
" title = "<?php echo @_ACTIVE; ?>
">
            <?php endif; ?>
           <?php endif; ?>
                                            </td>
                                        <?php if (! isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['statistics'] ) || $this->_tpl_vars['T_CURRENT_USER']->coreAccess['statistics'] != 'hidden'): ?>
                                            <td class = "centerAlign"><a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=statistics&option=user&sel_user=<?php echo $this->_tpl_vars['user']['login']; ?>
" title = "<?php echo @_STATISTICS; ?>
"><img src = "images/16x16/reports.png" title = "<?php echo @_STATISTICS; ?>
" alt = "<?php echo @_STATISTICS; ?>
" /></a></td>
                                        <?php endif; ?>
                                        <?php if (! isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] ) || $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] == 'change'): ?>
                                            <td class = "centerAlign">
            <?php if (! ( $this->_tpl_vars['user']['user_type'] == 'administrator' && $this->_tpl_vars['user']['user_types_ID'] == 0 && $this->_tpl_vars['T_CURRENT_USER']->user['user_type'] == 'administrator' && $this->_tpl_vars['T_CURRENT_USER']->user['user_types_ID'] != 0 )): ?>
                <a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=users&edit_user=<?php echo $this->_tpl_vars['user']['login']; ?>
" class = "editLink"><img border = "0" src = "images/16x16/edit.png" title = "<?php echo @_EDIT; ?>
" alt = "<?php echo @_EDIT; ?>
" /></a>&nbsp;
            <?php endif; ?>
            <?php if (! ( $this->_tpl_vars['user']['user_type'] == 'administrator' )): ?>
                <a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=module&op=module_xpay&action=view_user_statement&xuser_login=<?php echo $this->_tpl_vars['user']['login']; ?>
" class = "editLink"><img border = "0" src = "images/16x16/do_pay.png" title = "<?php echo @__XPAY_VIEW_USER_STATEMENT; ?>
" alt = "<?php echo @__XPAY_VIEW_USER_STATEMENT; ?>
" /></a>&nbsp;<br />
            <?php endif; ?>
            <?php if (! ( $this->_tpl_vars['user']['user_type'] == 'administrator' && $this->_tpl_vars['user']['user_types_ID'] == 0 && $this->_tpl_vars['T_CURRENT_USER']->user['user_type'] == 'administrator' && $this->_tpl_vars['T_CURRENT_USER']->user['user_types_ID'] != 0 )): ?>
             <?php if ($_SESSION['s_login'] != $this->_tpl_vars['user']['login']): ?>
               <img class = "ajaxHandle" src = "images/16x16/error_delete.png" title = "<?php echo @_DELETE; ?>
" alt = "<?php echo @_DELETE; ?>
" onclick = "if (confirm('<?php echo @_AREYOUSUREYOUWANTTODELETEUSER; ?>
')) deleteUser(this, '<?php echo $this->_tpl_vars['user']['login']; ?>
')"/>


             <?php else: ?>
              <img class = "ajaxHandle inactiveImage" src = "images/16x16/error_delete.png" title = "<?php echo @_DELETE; ?>
" alt = "<?php echo @_DELETE; ?>
" />
             <?php endif; ?>
            <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr class = "defaultRowHeight oddRowColor"><td class = "emptyCategory" colspan = "100%"><?php echo @_NODATAFOUND; ?>
</td></tr>
                                    <?php endif; unset($_from); ?>
                                </table>

<!--/ajax:usersTable-->
                 <?php $this->_smarty_vars['capture']['t_users_code'] = ob_get_contents(); ob_end_clean(); ?>
                 <?php echo smarty_function_eF_template_printBlock(array('title' => @_UPDATEUSERS,'data' => $this->_smarty_vars['capture']['t_users_code'],'image' => '32x32/user.png','help' => 'Users'), $this);?>

                </td></tr>

    <?php $this->_smarty_vars['capture']['moduleUsers'] = ob_get_contents(); ob_end_clean(); ?>

<?php endif; ?>
<?php /* Smarty version 2.6.26, created on 2012-06-08 14:15:46
         compiled from includes/personal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'includes/personal.tpl', 66, false),array('modifier', 'cat', 'includes/personal.tpl', 399, false),array('function', 'eF_template_html_select_date', 'includes/personal.tpl', 124, false),array('function', 'cycle', 'includes/personal.tpl', 252, false),array('function', 'eF_template_printBlock', 'includes/personal.tpl', 350, false),)), $this); ?>
<script><?php if ($this->_tpl_vars['T_BROWSER'] == 'IE6'): ?><?php $this->assign('globalImageExtension', 'gif'); ?>var globalImageExtension = 'gif';<?php else: ?><?php $this->assign('globalImageExtension', 'png'); ?>var globalImageExtension = 'png';<?php endif; ?></script>
<script>

 var areYouSureYouWantToCancelConst ='<?php echo @_AREYOUSUREYOUWANTTOCANCELJOB; ?>
';
 var sessionType ='<?php echo $_SESSION['s_type']; ?>
';
 var editUserLogin ='<?php echo $_GET['edit_user']; ?>
';
 var operationCategory ='<?php echo $_GET['op']; ?>
';
 var jobAlreadyAssignedConst ='<?php echo @_JOBALREADYASSIGNED; ?>
';
 var jobDoesNotExistConst ='<?php echo @_JOBDOESNOTEXIST; ?>
';
 var noPlacementsAssigned ='<?php echo @_NOPLACEMENTSASSIGNEDYET; ?>
';
 var onlyImageFilesAreValid ='<?php echo @_ONLYIMAGEFILESAREVALID; ?>
';

 var userHasLesson ='<?php echo @_USERHASTHELESSON; ?>
';
 var serverName ='<?php echo @G_SERVERNAME; ?>
';

 var msieBrowser ='<?php echo @MSIE_BROWSER; ?>
';
 var sessionLogin ='<?php echo $_SESSION['s_login']; ?>
';
 var clickToChangeStatus ='<?php echo @_CLICKTOCHANGESTATUS; ?>
';
 var youHaventSetAdditionalAccounts ='<?php echo @_MAPPEDACCOUNTSUCCESSFULLYDELETED; ?>
';
 var openFacebookSession ='<?php echo $this->_tpl_vars['T_OPEN_FACEBOOK_SESSION']; ?>
';
 var currentOperation ='<?php echo $this->_tpl_vars['T_OP']; ?>
';
var isInfoToolDisabled = <?php echo $this->_tpl_vars['T_CONFIGURATION']['disable_tooltip']; ?>
;

var jobsRows = new Array();
var branchesValues = new Array();
var jobValues = new Array();
var branchPositionValues = new Array();

var tabberLoadingConst = "<?php echo @_LOADINGDATA; ?>
";
var enableMyJobSelect = false;
</script>


<?php if ($_GET['add_user'] || $this->_tpl_vars['T_OP'] == 'account'): ?>

  <?php ob_start(); ?>
  <?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['javascript']; ?>

  <form <?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['attributes']; ?>
>
   <?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['hidden']; ?>


   <?php if (! ( isset ( $_GET['add_user'] ) )): ?>
   <fieldset class = "fieldsetSeparator">
   <legend><?php echo $this->_tpl_vars['T_TITLES']['account']['edituser']; ?>
</legend>
   <?php endif; ?>

   <table class = "formElements" width="90%">

   




    <?php if (( isset ( $_GET['add_user'] ) )): ?>

     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['new_login']['label']; ?>
:&nbsp;</td>
      <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['new_login']['html']; ?>
</td></tr>
      <tr><td></td><td class = "infoCell"><?php echo @_ONLYALLOWEDCHARACTERSLOGIN; ?>
</td></tr>
     <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['new_login']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['new_login']['error']; ?>
</td></tr><?php endif; ?>
     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['label']; ?>
:&nbsp;</td>
      <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['html']; ?>
</td></tr>
     <tr><td></td><td class = "infoCell"><?php echo ((is_array($_tmp=@_PASSWORDMUSTBE6CHARACTERS)) ? $this->_run_mod_handler('replace', true, $_tmp, "%x", $this->_tpl_vars['T_CONFIGURATION']['password_length']) : smarty_modifier_replace($_tmp, "%x", $this->_tpl_vars['T_CONFIGURATION']['password_length'])); ?>
</td></tr>
     <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['error']; ?>
</td></tr><?php endif; ?>

     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['label']; ?>
:&nbsp;</td>
      <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['html']; ?>
</td></tr>
     <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['error']; ?>
</td></tr><?php endif; ?>
    <?php else: ?>
     <?php if (! $this->_tpl_vars['T_LDAP_USER']): ?>
      <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['label']; ?>
:&nbsp;</td>
       <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['html']; ?>
</td></tr>
      <tr><td></td><td class = "infoCell"><?php echo ((is_array($_tmp=@_PASSWORDMUSTBE6CHARACTERS)) ? $this->_run_mod_handler('replace', true, $_tmp, "%x", $this->_tpl_vars['T_CONFIGURATION']['password_length']) : smarty_modifier_replace($_tmp, "%x", $this->_tpl_vars['T_CONFIGURATION']['password_length'])); ?>
</td></tr>
      <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['password_']['error']; ?>
</td></tr><?php endif; ?>

      <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['label']; ?>
:&nbsp;</td>
       <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['html']; ?>
</td></tr>
      <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['passrepeat']['error']; ?>
</td></tr><?php endif; ?>
     <?php else: ?>
      <tr><td class = "labelCell"><?php echo @_PASSWORD; ?>
:&nbsp;</td>
       <td style="white-space:nowrap;"><?php echo @_LDAPUSER; ?>
</td></tr>
     <?php endif; ?>
    <?php endif; ?>
    <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['name']['label']; ?>
:&nbsp;</td>
     <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['name']['html']; ?>
</td></tr>
    <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['name']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['name']['error']; ?>
</td></tr><?php endif; ?>

    <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['surname']['label']; ?>
:&nbsp;</td>
     <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['surname']['html']; ?>
</td></tr>
    <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['surname']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['surname']['error']; ?>
</td></tr><?php endif; ?>
    <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['email']['label']; ?>
:&nbsp;</td>
     <td><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['email']['html']; ?>
</td></tr>
    <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['email']['error'] && @G_VERSIONTYPE != 'enterprise'): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['email']['error']; ?>
</td></tr><?php endif; ?>
    <?php if (( $_SESSION['s_type'] == 'administrator' || ( @G_VERSIONTYPE == 'enterprise' && $this->_tpl_vars['T_CTG'] != 'personal' ) )): ?>
      <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['group']['label']; ?>
:&nbsp;</td><td><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['group']['html']; ?>
</td></tr>
      <!-- Removed in order to allowed to subadmins to change user type -->
      <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['user_type']['label']; ?>
:&nbsp;</td>
      <td><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['user_type']['html']; ?>
</td></tr>
      <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['user_type']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['user_type']['error']; ?>
</td></tr><?php endif; ?>
         <?php endif; ?>
    <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['languages_NAME']['label'] != ""): ?>
     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['languages_NAME']['label']; ?>
:&nbsp;</td>
      <td><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['languages_NAME']['html']; ?>
</td></tr>
      <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['languages_NAME']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['languages_NAME']['error']; ?>
</td></tr><?php endif; ?>
    <?php endif; ?>
    <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['timezone']['label']; ?>
:&nbsp;</td>
          <td><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['timezone']['html']; ?>
</td></tr>
    <?php if (( $_SESSION['s_type'] == 'administrator' || ( @G_VERSIONTYPE == 'enterprise' && $this->_tpl_vars['T_CTG'] != 'personal' ) )): ?>
     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['active']['label']; ?>
:&nbsp;</td>
      <td><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['active']['html']; ?>
</td></tr>
      <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM']['active']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['active']['error']; ?>
</td></tr><?php endif; ?>
    <?php endif; ?>
    <?php $_from = $this->_tpl_vars['T_USER_PROFILE_FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profile_fields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profile_fields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['profile_fields']['iteration']++;
?>
     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM'][$this->_tpl_vars['item']]['label']; ?>
:&nbsp;</td>
      <td class = "elementCell"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM'][$this->_tpl_vars['item']]['html']; ?>
</td></tr>
     <?php if ($this->_tpl_vars['T_PERSONAL_DATA_FORM'][$this->_tpl_vars['item']]['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM'][$this->_tpl_vars['item']]['error']; ?>
</td></tr><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    <?php $_from = $this->_tpl_vars['T_USER_PROFILE_DATES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profile_fields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profile_fields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['profile_fields']['iteration']++;
?>
     <tr><td class = "labelCell"><?php echo $this->_tpl_vars['item']['name']; ?>
:&nbsp;</td>
      <td class = "elementCell"><?php echo smarty_function_eF_template_html_select_date(array('prefix' => $this->_tpl_vars['item']['prefix'],'emptyvalues' => '1','time' => $this->_tpl_vars['item']['value'],'start_year' => "-45",'end_year' => "+10",'field_order' => $this->_tpl_vars['T_DATE_FORMATGENERAL']), $this);?>
</td></tr>
    <?php endforeach; endif; unset($_from); ?>
    <?php if (( ! isset ( $_GET['add_user'] ) )): ?>
    <tr><td class = "labelCell"><?php echo @_REGISTRATIONDATE; ?>
:&nbsp;</td>
     <td>#filter:timestamp-<?php echo $this->_tpl_vars['T_REGISTRATION_DATE']; ?>
#</td></tr>
      <?php endif; ?>
         <tr><td></td><td class = "submitCell" style = "text-align:left">
        <?php echo $this->_tpl_vars['T_PERSONAL_DATA_FORM']['submit_personal_details']['html']; ?>
</td></tr>
   </table>
  </form>
  <?php if (! ( isset ( $_GET['add_user'] ) )): ?>
      <?php if (( isset ( $this->_tpl_vars['T_PERSONAL_CTG'] ) || ( $_SESSION['s_type'] == 'administrator' || $_SESSION['employee_type'] == @_SUPERVISOR ) ) && isset ( $this->_tpl_vars['T_SOCIAL_INTERFACE'] )): ?>
   <?php endif; ?>
   <fieldset class = "fieldsetSeparator">
   <legend><?php echo $this->_tpl_vars['T_TITLES']['account']['profile']; ?>
</legend>
   <?php echo $this->_tpl_vars['T_AVATAR_FORM']['javascript']; ?>

   <form <?php echo $this->_tpl_vars['T_AVATAR_FORM']['attributes']; ?>
>
    <?php echo $this->_tpl_vars['T_AVATAR_FORM']['hidden']; ?>

    <table class = "formElements">
     <?php if (isset ( $this->_tpl_vars['T_SOCIAL_INTERFACE'] )): ?>
      <?php if (( $_GET['personal'] ) || ( $_GET['edit_user'] == $_SESSION['s_login'] )): ?>
             <?php endif; ?>
      <tr><td></td>
       <td><span>
        <img style="vertical-align:middle" src = "images/16x16/order.png" title = "<?php echo @_TOGGLEHTMLEDITORMODE; ?>
" alt = "<?php echo @_TOGGLEHTMLEDITORMODE; ?>
" />&nbsp;
        <a href = "javascript:toggleEditor('short_description','simpleEditor');" id = "toggleeditor_link"><?php echo @_TOGGLEHTMLEDITORMODE; ?>
</a>
       </span></td></tr>
      <tr><td class = "labelCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['short_description']['label']; ?>
:&nbsp;</td>
       <td class = "elementCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['short_description']['html']; ?>
</td></tr>
      <tr><td colspan = "2">&nbsp;</td></tr>
     <?php endif; ?>
     <tr><td class = "labelCell"><?php echo @_CURRENTAVATAR; ?>
:&nbsp;</td>
      <td class = "elementCell"><img src = "view_file.php?file=<?php echo $this->_tpl_vars['T_AVATAR']; ?>
" title="<?php echo @_CURRENTAVATAR; ?>
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
     
     <tr><td colspan = "2">&nbsp;</td></tr>
     <tr><td></td>
      <td class = "elementCell"><?php echo $this->_tpl_vars['T_AVATAR_FORM']['submit_upload_file']['html']; ?>
</td></tr>
    <?php endif; ?>
    </table>
   </form>
   </fieldset>
  <?php endif; ?>
 <?php $this->_smarty_vars['capture']['t_personal_data_code'] = ob_get_contents(); ob_end_clean(); ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['T_OP'] == 'account'): ?>
  <?php if (isset ( $this->_tpl_vars['T_ADDITIONAL_ACCOUNTS'] ) && $this->_tpl_vars['T_CONFIGURATION']['mapped_accounts'] == 0 || ( $this->_tpl_vars['T_CONFIGURATION']['mapped_accounts'] == 1 && $this->_tpl_vars['T_CURRENT_USER']->user['user_type'] != 'student' ) || ( $this->_tpl_vars['T_CONFIGURATION']['mapped_accounts'] == 2 && $this->_tpl_vars['T_CURRENT_USER']->user['user_type'] == 'administrator' )): ?>
 <?php ob_start(); ?>
  <div class = "headerTools">
   <span>
    <img src = "images/16x16/add.png" alt = "<?php echo @_ADDACCOUNT; ?>
" title = "<?php echo @_ADDACCOUNT; ?>
">
    <a href = "javascript:void(0)" onclick = "$('add_account').show();"><?php echo @_ADDACCOUNT; ?>
</a>
   </span>
  </div>
  <div id = "add_account" style = "display:none">
   <?php echo @_LOGIN; ?>
: <input type = "text" name = "account_login" id = "account_login">
   <?php echo @_PASSWORD; ?>
: <input type = "password" name = "account_password" id = "account_password">
   <img class = "ajaxHandle" src = "images/16x16/success.png" alt = "<?php echo @_ADD; ?>
" title = "<?php echo @_ADD; ?>
" onclick = "addAccount(this)">
   <img class = "ajaxHandle" src = "images/16x16/error_delete.png" alt = "<?php echo @_CANCEL; ?>
" title = "<?php echo @_CANCEL; ?>
" onclick = "$('add_account').hide();">
  </div>
  <br/>
  <fieldset class = "fieldsetSeparator">
   <legend><?php echo @_ADDITIONALACCOUNTS; ?>
</legend>
   <table id = "additional_accounts">
   <?php $_from = $this->_tpl_vars['T_ADDITIONAL_ACCOUNTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['additional_accounts_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['additional_accounts_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['additional_accounts_list']['iteration']++;
?>
    <tr><td>#filter:login-<?php echo $this->_tpl_vars['item']; ?>
#&nbsp;</td>
     <td><img class = "ajaxHandle" src = "images/16x16/error_delete.png" alt = "<?php echo @_DELETEACCOUNT; ?>
" title = "<?php echo @_DELETEACCOUNT; ?>
" onclick = "deleteAccount(this, '<?php echo $this->_tpl_vars['item']; ?>
')"></td>
   <?php endforeach; else: ?>
   <tr id = "empty_accounts"><td class = "emptyCategory"><?php echo @_YOUHAVENTSETADDITIONALACCOUNTS; ?>
</td></tr>
   <?php endif; unset($_from); ?>
   </table>
  </fieldset>
  <?php if ($this->_tpl_vars['T_FACEBOOK_ENABLED']): ?>
  <fieldset class = "fieldsetSeparator" id = "facebook_accounts">
   <legend><?php echo @_FACEBOOKMAPPEDACCOUNT; ?>
</legend>
   <?php if ($this->_tpl_vars['T_FB_ACCOUNT']): ?>
   <div><?php echo $this->_tpl_vars['T_FB_ACCOUNT']['fb_name']; ?>
 <img style = "vertical-align:middle" src = "images/16x16/error_delete.png" alt = "<?php echo @_DELETEACCOUNT; ?>
" title = "<?php echo @_DELETEACCOUNT; ?>
" onclick = "deleteFacebookAccount(this, '<?php echo $this->_tpl_vars['T_FB_ACCOUNT']['users_LOGIN']; ?>
')"></div>
   <?php else: ?>
   <div class = "emptyCategory" id = "empty_fb_accounts"><?php echo @_YOUHAVENTSETFACEBOOKACCOUNT; ?>
</div>
   <?php endif; ?>
  </fieldset>
  <?php endif; ?>
  <script>
  <?php if ($_GET['ctg'] == 'personal'): ?>var additionalAccountsUrl = '<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=personal';<?php else: ?>var additionalAccountsUrl = '<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=users&edit_user=<?php echo $_GET['edit_user']; ?>
';<?php endif; ?>
  </script>
 <?php $this->_smarty_vars['capture']['t_additional_accounts_code'] = ob_get_contents(); ob_end_clean(); ?>
 <?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['T_OP'] == 'status'): ?>
 <?php if (( $_SESSION['s_type'] == 'administrator' ) || $this->_tpl_vars['T_IS_SUPERVISOR']): ?>
  <?php $this->assign('courses_url', ($_SERVER['PHP_SELF'])."?ctg=users&edit_user=".($_GET['edit_user'])."&op=".($_GET['op'])."&lessons=1&"); ?>
  <?php $this->assign('_change_handles_', $this->_tpl_vars['_change_']); ?>
 <?php else: ?>
  <?php $this->assign('courses_url', ($_SERVER['PHP_SELF'])."?ctg=personal&op=".($_GET['op'])."&lessons=1&"); ?>
  <?php $this->assign('_change_handles_', false); ?>
 <?php endif; ?>
 <?php ob_start(); ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/common/courses_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $this->_smarty_vars['capture']['t_courses_list_code'] = ob_get_contents(); ob_end_clean(); ?>
 <?php ob_start(); ?>
 <?php if (! $this->_tpl_vars['T_SORTED_TABLE'] || $this->_tpl_vars['T_SORTED_TABLE'] == 'lessonsTable'): ?>
<!--ajax:lessonsTable-->
  <table id = "lessonsTable" size = "<?php echo $this->_tpl_vars['T_TABLE_SIZE']; ?>
" class = "sortedTable" useAjax = "1" url = "<?php echo $this->_tpl_vars['courses_url']; ?>
">
  <?php echo $this->_smarty_vars['capture']['lessons_list']; ?>

  </table>
<!--/ajax:lessonsTable-->
 <?php endif; ?>
 <?php $this->_smarty_vars['capture']['t_lessons_code'] = ob_get_contents(); ob_end_clean(); ?>
  <?php ob_start(); ?>
<!--ajax:groupsTable-->
  <table style = "width:100%" class = "sortedTable" size = "<?php echo $this->_tpl_vars['T_TABLE_SIZE']; ?>
" sortBy = "0" id = "groupsTable" useAjax = "1" rowsPerPage = "<?php echo @G_DEFAULT_TABLE_SIZE; ?>
" url = "<?php echo $_SERVER['PHP_SELF']; ?>
?<?php if ($this->_tpl_vars['T_CTG'] != 'personal' || $_SESSION['s_type'] == 'administrator'): ?>ctg=users&edit_user=<?php echo $_GET['edit_user']; ?>
<?php else: ?>ctg=personal<?php endif; ?>&op=status&">
   <tr class = "topTitle">
    <td class = "topTitle" name = "name" width="30%"><?php echo @_NAME; ?>
</td>
    <td class = "topTitle" name = "description" width="50%"><?php echo @_DESCRIPTION; ?>
</td>
    <td class = "topTitle centerAlign" name = "partof" width="20%"><?php echo @_CHECK; ?>
</td>
   </tr>
  <?php $_from = $this->_tpl_vars['T_DATA_SOURCE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['users_to_groups_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['users_to_groups_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['group']):
        $this->_foreach['users_to_groups_list']['iteration']++;
?>
   <tr class = "<?php echo smarty_function_cycle(array('values' => "oddRowColor, evenRowColor"), $this);?>
 <?php if (! $this->_tpl_vars['group']['active']): ?>deactivatedTableElement<?php endif; ?>">
    <td>
     <?php if ($this->_tpl_vars['_admin_']): ?>
      <a href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=user_groups&edit_user_group=<?php echo $this->_tpl_vars['group']['id']; ?>
" class = "editLink"><?php echo $this->_tpl_vars['group']['name']; ?>
</a>
     <?php else: ?>
      <?php echo $this->_tpl_vars['group']['name']; ?>

     <?php endif; ?>
    </td>
    <td><?php echo $this->_tpl_vars['group']['description']; ?>
</td>
    <td class = "centerAlign">
    <?php if (( $_GET['ctg'] == 'personal' && $_SESSION['s_type'] != 'administrator' ) || ( isset ( $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] ) && $this->_tpl_vars['T_CURRENT_USER']->coreAccess['users'] != 'change' )): ?>
     <?php if ($this->_tpl_vars['group']['partof'] == 1): ?>
      <img src = "images/16x16/success.png" alt = "<?php echo @_PARTOFTHISGROUP; ?>
" title = "<?php echo @_PARTOFTHISGROUP; ?>
" />
     <?php endif; ?>
    <?php else: ?>
     <input class = "inputCheckBox" type = "checkbox" id = "group_<?php echo $this->_tpl_vars['group']['id']; ?>
" name = "<?php echo $this->_tpl_vars['group']['id']; ?>
" onclick ="ajaxUserPost('group', '<?php echo $this->_tpl_vars['group']['id']; ?>
', this);" <?php if ($this->_tpl_vars['group']['partof'] == 1): ?>checked<?php endif; ?>>
    <?php endif; ?>
    </td>
   </tr>
  <?php endforeach; else: ?>
   <tr class = "defaultRowHeight oddRowColor"><td class = "emptyCategory" colspan = "3"><?php echo @_NODATAFOUND; ?>
</td></tr>
  <?php endif; unset($_from); ?>
  </table>
<!--/ajax:groupsTable-->
 <?php $this->_smarty_vars['capture']['t_users_to_groups_code'] = ob_get_contents(); ob_end_clean(); ?>
 <?php endif; ?>
<?php if ($this->_tpl_vars['T_OP'] == 'dashboard'): ?>
 <?php if ($this->_tpl_vars['T_SOCIAL_INTERFACE']): ?>
  <?php ob_start(); ?>
   <table class = "horizontalBlock">
    <tr><td>
   <?php if ($_SESSION['s_type'] != 'administrator'): ?>
      <span class = "rightOption smallHeader">
       <img class = "ajaxHandle" src = "images/32x32/catalog.png" title = "<?php echo @_MYCOURSES; ?>
" alt = "<?php echo @_MYCOURSES; ?>
">
       <a class = "titleLink" href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=lessons" title = "<?php echo @_MYCOURSES; ?>
"><?php echo @_MYCOURSES; ?>
</a>
      </span>
   <?php else: ?>
      <span class = "rightOption smallHeader">
       <img class = "ajaxHandle" src = "images/32x32/home.png" title = "<?php echo @_HOME; ?>
" alt = "<?php echo @_HOME; ?>
">
       <a class = "titleLink" href = "<?php echo $_SERVER['PHP_SELF']; ?>
?ctg=control_panel" title = "<?php echo @_HOME; ?>
"><?php echo @_HOME; ?>
</a>
      </span>
   <?php endif; ?>
      <span class = "leftOption"><?php echo $this->_tpl_vars['T_SIMPLEUSERNAME']; ?>
&nbsp;</span>
     </td>
    </tr>
   </table>
  <?php $this->_smarty_vars['capture']['t_status_change_interface'] = ob_get_contents(); ob_end_clean(); ?>
 <?php endif; ?>
<?php endif; ?>
<?php if (( isset ( $_GET['add_evaluation'] ) || isset ( $_GET['edit_evaluation'] ) )): ?>
<?php ob_start(); ?>
   <?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['javascript']; ?>

   <table width = "75%">
    <tr>
     <td width="70%">
       <form <?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['attributes']; ?>
>
       <?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['hidden']; ?>

        <table class = "formElements">
        <tr><td></td>
        <td><span>
         <img style="vertical-align:middle" src = "images/16x16/order.png" title = "<?php echo @_TOGGLEHTMLEDITORMODE; ?>
" alt = "<?php echo @_TOGGLEHTMLEDITORMODE; ?>
" />&nbsp;
         <a href = "javascript:toggleEditor('specification','simpleEditor');" id = "toggleeditor_link"><?php echo @_TOGGLEHTMLEDITORMODE; ?>
</a>
        </span></td></tr>
         <tr>
          <td class = "labelCell"><?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['specification']['label']; ?>
:&nbsp;</td>
          <td style="white-space:nowrap;"><?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['specification']['html']; ?>
</td>
         </tr>
         <?php if ($this->_tpl_vars['T_EVALUATIONS_FORM']['specification']['error']): ?><tr><td></td><td class = "formError"><?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['specification']['error']; ?>
</td></tr><?php endif; ?>
         <tr><td colspan = "2">&nbsp;</td></tr>
         <tr><td></td><td class = "submitCell" style = "text-align:left">
          <?php echo $this->_tpl_vars['T_EVALUATIONS_FORM']['submit_evaluation_details']['html']; ?>
</td>
         </tr>
      </table>
     </form>
    </td>
   </tr>
  </table>
  <?php if ($this->_tpl_vars['T_MESSAGE_TYPE'] == 'success'): ?>
     <script>parent.location = parent.location.toString()+'&tab=evaluations';</script>
  <?php endif; ?>
<?php $this->_smarty_vars['capture']['t_evaluations_code'] = ob_get_contents(); ob_end_clean(); ?>
<?php endif; ?>
<?php ob_start(); ?>
  <?php if (isset ( $_GET['add_user'] )): ?>
  <?php echo $this->_smarty_vars['capture']['t_personal_data_code']; ?>

  <?php elseif ($this->_tpl_vars['T_PERSONAL_CTG']): ?>
    <?php if (! $this->_tpl_vars['T_OP'] || $this->_tpl_vars['T_OP'] == 'dashboard'): ?>
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "social.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php elseif ($this->_tpl_vars['T_OP'] == 'account'): ?>
   <div class="tabber">
    <div class="tabbertab" title="<?php echo $this->_tpl_vars['T_TITLES']['account']['edituser']; ?>
">
     <?php echo smarty_function_eF_template_printBlock(array('title' => $this->_tpl_vars['T_TITLES']['account']['edituser'],'data' => $this->_smarty_vars['capture']['t_personal_data_code'],'image' => '32x32/profile.png'), $this);?>

    </div>
    <?php if (isset ( $this->_tpl_vars['T_ADDITIONAL_ACCOUNTS'] ) && $this->_tpl_vars['T_CONFIGURATION']['mapped_accounts'] == 0 || ( $this->_tpl_vars['T_CONFIGURATION']['mapped_accounts'] == 1 && $this->_tpl_vars['T_CURRENT_USER']->user['user_type'] != 'student' ) || ( $this->_tpl_vars['T_CONFIGURATION']['mapped_accounts'] == 2 && $this->_tpl_vars['_admin_'] )): ?>
    <div class="tabbertab<?php if (( $_GET['tab'] == 'mapped_accounts' )): ?> tabbertabdefault <?php endif; ?>" title = "<?php echo $this->_tpl_vars['T_TITLES']['account']['mapped']; ?>
">
     <?php echo smarty_function_eF_template_printBlock(array('title' => $this->_tpl_vars['T_TITLES']['account']['mapped'],'data' => $this->_smarty_vars['capture']['t_additional_accounts_code'],'image' => '32x32/users.png'), $this);?>

    </div>
    <?php endif; ?>
   </div>
    <?php elseif ($this->_tpl_vars['T_OP'] == 'status'): ?>
   <div class="tabber">
   <?php if (! $this->_tpl_vars['_admin_']): ?>
    <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'courses','title' => $this->_tpl_vars['T_TITLES']['status']['courses'],'data' => $this->_smarty_vars['capture']['t_courses_list_code'],'image' => '32x32/courses.png'), $this);?>

    <?php if ($this->_tpl_vars['T_CONFIGURATION']['lesson_enroll']): ?>
     <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'lessons','title' => $this->_tpl_vars['T_TITLES']['status']['lessons'],'data' => $this->_smarty_vars['capture']['t_lessons_code'],'image' => '32x32/lessons.png'), $this);?>

    <?php endif; ?>
   <?php endif; ?>
   <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'groups','title' => $this->_tpl_vars['T_TITLES']['status']['groups'],'data' => $this->_smarty_vars['capture']['t_users_to_groups_code'],'image' => '32x32/users.png'), $this);?>

    <?php if (( $this->_tpl_vars['T_SHOW_USER_FORM'] )): ?>
    <div class="tabbertab <?php if ($_GET['tab'] == 'user_form'): ?>tabbertabdefault<?php endif; ?>" title="<?php echo @_MYEMPLOYEEFORM; ?>
">
     <?php echo smarty_function_eF_template_printBlock(array('alt' => $this->_tpl_vars['T_USERNAME'],'title' => @_USERFORM,'titleStyle' => 'font-size:16px;font-weight:bold;','data' => $this->_smarty_vars['capture']['t_personal_form_data_code'],'image' => $this->_tpl_vars['T_SYSTEMLOGO'],'options' => $this->_tpl_vars['T_EMPLOYEE_FORM_OPTIONS']), $this);?>

    </div>
    <?php endif; ?>
   </div>
  <?php endif; ?>
  <?php else: ?>
    <?php if ($this->_tpl_vars['T_OP'] == 'account'): ?>
  <div class="tabber">
   <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'personal','title' => $this->_tpl_vars['T_TITLES']['account']['edituser'],'data' => $this->_smarty_vars['capture']['t_personal_data_code'],'image' => '32x32/profile.png'), $this);?>

  </div>
    <?php elseif ($this->_tpl_vars['T_OP'] == 'status'): ?>
  <div class="tabber">
   <?php if ($this->_tpl_vars['T_EDITEDUSER']->user['user_type'] != 'administrator'): ?>
    <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'courses','title' => $this->_tpl_vars['T_TITLES']['status']['courses'],'data' => $this->_smarty_vars['capture']['t_courses_list_code'],'image' => '32x32/courses.png'), $this);?>

    <?php if ($this->_tpl_vars['T_CONFIGURATION']['lesson_enroll']): ?>
     <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'lessons','title' => $this->_tpl_vars['T_TITLES']['status']['lessons'],'data' => $this->_smarty_vars['capture']['t_lessons_code'],'image' => '32x32/lessons.png'), $this);?>

    <?php endif; ?>
   <?php endif; ?>
   <?php echo smarty_function_eF_template_printBlock(array('tabber' => 'groups','title' => $this->_tpl_vars['T_TITLES']['status']['groups'],'data' => $this->_smarty_vars['capture']['t_users_to_groups_code'],'image' => '32x32/users.png'), $this);?>

  </div>
  <?php endif; ?>
 <?php endif; ?>
<?php $this->_smarty_vars['capture']['t_user_code'] = ob_get_contents(); ob_end_clean(); ?>
<?php if (( isset ( $_GET['add_evaluation'] ) || isset ( $_GET['edit_evaluation'] ) )): ?>
 <?php echo smarty_function_eF_template_printBlock(array('title' => ((is_array($_tmp=((is_array($_tmp=@_EVALUATIONOFEMPLOYEE)) ? $this->_run_mod_handler('cat', true, $_tmp, '&nbsp;') : smarty_modifier_cat($_tmp, '&nbsp;')))) ? $this->_run_mod_handler('cat', true, $_tmp, $_GET['edit_user']) : smarty_modifier_cat($_tmp, $_GET['edit_user'])),'data' => $this->_smarty_vars['capture']['t_evaluations_code'],'image' => '32x32/catalog.png'), $this);?>

<?php elseif ($_GET['show_avatars_list']): ?>
 <table width = "100%" cellpadding = "5" class = "filemanagerBlock">
  <tr><?php $_from = $this->_tpl_vars['T_SYSTEM_AVATARS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['avatars_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['avatars_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['avatars_list']['iteration']++;
?>
    <td align = "center"><a href = "javascript:void(0)" onclick = "parent.document.getElementById('select_avatar').selectedIndex = <?php echo ($this->_foreach['avatars_list']['iteration']-1); ?>
<?php if ($this->_tpl_vars['T_SOCIAL_INTERFACE']): ?>+1<?php endif; ?>;parent.document.getElementById('popup_close').onclick();window.close();"><img src = "<?php echo @G_SYSTEMAVATARSURL; ?>
<?php echo $this->_tpl_vars['item']; ?>
" border = "0" / ><br/><?php echo $this->_tpl_vars['item']; ?>
</a></td>
    <?php if ($this->_foreach['avatars_list']['iteration'] % 4 == 0): ?></tr><tr><?php endif; ?>
   <?php endforeach; endif; unset($_from); ?>
  </tr>
 </table>
<?php elseif ($_GET['printable']): ?>
 <?php echo smarty_function_eF_template_printBlock(array('alt' => $this->_tpl_vars['T_USERNAME'],'title' => $this->_tpl_vars['T_EMPLOYEE_FORM_CAPTION'],'titleStyle' => 'font-size:16px;font-weight:bold;','data' => $this->_smarty_vars['capture']['t_personal_form_data_code'],'image' => $this->_tpl_vars['T_SYSTEMLOGO'],'options' => $this->_tpl_vars['T_EMPLOYEE_FORM_OPTIONS']), $this);?>

<?php else: ?>
 <?php if (isset ( $_GET['add_user'] )): ?>
  <?php echo smarty_function_eF_template_printBlock(array('title' => @_NEWUSER,'data' => $this->_smarty_vars['capture']['t_user_code'],'image' => '32x32/user.png'), $this);?>

 <?php elseif ($this->_tpl_vars['T_PERSONAL_CTG']): ?>
      <?php if ($this->_tpl_vars['T_SOCIAL_INTERFACE']): ?>
   <?php echo $this->_smarty_vars['capture']['t_status_change_interface']; ?>

   <?php endif; ?>
  <?php echo smarty_function_eF_template_printBlock(array('title' => @_PERSONALDATA,'data' => $this->_smarty_vars['capture']['t_user_code'],'image' => '32x32/profile.png','main_options' => $this->_tpl_vars['T_TABLE_OPTIONS'],'help' => 'Dashboard'), $this);?>

 <?php else: ?>
  <?php if ($_GET['print_preview'] == 1): ?>
   <?php echo smarty_function_eF_template_printBlock(array('alt' => $this->_tpl_vars['T_USERNAME'],'title' => $this->_tpl_vars['T_EMPLOYEE_FORM_CAPTION'],'titleStyle' => 'font-size:16px;font-weight:bold;','data' => $this->_smarty_vars['capture']['t_personal_form_data_code'],'image' => $this->_tpl_vars['T_SYSTEMLOGO'],'options' => $this->_tpl_vars['T_EMPLOYEE_FORM_OPTIONS']), $this);?>

  <?php elseif ($_GET['print'] == 1): ?>
   <?php echo smarty_function_eF_template_printBlock(array('alt' => $this->_tpl_vars['T_USERNAME'],'title' => $this->_tpl_vars['T_EMPLOYEE_FORM_CAPTION'],'titleStyle' => 'font-size:16px;font-weight:bold;','data' => $this->_smarty_vars['capture']['t_personal_form_data_code'],'image' => $this->_tpl_vars['T_SYSTEMLOGO'],'options' => $this->_tpl_vars['T_EMPLOYEE_FORM_OPTIONS']), $this);?>

  <?php else: ?>
   <?php echo smarty_function_eF_template_printBlock(array('title' => (@_USEROPTIONSFOR)."<span class = 'innerTableName'>&nbsp;&quot;#filter:login-".($this->_tpl_vars['T_EDITEDUSER']->user['login'])."#&quot;</span>",'data' => $this->_smarty_vars['capture']['t_user_code'],'image' => '32x32/profile.png','main_options' => $this->_tpl_vars['T_TABLE_OPTIONS'],'options' => $this->_tpl_vars['T_STATISTICS_LINK']), $this);?>

  <?php endif; ?>
 <?php endif; ?>
<?php endif; ?>
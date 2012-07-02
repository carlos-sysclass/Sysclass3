<div class="blockContents">
 	{$T_MODULE_XUSER_EXTENDED_FORM.javascript}
	<form {$T_MODULE_XUSER_EXTENDED_FORM.attributes}>
		{$T_MODULE_XUSER_EXTENDED_FORM.hidden}
		<div class="grid_24">

<!-- <form action="administrator.php?ctg=users&edit_user=admin&tab=my_profile&op=account" method="post" name="set_avatar_form" id="set_avatar_form" enctype="multipart/form-data">  -->
   <fieldset class = "fieldsetSeparator">
   <legend>{$T_TITLES.account.profile}</legend>
   {$T_AVATAR_FORM.javascript}
   <form {$T_AVATAR_FORM.attributes}>
    {$T_AVATAR_FORM.hidden}
    <table class = "formElements">
     {if isset($T_SOCIAL_INTERFACE)}
      {if ($smarty.get.personal) || ($smarty.get.edit_user == $smarty.session.s_login)}
       {*@TODO: FILE UPLOAD MISSING HERE*}
      {/if}
      <tr><td></td>
       <td><span>
        <img style="vertical-align:middle" src = "images/16x16/order.png" title = "{$smarty.const._TOGGLEHTMLEDITORMODE}" alt = "{$smarty.const._TOGGLEHTMLEDITORMODE}" />&nbsp;
        <a href = "javascript:toggleEditor('short_description','simpleEditor');" id = "toggleeditor_link">{$smarty.const._TOGGLEHTMLEDITORMODE}</a>
       </span></td></tr>
      <tr><td class = "labelCell">{$T_AVATAR_FORM.short_description.label}:&nbsp;</td>
       <td class = "elementCell">{$T_AVATAR_FORM.short_description.html}</td></tr>
      <tr><td colspan = "2">&nbsp;</td></tr>
     {/if}
     <tr><td class = "labelCell">{$smarty.const._CURRENTAVATAR}:&nbsp;</td>
      <td class = "elementCell"><img src = "view_file.php?file={$T_AVATAR}" title="{$smarty.const._CURRENTAVATAR}" alt="{$smarty.const._CURRENTAVATAR}" {if isset($T_NEWWIDTH)} width = "{$T_NEWWIDTH}" height = "{$T_NEWHEIGHT}"{/if} /></td></tr>
    {if !isset($T_CURRENT_USER->coreAccess.users) || $T_CURRENT_USER->coreAccess.users == 'change'}
     <tr><td class = "labelCell">{$T_AVATAR_FORM.delete_avatar.label}:&nbsp;</td>
      <td class = "elementCell">{$T_AVATAR_FORM.delete_avatar.html}</td></tr>
     <tr><td class = "labelCell">{$T_AVATAR_FORM.file_upload.label}:&nbsp;</td>
      <td class = "elementCell">{$T_AVATAR_FORM.file_upload.html}</td></tr>
     <tr><td class = "labelCell">{$T_AVATAR_FORM.system_avatar.label}:&nbsp;</td>
      <td class = "elementCell">{$T_AVATAR_FORM.system_avatar.html}&nbsp;(<a href = "{$smarty.server.PHP_SELF}?{if $smarty.get.ctg=='personal'}ctg=personal{elseif $smarty.get.edit_user}ctg=users&edit_user={$smarty.get.edit_user}{/if}&show_avatars_list=1&popup=1" target = "POPUP_FRAME" onclick = "eF_js_showDivPopup('{$smarty.const._VIEWLIST}', 2)">{$smarty.const._VIEWLIST}</a>)</td></tr>
     <tr><td colspan = "2">&nbsp;</td></tr>
     <tr><td></td>
      <td class = "elementCell">{$T_AVATAR_FORM.submit_upload_file.html}</td></tr>
    {/if}
    </table>
   
   </fieldset>
			
		</div>
	</form>
</div>
<table class = "forumMessageTable style1" style = "width:960px">
    {section name = 'messages_list' loop = $T_POSTS}
    {assign var = "message_user" value = $T_POSTS[messages_list].users_LOGIN}
   <tr class = "{cycle values = "oddRowColorNoHover, evenRowColorNoHover"}">
   		<td class = "forumMessageCreator">
           <div>
           	<img src = "view_file.php?file={$T_POSTS[messages_list].avatar}" title="{$smarty.const._CURRENTAVATAR}" alt="{$smarty.const._CURRENTAVATAR}" width = "{$T_POSTS[messages_list].avatar_width}" height = "{$T_POSTS[messages_list].avatar_height}" /></div>
            <div>#filter:user_loginNoIcon-{$T_POSTS[messages_list].users_LOGIN}#</div>
       		{assign var = "current_userrole" value = $T_POSTS[messages_list].user_type}
            <div>{$smarty.const._POSITION}: {$T_USERROLES.$current_userrole}</div>
            <div>{$smarty.const._JOINED}: #filter:timestamp-{$T_POSTS[messages_list].timestamp}#</div>
            <div>{$smarty.const._POSTS}: {$T_USER_POSTS.$message_user}</div>
       	</td>
  	    <td>
         <div class = "blockHeader">{$T_POSTS[messages_list].title}</div>
         
         <div class = "forumMessageInfo">{$smarty.const._POSTEDBY}<span> #filter:user_loginNoIcon-{$T_POSTS[messages_list].users_LOGIN}# </span>{$smarty.const._ON} #filter:timestamp_time-{$T_POSTS[messages_list].timestamp}# {if $T_POSTS[messages_list].replyto}{$smarty.const._INREPLYTO}: <a href = "{$smarty.server.PHP_SELF}?ctg=forum&topic={$smarty.get.topic}&view_message={$T_POSTS[messages_list].replyto}#{$T_POSTS[messages_list].replyto}">{$T_POSTS[messages_list].reply_title}</a>{/if}</div>
        </td>
  </tr>
   {/section}
</table>

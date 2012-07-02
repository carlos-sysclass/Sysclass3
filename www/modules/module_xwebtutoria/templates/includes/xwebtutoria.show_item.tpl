{capture name="xwebtutoria_item"}
<div class="foto grid_4 alpha">
	<img src = "view_file.php?file={$T_TUTORIA.avatar}" title="{$smarty.const._CURRENTAVATAR}" alt="{$smarty.const._CURRENTAVATAR}" width = "{$T_TUTORIA.avatar_width}" height = "{$T_TUTORIA.avatar_height}" />
	{if $T_TUTORIA.user_id != $T_CURRENT_USER->user.id}
		<div>{$T_TUTORIA.username}</div>
	{else}
		<div>{$smarty.const.__YOURSELF}</div>
	{/if}
</div>
<div class="pergunta grid_20 omega">
	<div class="texto">
		<p>
		{$T_TUTORIA.body}
		</p>
	</div>
	<div class="blockFooter">
		<span class="to-left">
			<img 
				src = "images/others/transparent.gif"
				class="sprite16 sprite16-calendar3"
				border = "0"/>
			
			<time datetime="#filter:isodatetime-{$T_TUTORIA.datetime}#" pubdate="">#filter:ext-date-{$T_TUTORIA.datetime}#</time>
			
		</span>
		<span class="to-right">
			{if $T_TUTORIA.user_id != $T_CURRENT_USER->user.id}
				<img 
					src = "images/others/transparent.gif"
					class="sprite16 sprite16-undo"
					border = "0"/>
				<a href="{$T_XWEBTUTORIA_BASEURL}&action=add_xwebtutoria&xwebtutoria_id={$T_TUTORIA.id}">responder</a>
			{/if}
			{if $T_TUTORIA.total_childs > 0}
				<img 
					src = "images/others/transparent.gif"
					class="sprite16 sprite16-refresh"
					border = "0"/>
				<a onclick="xWebtutoriaAPI.openConversation({$T_TUTORIA.id});" href="javascript: void(0);" class="xwebtutoria-open-conversation">conversa</a>
			{/if}
		</span>
	</div>
</div>
<div class="clear"></div>
{/capture}

{eF_template_printBlock
	id 			= "dasdas"
	data			= $smarty.capture.xwebtutoria_item
	contentclass	= "xwebtutoria-list"
	class			= ""
	options			= ""
	absoluteImagePath	= true
}
<div id="xwebtutoria-child-item-{$T_TUTORIA.id}" class="xwebtutoria-child-list">
	{if $T_TUTORIA.child > 0}
		{include 
			file="$T_XWEBTUTORIA_BASEDIR/templates/actions/load_xwebtutoria_conversation.tpl"
			T_XWEBTUTORIA_LIST= $tutoria.child
		}
	{/if}
</div>
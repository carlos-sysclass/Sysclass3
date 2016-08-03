{if isset($T_ITEM.on_header) && $T_ITEM.on_header}
	{assign var="hasSubMenu" value=false}
	{assign var="hasIcon" value=false}
	{assign var="iconClass" value=""}
	{assign var="subMenuClass" value=""}
	{assign var="selectedClass" value=""}

	{if isset($T_ITEM.sub) && $T_ITEM.sub|@count > 0}
		{foreach item="subitem" from=$T_ITEM.sub}
			{if isset($subitem.on_header) && $subitem.on_header}
				{assign var="hasSubMenu" value=true}	
				{assign var="subMenuClass" value="hasSubmenu"}
				{break}
			{/if}
		{/foreach}
	{/if}

	{if isset($T_ITEM.icon)}
		{assign var="hasIcon" value=true}
		{assign var="iconClass" value="glyphicons `$T_ITEM.icon`"}
	{/if}

	{if isset($T_ITEM.selected) && $T_ITEM.selected}
		{assign var="selectedClass" value="active"}
	{/if}

	<li class="{$subMenuClass} {$iconClass} {$selectedClass}">
		<a {if $hasSubMenu}data-toggle="collapse" href="#{$T_ITEM.url}"{/if} 
		href="{if isset($T_ITEM.absolute) && $T_ITEM.absolute}{$T_ITEM.url}{else}{$T_CONTEXT['basePath']}{$T_ITEM.url}{/if}">
			{if $hasIcon}<i></i>{/if}
			<span>{$T_ITEM.text}</span> 
		</a>
		{if $hasSubMenu}
			<ul class="collapse" id="{$T_ITEM.url}">
				{foreach item="item" from=$T_ITEM.sub}
					{include file="block/menu-item-auth.tpl" T_ITEM=$item}
				{/foreach}
			</ul> 
			<span class="count">{$T_ITEM.sub|@count}</span>
		{/if}
	</li>
{/if}
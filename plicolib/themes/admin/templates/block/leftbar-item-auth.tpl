
	{assign var="hasSubMenu" value=false}
	{assign var="hasIcon" value=false}
	{assign var="iconClass" value=""}
	{assign var="subMenuClass" value=""}
	{assign var="selectedClass" value=""}

	{if isset($T_ITEM.items) && $T_ITEM.items|@count > 0}
		{foreach item="subitem" from=$T_ITEM.items}
			{assign var="hasSubMenu" value=true}	
			{assign var="subMenuClass" value="hasSubmenu"}
			{break}
		{/foreach}
	{/if}

	{if isset($T_ITEM.icon)}
		{assign var="hasIcon" value=true}
		{assign var="iconClass" value=$T_ITEM.icon}
	{/if}

	{if isset($T_ITEM.selected) && $T_ITEM.selected}
		{assign var="selectedClass" value="active"}
	{/if}

	<li class="{$subMenuClass} {$iconClass} {$selectedClass}">
		<a {if $hasSubMenu}data-toggle="collapse" href="#{$T_ITEM.link}"{/if} href="{$T_ITEM.link}">
			{if $hasIcon}<i></i>{/if}
			<span>{$T_ITEM.text}</span> 
		</a>
		{if $hasSubMenu}
			<ul class="collapse" id="{$T_ITEM.link}">
				{foreach item="item" from=$T_ITEM.items}
					{include file="block/leftbar-item-auth.tpl" T_ITEM=$item}
				{/foreach}
			</ul> 
			<span class="count">{$T_ITEM.sub|@count}</span>
		{/if}
	</li>

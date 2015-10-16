{assign var="groups" value=$T_DATA.data.groups}
{assign var="links" value=$T_DATA.data.links}

{foreach $links as $group_id => $grouplinks}
	<h4 class="block border-bottom"><i class="icon-th"></i> {$groups[$group_id]}</h4>
	<div class="">
	{foreach $grouplinks as $link_id => $link}
		<a class="icon-btn" href="{$link.link}">
			<i class="{if isset($link.icon)}{$link.icon}{else}icon-info-sign{/if}"></i>
			<div>{$link.text}</div>
			{if isset($link.count)}
				<span class="badge badge-important">{$link.count}</span>
			{/if}
		</a>
	{/foreach}
	</div>
{/foreach}
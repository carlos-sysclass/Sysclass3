<ul class="breadcrumb">
	<li>Você está aqui:&nbsp;</li>
	{foreach name="bread_it" item="bread" from=$T_BREADCRUMBS}
	
		<li><a href="{$bread['href']}" {if $bread['icon']}class="glyphicons {$bread['icon']}"{/if}><i></i>{$bread['text']}</a></li>
		{if !$smarty.foreach.bread_it.last}
			<li class="divider"></li>		
		{/if}
	{/foreach}
</ul>

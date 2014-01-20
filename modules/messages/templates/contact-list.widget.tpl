{if $T_DATA.data|@count > 0}
<div class="list-group message-recipient-group">
  {foreach $T_DATA.data as $item}
  <a class="list-group-item message-recipient-item" href="{$item.link}" data-target="#message-contact-dialog">
    {if isset($item.icon)}
    <span class="text-{$item.color}"><i class="icon-{$item.icon}"></i></span>
    {/if}
    {$item.text}
  </a>
  {/foreach}
</div>
{/if}
<!--
{if $T_DATA.data|@count > 0}
	<ul class="message-recipient-group ver-inline-menu ver-inline-notabbable">
		<li class="active">
			<a>
				<i class="icon-{$T_DATA.icon}"></i>
				{$T_DATA.header}
			</a>
		</li>
		{foreach $T_DATA.data as $item}
		<li>
			<a href="{$item.link}" class="message-recipient-item" data-target="#message-contact-dialog">
				{if isset($item.icon)}
					<span class="text-{$item.color}"><i class="icon-{$item.icon}"></i></span>
				{/if}
				{$item.text}
			</a>
		</li>
		{/foreach}
	</ul>
{/if}
-->
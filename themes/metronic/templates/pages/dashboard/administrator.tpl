{extends file="layout/default.tpl"}
{block name="sidebar"}{/block}
{block name="content"}
	{if $T_WIDGETS|@count > 0}
		{foreach $T_WIDGETS as widget}
			{if $widget@first}
				<div class="row-fluid">
			{/if}
			{if $widget.type == 'separator'}
				</div>
				<div class="separator bottom"></div>
				{if !$it.last}
				<div class="row-fluid">
				{/if}
			{/if}
			<div class="span{$widget.weight}">
				{if isset($widget.template)}
					{include file="`$widget.template`.tpl" T_DATA=$widget.data}
				{else}
					{include file="widgets/`$widget.type`.tpl" T_DATA=$widget.data}
				{/if}
			</div>
			{if $it.last}
				</div>
				<div class="separator bottom"></div>
			{/if}
		{/foreach}
	{/if}
{/block}
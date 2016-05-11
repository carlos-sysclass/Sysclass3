{extends file="layout/default-auth.tpl"}


{block name="content"}
<div class="innerLR">
	{if $T_WIDGETS|@count > 0}
		{foreach $T_WIDGETS as $widget}
		<div class="row-fluid">
			<div class="span12">
				{if isset($widget.data.template)}
					{include file="`$widget.data.template`.tpl" T_DATA=$widget.data}
				{else if isset($widget.template)}
					{include file="`$widget.template`.tpl" T_DATA=$widget.data}
				{else if isset($widget.data.type)}
					{include file="widgets/`$widget.data.type`.tpl" T_DATA=$widget.data}
				{else}
					{*include file="widgets/widget.tpl" T_DATA=$widget.data*}
				{/if}
			</div>
		</div>
		{/foreach}
	{/if}
</div>
<div class="separator bottom"></div>
{/block}
{if $T_MODULE_PAGAMENTO_OPTIONS}
<div class="grid_24 box">
	<div class="headerTools">
		{foreach name="pagamento_options_iteration" key="option_index" item="option" from=$T_MODULE_PAGAMENTO_OPTIONS}
			<span>
				<a href="{$option.href}" {if $option.selected} class="selected" {/if} title="{$option.hint}">
					<img src="{$option.image}">
					{$option.text}
				</a>
			</span>
		{/foreach}
	</div>
</div>
<div class="clear"></div>
{/if}
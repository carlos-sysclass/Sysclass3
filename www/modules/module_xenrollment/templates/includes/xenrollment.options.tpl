{if $T_XENROLLMENT_OPTIONS}
<div class="grid_16 box border">
	<div class="headerTools">
		{foreach name="xenrollment_iteration" key="option_index" item="option" from=$T_XENROLLMENT_OPTIONS}
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
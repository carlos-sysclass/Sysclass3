<table class="style1">
	{foreach name="instance_opt_it" item="item" key="key" from=$T_XPAY_CIELO_OPT}
		{if ($smarty.foreach.instance_opt_it.iteration % 2 == 1)}
			<tr>
		{/if}
			<td width="5%">
				<input type="radio" value="{$key}" name="instance_option" class="xpay-instance-option" {if $T_XPAY_CIELO_DEFAULT_OPTION == $key}checked="checked"{/if}>
			</td>
			<td>
				{$item}
			</td>
		{if ($smarty.foreach.instance_opt_it.iteration % 2 == 0)}
			</tr>
		{/if}
	{/foreach}
	{if ($T_XPAY_CIELO_OPT|@count % 2 == 1)}
		<td colspan="2"></td>
		</tr>
	{/if}
</table>
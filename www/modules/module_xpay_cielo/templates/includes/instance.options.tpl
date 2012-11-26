<table class="style1">
	{foreach name="instance_opt_it" item="item" key="key" from=$T_XPAY_CIELO_OPT}
		{if ($smarty.foreach.instance_opt_it.iteration % 2 == 1)}
			<tr>
		{/if}
			<td width="5%">
				<input type="radio" checked="checked" value="{$key}" name="instance_option" class="xpay-instance-option">
			</td>
			<td>
				{$item}
			</td>
		{if ($smarty.foreach.instance_opt_it.iteration % 2 == 0)}
			</tr>
		{/if}
	{/foreach}
</table>
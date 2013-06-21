Valores aplicados as faturas
<table class="style1">
	<thead>
		<tr>
			<th>Descrição</th>
			<th>Tipo</th>
			<th>Valor Base</th>
			<th>Valor Aplicado</th>
			<th>Período</th>
			<th>Somente se..</th>
		</tr>
	</thead>
	<tbody>
	{foreach item="rule" from=$T_XPAY_CURRENT_RULES}
		<tr class="{if $rule.active == 0}xpay-rule-disabled{/if}">
			<td>
			{$rule.description}
			</td>
			<td>
				{if $rule.type_id == -1}
					<span class="xpay-rule-discount">Desconto</span>
				{/if}
				{if $rule.type_id == 1}
					<span class="xpay-rule-charge">Acréscimo</span>
				{/if}
			</td>
			<td>
				{if $rule.base_price_applied == 1}
					<span class="xpay-rule-base-price">Inicial</span>
				{else}
					<span class="xpay-rule-base-price">Valor Anterior</span>
				{/if}
			</td>
			<td>
				{if $rule.percentual == 1}
					<span class="xpay-rule-base-price">{$rule.valor*100}%</span>
				{else}
					<span class="xpay-rule-workflow-price">#filter:currency:{$rule.valor}#</span>
				{/if}
			</td>
			<td>
				{if $rule.applied_on == "once"}
					<span class="xpay-rule-base-price">Uma vez</span>
				{/if}
				{if $rule.applied_on == "per_day"}
					<span class="xpay-rule-workflow-price">Diário</span>
				{/if}
				{if $rule.applied_on == "per_month"}
					<span class="xpay-rule-workflow-price">Mensal</span>
				{/if}
			</td>
			<td>
				{foreach name="tag_iterator" item="tag" from=$rule.tags}
					{if $T_XPAY_TAGS_TRANSLATE[$tag]}
						{if !$smarty.foreach.tag_iterator.first},&nbsp;{/if}{$T_XPAY_TAGS_TRANSLATE[$tag]}
					{/if}
				{/foreach}
			</td>
		</tr>
	{/foreach}
	</tbody>
</table>
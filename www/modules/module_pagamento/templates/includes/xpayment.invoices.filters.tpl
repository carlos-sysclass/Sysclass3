{if $T_PAGAMENTO_FILTER_FORM}
<div class="grid_16 box border" style="margin-top: 15px;">
	<div class="flat_area">
		{$T_PAGAMENTO_FILTER_FORM.javascript}
		<form {$T_PAGAMENTO_FILTER_FORM.attributes}>
			{$T_PAGAMENTO_FILTER_FORM.hidden}
			<div class="grid_4">
			{if $T_PAGAMENTO_FILTER_FORM.ies_id}
				<label>{$T_PAGAMENTO_FILTER_FORM.ies_id.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.ies_id.html}
			{/if}
				<label>{$T_PAGAMENTO_FILTER_FORM.parcela_type.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.parcela_type.html}
			</div>
			<div class="grid_4">
				<label>{$T_PAGAMENTO_FILTER_FORM.start_date.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.start_date.html}
				<label>{$T_PAGAMENTO_FILTER_FORM.end_date.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.end_date.html}
			</div>
			<div class="grid_4">
				<label>{$T_PAGAMENTO_FILTER_FORM.courses.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.courses.html}
				<!-- 
				<label>{$T_PAGAMENTO_FILTER_FORM.classes.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.classes.html}
				 -->
			</div>
			
			<div class="grid_4">
				<label>{$T_PAGAMENTO_FILTER_FORM.show_grouped.label}</label>
				{$T_PAGAMENTO_FILTER_FORM.show_grouped.html}
				<div class="clear"></div>
				<button class="button_colour round_all" type="submit" name="{$T_PAGAMENTO_FILTER_FORM.submit_apply.name}" value="{$T_PAGAMENTO_FILTER_FORM.submit_apply.value}">
					<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
					<span>{$T_PAGAMENTO_FILTER_FORM.submit_apply.value}</span>
				</button>
			</div>
		</form>
	</div>
</div>
<div class="clear"></div>
{/if}
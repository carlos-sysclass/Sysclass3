<div id="details-block">
{foreach $T_USER_DETAILS_INFO.fields as $field}
	{if ($field.HTMLType == "hidden")}
		{$field.rendered nofilter}
	{else}
		<div class="col-md-{if is_null($field.options.weight)}12{else}{$field.options.weight}{/if}">
			<div class="form-group">
				<label class="control-label">{$field.label}</label>
				{$field.rendered nofilter}
			</div>
		</div>
	{/if}
{/foreach}
</div>

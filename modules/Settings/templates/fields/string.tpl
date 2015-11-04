<div class="form-group">
    <label class="control-label col-md-3" style="text-align:left">
        {if $T_FIELD.label}
			{translateToken value=$T_FIELD.label}
		{else}
			{$T_FIELD.name}
		{/if}
    </label>
    <div class="col-md-9">
		<input name="{$T_FIELD.name}" value="" type="text" placeholder="{translateToken value=$T_FIELD.label}" class="form-control" data-rule-required="true" />

        <span class="help-text">
            <small>{translateToken value=$T_FIELD.description}</small>
        </span>
    </div>
</div>


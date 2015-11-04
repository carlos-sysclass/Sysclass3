<div class="form-group">
    <label class="control-label col-md-3" style="text-align:left">
        {if $T_FIELD.label}
			{translateToken value=$T_FIELD.label}
		{else}
			{$T_FIELD.name}
		{/if}
    </label>
    <div class="col-md-9">
        <input type="checkbox" name="{$T_FIELD.name}" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="primary" data-on-text="{translateToken value='YES'}" data-off-color="warning" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update="{$T_FIELD.name}" data-update-single="true" >

        <span class="help-text">
            <small>{translateToken value=$T_FIELD.description}</small>
        </span>
    </div>
</div>


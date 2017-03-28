{if $T_CHECK_OLD}
	<div class="form-group col-md-4">
		<label class="control-label">{translateToken value="Current password"}</label>
		<input type="password" name="old-password" class="form-control" autocomplete="off" />
	</div>
{/if}
<div class="form-group col-md-{if $T_CHECK_OLD}4{else}6{/if}">
	<label class="control-label">{translateToken value="New password"}</label>
	<input type="password" name="new-password" class="form-control password_strength" autocomplete="off" />
</div>
<div class="form-group col-md-{if $T_CHECK_OLD}4{else}6{/if}">
	<label class="control-label">{translateToken value="Re-enter new password"}</label>
	<input type="password" name="new-password-confirm" class="form-control" autocomplete="off" />
</div>
<div class="clearfix"></div>

<div class="col-md-12">
    <span class="pwstrength_viewport_progress"></span>
    <span class="pwstrength_viewport_verdict"></span>
</div>
<div class="clearfix"></div>

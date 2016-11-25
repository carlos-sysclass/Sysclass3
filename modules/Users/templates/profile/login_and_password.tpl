{if !$T_CONFIGURATION.enable_email_login}
<div class="form-group col-md-6 checkunique-me" data-check-url="/module/users/check-login">
	<label class="control-label">{translateToken value="Login"}</label>
    <div class="input-icon right">
        <i data-container="body" data-original-title="{translateToken value='Login can be used'}" class="fa fa-check tooltips checkunique-ok"></i>
        <i data-container="body" data-original-title="{translateToken value='Login already exists'}" class="fa fa-exclamation tooltips checkunique-error"></i>
        <input type="text" name="login" class="form-control " autocomplete="off" data-rule-remote="/module/users/check-login" data-msg-remote="{translateToken value='Please pick another username'}" />
    </div>
</div>
{/if}
<div class="form-group {if !$T_CONFIGURATION.enable_email_login}col-md-12{else}col-md-12{/if}">
	<label class="control-label">{translateToken value="New Password"}</label>
	<input type="password" name="new-password" class="form-control password_strength" autocomplete="off" />
    <span class="pwstrength_viewport_progress"></span>
    <span class="pwstrength_viewport_verdict"></span>
</div>
<div class="clearfix"></div>

<div class="col-md-12">

</div>
<div class="clearfix"></div>

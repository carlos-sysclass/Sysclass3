<form role="form" class="" method="post" action="{$T_FORM_ACTIONS.password}">
	<div class="form-group col-md-4">
		<label class="control-label">Current Password</label>
		<input type="password" name="password" class="form-control" />
	</div>
	<div class="form-group col-md-4">
		<label class="control-label">New Password</label>
		<input type="password" name="new-password" class="form-control password_strength" />
	</div>
	<div class="form-group col-md-4">
		<label class="control-label">Re-type New Password</label>
		<input type="password" name="new-password-confirm" class="form-control" />
	</div>
	<div class="clearfix"></div>

    <div class="col-md-12">
        <span class="pwstrength_viewport_progress"></span>
        <span class="pwstrength_viewport_verdict"></span>
    </div>
    <div class="clearfix"></div>
    
	<div class="margin-top-10 col-md-12">
		<button class="btn green" type="submit">Save Changes</button>
	</div>
</form>
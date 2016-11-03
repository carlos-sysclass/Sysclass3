<div id="dialogs-enroll-settings" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="form-horizontal" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-lock"></i>
                        {translateToken value='Enrollment Settings'}
                        <span data-update="name"></span>
                    </h4>
                </div>
                <div class="modal-body ">
					<div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3" style="text-align:left">
                                {translateToken value="Automatic approval"}
                            </label>
                            <div class="col-md-9">
                                <input type="checkbox" name="signup_auto_approval" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="primary" data-on-text="{translateToken value='YES'}" data-off-color="warning" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update="signup_auto_approval" data-update-single="true" >

                                <span class="help-text">
                                    <small>{translateToken value="Users will be automaticaly approved"}</small>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" style="text-align:left">
                                {translateToken value="Public enroll"}
                            </label>
                            <div class="col-md-9">
                                <input type="checkbox" name="signup_enable_new_users" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="primary" data-on-text="{translateToken value='YES'}" data-off-color="warning" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update="signup_enable_new_users" data-update-single="true" >

                                <span class="help-text">
                                    <small>{translateToken value="Users without a sysclass account can enroll in this course."}</small>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" style="text-align:left">
                                {translateToken value="Active"}
                            </label>
                            <div class="col-md-9">
                                <input type="checkbox" name="signup_active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="primary" data-on-text="{translateToken value='YES'}" data-off-color="warning" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update="signup_active" data-update-single="true" >

                                <span class="help-text">
                                    <small>{translateToken value="Enrollment on this program is active"}</small>
                                </span>
                            </div>
                        </div>
					</div>
                    <div class="form-actions nobg">
                        <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
                    </div>
    			</div>
    		</div>
        </form>
	</div>
</div>
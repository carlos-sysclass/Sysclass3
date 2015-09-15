<div class="modal fade" id="dialogs-roles-create" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form id="form-role" role="form" class="form-validate" method="post" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title event-title">{translateToken value="Create Role"}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
						<div class="form-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{translateToken value="Name"}</label>
										<input name="name" value="" type="text" placeholder="{translateToken value="Name"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
					                    <label class="control-label">{translateToken value="Active"}</label>
					                    <input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-value-unchecked="0" data-update-single="true">
					                </div>
								</div>
							</div>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
                    <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
                </div>
            </form>
        </div>
    </div>
</div>

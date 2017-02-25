<div id="dialogs-enroll-users" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-lock"></i>
                    {translateToken value='Manage enrolled users'}
                </h4>
            </div>
            <div class="modal-body ">
            	<form class="form-horizontal" action="#">
					<div class="form-body">
						<div class="form-group">
							
                            <label class="">{translateToken value="Search for Group or User"}</label>
                    		<input type="hidden" class="select2-me form-control col-md-12" name="user_or_group" data-placeholder="{translateToken value='Please, Select'}" data-url="/module/enroll/datasource/users" />

						</div>
					</div>
                    <div class="row margin-top-20">
                        <div class="col-md-12">
                            {include "`$smarty.current_dir`/../blocks/table.tpl" 
                            T_MODULE_CONTEXT=$T_ENROLL_USERS_DIALOG_CONTEXT
                            T_MODULE_ID=$T_ENROLL_USERS_DIALOG_CONTEXT.block_id}
                        </div>
                    </div>
                </form>        
			</div>
		</div>
	</div>
</div>
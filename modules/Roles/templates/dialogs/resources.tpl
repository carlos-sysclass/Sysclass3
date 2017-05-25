<div id="dialogs-roles-resources" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{translateToken value="Close"}">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-lock"></i>
                    {translateToken value='Manage permissions'}
                </h4>
            </div>
            <div class="modal-body">
				<div class="row margin-top-20">
					<div class="col-md-12">
						{include "`$smarty.current_dir`/../blocks/table.tpl" 
						FORCE_INIT=1
						T_MODULE_CONTEXT=$T_ROLES_RESOURCES_DIALOG_CONTEXT
						T_MODULE_ID="roles_resources"}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="permission-add-dialog-modal" class="modal fade" tabindex="-1">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{translateToken value="Add new permission"}</h4>
	</div>
	<div class="modal-body">
		<form role="form" class="form-horizontal" method="post" action="">
			<div class="form-body">
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Permission Type"}</label>
					<div class="controls">
						<input type="hidden" class="select2-me form-control input-block-level" name="condition_id" data-url="/module/permission/combo/items" data-select-search="true" data-placeholder="Pesquisar..." />
					</div>
				</div>
				<div id="permission-add-dialog-options">
					<p class="form-control-static">{translateToken value="Select a permission type above to view its options."}</p>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-success save-permission-action">{translateToken value="Add"}</button>
		<button type="button" class="btn btn-danger" data-dismiss="modal">{translateToken value="Cancel"}</button>
	</div>
</div>
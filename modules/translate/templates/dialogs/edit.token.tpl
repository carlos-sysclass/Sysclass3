<div id="translate-edit-token-modal" class="modal fade" tabindex="-1">
	<form role="form" class="form-horizontal" method="post" action="">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">
				{translateToken value="Edit Token"} "<span  data-update="token"></span>"
				<small>
					<span  data-update="srclang"></span> <span class="icon-arrow-right"><i></i></span> <span  data-update="dstlang"></span>
				</small>
			</h4>
		</div>
		<div class="modal-body">
			<div class="form-body">
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Term"}</label>
					<input type="text" name="text" placeholder="Text" autocomplete="off" class="form-control placeholder-no-fix" data-rule-required="1">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success save-action">{translateToken value="Save"}</button>
			<button type="button" class="btn btn-danger" data-dismiss="modal">{translateToken value="Cancel"}</button>
		</div>
	</form>
</div>
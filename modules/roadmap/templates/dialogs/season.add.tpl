<div id="roadmap-season-dialog-modal" class="modal fade" tabindex="-1">
    <form role="form" class="form-horizontal form-validate" method="post" action="" data-validate="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">{translateToken value="Add a new Season"}</h4>
        </div>
        <div class="modal-body">

            <div class="form-body">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Season name"}</label>
                    <input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
                </div>
                <div class="form-group">
                    <label class="control-label">{translateToken value="Active"}</label>
                    <input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success save-season-action">{translateToken value="Add"}</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">{translateToken value="Cancel"}</button>
        </div>
    </form>
</div>

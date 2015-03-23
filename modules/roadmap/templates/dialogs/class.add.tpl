<div id="roadmap-class-dialog-modal" class="modal fade" tabindex="-1">
    <form role="form" class="form-horizontal form-validate" method="post" action="" data-validate="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">{translateToken value="Add a new Class"}</h4>
        </div>
        <div class="modal-body">
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Instructors"}</label>
                    <input type="hidden" class="select2-me form-control input-block-level" name="class" data-placeholder="{translateToken value='Please select..'}" data-url="/module/courses/items/classes/combo" data-minimum-results-for-search="4" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success save-season-action">{translateToken value="Add"}</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">{translateToken value="Cancel"}</button>
        </div>
    </form>
</div>

<div id="dialogs-storage-library" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-image"></i>
                    {translateToken value='Media Library'}
                </h4>
            </div>
            <div class="modal-body">
                <h5 class="form-section margin-bottom-10 margin-top-10">
                    <i class="fa fa-language"></i>
                    {translateToken value="Files"}

                    <span class="btn btn-sm btn-primary pull-right fileupload-widget fileinput-button" data-fileupload-url="/module/storage/upload">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>{translateToken value="File"}</span>
                        <input type="file" id="file-upload" name="files[]">
                    </span>
                </h5>
                <div id="library_tree"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default select-action">Select</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

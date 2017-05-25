<div id="dialogs-storage-library" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{translateToken value="Close"}">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-image"></i>
                    {translateToken value='Content library'}
                </h4>
            </div>
            <div class="modal-body">
                <h5 class="form-section margin-bottom-10 margin-top-10">
                    <i class="fa fa-folder"></i>
                    {translateToken value="Files"}

                    <a href="javascript: void(0);" class="btn btn-sm btn-danger pull-right deletefile-action" >
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        {translateToken value="Delete"}
                    </a>

                    <span class="btn btn-sm btn-primary pull-right fileupload-widget fileinput-button" data-fileupload-url="/module/storage/upload">
                        <i class="fa fa-file" aria-hidden="true"></i>
                        <span>{translateToken value="New file"}</span>
                        <input type="file" id="file-upload" name="files[]">
                    </span>
                    <a href="javascript: void(0);" class="btn btn-sm btn-info pull-right editable-me addfolder-action"
                        data-type="text"
                        data-name="name"
                        data-send="never"
                        data-original-title="{translateToken value='Add Folder'}"
                        data-inputclass="form-control"
                    >
                        <i class="fa fa-folder" aria-hidden="true"></i>
                        {translateToken value="New folder"}
                    </a>
                </h5>
                <div class=" pull-right file-loader">
                    <div class="file-loader-text">
                        <i class="fa fa-spinner fa-spin loader-spinner"></i>
                        <span class="load-total"></span> /  <span class="file-total"></span>

                        <!-- (<span class="load-bitrate"></span>/s) -->
                        <!-- <span class="load-percent">0</span>% -->
                    </div>
                    <div class="progress progress-mini no-margin file-loader-progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                            <span class="sr-only">40% Complete (success)</span>
                        </div>
                    </div>
                </div>

                <div id="library_tree"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default select-action">{translateToken value="Select"}</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">{translateToken value="Close"}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

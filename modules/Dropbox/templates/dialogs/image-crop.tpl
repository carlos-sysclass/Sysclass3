<div id="dropbox-image-crop" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-image"></i>
                    {translateToken value='Crop Image'}
                </h4>
            </div>
            <div class="modal-body" align="center">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-inline">
                            <div class="inline-item">
                                <label class="control-label">{translateToken value='You can crop your picture, if you wish'}</label>
                            </div>
                            <div class="inline-item size-container">
                                <label class="control-label">{translateToken value="Sizes:"}</label>
                            </div>
                            <div class="inline-item size-container">
                                <select class="select2-me form-control" name="default_sizes" data-placeholder="{translateToken value='Please, select the size'}">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="" class="crop-container" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default save-action" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

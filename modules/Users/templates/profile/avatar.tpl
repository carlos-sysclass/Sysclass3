<div class="form-group fileupload-me" data-fileupload-url="/module/dropbox/upload/avatar" data-image-crop="true" data-model-file="avatar.file_id">
    <input type="hidden" name="avatar.file_id" value="{$T_EDIT_USER.avatars[0].id}" />
    <ul class="list-group content-timeline-items">
    </ul>

    <span class="btn btn-primary fileinput-button">
        <i class="fa fa-cloud-upload"></i>
        <span>{translateToken value="Upload file"}</span>
        <input type="file" name="files[]">
    </span>
</div>

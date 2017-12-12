<div class="form-body">
	<h5 class="form-section margin-bottom-10 margin-top-10">
		<i class="fa fa-camera"></i>
		{translateToken value="Picture ID 1"} (<span>{translateToken value="identification card, driver’s license, passport"}</span>)
	</h5>
	<div class="form-group fileupload-me" data-fileupload-url="/module/dropbox/upload/documents" data-image-crop="true" data-model-file="documents.file_id">
		<input type="hidden" name="documents.file_id" value="{$T_FILE_PICTURE_1.id}" />
		<ul class="list-group content-timeline-items">
	    </ul>
	    <span class="btn btn-primary fileinput-button">
	        <i class="fa fa-cloud-upload"></i>
	        <span>{translateToken value="Upload document"}</span>
	        <input type="file" name="file_picture_1[]">
	    </span>
	</div>
</div>
<div class="form-body">
	<h5 class="form-section margin-bottom-10 margin-top-10">
		<i class="fa fa-camera"></i>
		{translateToken value="Picture ID 2"} (<span>{translateToken value="identification card, driver’s license, passport"}</span>)
	</h5>
	<div class="form-group fileupload-me" data-fileupload-url="/module/dropbox/upload/documents" data-image-crop="true" data-model-file="documents.file_id">
		<input type="hidden" name="documents.file_id" value="{$T_FILE_PICTURE_2.id}" />
		<ul class="list-group content-timeline-items">
	    </ul>
	    <span class="btn btn-primary fileinput-button">
	        <i class="fa fa-cloud-upload"></i>
	        <span>{translateToken value="Upload document"}</span>
	        <input type="file" name="file_picture_2[]">
	    </span>
	</div>
</div>
<div class="form-body">
	<h5 class="form-section margin-bottom-10 margin-top-10">
		<i class="fa fa-camera"></i>
		{translateToken value="High-School/Secondary School Diploma or Transcript”"}
	</h5>
	<div class="form-group fileupload-me" data-fileupload-url="/module/dropbox/upload/documents" data-image-crop="true" data-model-file="documents.file_id">
		<input type="hidden" name="documents.file_id" value="{$T_FILE_TRANSCRIPT_1.id}" />
		<ul class="list-group content-timeline-items">
	    </ul>
	    <span class="btn btn-primary fileinput-button">
	        <i class="fa fa-cloud-upload"></i>
	        <span>{translateToken value="Upload document"}</span>
	        <input type="file" name="file_transcript_1[]">
	    </span>
	</div>
</div>
<div class="form-body">
	<h5 class="form-section margin-bottom-10 margin-top-10">
		<i class="fa fa-camera"></i>
		{translateToken value="High-School/Secondary School Diploma or Transcript”"}
	</h5>
	<div class="form-group fileupload-me" data-fileupload-url="/module/dropbox/upload/documents" data-image-crop="true" data-model-file="documents.file_id">
		<input type="hidden" name="documents.file_id" value="{$T_FILE_TRANSCRIPT_2.id}" />
		<ul class="list-group content-timeline-items">
	    </ul>
	    <span class="btn btn-primary fileinput-button">
	        <i class="fa fa-cloud-upload"></i>
	        <span>{translateToken value="Upload document"}</span>
	        <input type="file" name="file_transcript_2[]">
	    </span>
	</div>
</div>
<!--
	Objetive:
	[ program objectives]

	Goals: 
	[ program objectives]

	Prerequisites: 
	Number of Courses:
	Monthly Cost:	Total Cost: 
	Length:	Start Date:	End Date:
	Coordinator:	Contact:
-->



<div class="form-group fileupload-me" data-fileupload-url="/module/dropbox/upload/image" data-model-file="logo_id">
	<label class="control-label">{translateToken value=""}
	<input type="hidden" name="image_id" />
    <ul class="list-group content-timeline-items">
    </ul>

	<span class="btn btn-primary fileinput-button">
        <i class="fa fa-plus"></i>
        <span>{translateToken value="Set program image"}</span>
        <input type="file" name="files[]">
    </span>
</div>

<div class="form-group">
	<label class="control-label">{translateToken value="Objectives"}
        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='The objective to be achieved by this program'}">
            <i class="fa fa-question-circle"></i>
        </span>

	</label>
	<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="objectives" rows="6" placeholder="{translateToken value="Course Objectives"}"></textarea>
</div>
<!-- 
<div class="form-group">
	<label class="control-label">
		{translateToken value="Goals"}
        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='The goals to be achieved by the users'}">
            <i class="fa fa-question-circle"></i>
        </span>
	</label>
	<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="goals" rows="6" placeholder="{translateToken value="Users Goals"}"></textarea>
</div>
 -->
<div class="form-group">
	<label class="control-label">{translateToken value="Instructor"}</label>
	<select class="select2-me form-control" name="coordinator_id">
		<option value="">{translateToken value="Please, Select"}</option>
		{foreach $T_INSTRUCTORS as $id => $instructor}
			<option value="{$instructor.id}">#{$instructor.id} - {$instructor.name} {$instructor.surname}</option>
		{/foreach}
	</select>
</div>

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

<div class="form-group">
	<label class="control-label">{translateToken value="Objectives"}
        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='The objective to be achieved by this program'}">
            <i class="fa fa-question"></i>
        </span>

	</label>
	<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="objectives" rows="6" placeholder="{translateToken value="Course Objectives"}"></textarea>
</div>
<!-- 
<div class="form-group">
	<label class="control-label">
		{translateToken value="Goals"}
        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='The goals to be achieved by the students'}">
            <i class="fa fa-question"></i>
        </span>
	</label>
	<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="goals" rows="6" placeholder="{translateToken value="Students Goals"}"></textarea>
</div>
 -->
<div class="form-group">
	<label class="control-label">{translateToken value="Instructor"}</label>
	<select class="select2-me form-control" name="coordinator_id">
		<option value="">{translateToken value="Please Select"}</option>
		{foreach $T_INSTRUCTORS as $id => $instructor}
			<option value="{$instructor.id}">#{$instructor.id} - {$instructor.name} {$instructor.surname}</option>
		{/foreach}
	</select>
</div>

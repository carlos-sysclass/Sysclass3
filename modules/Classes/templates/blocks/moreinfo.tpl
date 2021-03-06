<!--
Number of Units: [calculated]
Number of Tests: [calculated]
Number of Exams: [calculated]

Papers: [calculated] // TRABALHOS DO MÓDULO
	Theme:
	Minimum Size:
	Instructions:
	Due Date:

Length:
	Start Date: [calculated]
	End Date: [calculated]

Course Reading:
	ISBN
	Name
	URL
	Required?
-->

<div class="form-group">
	<label class="control-label">{translateToken value="Objectives"}
        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='The objective to be achieved by this course'}">
            <i class="fa fa-question-circle"></i>
        </span>

	</label>
	<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="objectives" rows="6" placeholder="{translateToken value="Course objectives"}"></textarea>
</div>
<!-- 
<div class="form-group">
	<label class="control-label">
		{translateToken value="Goals"}
        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='The goals to be achieved by the users'}">
            <i class="fa fa-question-circle"></i>
        </span>
	</label>
	<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="goals" rows="6" placeholder="{translateToken value="Users Goals"}" data-rule-required="true"></textarea>
</div>
 -->
<div class="form-group">
	<label class="control-label">{translateToken value="Coordinator"}</label>
	<select class="select2-me form-control" name="professor_id">
		<option value="">{translateToken value="Please, select"}</option>
		{foreach $T_INSTRUCTORS as $id => $instructor}
			<option value="{$instructor.id}">#{$instructor.id} - {$instructor.name} {$instructor.surname}</option>
		{/foreach}
	</select>
</div>

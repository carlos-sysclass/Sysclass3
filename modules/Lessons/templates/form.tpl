{extends file="layout/default.tpl"}
{block name="content"}
<div id="form-{$T_MODULE_ID}">
<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['lessons_content']) &&  ($T_SECTION_TPL['lessons_content']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">
					<i class="fa fa-dropbox"></i>
					{translateToken value="Content Editor"}
				</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['lessons_content_text']) &&  ($T_SECTION_TPL['lessons_content_text']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Text Content"}</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['lessons_content_video']) &&  ($T_SECTION_TPL['lessons_content_video']|@count > 0))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">{translateToken value="Video Content"}</a>
			</li>
			{/if}
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Class"}</label>
					<select class="select2-me form-control" name="class_id" data-rule-min="1" data-placeholder="{translateToken value="Select Class"}">
						<option value=""></option>
						{foreach $T_CLASSES as $classe}
							<option value="{$classe.id}">{$classe.name}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Instructors"}</label>
					<!--<input type="hidden" class="select2-me form-control input-block-level" name="instructor_id" data-placeholder="{translateToken value='Instructors'}" data-url="/module/courses/items/instructor/combo" data-minimum-results-for-search="4" data-multiple="false" />-->
					<select class="select2-me form-control" name="instructor_id" multiple="multiple">
						<option value="">{translateToken value="Please Select"}</option>
						{foreach $T_INSTRUCTORS as $id => $instructor}
							<option value="{$instructor.id}">{$instructor.name} {$instructor.surname}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
				<div class="form-actions nobg">



					<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>

					<button class="btn btn-warning save-and-add-action" type="button">{translateToken value="Save and Add another Lesson"}</button>
				</div>
			</div>

			{if (isset($T_SECTION_TPL['lessons_content']) &&  ($T_SECTION_TPL['lessons_content']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
				    {foreach $T_SECTION_TPL['lessons_content'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['lessons_content_text']) &&  ($T_SECTION_TPL['lessons_content_text']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
				    {foreach $T_SECTION_TPL['lessons_content_text'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['lessons_content_video']) &&  ($T_SECTION_TPL['lessons_content_video']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_4">
				    {foreach $T_SECTION_TPL['lessons_content_video'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
		</div>
	</div>

</form>
</div>
<!--
<script type="text/template" id="file-upload-new-video-item">
	<li class="row">
		<div class="col-md-9 file-name"></div>
		<div class="col-md-3">
			<div id="progress" style="height: 15px;">
				<div class="progress-bar progress-bar-success"></div>
			</div>
			<span class="btn btn-success fileinput-button" style="display: none">
				<i class="glyphicon glyphicon-plus"></i>
				<span>Select files...</span>

			</span>
		</div>
	</li>
</script>
-->



<!--
<a id="teste" href="http://local.beta.sysclass.com/files/lessons/1/video/login-background%20%2822%29.mp4" class="btn btn-default" data-toggle="modal" data-target="#filemodal">VIDEO</a>

<div class="modal fade" id="filemodal" tabindex="-1" role="dialog" aria-labelledby="videoModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div>
          <iframe src="http://docs.google.com/gview?url=http://infolab.stanford.edu/pub/papers/google.pdf&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
-->
{/block}



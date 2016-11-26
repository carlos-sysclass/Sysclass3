{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['moreinfo']) &&  ($T_SECTION_TPL['moreinfo']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">
					{translateToken value="More Info"}
				</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['units']) &&  ($T_SECTION_TPL['units']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Units"}</a>
			</li>
			{/if}
			{if ((isset($T_SECTION_TPL['tests']) &&  ($T_SECTION_TPL['tests']|@count > 0)) || (isset($T_SECTION_TPL['grades']) &&  ($T_SECTION_TPL['grades']|@count > 0)))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">{translateToken value="Tests and Grades"}</a>
			</li>
			{/if}

			{if (isset($T_SECTION_TPL['communications']) &&  ($T_SECTION_TPL['communications']|@count > 0))}
			<li>
				<a href="#tab_1_5" data-toggle="tab">{translateToken value="Tests and Grades"}</a>
			</li>
			{/if}
            <!--
			{if (isset($T_SECTION_TPL['roadmap']) &&  ($T_SECTION_TPL['roadmap']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Road Map"}</a>
			</li>
			{/if}
            -->
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
			<li>
				<a href="#tab_1_6" data-toggle="tab">{translateToken value="Permissions"}</a>
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
					<label class="control-label">{translateToken value="Program"}</label>
					<select class="select2-me form-control" name="course_id" data-rule-min="1" data-placeholder="{translateToken value="Select Program"}">
						<option value=""></option>
						{foreach $T_PROGRAMS as $classe}
							<option value="{$classe.id}">{$classe.name}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Description"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="description" rows="6" placeholder="{translateToken value="Put your description here..."}" data-rule-required="true"></textarea>
				</div>
				<!--
				<div class="form-group">
					<label class="control-label">{translateToken value="Instructors"}</label>
					<select class="select2-me form-control" name="instructor_id" multiple="multiple">
						<option value="">{translateToken value="Please Select"}</option>
						{foreach $T_INSTRUCTORS as $id => $instructor}
							<option value="{$instructor.id}">{$instructor.name} {$instructor.surname}</option>
						{/foreach}
					</select>
				</div>
				-->

				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
				<div class="form-actions nobg">
					<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
				</div>
			</div>
			{if (isset($T_SECTION_TPL['moreinfo']) &&  ($T_SECTION_TPL['moreinfo']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
				    {foreach $T_SECTION_TPL['moreinfo'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_LESSONS_BLOCK_CONTEXT T_MODULE_ID=$T_LESSONS_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}

					<div class="form-actions nobg">
						<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
					</div>
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['units']) &&  ($T_SECTION_TPL['units']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
				    {foreach $T_SECTION_TPL['units'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_LESSONS_BLOCK_CONTEXT T_MODULE_ID=$T_LESSONS_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if ((isset($T_SECTION_TPL['tests']) &&  ($T_SECTION_TPL['tests']|@count > 0)) || (isset($T_SECTION_TPL['grades']) &&  ($T_SECTION_TPL['grades']|@count > 0)))}
				<div class="tab-pane fade in" id="tab_1_4">
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['communications']) &&  ($T_SECTION_TPL['communications']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_5">
				</div>
			{/if}
            <!--
			{if (isset($T_SECTION_TPL['roadmap']) &&  ($T_SECTION_TPL['roadmap']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">

				</div>
			{/if}
            -->
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_6">
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
		</div>
	</div>
</form>
{/block}

{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>

			{if (isset($T_SECTION_TPL['lessons']) &&  ($T_SECTION_TPL['lessons']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Lessons"}</a>
			</li>
			{/if}
			{if ((isset($T_SECTION_TPL['tests']) &&  ($T_SECTION_TPL['tests']|@count > 0)) || (isset($T_SECTION_TPL['grades']) &&  ($T_SECTION_TPL['grades']|@count > 0)))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Tests and grades"}</a>
			</li>
			{/if}

			{if (isset($T_SECTION_TPL['communications']) &&  ($T_SECTION_TPL['communications']|@count > 0))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">{translateToken value="Tests and Grades"}</a>
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
				<a href="#tab_1_5" data-toggle="tab">{translateToken value="Permissions"}</a>
			</li>
			{/if}
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Title"}</label>
							<input name="title" value="" type="text" placeholder="{translateToken value="Title"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Department"}</label>
							<select class="select2-me form-control" data-placeholder="{translateToken value='Please, select'}" name="area_id" data-rule-required="true" data-rule-min="1">
								<option value=""></option>
								{foreach $T_KNOWLEDGE_AREAS as $id => $area}
									<option value="{$area.id}">{$area.name}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Question type"}</label>
							<select class="select2-me form-control" data-placeholder="{translateToken value='Please, select'}" name="type_id" data-rule-required="true">
								<option value=""></option>
								{foreach $T_QUESTIONS_TYPES as $id => $type}
									<option value="{$type.id}">{$type.name}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Level"}</label>
							<select class="select2-me form-control" name="difficulty_id" data-rule-required="true" data-placeholder="{translateToken value='Please, select'}" data-rule-min="1">
								<option value=""></option>
								{foreach $T_QUESTIONS_DIFFICULTIES as $id => $difficulty}
									<option value="{$difficulty.id}">{$difficulty.name}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Question"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="question" rows="6" placeholder="{translateToken value="Put your question here..."}" data-rule-required="true"></textarea>
				</div>
				<div class="question-type-container" id="question-type-container">
					<!--
					INSERT INTO `mod_questions_types` (`id`,`name`) VALUES ('combine', 'Combine');
					-->
					<div class="question-types-item question-type-combine">
	                    <div class="alert alert-warning" role="alert">
	                        Not implemented yet!
	                    </div>
					</div>
					<!-- NO DATA NEEDED -->

					<div class="question-types-item question-type-free_text">
	                    
					</div>

					<div class="question-types-item question-type-true_or_false">
						<div class="form-group">
							<label class="control-label">{translateToken value="The answer is..."}</label>
							<input type="checkbox" name="answer" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="primary" data-on-text="{translateToken value='TRUE'}" data-off-color="warning" data-off-text="{translateToken value='FALSE'}" checked="checked" value="1" data-value-unchecked="0" data-update-single="true">
						</div>
					</div>
					<div class="question-types-item question-type-simple_choice">
						<h5 class="form-section no-margin">
							Choices
							<a class="btn btn-link btn-sm add-choice-action" data-toggle="modal">
								<i class="fa fa-plus-square"></i>
								{translateToken value="New choice"}
							</a>
						</h5>
						<div class="row">
							<div class="col-md-12">
								<ul class="list-group ui-sortable margin-bottom-10">

								</ul>
							</div>
						</div>
					</div>
					<div class="question-types-item question-type-multiple_choice">
						<h5 class="form-section no-margin">
							Choices
							<a class="btn btn-link btn-sm add-choice-action" data-toggle="modal">
								<i class="fa fa-plus-square"></i>
								{translateToken value="New choice"}
							</a>
						</h5>
						<div class="row">
							<div class="col-md-12">
								<ul class="list-group ui-sortable margin-bottom-10">

								</ul>
							</div>
						</div>
					</div>
					<div class="question-types-item question-type-fill_blanks">
	                    <div class="alert alert-warning" role="alert">
	                        Not implemented yet!
	                    </div>
	                    <!--
						<div class="alert alert-info">
							<i class="fa fa-info-circle"></i>
							{translateToken value="Type your question, and where you need to insert a blank, enter the following sequence"} <span class="btn btn-xs btn-default disabled"><strong>###</strong></span>
						</div>
						-->
					</div>

				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
			</div>
			{if (isset($T_SECTION_TPL['lessons']) &&  ($T_SECTION_TPL['lessons']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
				    {foreach $T_SECTION_TPL['lessons'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_LESSONS_BLOCK_CONTEXT T_MODULE_ID=$T_LESSONS_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if ((isset($T_SECTION_TPL['tests']) &&  ($T_SECTION_TPL['tests']|@count > 0)) || (isset($T_SECTION_TPL['grades']) &&  ($T_SECTION_TPL['grades']|@count > 0)))}
				<div class="tab-pane fade in" id="tab_1_3">
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['communications']) &&  ($T_SECTION_TPL['communications']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_4">
				</div>
			{/if}
            <!--
			{if (isset($T_SECTION_TPL['roadmap']) &&  ($T_SECTION_TPL['roadmap']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">

				</div>
			{/if}
            -->
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_5">
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
	</div>
</form>
<script type="text/template" id="question-simple_choice-item">
	<div class="input-group ">
		<div class="input-group-btn">
             <span class="btn btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move choice"}">
                <i class="fa fa-arrows"></i>
            </span>
		</div>
    	<input value="<%= model.choice %>" type="text" placeholder="{translateToken value="Choice"}" class="form-control " />
        <div class="input-group-btn">
        	<% if (!model.answer) { %>
            <a class="btn btn-success select-choice-action" type="button">
                {translateToken value="Mark as correct"}
            </a>
            <% } %>
	        <a class="btn btn-danger remove-choice-action">
	            <i class="fa fa-trash"></i>
	        </a>
        </div>
    </div>
</script>

{/block}

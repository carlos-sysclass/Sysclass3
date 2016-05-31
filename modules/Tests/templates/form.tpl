{extends file="layout/default.tpl"}
{block name="content"}
<div id="form-{$T_MODULE_ID}">
<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<input name="type" type="hidden" value="test" />
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			<li>
				<a href="#tab_1_2" data-toggle="tab">
					<i class="fa fa-cogs"></i>
					{translateToken value="Test Settings"}
				</a>
			</li>

			{if (isset($T_SECTION_TPL['questions-list']) &&  ($T_SECTION_TPL['questions-list']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">
					<i class="fa fa-question"></i>
					{translateToken value="Questions"}
				</a>
			</li>
			{/if}

			{if (isset($T_SECTION_TPL['tests_execution']) &&  ($T_SECTION_TPL['tests_execution']|@count > 0))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">
					<i class="fa fa-question"></i>
					{translateToken value="Executions"}
				</a>
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
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Grade Rule"}</label>
					<select class="select2-me form-control" name="grade_id" data-placeholder="{translateToken value="Select Class"}">
						<option value="">{translateToken value="Select Grade Rule"}</option>
						{foreach $T_GRADES as $grade}
							<option value="{$grade.id}">{$grade.name}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Instructors"}</label>
					<!--<input type="hidden" class="select2-me form-control input-block-level" name="instructor_id" data-placeholder="{translateToken value='Instructors'}" data-url="/module/courses/items/instructor/combo" data-minimum-results-for-search="4" data-multiple="false" />-->
					<select class="select2-me form-control" name="instructor_id">
						<option value="">{translateToken value="Please Select"}</option>
						{foreach $T_INSTRUCTORS as $id => $instructor}
							<option value="{$instructor.id}">#{$instructor.id} - {$instructor.name} {$instructor.surname}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
			</div>

			<div class="tab-pane fade in" id="tab_1_2">
		        <!--
		            Manter histórico:   Repetições (Deixe em branco para não limitar)
		            Mostrar lista ordenada:     Exibir o peso da pergunta nas perguntas de múltipla escolha
		            Obrigatoriedade de resposta de todas as perguntas:
		            Permitir ao aluno repetir somente as perguntas que tenha respondido errado:     Somente, se a repetição do teste for permitida
		        -->
			    <div class="portlet">
			        <div class="portlet-title">
			            <div class="caption">
			                <i class="fa fa-clock-o"></i>Duration settings
			            </div>
			        </div>
			        <div class="portlet-body">
			            <div class="row">
			                <!-- Duração em minutos:      Deixe em branco para não ter prazo limitado -->
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Total time in minutes available for the test execution. Leave 0 (zero) for unlimited time.'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Time limit in minutes?"}
			                        </label>

			                        <input name="time_limit" value="" type="text" placeholder="{translateToken value="Time Limit"}" class="form-control input-xsmall" data-rule-required="false" data-rule-number="true" data-rule-max="500" />
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            {translateToken value="Allow pause the test?"}
			                        </label>

			                        <input type="checkbox" name="allow_pause" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            {translateToken value="How many times the user can have the test?"}
			                        </label>
			                        <input name="test_repetition" value="" type="text" placeholder="{translateToken value="Test Repetition Times"}" class="form-control input-xsmall" data-rule-required="false" data-rule-number="true" data-rule-min="1" data-rule-max="10" />
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			    <div class="portlet">
			        <div class="portlet-title">
			            <div class="caption">
			                <i class="fa fa-cog"></i>Questions settings
			            </div>
			        </div>
			        <div class="portlet-body">
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the user the weight of the question during the test'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Show question weight?"}
			                        </label>

			                        <input type="checkbox" name="show_question_weight" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the user the difficulty of the question during the test'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Show question Difficulty?"}
			                        </label>

			                        <input type="checkbox" name="show_question_difficulty" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the user the type of the question during the test'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Show question type?"}
			                        </label>

			                        <input type="checkbox" name="show_question_type" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Block user input only in the current question'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Show questions one by one?"}
			                        </label>

			                        <input type="checkbox" name="show_one_by_one" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='It allows the user to navigate through the test\'s questions'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Can navigate through the test?"}
			                        </label>

			                        <input type="checkbox" name="can_navigate_through" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Shows the correct answer after user response. This feature will block the question after the user response.'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Show correct answers?"}
			                        </label>

			                        <input type="checkbox" name="show_correct_answers" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the questions in a randomized order'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Randomize the order of questions?"}
			                        </label>

			                        <input type="checkbox" name="randomize_questions" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Randomize all alternatives from simple and multiple choice questions'}">
			                                <i class="fa fa-question"></i>
			                            </span>
			                            {translateToken value="Shuffle questions alternatives?"}
			                        </label>

			                        <input type="checkbox" name="randomize_answers" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" checked="checked" value="1" data-value-unchecked="0">
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>

			{if (isset($T_SECTION_TPL['questions-list']) &&  ($T_SECTION_TPL['questions-list']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
				    {foreach $T_SECTION_TPL['questions-list'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['tests_execution']) &&  ($T_SECTION_TPL['tests_execution']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_4">
				    {foreach $T_SECTION_TPL['tests_execution'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success save-action" type="button">{translateToken value="Save Changes"}</button>
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



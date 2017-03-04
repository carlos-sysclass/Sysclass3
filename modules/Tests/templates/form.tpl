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
					{translateToken value="Settings"}
				</a>
			</li>

			{if (isset($T_SECTION_TPL['questions-list']) &&  ($T_SECTION_TPL['questions-list']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">
					<i class="fa fa-question-circle"></i>
					{translateToken value="Questions"}
				</a>
			</li>
			{/if}

			{if (isset($T_SECTION_TPL['tests_execution']) &&  ($T_SECTION_TPL['tests_execution']|@count > 0))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">
					<i class="fa fa-question-circle"></i>
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
					<label class="control-label">{translateToken value="Course"}</label>
					<select class="select2-me form-control" name="class_id" data-rule-min="1" data-placeholder="{translateToken value="Select course"}">
						<option value="">{translateToken value="Select course"}</option>
						{foreach $T_CLASSES as $classe}
							<option value="{$classe.id}">{$classe.name}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Grade rules"}
                        <span class="badge badge-warning tooltips" data-original-title="{translateToken value='You can select a customized rule to show yours users grades in your prefered way. If you do not choose, the grades will be showed in the [0-100] standard'}">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </label>

					<select class="select2-me form-control" name="test.grade_id" data-placeholder="{translateToken value="Select grade rule"}">
						<option value="">{translateToken value="Select grade rule"}</option>
						{foreach $T_GRADES as $grade}
							<option value="{$grade.id}">{$grade.name}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Instructors"}</label>
					<select class="select2-me form-control" name="instructor_id">
						<option value="">{translateToken value="Please select"}</option>
						{foreach $T_INSTRUCTORS as $id => $instructor}
							<option value="{$instructor.id}">#{$instructor.id} - {$instructor.name} {$instructor.surname}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-value-unchecked="0" data-update-single="true">
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
			                <i class="fa fa-clock-o"></i>{translateToken value="Duration settings"}
			            </div>
			        </div>
			        <div class="portlet-body">
			            <div class="row">
			                <!-- Duração em minutos:      Deixe em branco para não ter prazo limitado -->
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Total time in minutes available for the test execution. Leave 0 (zero) for unlimited time.'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Time limit in minutes"}
			                        </label>

			                        <input name="test.time_limit" value="" type="text" placeholder="{translateToken value="Time limit"}" class="form-control input-xsmall" data-rule-required="false" data-rule-number="true" data-rule-max="500" />
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            {translateToken value="Allow pause the test"}
			                        </label>

			                        <input type="checkbox" name="test.allow_pause" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            {translateToken value="Number of attempts. '0' (zero) for unlimited."}
			                        </label>
			                        <input name="test.test_repetition" value="" type="text" placeholder="{translateToken value="Times allowed to retake the test."}" class="form-control input-xsmall" data-rule-required="false" data-rule-number="true" data-rule-min="0" data-rule-max="9999" />
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			    <div class="portlet">
			        <div class="portlet-title">
			            <div class="caption">
			                <i class="fa fa-cog"></i>{translateToken value="Options"}
			            </div>
			        </div>
			        <div class="portlet-body">
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the user the weight of the question during the test'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Show question weight"}
			                        </label>

			                        <input type="checkbox" name="test.show_question_weight" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show user the level of difficulty of the question during the test'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Show question level"}
			                        </label>

			                        <input type="checkbox" name="test.show_question_difficulty" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the user the type of the question during the test'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Show question type"}
			                        </label>

			                        <input type="checkbox" name="test.show_question_type" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Block user input only in the current question'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Show questions one by one"}
			                        </label>

			                        <input type="checkbox" name="test.show_one_by_one" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='It allows the user to navigate through the test\'s questions'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Navigate through the test"}
			                        </label>

			                        <input type="checkbox" name="test.can_navigate_through" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Shows the correct answer after user response. This feature will block the question after the user response.'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Show correct answer"}
			                        </label>

			                        <input type="checkbox" name="test.show_correct_answers" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show the questions in a randomized order'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Randomize the order"}
			                        </label>

			                        <input type="checkbox" name="test.randomize_questions" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label class="control-label">
			                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Randomize all alternatives from simple and multiple choice questions'}">
			                                <i class="fa fa-question-circle"></i>
			                            </span>
			                            {translateToken value="Shuffle questions"}
			                        </label>

			                        <input type="checkbox" name="test.randomize_answers" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='YES'}" data-off-color="danger" data-off-text="{translateToken value='NO'}" value="1" data-value-unchecked="0" data-update-single="true">
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>

			{if (isset($T_SECTION_TPL['questions-list']) &&  ($T_SECTION_TPL['questions-list']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
				    <div class="portlet">
				        <div class="portlet-title">
				            <div class="caption">
				                <i class="fa fa-cog"></i>{translateToken value="Questions settings"}
				            </div>
				        </div>
				        <div class="portlet-body">
				            <div class="row">
				                <div class="col-md-4">
				                    <div class="form-group">
				                        <label class="control-label">
				                            <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Show only a set of questions? Leave 0 to show all questions'}">
				                                <i class="fa fa-question-circle"></i>
				                            </span>
				                            {translateToken value="Number of questions"}
				                        </label>

				                        <input name="test.test_max_questions" value="" type="text" placeholder="{translateToken value="Number of times a test can be retaken."}" class="form-control input-xsmall" data-rule-required="false" data-rule-number="true" data-rule-min="0" data-rule-max="9999" />
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
				    <div class="portlet">
				        <div class="portlet-title">
				            <div class="caption">
				                <i class="fa fa-cog"></i>{translateToken value="Questions"}
				            </div>
				        </div>
				        <div class="portlet-body">


						    {foreach $T_SECTION_TPL['questions-list'] as $template}
						        {include file=$template}
						    {/foreach}
						</div>
					</div>
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
		<button class="btn btn-success save-action" type="button">{translateToken value="Save changes"}</button>
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
				<i class="glyphicon glyphfa fa fa-plus-square"></i>
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



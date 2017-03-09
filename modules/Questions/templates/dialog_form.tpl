						<div class="form-body">
                            <!-- BEGIN FORM STRUCT -->    
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
                                        <select class="select2-me form-control" name="area_id" data-rule-required="true" data-rule-min="1">
                                            <option value="" selected="selected">{translateToken value="Please, select"}</option>
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
                                        <select class="select2-me form-control" name="type_id" data-rule-required="true">
                                            <option value="">{translateToken value="Please, select"}</option>
                                            {foreach $T_QUESTIONS_TYPES as $id => $type}
                                                <option value="{$type.id}">{$type.name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Level"}</label>
                                        <select class="select2-me form-control" name="difficulty_id" data-rule-required="true" data-rule-min="1">
                                            <option value="">{translateToken value="Please, select"}</option>
                                            {foreach $T_QUESTIONS_DIFFICULTIES as $id => $difficulty}
                                                <option value="{$difficulty.id}">{$difficulty.name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{translateToken value="Question"}</label>

                                <div class="wysihtml">
                                    <div class="toolbar">
                                      <a data-wysihtml5-command="bold">bold</a>
                                    </div>
                                    <div class="wysihtml-form-control form-control" name="question" rows="6" placeholder="{translateToken value="Put your question here..."}" data-rule-required="true"></div>
                                    <input type="hidden" name="question" />
                                </div>
                                <!-- 
                                <textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="question" rows="6" placeholder="{translateToken value="Put your question here..."}" data-rule-required="true"></textarea> -->
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
                                    <div class="alert alert-warning" role="alert">
                                        Not implemented yet!
                                    </div>
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
                                            {translateToken value="New Choice"}
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
                                            {translateToken value="New Choice"}
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
                            <!-- END FORM STRUCT -->
						</div>
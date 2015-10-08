<div class="modal fade" id="dialogs-questions-create" tabindex="-1" role="basic" aria-hidden="true" data-animation="false">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
        	<form id="form-question" role="form" class="form-validate" method="post" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title event-title">{translateToken value="Create Question"}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        
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
                                        <label class="control-label">{translateToken value="Departament"}</label>
                                        <select class="select2-me form-control" name="area_id" data-rule-required="true" data-rule-min="1">
                                            <option value="">{translateToken value="Please Select"}</option>
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
                                        <label class="control-label">{translateToken value="Question Type"}</label>
                                        <select class="select2-me form-control" name="type_id" data-rule-required="true">
                                            <option value="">{translateToken value="Please Select"}</option>
                                            {foreach $T_QUESTIONS_TYPES as $id => $type}
                                                <option value="{$type.id}">{$type.name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Difficulty"}</label>
                                        <select class="select2-me form-control" name="difficulty_id" data-rule-required="true" data-rule-min="1">
                                            <option value="">{translateToken value="Please Select"}</option>
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
                                    <div class="alert alert-warning" role="alert">
                                        Not implemented yet!
                                    </div>
                                </div>

                                <div class="question-types-item question-type-true_or_false">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="The answer is..."}</label>
                                        <input type="checkbox" name="answer" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="primary" data-on-text="{translateToken value='TRUE'}" data-off-color="warning" data-off-text="{translateToken value='FALSE'}" checked="checked" value="1" data-value-unchecked="0">
                                    </div>
                                </div>
                                <div class="question-types-item question-type-simple_choice">
                                    <h5 class="form-section no-margin">
                                        Choices
                                        <a class="btn btn-link btn-sm add-choice-action" data-toggle="modal">
                                            <i class="icon-plus"></i>
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
                                            <i class="icon-plus"></i>
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
                            <!-- BEGIN FORM STRUCT -->
						</div>

					</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
                    <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                {translateToken value="This is correct!"}
            </a>
            <% } %>
            <a class="btn btn-danger remove-choice-action">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</script>

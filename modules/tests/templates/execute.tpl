{extends file="layout/default.tpl"}

{block name="content"}
<div id="tests-execute-block">
    <form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
        <div class="form-body">
            {foreach $T_TEST.questions as $index => $item}
                {assign var="question" value=$item.question}
                {assign var="type" value=$question.type_id}

                <div class="test-question-container">
                    <h5 class="section-title">
                        <span class="label label-primary">{translateToken value="Question"} #{$index+1}</span>
                        <i>{$question.question}</i>
                    </h5>

                    {if $type == "combine"}
                        <script type="text/template" id="tab_lesson_exercises-question-combine-template">
                            <div class="answer-container">
                                <div class="alert alert-warning" role="alert">
                                    Not implemented yet!
                                </div>
                            </div>
                        </script>
                    {elseif $type == "true_or_false"}
                        <div class="answer-container">
                            <div class="form-group">
                                <label class="control-label">{translateToken value="The answer is..."}</label>
                                <input type="checkbox" name="answer[{$item.id}]" data-update="answer.{$item.id}" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='TRUE'}" data-off-color="danger" data-off-text="{translateToken value='FALSE'}" checked="checked" value="1" data-value-unchecked="0">
                            </div>
                        </div>
                    {elseif $type == "simple_choice"}
                        <div class="answer-container">
                            <ul class="list-group">
                            {foreach $question.options as $index => $option}
                                <li>
                                    <label>
                                        <input type="radio" name="answer[{$item.id}]" data-update="answer.{$item.id}" class="icheck-me" data-skin="square" data-color="green" value="{$option.index}"> {$option.choice}
                                    </label>
                                </li>
                            {/foreach}
                            </ul>
                        </div>
                    {elseif $type == "multiple_choice"}
                        <div class="answer-container">
                            <ul class="list-group">
                            {foreach $question.options as $index => $option}
                                <li>
                                    <label>
                                        <input type="checkbox" name="answer[{$item.id}]" data-update="answer.{$item.id}" class="icheck-me" data-skin="square" data-color="green" value="{$option.index}"> {$option.choice}
                                    </label>
                                </li>
                            {/foreach}
                            </ul>
                        </div>
                    {elseif $type == "fill_blanks"}
                        <div class="answer-container">
                            {$question.question}
                        </div>
                    {else} {* ASSUMES FREE TEXT *}
                        <div class="answer-container">
                            <textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="answer.{$item.id}" rows="6" placeholder="{translateToken value="Put your answer here..."}"></textarea>
                        </div>
                    {/if}
                </div>
            {/foreach}
        </div>
        <div class="form-actions nobg">
            <button class="btn btn-success save-action" type="button">{translateToken value="Save Changes"}</button>
        </div>
    </form>
    <ul class="test-sidebar-info">
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-slack text-primary "></i>
                {translateToken value="Total Questions"}:
            </span>
            <strong class="text-primary pull-right">{$T_TEST.total_questions}</strong>
        </li>
        {if $T_TEST.time_limit > 0}
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-clock-o text-primary"></i>
                {translateToken value="Time limit"}:
            </span>
            <strong class="text-primary pull-right">
                <span class="test-time-limit">{$T_TEST.time_limit}</span> {translateToken value="minutes"}
            </strong>
        </li>
        {/if}
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-graduation-cap text-primary"></i>
                {translateToken value="Maximum Score"}:
            </span>
            <strong class="text-primary pull-right">
                {$T_TEST.score} {translateToken value="points"}
            </strong>
        </li>
    </ul>
</div>
{/block}

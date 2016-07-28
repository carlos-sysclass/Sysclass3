{extends file="layout/default.tpl"}
{block name="content"}
<div id="tests-execute-block">
    <ul class="test-sidebar-info">
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-slack text-default "></i>
                {translateToken value="Try"}:
            </span>
            <strong class="text-primary pull-right">
                <span class="text-try-index-text">{$T_TEST.executions|@count}</span> 
                {if $T_TEST.test.test_repetition > 0}
                / {$T_TEST.test.test_repetition}
                {/if}
            </strong>
        </li>
        {if $T_EXECUTION.pending == 1}
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-slack text-default "></i>
                {translateToken value="Total Questions"}:
            </span>
            <strong class="text-primary pull-right">
                {if ($T_TEST.test.test_max_questions <= 0)}
                    {$T_TEST.questions|@count}
                {else}
                    {$T_TEST.test.test_max_questions}
                {/if}

                
            </strong>
        </li>
        {/if}
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-graduation-cap text-default"></i>
                {translateToken value="Maximum Score"}:
            </span>
            <strong class="text-primary pull-right">
                {$T_TEST.score} {translateToken value="points"}
            </strong>
        </li>
        {if $T_EXECUTION.pending == 0}
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-graduation-cap text-default"></i>
                {translateToken value="Your Score"}:
            </span>
            <strong class="text-primary pull-right">
                {if ($T_EXECUTION.pass == 0)}
                    <span class="label label-danger">{$T_EXECUTION.user_grade}</span>
                {else}
                    <span class="label label-primary">{$T_EXECUTION.user_grade}</span>
                {/if}
                <small>{$T_EXECUTION.user_points} {translateToken value="points"}</small>
            </strong>
        </li>
        <li>
            <span class="pull-left">
                <i class="fa fa-lg fa-graduation-cap text-default"></i>
                {translateToken value="Approved"}:
            </span>
            <strong class="text-primary pull-right">
                {if ($T_EXECUTION.pass == 0)}
                    <span class="label label-danger">{translateToken value="No"}</span>
                {else}
                    <span class="label bg-green font-green">{translateToken value="Yes"}</span>
                {/if}
            </strong>
        </li>

        {/if}

        {if $T_TEST.time_limit > 0}
           <li>
                <span class="pull-left">
                    <i class="fa fa-lg fa-clock-o text-default"></i>
                    {translateToken value="Time limit"}:
                </span>
                <strong class="text-primary pull-right">
                    {$T_TEST.time_limit} {translateToken value="minutes"}
                </strong>
            </li>
            <li class="test-time-limit">
                {if $T_EXECUTION.pending == 0}
                    <span class="pull-left">
                        <i class="fa fa-lg fa-clock-o text-default"></i>
                        {translateToken value="Completed In"}:
                    </span>
                {else}
                    <span class="pull-left">
                        <i class="fa fa-lg fa-clock-o text-default"></i>
                        {translateToken value="Time left"}:
                    </span>
                {/if}
                <strong class="pull-right">
                    <span class="test-time-limit-text"></span>
                </strong>
                <div class="clearfix"></div>
                <div class="progress progress-striped no-margin margin-top-10 active">
                    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar">
                        <span class="progress-text"></span>
                    </div>
                </div>
            </li>
        {/if}
    </ul>
    <form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
        <div class="form-body">
            {foreach $T_TEST.questions as $index => $item}
                {assign var="question" value=$item.question}
                {assign var="type" value=$question.type_id}

                <div class="test-question-container">
                    <h5 class="section-title">
                        <span class="label label-primary">{translateToken value="Question"} #{$index+1}</span>
                    </h5>
                    <div>
                    {$question.question nofilter}
                    </div>

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
                            <ul class="list-group">
                                <li>
                                    <label>
                                        <input type="radio" name="answers[{$item.id}]" data-update="answers.{$item.id}" class="icheck-me" data-skin="square" data-color="green" value="1"> {translateToken value='TRUE'}
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="answers[{$item.id}]" data-update="answers.{$item.id}" class="icheck-me" data-skin="square" data-color="red" value="0"> {translateToken value='FALSE'}
                                    </label>
                                </li>
                            </ul>
                        </div>
                    {elseif $type == "simple_choice"}
                        <div class="answer-container">
                            <ul class="list-group">
                            {foreach $question.options as $index => $option}
                                <li>
                                    <label>
                                        <input type="radio" name="answers[{$item.id}]" data-update="answers.{$item.id}" class="icheck-me" data-skin="square" data-color="green" value="{$option.index}"> {$option.choice}
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
                                        <input type="checkbox" name="answers[{$item.id}]" data-update="answers.{$item.id}" class="icheck-me" data-skin="square" data-color="green" value="{$option.index}"> {$option.choice}
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
                            <textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="answers.{$item.id}" data-update="answers.{$item.id}" rows="6" placeholder="{translateToken value="Put your answer here..."}"></textarea>
                        </div>
                    {/if}
                </div>
            {/foreach}
        </div>
        {if $T_EXECUTION.pending == 1}
            <div class="nobg no-border" align="center">
                <button class="btn btn-success finish-test-action" type="button">{translateToken value="Complete Test"}</button>
            </div>
        {else}
            <div class="nobg no-border" align="center">
                <a class="btn btn-primary" href="/dashboard">{translateToken value="Back to home page"}</a>
            </div>
        {/if}
    </form>

</div>
{/block}

{assign var="last_try" value=$T_TEST.executions|@end}
{if $T_TEST.test.test_repetition > 0 && $T_TEST.test.executions|@count >= $T_TEST.test.test_repetition}
    <div class="alert alert-warning alert-dismissable">
        <button data-dismiss="alert" class="close" type="button"></button>
        <i class="fa fa-warning"></i>
        {translateToken value="No more retakes avalible for this test."}
    </div>
{/if}

<div class="form-body">
    <!--
    <h4 class="form-section">
        <i class="fa fa-list-ol"></i>
        {translateToken value='Test Info'}: <strong>{$T_TEST.name}</strong>
    </h4>
    -->
    {if $T_TEST.info || $T_TEST.test.instructors|@count > 0}
    <div class="row">
        <div class="col-md-12">
            {if $T_TEST.info}
                <p class="">
                    <strong class="text-primary">{$T_TEST.info}</strong>
                </p>
            {/if}
            {if $T_TEST.test.instructors|@count > 0}
            <p class="">
                <span>{translateToken value="Instructor"}:</span>
                <strong class="text-primary pull-right">
                    {foreach $T_TEST.test.instructors as $instructor}
                        {$instructor.name} {$instructor.surname}
                        {if !$instructor@last}
                            ,&nbsp;
                        {/if}
                    {/foreach}
                </strong>
                <div class="clearfix"></div>
            </p>
            <hr />
            {/if}
        </div>
    </div>
    {/if}
    <div class="row">
        <div class="{if $last_try}col-md-6 col-sm-6{else}col-md-12 col-sm-12{/if}">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list-ol"></i>
                        <span class="hidden-480">
                        {translateToken value="Assignment details"} </span>
                    </div>
                </div>
                <div class="portlet-body test-details">

                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-slack text-primary "></i>
                            {translateToken value="Total questions"}:
                        </span>
                        <strong class="text-primary pull-right">
                        {if ($T_TEST.test.test_max_questions <= 0)}
                            {$T_TEST.questions|@count}
                        {else}
                            {$test_questions_size=$T_TEST.questions|@count}
                            {math equation="min(a, b)" a=$T_TEST.test.test_max_questions b=$test_questions_size}
                        {/if}
                        </strong>
                    </p>
                    <hr />
                    <p class="">
                        {if $T_TEST.time_limit > 0}
                            <span>
                                <i class="fa fa-lg fa-clock-o text-primary"></i>
                                {translateToken value="Time limit"}:
                            </span>
                            <strong class="text-primary pull-right">
                            {$T_TEST.test.time_limit} {translateToken value="minutes"}
                            </strong>
                        {else}
                            <span>
                                <i class="fa fa-lg fa-clock-o text-primary"></i>
                                {translateToken value="Time limit"}:
                            </span>
                            <strong class="text-primary pull-right">
                            {translateToken value="No time limit"}
                            </strong>
                        {/if}
                    </p>
                    <hr />
                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-repeat text-primary "></i>
                            {translateToken value="Retakes limit"}:
                        </span> 
                        {if $T_TEST.test.test_repetition > 0}
                        <strong class="text-primary pull-right">
                            {$T_TEST.executions|@count}/{$T_TEST.test.test_repetition}
                        </strong>
                        {else}
                        <strong class="text-primary pull-right">
                            {translateToken value="No retake limit"}
                        </strong>
                        {/if}                        
                    </p>
                    <hr />
                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-graduation-cap text-primary"></i>
                            {translateToken value="Maximum score"}:
                        </span>
                        <strong class="text-primary pull-right">{$T_TEST.score}</strong>
                    </p>
                </div>
            </div>
        </div>
        {if $last_try}
            {if !$last_try.pass}
                {assign var="text_class" value="text-danger"}
                {assign var="portlet_class" value="red"}
            {else}
                {assign var="text_class" value="font-green"}
                {assign var="portlet_class" value="green"}
            {/if}


        <div class="col-md-6 col-sm-6">
            <div class="portlet box {$portlet_class}">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i>
                        <span class="hidden-480">
                         {translateToken value="Your last attempt"} </span>
                    </div>
                </div>
                <div class="portlet-body test-details">
                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-slack {$text_class}"></i>
                            {translateToken value="Questions answered"}:
                        </span>
                        <strong class="{$text_class} pull-right">{$last_try.answers|json_decode:true|array_filter:'strlen'|count}</strong>
                    </p>
                    <hr />
                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-clock-o {$text_class}"></i>
                            {translateToken value="You took"}:
                        </span>
                        <strong class="{$text_class} pull-right">
                            {$last_try.progress.time_elapsed / 60} {translateToken value="minutes"}
                        </strong>
                    </p>
                    <hr />
                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-repeat {$text_class}"></i>
                            {translateToken value="Times the test was taken"}:
                        </span>
                        <strong class="{$text_class} pull-right">
                            {$T_TEST.executions|@count} {translateToken value="times"}
                        </strong>
                    </p>
                    <hr />
                    <p class="">
                        <span>
                            <i class="fa fa-lg fa-graduation-cap {$text_class}"></i>
                            {translateToken value="Your score"}:
                        </span>
                        <strong class="{$text_class} pull-right">
                            {$last_try.user_points} {translateToken value="points"}
                            &nbsp;
                            <span class="label label-primary">
                                {if $last_try.user_grade}
                                    {$last_try.user_grade}
                                {else}
                                    {($last_try.user_score*100)|round:2}%
                                {/if}
                            </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>
        {/if}
    </div>
</div>

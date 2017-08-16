{extends file="layout/default.tpl"}
{block name="content"}                                    
<div class="portlet box dark-blue" id="form_wizard_1">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-exchange"></i>
            <span class="caption-subject bold uppercase"> {$T_PROGRAM->name} - Complete Registration
            </span>
        </div>
    </div>
    <div class="portlet-body form bordered">
            <div class="form-wizard">
                <div class="form-body">
                    <ul class="nav nav-pills nav-justified steps">
                        <li>
                            <a href="#tab1" data-toggle="tab" class="step">
                                <span class="number"> 1 </span>
                                <span class="desc">
                                    <i class="fa fa-check"></i> Pre Requisites </span>
                            </a>
                        </li>
                        {if $T_EXECUTION_IS_DONE}
                        <li>
                            <a href="#tab2" data-toggle="tab" class="step">
                                <span class="number"> 2 </span>
                                <span class="desc">
                                    <i class="fa fa-check"></i> Payment </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab3" data-toggle="tab" class="step">
                                <span class="number"> 3 </span>
                                <span class="desc">
                                    <i class="fa fa-check"></i> Confirm </span>
                            </a>
                        </li>
                        {/if}
                    </ul>
                    <div id="bar" class="progress progress-striped" role="progressbar">
                        <div class="progress-bar progress-bar-success"> </div>
                    </div>
                    <div class="tab-content">

                        <div class="alert alert-danger display-none">
                            <button class="close" data-dismiss="alert"></button>
                            You have some form errors. Please check below. 
                        </div>
                        <div class="alert alert-success display-none">
                            <button class="close" data-dismiss="alert"></button>
                            Your form validation is successful! 
                        </div>

                        <div class="tab-pane" id="tab1">
                            <form class="form-horizontal" action="#" id="submit_form" method="POST">
                                <div class="form-body">
                                    <h4 class="form-section">Your Info:</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Name:"}</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold">{$T_CURRENT_USER['name']} {$T_CURRENT_USER['surname']}</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Communication in English:"}</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold">
                                            {$T_USER_ATTRS['english_communication']}</p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Course:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold" data-display="username">{$T_PROGRAM->name}</p>
                                        </div>
                                    </div>

                                    {if $T_EXECUTION_IS_DONE}
                                        <div class="form-group">
                                            <label class="control-label col-md-3">English Test Grade:</label>
                                            <div class="col-md-4">
                                                <p class="form-control-static bold">{$T_EXECUTION->user_grade}</p>
                                            </div>
                                        </div>
                                    {else}
                                        <div class="alert alert-info tips-container">
                                            <span class="btn btn-sm" style="cursor: default;">
                                                <i class="fa fa-info-circle fa-lg"></i>
                                                In order to complete the enrollment, you need to do a English Test and get at least a 70 grade
                                            </span>
                                            <div class="pull-right">
                                                <a href="javascript:;" class="btn btn-default btn-sm do-test-action" data-test-id="92">
                                                <i class="fa fa-list-ol "></i>
                                                Take the test Now
                                                </a>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                            </form>
                        </div>
                        {if $T_EXECUTION_IS_DONE}
                        <div class="tab-pane" id="tab2">
                            <form class="form-horizontal" action="#" id="submit_form" method="POST">
                                <div class="form-body">
                                    <h4 class="form-section">Payment Details</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Currency"}:</label>
                                        <div class="col-md-4">
                                            <select class="select2-me form-control input-block-level" name="price_currency" style="min-width: 150px;" data-search="false">
                                                <option value="">{translateToken value="Select"}</option>
                                                {foreach $T_CURRENCIES as $currency}
                                                    <option value="{$currency->code}"{if $T_ENROLLMENT->currency_code == $currency->code} selected="selected"{/if}>{$currency->code} ({$currency->name})</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Total price"}:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold price_total"></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Installments"}:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold">{$T_PROGRAM->price_step_units}</p>
                                            <!--
                                            <select class="select2-me form-control input-block-level" name="price_step_type" style="min-width: 150px;" data-search="false">
                                                {for $unit=1 to $T_PROGRAM->price_step_units}
                                                    <option value="{$unit}" {if $unit == $T_PAYMENT->price_step_units}selected="selected"{/if}>{$unit}</option>
                                                {/for}
                                            </select>
                                            -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Period"}:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static">{translateToken value=$T_PAYMENT->price_step_type}</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <form class="form-horizontal" action="#" id="submit_form" method="POST">
                                <div class="form-body">
                                    <h4 class="form-section">Your Info:</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Name:"}</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold">{$T_CURRENT_USER['name']} {$T_CURRENT_USER['surname']}</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Communication in English:"}</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold">
                                            {$T_USER_ATTRS['english_communication']}</p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Course:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold" data-display="username">{$T_PROGRAM->name}</p>
                                        </div>
                                    </div>


                                    {if $T_EXECUTION_IS_DONE}
                                        <div class="form-group">
                                            <label class="control-label col-md-3">English Test Grade:</label>
                                            <div class="col-md-4">
                                                <p class="form-control-static bold">{$T_EXECUTION->user_grade}</p>
                                            </div>
                                        </div>
                                    {/if}


                                    <h4 class="form-section">Payment Details</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Total price"}:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold price_total"></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Installments"}:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static bold">{$T_PROGRAM->price_step_units}</p>
                                            <!--
                                            <select class="select2-me form-control input-block-level" name="price_step_type" style="min-width: 150px;" data-search="false">
                                                {for $unit=1 to $T_PROGRAM->price_step_units}
                                                    <option value="{$unit}" {if $unit == $T_PAYMENT->price_step_units}selected="selected"{/if}>{$unit}</option>
                                                {/for}
                                            </select>
                                            -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{translateToken value="Period"}:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static">{translateToken value=$T_PAYMENT->price_step_type}</p>
                                        </div>
                                    </div>
                                </div>



                                    <div class="text-center">
                                        <div id="paypal-button"></div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        {/if}
                    </div>
                </div>
                <div class="form-actions">
                    <div class="text-center">
                        <a href="javascript:;" class="btn btn-default button-previous">
                            <i class="fa fa-angle-left"></i> Back 
                        </a>
                        <a href="javascript:;" class="btn btn-default button-next">
                            Continue <i class="fa fa-angle-right"></i>
                        </a>

                    </div>
                </div>
            </div>
        
    </div>
</div>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
_before_init_functions.push(function() {
    this.addResource("T_ENROLL_ID", {$T_ENROLL_ID} );
});
</script>
{/block}
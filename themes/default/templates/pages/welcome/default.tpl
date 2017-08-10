{extends file="layout/default.tpl"}
{block name="content"}                                    
<div class="portlet box dark-blue" id="form_wizard_1">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-exchange"></i>
            <span class="caption-subject bold uppercase"> Form Wizard -
                <span class="step-title"> Step 1 of 3 </span>
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
                                    <i class="fa fa-check"></i>Pre requisites</span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2" data-toggle="tab" class="step">
                                <span class="number"> 2 </span>
                                <span class="desc">
                                    <i class="fa fa-check"></i> Payments </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab3" data-toggle="tab" class="step">
                                <span class="number"> 3 </span>
                                <span class="desc">
                                    <i class="fa fa-check"></i> Confirm </span>
                            </a>
                        </li>
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

                        <div class="tab-pane active form-body" id="tab1">
                            <form class="" action="#" id="submit_form" method="POST">
                            <p class="help-text">In order to complete the enrollment, you need to check your.... </p>
                            <div class="form-group">
                                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                <label class="control-label">Communication in English</label>
                                <p class="form-control-static bold">{$T_USER_ATTRS['english_communication']}</p>

                                <!--
                                <select class="select2-me form-control" name="english_communication" data-rule-required="true" data-placeholder="{translateToken value="Communication in English"}">
                                    <option value="">{translateToken value="Select"}</option>
                                    <option value="Unable to Communicate">Unable to Communicate</option>
                                    <option value="Basic">Basic</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                    <option value="Native Speaker">Native Speaker</option>
                                </select>
                                -->
                            </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <form class="" action="#" id="submit_form" method="POST">
                                <div class="text-center">
                                    <a href="javascript:;" id="paypal-button" class="btn btn-outline" style="padding: 0; margin-top: 12px;"></a>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab3">
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="text-center">
                            <a href="javascript:;" class="btn default button-previous">
                                <i class="fa fa-angle-left"></i> Back 
                            </a>
                            <a href="javascript:;" class="btn btn-outline btn-primary do-test-action" data-test-id="92">
                                <i class="fa fa-list-ol "></i>
                                 Take the test
                            </a>
                            <!--
                            <a href="javascript:;" class="btn btn-outline btn-primary do-payment-action">
                                <i class="fa fa-list-ol "></i>
                                 Do the payment
                            </a>
                            -->



                    </div>
                </div>
            </div>
        
    </div>
</div>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>

{/block}
{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
    <div class="form-body">

        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Name"}</label>
                    <input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label">{translateToken value="Color"}</label>
                    <input name="color" value="" type="text" placeholder="Color" class="form-control" data-rule-required="true" />
                </div>

                <div class="clearfix"></div>
            </div>
        </div>

    </div>
    <div class="form-actions nobg">
        <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
    </div>
</form>
{/block}

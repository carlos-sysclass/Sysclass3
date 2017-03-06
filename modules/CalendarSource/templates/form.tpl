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
                    <select class="select2-me form-control" name="class_name" data-rule-required="1" data-placeholder="{translateToken value='Color'}" data-format-as="color-list">
                        <option value="">{translateToken value="Please select"}</option>
                        {foreach $T_COLORS as $color}
                            <option value="bg-{$color.info}" data-class="bg-{$color.info}">{$color.info}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">{translateToken value="Active"}</label>
                    <input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1"  data-value-unchecked="0" data-update-single="true">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
    <div class="form-actions nobg">
        <button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
    </div>
</form>
{/block}

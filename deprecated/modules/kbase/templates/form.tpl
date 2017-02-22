{extends file="layout/default.tpl"}
{block name="content"}
<div id="form-{$T_MODULE_ID}">
<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
    <div class="form-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
            </li>
            {if (isset($T_SECTION_TPL['questions-list']) &&  ($T_SECTION_TPL['questions-list']|@count > 0))}
            <li>
                <a href="#tab_1_2" data-toggle="tab">
                    <i class="fa fa-question"></i>
                    {translateToken value="Questions"}
                </a>
            </li>
            {/if}
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Title"}</label>
                    <input name="title" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
                </div>
                <div class="form-group">
                    <label class="control-label">{translateToken value="Approved"}</label>
                    <input type="checkbox" name="approved" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
                </div>
            </div>

            {if (isset($T_SECTION_TPL['questions-list']) &&  ($T_SECTION_TPL['questions-list']|@count > 0))}
                <div class="tab-pane fade in" id="tab_1_2">
                    {foreach $T_SECTION_TPL['questions-list'] as $template}
                        {include file=$template}
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>
    <div class="form-actions nobg">
        <button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
    </div>
</form>
</div>
{/block}



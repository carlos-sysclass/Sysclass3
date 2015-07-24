{extends file="layout/default.tpl"}
{block name="content"}
<div id="form-{$T_MODULE_ID}">
<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
    <div class="form-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
            </li>
            {if (isset($T_SECTION_TPL['advertising-banners']) &&  ($T_SECTION_TPL['advertising-banners']|@count > 0))}
            <li>
                <a href="#tab_1_2" data-toggle="tab">
                    <i class="fa fa-question"></i>
                    {translateToken value="Banners"}
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
                    <label class="control-label">{translateToken value="Placement"}</label>
                    <!--
                    <input type="hidden" class="select2-me form-control input-block-level" name="area_id" data-placeholder="{translateToken value='Knowledge Area'}" data-url="/module/areas/items/me/combo" data-minimum-results-for-search="4" />
                    -->
                    <select class="select2-me form-control" name="placement" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Knowledge Area'}">
                    {foreach $T_PLACEMENTS as $placement}
                            <option value="{$placement.id}">{$placement.name}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">{translateToken value="View Type"}</label>
                    <!--
                    <input type="hidden" class="select2-me form-control input-block-level" name="area_id" data-placeholder="{translateToken value='Knowledge Area'}" data-url="/module/areas/items/me/combo" data-minimum-results-for-search="4" />
                    -->
                    <select class="select2-me form-control" name="view_type" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Knowledge Area'}">
                    {foreach $T_VIEW_TYPES as $view_type}
                            <option value="{$view_type.id}">{$view_type.name}</option>
                    {/foreach}
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">{translateToken value="active"}</label>
                    <input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1" data-value-unchecked="0">
                </div>
            </div>

            {if (isset($T_SECTION_TPL['advertising-banners']) &&  ($T_SECTION_TPL['advertising-banners']|@count > 0))}
                <div class="tab-pane fade in" id="tab_1_2">
                    {foreach $T_SECTION_TPL['advertising-banners'] as $template}
                        {include file=$template}
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>
    <div class="form-actions nobg">
        <button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
    </div>
</form>
</div>
{/block}



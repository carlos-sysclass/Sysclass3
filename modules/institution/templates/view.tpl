{extends file="layout/default.tpl"}
{block name="content"}
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="backgrid-table">
            <table class="table table-striped table-bordered table-hover table-full-width data-table" id="view-{$T_MODULE_ID}">
                <thead>
                    <tr>
                        {foreach $T_MODULE_CONTEXT.datatable_fields as $field}
                            <th class="{$field.sClass} {$field.sType}">
                                {if !isset($field.label)}
                                    {$field.mData}
                                {else}
                                    {translateToken value=$field.label}
                                {/if}
                            </th>
                        {/foreach}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
{/block}

<div class="backgrid-table">
    <table class="table table-striped table-bordered table-hover table-full-width data-table" id="{$T_MODULE_ID}-table">
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

<script>
_before_init_functions.push(function() {
    $SC.addResource("{$T_MODULE_ID}_context", {$T_MODULE_CONTEXT|@json_encode nofilter});
});
{if $FORCE_INIT}
    _lazy_init_functions.push(function() {
        // START DATATABLE HERE
        var tableViewClass = $SC.module("utils.datatables").tableViewClass;
        var tableView = new tableViewClass({
            el : "#{$T_MODULE_ID}-table",
            datatable : {
                "sAjaxSource": "{$T_MODULE_CONTEXT.ajax_source}",
                "aoColumns": {$T_MODULE_CONTEXT.datatable_fields|@json_encode nofilter}
            }
        });

        $SC.addTable("view-{$T_MODULE_ID}", tableView);
    });
{/if}
</script>

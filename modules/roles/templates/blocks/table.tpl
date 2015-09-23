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
{if $FORCE_INIT}
<script>
_lazy_init_functions.push(function() {
    // START DATATABLE HERE
    var tableViewClass = $SC.module("utils.datatables").tableViewClass;
    var tableView = new tableViewClass({
        el : "#view-{$T_MODULE_ID}",
        datatable : {
            "sAjaxSource": "{$T_MODULE_CONTEXT.ajax_source}",
            "aoColumns": {$T_MODULE_CONTEXT.datatable_fields|@json_encode}
        }
    });

    $SC.addTable("view-{$T_MODULE_ID}", tableView);
});
</script>
{/if}

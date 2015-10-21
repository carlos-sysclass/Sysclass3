<div id="questions-select-modal" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-database"></i>
                    {translateToken value='Question\'s Database'}
                </h4>
            </div>
            <div class="modal-body">
                <div class="backgrid-table">
                    <table class="table table-striped table-bordered table-hover table-full-width data-table" id="questions-select-modal-table">
                        <thead>
                            <tr>
                                {foreach $T_QUESTIONS_SELECT_BLOCK_CONTEXT.datatable_fields as $field}
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="dialogs-organization-social" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-database"></i>
                    {translateToken value='Add Social Info'}
                </h4>
            </div>
            <div class="modal-body">
                <div class="row margin-top-20">
                    <div class="col-md-12">
                        <form id="form-social-info" role="form" class="form-validate" method="post" action="">

                        {include "`$smarty.current_dir`/../blocks/social.tpl" 
                        T_MODULE_CONTEXT=$T_ORGANIZATION_SOCIAL_DIALOG_CONTEXT
                        T_MODULE_ID="organization-social"
                        T_SHOW_LANGUAGE="language_code"}
                        </form>
                    </div>
                </div>
            </div>
            <!--
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
            -->
            <div class="modal-footer">
                <button class="btn btn-success save-action" type="submit">{translateToken value="Save Changes"}</button>
                <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

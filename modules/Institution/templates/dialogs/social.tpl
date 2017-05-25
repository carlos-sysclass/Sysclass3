<div id="dialogs-organization-social" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{translateToken value="Close"}">
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



                        {if (isset($T_SECTION_TPL['address']) &&    ($T_SECTION_TPL['address']|@count > 0))}
                            <div class="form-body">
                                <h5 class="form-section margin-bottom-10 margin-top-10">
                                    {translateToken value="Address"}
                                </h5>
                            </div>
                            {foreach $T_SECTION_TPL['address'] as $template}
                                {include file=$template}
                                <div class="clearfix"></div>
                            {/foreach}
                            
                        {/if}

                        <div class="form-body">
                            <h5 class="form-section margin-bottom-10 margin-top-10">
                                {translateToken value="Social info"}
                            </h5>
                            {include "`$smarty.current_dir`/../blocks/social.tpl" 
                            T_MODULE_CONTEXT=$T_ORGANIZATION_SOCIAL_DIALOG_CONTEXT
                            T_MODULE_ID="organization-social"
                            T_SHOW_LANGUAGE="locale_code"}
                        </div>                        


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
                <button class="btn btn-success save-action" type="submit">{translateToken value="Save changes"}</button>
                <button type="button" class="btn default" data-dismiss="modal">{translateToken value="Close"}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

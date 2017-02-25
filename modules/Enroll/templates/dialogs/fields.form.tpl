<div id="enroll-fields-dialog" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" class="form-horizontal form-validate" method="post" action="{$T_FORM_ACTION}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">{translateToken value="Fields details"}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label">{translateToken value="Field name"}</label>
                            <input 
                                type="hidden" 
                                class="select2-me form-control col-md-12" 
                                name="field_id"
                                data-placeholder="{translateToken value='Please, Select'}" 
                                data-url="/module/forms/items/fields" 
                            />
                        </div>

                        <div class="form-group">
                            <label class="control-label">{translateToken value="Required"}</label>
                            <input type="checkbox" name="required" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save-action">
                        {translateToken value="Save"}
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        {translateToken value="Cancel"}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

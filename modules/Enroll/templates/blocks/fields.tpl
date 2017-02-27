<div class="row">
    <div class="col-md-12" id="fields-create-container">
        <ul class="list-group ui-sortable margin-bottom-10 items-container">
        </ul>
        <!--
        <a class="btn btn-sm btn-primary btn-link add-period-action" href="javascript: void(0);">
            <i class="fa fa-plus"></i>
            {translateToken value="Create Period"}

        </a>
        -->
        <a class="btn btn-sm btn-link add-item-action" href="javascript: void(0);">
            <i class="fa fa-plus"></i>
            {translateToken value="Add Field"}

        </a>
    </div>
</div>

<script type="text/template" id="enroll-fields-item">

    <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag to reposition item"} ">
        <i class="fa fa-arrows"></i>
    </a>
    <a class="btn btn-sm btn-default tooltips view-item-detail" data-original-title="{translateToken value="Expand / Collpase"}">
        <i class="fa fa-angle-down"></i>
    </a>
    <!--
    <% if (_.has(model, 'field')) { %>
        <a href="#" class="btn btn-sm <% if (model.active == "0") { %>text-danger<% } %>">
            <%= model.field.name %>
        </a>
        <i><%= model.field.type.name %></i>
    <% } else { %>
        <span data-update="field.name">[Blank]</span>
        <b>Type: </b>
        <i data-update="field.type.name">[Unknown]</i>
    <% } %>
    -->

    <strong> Name: </strong>
    <i data-update="field.name">[Blank]</i>
    <strong> Type: </strong>
    <i data-update="field.type.name">[Blank]</i>
    <strong> Label: </strong>
    <i data-update="label">[Blank]</i>

    <div class="list-file-item-options">
        <% if (typeof model.field_id !== 'undefined') { %>
            <span class="btn btn-default btn-sm"><span class="counter">0</span> / <span class="total">0</span></span>
        <% } %>
        <a class="btn btn-sm btn-danger delete-item-action" href="javascript: void(0);"
            data-toggle="confirmation"
            data-original-title="{translateToken value="Are you sure?"}"
            data-placement="left"
            data-singleton="true"
            data-popout="true"
            data-btn-ok-icon="fa fa-trash"
            data-btn-ok-class="btn-sm btn-danger"
            data-btn-cancel-icon="fa fa-times"
            data-btn-cancel-class="btn-sm btn-warning"
            data-btn-ok-label="{translateToken value="Yes"}"
            data-btn-cancel-label="{translateToken value="No"}"
        >
            <i class="fa fa-trash"></i>
        </a>
    </div>
    <div class="detail-container form-horizontal">
        <h5 class="form-section no-margin margin-bottom-5">Details</h5>
        <form action="#">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">{translateToken value="Field Name"}</label>
                        <div class="col-md-9">
                            <select name="field_id" class="select2-me form-control col-md-12" data-placeholder="{translateToken value='Please select'}" data-rule-required="true">
                                {foreach $T_FORM_FIELDS as $field}
                                <option value="{$field.id}">{$field.name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">{translateToken value="Label Name"}</label>
                        <div class="col-md-9">
                            <input name="label" value="" type="text" placeholder="{translateToken value="Label"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">{translateToken value="Required"}</label>
                        <div class="col-md-9">
                            <input type="checkbox" name="required" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-value-unchecked="0" data-update-single="true" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions nobg">
                <button class="btn btn-success save-action" type="button">{translateToken value="Save changes"}</button>
            </div>
        </form>
    </div>
</script>
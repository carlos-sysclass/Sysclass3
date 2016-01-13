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

    <a href="#" class="btn btn-sm <% if (model.active == "0") { %>text-danger<% } %>">
        <%= model.field.name %>
    </a>
    <i><%= model.field.type.name %></i>

    <div class="list-file-item-options">
        <% if (typeof model.field_id !== 'undefined') { %>
            <span class="btn btn-default btn-sm"><span class="counter">0</span> / <span class="total">0</span></span>

            <a class="btn btn-sm btn-primary tooltips edit-item-detail" href="javascript: void(0);" data-original-title="{translateToken value="Edit Field Info"}">
                <i class="fa fa-edit"></i>
            </a>
            <input type="checkbox" name="active" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" <% if (model.active == "1") { %>checked="checked"<% } %> value="1">

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
    <div class="detail-container">
        <h5 class="form-section no-margin margin-bottom-5">Details</h5>
    </div>
</script>
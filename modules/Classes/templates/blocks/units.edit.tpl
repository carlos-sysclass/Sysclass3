<div id="block_lessons_edit" data-widget-id="lessons-edit-widget">
    <!--
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <div class="nav navbar-nav navbar-right">
                <a class="btn btn-success roadmap-add-season" href="#">Add a Season</a>
                <a class="btn btn-warning roadmap-add-class" href="#">Add a Class</a>
            </div>
        </div>
    </div>
    -->
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group ui-sortable margin-bottom-10">

            </ul>
            <a class="btn btn-sm btn-primary btn-link add-lesson-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                {translateToken value="Create unit"}
            </a>
            <a class="btn btn-sm btn-success btn-link add-test-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                {translateToken value="Create test"}
            </a>
            <!--
            <a class="btn btn-sm btn-warning btn-link import-item-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                {translateToken value="Import Lesson"}
            </a>
            -->
        </div>
    </div>
</div>

<script type="text/template" id="class-lesson-item-template">
    <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag to reposition item"} ">
        <i class="fa fa-arrows"></i>
    </a>
    <span class="btn btn-sm btn-circle btn-default disabled">
        <i class="fa fa-file"></i>
        Lesson
    </span>

    <a href="#" class="editable-me <% if (model.active == "0") { %>text-danger<% } %>"
        data-type="text"
        data-name="name"
        data-send="never"
        data-original-title="Lesson Name"
        data-inputclass="form-control"
    >
        <%= model.name %>
    </a>
    <div class="list-file-item-options">
        <% if (!_.isUndefined(model.id)) { %>
            <span class="btn btn-default btn-sm"><span class="counter">X</span> / <span class="total">X</span></span>

          <a class="btn btn-sm btn-primary tooltips" href="/module/lessons/edit/<%= model.id %>" data-original-title="Edit unit info">
                <i class="fa fa-edit"></i>
            </a>
            <!--
            <a class="btn btn-sm btn-info view-item-detail tooltips" href="javascript: void(0);" data-original-title="View details">
                <i class="fa fa-info-circle"></i>
            </a>
            -->

            <input type="checkbox" name="active-<%= model.id %>" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" <% if (model.active == "1") { %>checked="checked"<% } %> value="1">
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
        <% } else { %>
            <a class="btn btn-sm btn-danger delete-unsaved-item-action" href="javascript: void(0);"
            >
                <i class="fa fa-trash"></i>
            </a>

        <% } %>
    </div>
    <div class="detail-container">
        <h5 class="form-section no-margin">Details</h5>

    </div>
</script>

<script type="text/template" id="class-test-item-template">
    <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag to reposition item"} ">
        <i class="fa fa-arrows"></i>
    </a>

    <span class="btn btn-sm btn-circle btn-default disabled">
        <i class="fa fa-list-ol "></i>
        Test
    </span>
    <a href="#" class="editable-me <% if (model.active == "0") { %>text-danger<% } %>"
        data-type="text"
        data-name="name"
        data-send="never"
        data-original-title="Lesson Name"
        data-inputclass="form-control"
        <% if (typeof model.id !== 'undefined') { %>
        <% } else { %>
        <% } %>
    >
        <%= model.name %>
    </a>
    <div class="list-file-item-options">
        <% if (!_.isUndefined(model.id)) { %>
            <span class="btn btn-default btn-sm"><span class="counter">X</span> / <span class="total">X</span></span>

            <a class="btn btn-sm btn-primary tooltips" href="/module/tests/edit/<%= model.id %>" data-original-title="Edit test info">
                <i class="fa fa-edit"></i>
            </a>
            <!--
            <a class="btn btn-sm btn-info view-item-detail tooltips" href="javascript: void(0);" data-original-title="View details">
                <i class="fa fa-info-circle"></i>
            </a>
            -->

            <input type="checkbox" name="active-<%= model.id %>" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" <% if (model.active == "1") { %>checked="checked"<% } %> value="1">


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
        <% } else { %>
            <a class="btn btn-sm btn-danger delete-unsaved-item-action" href="javascript: void(0);"
            >
                <i class="fa fa-trash"></i>
            </a>
        <% } %>
    </div>
    <div class="detail-container">
        <h5 class="form-section no-margin">Details</h5>

    </div>
</script>


<script type="text/template" id="lessons-edit-add-item">
    <div class="input-group">
        <span class="input-group-btn" style="vertical-align: top;">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-check"></i>
            </button>
        </span>
        <input name="new-lesson-input" value="" type="text" placeholder="Type the unit name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
    </div>
</script>


{if $FORCE_INIT}
<script>
/*
_lazy_init_functions.push(function() {
    //console.warn({$T_MODULE_CONTEXT|@json_encode});
});
*/
</script>
{/if}


{if (isset($T_SECTION_TPL['lessons_content']) &&  ($T_SECTION_TPL['lessons_content']|@count > 0))}
    {foreach $T_SECTION_TPL['lessons_content'] as $template}
        {*include file=$template*}
    {/foreach}
{/if}


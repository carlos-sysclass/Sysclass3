<div id="block_roadmap" data-widget-id="roadmap-classes-widget" data-course-id="{$T_ENTITY_ID}">
    <!--
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                    <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Allow the user to select the the classes order'}">
                        <i class="fa fa-question"></i>
                    </span>
                {translateToken value="Enable user selection"}

                </label>
                <input type="checkbox" name="has_student_selection" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                    <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Provides multiple roadmaps based on course enrollment dates'}">
                        <i class="fa fa-question"></i>
                    </span>
                    {translateToken value="Enable Course Periods"}
                    <span class="badge badge-info">BETA</span>
                </label>

                <input type="checkbox" name="has_periods" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                    <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Provides multiple roadmaps based on course enrollment dates'}">
                        <i class="fa fa-question"></i>
                    </span>
                    {translateToken value="Enable Course Groupings"}
                    <span class="badge badge-info">BETA</span>
                </label>

                <input type="checkbox" name="has_grouping" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
            </div>
        </div>
    </div>
    -->
    <h5 class="form-section margin-bottom-10 margin-top-10">
        <i class="fa fa-calendar"></i>
        {translateToken value="Courses avaliable"}
    </h5>

    <div class="row">
        <div class="col-md-12">
            <!--
            <ul class="list-group ui-sortable margin-bottom-10 items-container">
            </ul>
            -->
            <div class="margin-bottom-10 items-container">

            </div>
            <div class="no-period-container"></div>
            <a class="btn btn-sm btn-link btn-link add-block-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                {translateToken value="Create Block"}
            </a>
        </div>
    </div>

    <!--
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{translateToken value="Course Grouping"}
                    <a class="btn btn-default btn-sm tooltips roadmap-add-grouping" data-original-title="{translateToken value="Add a new course grouping"} "><i class="fa fa-plus"></i></a>
                </label>
                <select class="select2-me form-control" name="roadmap_grouping_id" data-placeholder="{translateToken value='Course Grouping'}">
                    <option value="-1">All Groupings</option>
                {foreach $T_ROADMAP_COURSES_GROUPING as $item}
                        <option value="{$item.id}">{$item.name}</option>
                {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            {translateToken value="Roadmap for course grouping: "}<strong data-update="roadmap_grouping">All</strong>
            <div class="panel-buttons panel-buttons-sm">
                <a class="btn btn-success btn-sm roadmap-add-season" href="#">Add a Season</a>
                <a class="btn btn-warning btn-sm roadmap-add-class" href="#">Add a Class</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="block_roadmap-accordion">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="block_roadmap-all_lessons-accordion">
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
</div>

<script type="text/template" id="period-edit-item">
    <div class="panel-heading">
        <% if (!_.isEmpty(data.id)) { %>
        <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag to reposition item"} ">
            <i class="fa fa-arrows"></i>
        </a>
        <% } %>
        <a class="btn btn-sm btn-default tooltips toogle-visible-item" data-original-title="{translateToken value="Expand / Collpase"}">
            <i class="fa fa-angle-up"></i>
        </a>
        <% if (!_.isEmpty(data.id)) { %>
        <a href="javascript:void(0);" class="editable-me <% if (data.active == "0") { %>text-danger<% } %>"
            data-type="text"
            data-name="name"
            data-send="never"
            data-original-title="Period Name"
            data-inputclass="form-control"
        >
            <%= data.name %>
        </a>
        <% } else { %>
            <%= data.name %>
        <% } %>
        <!--
        <% if (typeof data.max_classes == 'undefined' || data.max_classes <= 0) { %>
            <span class="size-counter"><%= _.size(data.classes) %></span> {translateToken value="total classes"}
        <% } else { %>
            <span class="size-counter"><%= _.size(data.classes) %></span> / <%= max_classes %> {translateToken value="classes selected"}
        <% } %>
        -->
        <div class="list-file-item-options">
            <% if (!_.isEmpty(data.id)) { %>
                <span class="btn btn-default btn-sm"><span class="period-counter">X</span> / <span class="period-total">X</span></span>

                <input type="checkbox" name="active" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" <% if (data.active == "1") { %>checked="checked"<% } %> value="1">


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
            <% } %>
        </div>

        <!--
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= data.id %>">
            <i class="icon-minus"></i>
        </a>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= data.id %>">
            <i class="fa fa-arrows"></i>
        </a>
        -->
    </div>

    <div id="season-<%= data.id %>" class="panel-body in subitems-container">
        <ul class="list-group ui-sortable  margin-bottom-10"></ul>
        <div class="cleafix"></div>
        <a class="btn btn-sm btn-link add-item-action" href="javascript: void(0);">
            <i class="fa fa-plus"></i>
            {translateToken value="Create Class"}
        </a>
    </div>

</script>

<script type="text/template" id="classes-edit-item">
<% console.warn(data) %>
    <a href="#" class="btn btn-sm editable-me <% if (data.active == "0") { %>text-danger<% } %>"
        data-type="text"
        data-name="name"
        data-send="never"
        data-original-title="Class Name"
        data-inputclass="form-control"
    >
        <%= data.name %>
    </a>
    <div class="list-file-item-options">
        <% if (typeof data.id !== 'undefined') { %>
            <span class="btn btn-default btn-sm"><span class="counter">X</span> / <span class="total">X</span></span>

            <a class="btn btn-sm btn-primary tooltips" href="/module/classes/edit/<%= data.id %>" data-original-title="Edit class info">
                <i class="fa fa-edit"></i>
            </a>

            <a class="btn btn-sm btn-info view-item-detail tooltips" href="javascript: void(0);" data-original-title="View details">
                <i class="fa fa-info-circle"></i>
            </a>

            <input type="checkbox" name="active" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" <% if (data.active == "1") { %>checked="checked"<% } %> value="1">

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
    <% //if (_.has(data, 'class')) { %>
    <div class="detail-container">
        <h5 class="form-section no-margin margin-bottom-5">Details</h5>
        <% if (!_.isEmpty(data.description)) { %>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                    <span>{translateToken value="Description"}</span>
                    <p class="">
                        <strong><%= data.description %></strong>
                    </p>
            </div>
        </div>
        <hr class="no-margin margin-bottom-5"></hr>
        <% } %>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <span>{translateToken value="Total Units"}</span>
                <% if (_.size(data.units) == 0) { %>
                    <strong class="text-danger pull-right">0</strong>
                <% } else { %>
                    <strong class="text-primary pull-right"><%= _.size(data.units) %></strong>
                <% } %>
            </div>
            <% if (_.isObject(data.professor)) { %>
            <div class="col-md-6 col-sm-6">
                <span>{translateToken value="Instructor"}</span>
                <strong class="text-primary pull-right"><%= sprintf('%(name)s %(surname)s', data.professor) %></strong>
            </div>
            <% } else { %>
                <div class="col-md-6 col-sm-6">
                    <p>
                        <span>{translateToken value="Instructors"}</span>
                        <strong class="text-danger pull-right">{translateToken value="No Instructors"}</strong>
                    </p>
                </div>
            <% } %>
        </div>

    <% //} %>
</script>
<!--
<script type="text/template" id="tab_roadmap-season-template">
    <div class="panel-heading">
        <a class="btn btn-xs btn-default tooltips drag-handler" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="fa fa-arrows"></i>
        </a>
        <a href="#season-<%= id %>"> <%= name %> </a>
        <small>
        <% if (typeof max_classes == 'undefined') { %>
            <span class="size-counter"><%= _.size(classes) %></span> {translateToken value="total classes"}
        <% } else { %>
            <span class="size-counter"><%= _.size(classes) %></span> / <%= max_classes %> {translateToken value="classes selected"}
        <% } %>
        </small>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="icon-minus"></i>
        </a>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="fa fa-arrows"></i>
        </a>
    </div>

    <div id="season-<%= id %>" class="panel-body in">
        <ul class="list-group <% if (_.size(classes)== 0) { %>empty-list-group<% } %> floated-list">
            <% if (_.size(classes)== 0) { %>
            <% } else { %>
                <% _.each(classes, function (classe, i) { %>
                <li class="list-group-item draggable btn btn-block btn-default green-stripe float-left" data-class-id="<%= classe.id %>">
                    <p class="list-group-item-text float-left">
                        <a href="#class-tab" class="class-change-action" data-ref-id="<%= classe.id %>" >
                            <%= classe.name %>
                        </a>
                    </p>
                    <div class="float-right list-group-item-actions">
                        <a class="btn btn-xs btn-primary " href="#season-<%= id %>">
                            <i class="icon-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger " href="#season-<%= id %>">
                            <i class="icon-trash"></i>
                        </a>
                    </div>
                </li>
                <% }) %>
            <% } %>
        </ul>
    </div>
    <div class="clearfix"></div>
</script>
<script type="text/template" id="tab_roadmap-season-template2">
    <h5>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="fa fa-arrows"></i>
        </a>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="icon-minus"></i>
        </a>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="fa fa-arrows"></i>
        </a>
        <a href="#season-<%= id %>"> <%= name %> </a>
        <small>
        <% if (typeof max_classes == 'undefined') { %>
            <span class="size-counter"><%= _.size(classes) %></span> {translateToken value="total classes"}
        <% } else { %>
            <span class="size-counter"><%= _.size(classes) %></span> / <%= max_classes %> {translateToken value="classes selected"}
        <% } %>
        </small>
    </h5>

    <div id="season-<%= id %>" class="in">
        <ul class="list-group <% if (_.size(classes)== 0) { %>empty-list-group<% } %> floated-list">
            <% if (_.size(classes)== 0) { %>
            <% } else { %>
                <% _.each(classes, function (classe, i) { %>
                <li class="list-group-item draggable btn btn-block btn-default green-stripe float-left" data-class-id="<%= classe.id %>">
                    <p class="list-group-item-text float-left">
                        <a href="#class-tab" class="class-change-action" data-ref-id="<%= classe.id %>" >
                            <%= classe.name %>
                        </a>
                    </p>
                    <div class="float-right list-group-item-actions">
                        <a class="btn btn-xs btn-primary " href="#season-<%= id %>">
                            <i class="icon-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger " href="#season-<%= id %>">
                            <i class="icon-trash"></i>
                        </a>
                    </div>
                </li>
                <% }) %>
            <% } %>
        </ul>
    </div>
    <div class="clearfix"></div>
</script>
-->
{if $FORCE_INIT}
<script>
_lazy_init_functions.push(function() {
    //console.warn({$T_MODULE_CONTEXT|@json_encode});
});
</script>
{/if}

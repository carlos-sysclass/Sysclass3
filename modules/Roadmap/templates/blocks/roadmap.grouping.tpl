<div id="block_roadmap" data-widget-id="roadmap-grouping-widget" data-course-id="{$T_ENTITY_ID}">
    <div class="row">
        <!--
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                    <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Provides multiple roadmaps based on course registration dates'}">
                        <i class="fa fa-question-circle"></i>
                    </span>
                    {translateToken value="Enable course periods"}
                    <span class="badge badge-info">BETA</span>
                </label>

                <input type="checkbox" name="has_periods" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
            </div>
        </div>
        -->
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">
                    <span class="badge badge-warning tooltips" data-original-title="{translateToken value='Provides rules to control user registration'}">
                        <i class="fa fa-question-circle"></i>
                    </span>
                    {translateToken value="Enable course group"}
                    <span class="badge badge-info">BETA</span>
                </label>

                <input type="checkbox" name="has_grouping" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group ui-sortable margin-bottom-10 items-container">
            </ul>
            <!--
            <a class="btn btn-sm btn-primary btn-link add-period-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                {translateToken value="Create period"}

            </a>
            -->
            <a class="btn btn-sm btn-link add-item-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                {translateToken value="Create groups"}

            </a>
        </div>
    </div>

    <!--
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{translateToken value="Course groups"}
                    <a class="btn btn-default btn-sm tooltips roadmap-add-groups" data-original-title="{translateToken value="Add a new course groups"} "><i class="fa fa-plus"></i></a>
                </label>
                <select class="select2-me form-control" name="roadmap_grouping_id" data-placeholder="{translateToken value='Course groups'}">
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
            {translateToken value="Roadmap for course groups: "}<strong data-update="roadmap_grouping">All</strong>
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

<script type="text/template" id="grouping-edit-item">

    <a href="#" class="editable-me <% if (data.active == "0") { %>text-danger<% } %>"
        data-type="text"
        data-name="class.name"
        data-send="never"
        data-original-title="Class Name"
        data-inputclass="form-control"

    >
        <%= data.name %>

    </a>
    <% if (data.start_date != 0 || data.end_date != 0) { %>
    <i>
        <small>
        <% if (data.start_date != 0) { %>
            {translateToken value="From"}
            <strong><%= $SC.module("views").formatValue(data.start_date, "date", "unix-timestamp") %></strong>
        <% } %>
        <% if (data.end_date != 0) { %>
            {translateToken value="To"}
            <strong><%= $SC.module("views").formatValue(data.end_date, "date", "unix-timestamp") %></strong>
        <% } %>
        </small>
    </i>
    <% } else { %>
        <i>
            <small class="text-danger"><strong>No dates Defined</strong></small>
        </i>
    <% } %>

    <div class="list-file-item-options">
        <% if (typeof data.id !== 'undefined') { %>
            <span class="btn btn-default btn-sm"><span class="counter">0</span> / <span class="total">0</span></span>

            <a class="btn btn-sm btn-primary tooltips edit-item-detail" href="javascript: void(0);" data-original-title="Edit groups info">
                <i class="fa fa-edit"></i>
            </a>
            <!--
            <a class="btn btn-sm btn-info view-item-detail tooltips" href="javascript: void(0);" data-original-title="Details">
                <i class="fa fa-info-circle"></i>
            </a>
            -->
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
    <% if (_.has(data, 'class')) { %>
    <div class="detail-container">
        <h5 class="form-section no-margin margin-bottom-5">Details</h5>
        <% if (!_.isEmpty(data.class.description)) { %>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                    <span>{translateToken value="Description"}</span>
                    <p class="">
                        <strong><%= data.class.description %></strong>
                    </p>
            </div>
        </div>
        <hr class="no-margin margin-bottom-5"></hr>
        <% } %>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <p>
                    <span>{translateToken value="Total Lessons"}</span>
                    <% if (data.class.total_lessons == 0) { %>
                        <strong class="text-danger pull-right"><%= data.class.total_lessons %></strong>
                    <% } else { %>
                        <strong class="text-primary pull-right"><%= data.class.total_lessons %></strong>
                    <% } %>

                </p>
            </div>
            <% if (_.isObject(data.class.instructors)) { %>
            <div class="col-md-6 col-sm-6">
                <div>
                    <span>{translateToken value="Instructor"}</span>
                    <ul class="pull-right">
                        <%
                            var instructors = _.map(data.class.instructors, function(data) {
                                return _.pick(data, "name", "surname");
                            });
                        %>
                        <% _.each(instructors, function(item) { %>
                            <li class="text-primary"><strong><% print(item.name  + " " + item.surname); %></strong></li>
                        <% }); %>
                    </ul>
                </div>
            </div>
            <% } else { %>
                <div class="col-md-6 col-sm-6">
                    <p>
                        <span>{translateToken value="Instructor"}</span>
                        <strong class="text-danger pull-right">{translateToken value="No Instructor defined"}</strong>
                    </p>
                </div>
            <% } %>
        </div>

    <% } %>
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

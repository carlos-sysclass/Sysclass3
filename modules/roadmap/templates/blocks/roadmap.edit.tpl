<div id="block_roadmap">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{translateToken value="Course Grouping"}
                    <a class="btn btn-default btn-sm tooltips roadmap-add-grouping" data-original-title="{translateToken value="Add a new Course Grouping"} "><i class="fa fa-plus"></i></a>
                </label>
                <select class="select2-me form-control" name="roadmap_grouping_id" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Course Grouping'}">
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
            {translateToken value="Roadmap for Course Grouping: "}<strong data-update="roadmap_grouping">All</strong>
            <div class="panel-buttons panel-buttons-sm">
                <a class="btn btn-success btn-sm roadmap-add-season" href="#">Add a Season</a>
                <a class="btn btn-warning btn-sm roadmap-add-class" href="#">Add a Class</a>
            </div>
        </div>
        <div class="panel-body">
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
</div>
<script type="text/template" id="tab_roadmap-season-template">
    <div class="panel-heading">
        <a href="#season-<%= id %>"> <%= name %> </a>
        <small>
        <% if (typeof max_classes == 'undefined') { %>
            <span class="size-counter"><%= _.size(classes) %></span> {translateToken value="total classes"}
        <% } else { %>
            <span class="size-counter"><%= _.size(classes) %></span> / <%= max_classes %> {translateToken value="classes selected"}
        <% } %>
        </small>
        <a class="btn btn-xs btn-default tooltips" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="fa fa-arrows"></i>
        </a>
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
{if $FORCE_INIT}
<script>
_lazy_init_functions.push(function() {
    //console.warn({$T_MODULE_CONTEXT|@json_encode});
});
</script>
{/if}

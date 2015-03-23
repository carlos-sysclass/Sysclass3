<div id="block_roadmap">
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
        <!--
            <a href="#" class="navbar-brand disabled">
                <strong>{translateToken value="You're in:"} </strong>
            </a>
            <a href="#" class="navbar-brand course-title">
                 {translateToken value="Course"}
            </a>
        -->
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <div class="nav navbar-nav navbar-right">
                <a class="btn btn-success roadmap-add-season" href="#">Add a Season</a>
                <a class="btn btn-warning roadmap-add-class" href="#">Add a Class</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">

<!--    <div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent"> -->
            <div id="block_roadmap-accordion">
            </div>
<!--    </div> -->
        </div>
        <div class="col-md-4">
            <div id="block_roadmap-all_lessons-accordion">
            </div>
        </div>
    </div>

</div>
<script type="text/template" id="tab_roadmap-season-template">
    <h5>
        <a class="btn btn-xs btn-default" data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>">
            <i class="icon-minus"></i>
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
</script>
{if $FORCE_INIT}
<script>
_lazy_init_functions.push(function() {
    //console.warn({$T_MODULE_CONTEXT|@json_encode});
});
</script>
{/if}

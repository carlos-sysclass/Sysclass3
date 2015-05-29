<div id="block_lessons_edit">
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
<!--    <div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent"> -->
            <ul class="list-group ui-sortable margin-bottom-10">

            </ul>
            <a class="btn btn-sm btn-primary btn-link add-item-action" href="javascript: void(0);">
                <i class="fa fa-plus"></i>
                Create Lesson
            </a>


<!--    </div> -->
        </div>
    </div>
</div>

<script type="text/template" id="lessons-edit-item">
    <li class="list-file-item draggable <% if (data.active == "1") { %>green-stripe<% } else { %>red-stripe<% } %>">
        <a href="<% if (typeof data.id !== 'undefined') { %>/module/lessons/edit/<%= data.id %><% } else { %>javascript: void(0);<% } %>"><%= data.name %></a>
        <div class="list-file-item-options">
            <% if (typeof data.id !== 'undefined') { %>
                <% if (data.active == "1") { %>
                    <a class="btn btn-sm btn-danger remove-file-action" data-file-id="<%= data.id %>" href="javascript: void(0);">
                        <i class="fa fa-trash"></i>
                    </a>
                <% } else { %>
                    <a class="btn btn-sm btn-success remove-file-action" data-file-id="<%= data.id %>" href="javascript: void(0);">
                        <i class="fa fa-check"></i>
                    </a>
                <% } %>
            <% } %>

        </div>
    </li>
</script>


<script type="text/template" id="lessons-edit-add-item">
    <li class="new-lesson-input-container">
        <div class="input-group">
            <span class="input-group-btn" style="vertical-align: top;">
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-check"></i>
                </button>
            </span>
            <input name="new-lesson-input" value="" type="text" placeholder="Type the lesson name" class="form-control" data-rule-required="true" data-rule-minlength="3" />

        </div>
    </li>
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
        {include file=$template}
    {/foreach}
{/if}


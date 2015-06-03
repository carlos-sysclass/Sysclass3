<div id="block_lessons_edit" data-widget-id="lessons-edit-widget" data-class-id="227">
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
    <a href="#" class="editable-me <% if (data.active == "0") { %>text-danger<% } %>"
        data-type="text"
        data-name="name"
        <% if (typeof data.id !== 'undefined') { %>
        data-url="/module/lessons/item/me/<%= data.id %>"
        data-pk="<%= data.id %>"
        data-method="put"
        <% } else { %>
        data-url="/module/lessons/item/me"
        data-pk=""
        data-method="post"
        <% } %>
        data-original-title="Enter username"
        data-inputclass="form-control"
    >
        <%= data.name %>
    </a>
    <div class="list-file-item-options">
        <% if (typeof data.id !== 'undefined') { %>
            <a class="btn btn-sm btn-primary tooltips" href="/module/lessons/edit/<%= data.id %>" data-original-title="Edit lesson info">
                <i class="fa fa-edit"></i>
            </a>
            <% if (data.active == "1") { %>
                <a class="btn btn-sm btn-warning disable-item-action" href="javascript: void(0);">
                    <i class="fa fa-times"></i>
                </a>
            <% } else { %>
                <a class="btn btn-sm btn-success enable-item-action" href="javascript: void(0);">
                    <i class="fa fa-check"></i>
                </a>
            <% } %>
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
</script>


<script type="text/template" id="lessons-edit-add-item">
    <div class="input-group">
        <span class="input-group-btn" style="vertical-align: top;">
            <button type="button" class="btn btn-primary">
                <i class="fa fa-check"></i>
            </button>
        </span>
        <input name="new-lesson-input" value="" type="text" placeholder="Type the lesson name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
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


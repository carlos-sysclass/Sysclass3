<div class="form-group" id="content-timeline">
    <!--
    <ul class="list-group ui-sortable margin-top-20">
    </ul>
    -->
    <div class="pull-left">

        <!--
        <span class="btn btn-sm btn-link timeline-addurl">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add Url"}</span>
        </span>
        -->        
        <span class="btn btn-sm btn-link fileinput-button fileupload" data-fileupload-url="/module/dropbox/upload/lesson">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add File"}</span>
            <input type="file" name="files[]">
        </span>
        <span class="btn btn-sm btn-link timeline-addlibrary" data-library-path="library">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add from library"}</span>
        </span>
        <!--
        <span class="btn btn-sm btn-link timeline-addtext">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add Text"}</span>
        </span>
        
        <span class="btn btn-sm btn-link timeline-addexercise">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add Exercises"}</span>
        </span>
        -->
    </div>
    <div class="pull-right">
        <a class="btn btn-sm btn-primary timeline-expand" href="javascript:void(0);">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Expand All"}</span>
        </a>
        <a class="btn btn-sm btn-warning timeline-collapse" href="javascript:void(0);">
            <i class="fa fa-minus"></i>
            <span>{translateToken value="Collapse All"}</span>
        </a>
        <!--
        <a class="btn btn-sm btn-success timeline-collapse tooltips" href="javascript:void(0);" data-original-title="{translateToken value="Copy content from another lesson"}">
            <i class="fa fa-copy"></i>
            <span>{translateToken value="Copy"}</span>
        </a>
        -->
    </div>
    <div class="clearfix"></div>



    <!-- 
    <div class="timeline content-timeline-items">
    </div> -->


</div>

<style>
.content-video-item {
    min-height: 293px;
}
.content-video-empty {
    text-align: center;
}
.content-video-empty-item {
    background: #eee none repeat scroll 0 0;
    border: 1px dashed #ccc;
    display: block;
    min-height: 288px;
    position: relative;
}
.content-video-empty-item a {
    bottom: 44%;
    display: block;
    font-size: 1.5em;
    position: absolute;
    top: 44%;
    width: 100%;
}
</style>


<div id="content-video-widget" data-widget-id="unit-conntent-video-widget">
    <div class="row">
        <div class="col-md-12">
            <div class="unit-content-video-block">
                <div class="panel panel-default draggable">
                    <div class="panel-heading">
                        <a class="btn btn-sm btn-default tooltips toogle-visible-item" data-original-title="{translateToken value="Expand / Collpase"}">
                            <i class="fa fa-angle-up"></i>
                        </a>

                            {translateToken value="Video"}
                        <div class="list-file-item-options">
                            <!--
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
                            -->
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
                    <div id="content-video-widget-container" class="panel-body in subitems-container">
                        <div class="row content-videos">
                            <div class="cleafix"></div>
                        </div>
                        <h5 class="form-section margin-bottom-10 margin-top-10">
                            <i class="fa fa-language"></i>
                            {translateToken value="Description"}
                            <a href="javascript: void(0);" class="btn btn-sm btn-success pull-right">
                                {translateToken value="Save"}
                            </a>
                        </h5>
                        <div class="row content-videos-metadata">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                    <label class="control-label">{translateToken value="Description"}</label>
                                    <input class="form-control" name="title" value="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{translateToken value="Tags"}</label>
                                    <input type="hidden" class="select2-me form-control" name="tags" data-placeholder="{translateToken value="Choose tags"}" multiple="true" data-tags="true" />
                                </div>
                            </div>
                        </div>
                        <div class="content-videos-translation">
                            <h5 class="form-section margin-bottom-10 margin-top-10">
                                <i class="fa fa-language"></i>
                                {translateToken value="Subtitles"}

                                <div class="dropdown pull-right">
                                    <a href="javascript: void(0);" data-close-others="true" data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-primary">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        {translateToken value="Add a Legend"}
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript: void(0);" class="dialogs-messages-send-action" data-mode="user">
                                                <i class="fa fa-book" aria-hidden="true"></i>
                                            {translateToken value="From media library"}
                                            </a>              
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="dialogs-messages-send-action" data-mode="user">
                                                <i class="fa fa-language" aria-hidden="true"></i>
                                            {translateToken value="Automatic Translation"}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    No subtitles found
                                </div>
                                <!--
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Original Language"}</label>
                                        <div class="input-group ">
                                            <select class="select2-me form-control" name="related[lang_from]" data-placeholder="{translateToken value="Choose language"}">
                                                {foreach $T_LANGUAGES as $lang}
                                                    <option value="{$lang.code}" <% if (model.language_code == '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                                                {/foreach}
                                            </select>
                                            <div class="input-group-btn">
                                                <button class="btn green save-file-content" type="button">
                                                    {translateToken value="Save"}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Automatic translate to"}</label>
                                        <div class="input-group ">
                                            <select class="select2-me form-control" name="related[lang_to]" data-placeholder="{translateToken value="Choose language"}">
                                                {foreach $T_LANGUAGES as $lang}
                                                    <option value="{$lang.code}" <% if (model.language_code != '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                                                {/foreach}
                                            </select>
                                            <div class="input-group-btn">
                                                <button class="btn green translate-file-content" type="button">
                                                    <i class="fa fa-language"></i>
                                                    {translateToken value="Translate"}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>

                        <div class="content-videos-poster">
                            <h5 class="form-section margin-bottom-10 margin-top-10">
                                <i class="fa fa-language"></i>
                                {translateToken value="Video Poster"}

                                <a href="javascript: void(0);" class="btn btn-sm btn-primary pull-right">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    {translateToken value="Add a Poster"}
                                </a>
                            </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    No subtitles found
                                </div>
                                <!--
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Original Language"}</label>
                                        <div class="input-group ">
                                            <select class="select2-me form-control" name="related[lang_from]" data-placeholder="{translateToken value="Choose language"}">
                                                {foreach $T_LANGUAGES as $lang}
                                                    <option value="{$lang.code}" <% if (model.language_code == '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                                                {/foreach}
                                            </select>
                                            <div class="input-group-btn">
                                                <button class="btn green save-file-content" type="button">
                                                    {translateToken value="Save"}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{translateToken value="Automatic translate to"}</label>
                                        <div class="input-group ">
                                            <select class="select2-me form-control" name="related[lang_to]" data-placeholder="{translateToken value="Choose language"}">
                                                {foreach $T_LANGUAGES as $lang}
                                                    <option value="{$lang.code}" <% if (model.language_code != '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                                                {/foreach}
                                            </select>
                                            <div class="input-group-btn">
                                                <button class="btn green translate-file-content" type="button">
                                                    <i class="fa fa-language"></i>
                                                    {translateToken value="Translate"}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>


                        <!-- <ul class="list-group ui-sortable margin-bottom-10"></ul> -->
                        
                        <!--
                        <a class="btn btn-sm btn-link add-item-action" href="javascript: void(0);">
                            <i class="fa fa-plus"></i>
                                {translateToken value="Create Class"}
                        </a>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
<script type="text/template" id="content-video-empty">
    <div class="content-video-empty-item">
        <a href="javascript:void(0)">
            <i class="fa fa-plus-circle"></i>
            {translateToken value="Add a new video"}
        </a>
    </div>
</script>

<script type="text/template" id="content-video-item">
    <% var file = model.file %>
    <video src="<%= file.url %>" style="max-width: 100%;" controls="true" class="videojs">
    </video>
</script>




<script type="text/template" id="text-timeline-item">
    <div class="timeline-badge">
        <div class="timeline-icon">
            <i class="fa fa-align-left"></i>
        </div>
    </div>
    <div class="timeline-body">
        <div class="timeline-body-arrow"></div>
        <div class="timeline-body-head">
            <div class="timeline-body-head-caption">
                <span class="timeline-body-alerttitle text-primary">
                    <span class="btn btn-sm btn-default hidden-sm hidden-md hidden-lg">
                        <i class="fa fa-file-video-o"></i>
                    </span>
                     <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
                        <i class="fa fa-arrows"></i>
                    </span>
                    Content
                </span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default text-loading hidden">
                    <i class="fa fa-spinner fa-spin"></i>
                    {translateToken value="Saving"}
                </span>
                <a class="btn btn-sm btn-warning edit-text-content hidden" href="javascript: void(0);">
                    <i class="fa fa-edit"></i>
                    Edit
                </a>

                <a class="btn btn-sm btn-primary save-text-content" href="javascript: void(0);">
                    <i class="fa fa-save"></i>
                    Save
                </a>
                <a class="btn btn-sm btn-danger delete-text-content" href="javascript: void(0);"
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
        </div>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper">
                <div class="form-group wysihtml5-container">
                    <textarea class="wysihtml5 form-control placeholder-no-fix" rows="6" placeholder="{translateToken value="Put your content here"}"><%= info %></textarea>
                </div>
                <div class="preview hidden">

                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="url-timeline-item">
    <div class="timeline-badge">
        <div class="timeline-icon">
            <i class="fa fa-align-left"></i>
        </div>
    </div>
    <div class="timeline-body">
        <div class="timeline-body-arrow"></div>
        <div class="timeline-body-head">
            <div class="timeline-body-head-caption">
                <span class="timeline-body-alerttitle text-primary">
                    <span class="btn btn-sm btn-default hidden-sm hidden-md hidden-lg">
                        <i class="fa fa-file-video-o"></i>
                    </span>
                     <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
                        <i class="fa fa-arrows"></i>
                    </span>
                    {translateToken value="Insert a url"}
                </span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default text-loading hidden">
                    <i class="fa fa-spinner fa-spin"></i>
                    {translateToken value="Saving"}
                </span>

                <a class="btn btn-sm btn-primary save-url-content" href="javascript: void(0);">
                    <i class="fa fa-save"></i>
                    Save
                </a>
                <a class="btn btn-sm btn-danger delete-url-content" href="javascript: void(0);"
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
        </div>
        <div class="clearfix"></div>
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper">
                <div class="form-group">
                    <input value="<%= model.content %>" type="text" placeholder="{translateToken value="Insert your own URL, from youtube, s3, etc..."}" class="form-control" data-rule-required="true" data-rule-minlength="3" data-rule-url="true" />
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="exercise-timeline-item">
    <div class="timeline-badge">
        <div class="timeline-icon">
            <i class="fa fa-list-ol"></i>
        </div>
    </div>
    <div class="timeline-body">
        <div class="timeline-body-arrow"></div>
        <div class="timeline-body-head">
            <div class="timeline-body-head-caption">
                <span class="timeline-body-alerttitle text-primary">
                    <span class="btn btn-sm btn-default hidden-sm hidden-md hidden-lg">
                           <i class="fa fa-file-video-o"></i>
                    </span>
                     <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
                        <i class="fa fa-arrows"></i>
                    </span>
                    Exercises
                </span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default text-loading hidden">
                    <i class="fa fa-spinner fa-spin"></i>
                    {translateToken value="Saving"}
                </span>
                <!--
                <div class="btn-group">
                    <button data-close-others="true" data-hover="dropdown" data-toggle="dropdown" type="button" class="btn btn-sm btn-primary add-question dropdown-toggle">
                        <i class="fa fa-plus"></i>
                        {translateToken value="Create a new Question"}
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul role="menu" class="dropdown-menu pull-right">
                        <li><a href="#">Action </a></li>
                        <li><a href="#">Another action </a></li>
                        <li><a href="#">Something else here </a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link </a></li>
                    </ul>
                </div>
                href="/module/questions/add"
                -->
                <a class="btn btn-sm btn-primary create-question" data-target="#questions-create-modal" data-toogle="modal">  
                    <i class="fa fa-plus"></i>
                    {translateToken value="Add a new Question"}
                </a>


                <a class="btn btn-sm btn-warning select-question" data-target="#questions-select-modal" data-toogle="modal" >
                    <i class="fa fa-database"></i>
                    {translateToken value="Questions Database"}
                </a>
                <a class="btn btn-sm btn-danger delete-content" href="javascript: void(0);"
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
        </div>
        <div class="clearfix"></div>
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper">
                <ul class="list-group questions-container"></ul>
                <div class=" hidden">

                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="exercise-question-timeline-item">
<li class="list-file-item list-question-item">
    <!--
    <a class="btn btn-sm btn-default tooltips drag-handler" data-original-title="{translateToken value="Drag to reposition item"} ">
        <i class="fa fa-arrows"></i>
    </a>
    -->

    <span class="<% if (model.active == "0") { %>text-danger<% } else { %>text-primary<% } %>"
    >
        <%= model.title %>
    </span>

    <span class="btn btn-sm btn-circle btn-default disabled">
        <!-- <i class="fa fa-question"></i> -->
        <%= model.type %>
    </span>
    <% if (model.difficulty == "Easy") { %>
        <span class="btn btn-sm btn-circle green disabled">
            <%= model.difficulty %>
        </span>
    <% } else if (model.difficulty == "Normal") { %>
        <span class="btn btn-sm btn-circle yellow disabled">
            <%= model.difficulty %>
        </span>
    <% } else if (model.difficulty == "Hard") { %>
        <span class="btn btn-sm btn-circle red disabled">
            <%= model.difficulty %>
        </span>
    <% } else if (model.difficulty == "Very Hard") { %>
        <span class="btn btn-sm btn-circle dark disabled">
            <%= model.difficulty %>
        </span>
    <% } %>

    <div class="list-file-item-options">
        <% if (!_.isUndefined(model.id)) { %>
            <!--
            <input type="checkbox" name="question_active_<%= model.id %>" data-update="active" id="question_active_<%= model.id %>" class="form-control bootstrap-switch-me tooltips" data-original-title="{translateToken value="Toogle Active"}" data-wrapper-class="item-option" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" value="1" data-value-unchecked="0">
            -->
        <% } %>
        <a class="btn btn-sm btn-danger delete-question-action" href="javascript: void(0);"
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
</li>
</script>
<script type="text/template" id="exercise-question-timeline-item2">
    <li class="list-file-item list-question-item">
        <span class="btn btn-sm">
            <%= model.title %>
        </span>
        <div class="list-file-item-options">
            <!--
            <a class="btn btn-sm btn-primary" href="/module/questions/edit/<%= model.question_id %>" >
                <i class="fa fa-edit"></i>
            </a>
            -->

            <a class="btn btn-sm btn-info info-question-item" >
                <i class="fa fa-info-circle"></i>
            </a>


            <a class="btn btn-sm btn-danger delete-question-item" href="javascript: void(0);" data-question-id="<%= model.question_id %>"
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
                style="display: inline-block;"
            >
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </li>
</script>


<script type="text/template" id="fileupload-upload-timeline-item">
    <%
        var file_type = "other";
        if (/^video\/.*$/.test(file.type)) {
            file_type = "video";
        } else if (/^image\/.*$/.test(file.type)) {
            file_type = "image";
        } else if (/^audio\/.*$/.test(file.type)) {
            file_type = "audio";
        } else if (/.*\/pdf$/.test(file.type)) {
            file_type = "pdf";
        }
    %>
    <div class="timeline-badge">
        <div class="timeline-icon">
            <% if (file_type == "video") { %>
                <i class="fa fa-file-video-o"></i>
            <% } else if (file_type == "image") { %>
                <i class="fa fa-file-image-o"></i>
            <% } else if (file_type == "audio") { %>
                <i class="fa fa-file-sound-o"></i>
            <% } else if (file_type == "pdf") { %>
                <i class="fa fa-file-pdf-o"></i>
            <% } else { %>
                <i class="fa fa-file-o"></i>
            <% }  %>
        </div>
    </div>
    <div class="timeline-body">
        <div class="timeline-body-arrow"></div>
        <div class="timeline-body-head">
            <div class="timeline-body-head-caption">
                <span class="timeline-body-alerttitle text-danger">
                    <span class="btn btn-sm btn-default hidden-sm hidden-md hidden-lg">
                        <% if (file_type == "video") { %>
                            <i class="fa fa-file-video-o"></i>
                        <% } else if (file_type == "image") { %>
                            <i class="fa fa-file-image-o"></i>
                        <% } else if (file_type == "audio") { %>
                            <i class="fa fa-file-sound-o"></i>
                        <% } else if (file_type == "pdf") { %>
                            <i class="fa fa-file-pdf-o"></i>
                        <% } else { %>
                            <i class="fa fa-file-o"></i>
                        <% }  %>
                    </span>
                    <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
                        <i class="fa fa-arrows"></i>
                    </span>

                    <%= file.name %>
                </span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default disabled text-loading">
                    <i class="fa fa-spinner fa-spin"></i>
                    <span class="load-percent">0</span>{translateToken value="% Complete"}

                </span>
                <span class="btn btn-sm btn-default disabled text-loading">

                    <span class="load-total"></span> / <%= opt.formatFileSize(file.size) %> (<span class="load-bitrate"></span>/s)
                </span>
                    <!--
                    <a class="btn btn-sm btn-success start" data-file-id="" href="javascript: void(0);">
                        <i class="fa fa-upload"></i>
                    </a>
                    -->

                    <a class="btn btn-sm btn-danger delete-file-content" href="javascript: void(0);"
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
                        <i class="fa fa-times"></i>
                    </a>

            </div>
        </div>
        <div class="clearfix"></div>
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper">
                <div class="preview"></div>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="fileupload-download-timeline-item">
    <% var file = model.file %>
    <%
        var file_type = "other";
        if (/^video\/.*$/.test(file.type)) {
            file_type = "video";
        } else if (/^image\/.*$/.test(file.type)) {
            file_type = "image";
        } else if (/^audio\/.*$/.test(file.type)) {
            file_type = "audio";
        } else if (/.*\/pdf$/.test(file.type)) {
            file_type = "pdf";
        }
    %>

    <div class="timeline-badge">
        <div class="timeline-icon">
            <% if (file_type == "video") { %>
                <i class="fa fa-file-video-o"></i>
            <% } else if (file_type == "image") { %>
                <i class="fa fa-file-image-o"></i>
            <% } else if (file_type == "audio") { %>
                <i class="fa fa-file-sound-o"></i>
            <% } else if (file_type == "pdf") { %>
                <i class="fa fa-file-pdf-o"></i>
            <% } else { %>
                <i class="fa fa-file-o"></i>
            <% }  %>
        </div>
    </div>
    <div class="timeline-body">
        <div class="timeline-body-arrow"></div>
        <div class="timeline-body-head">
            <div class="timeline-body-head-caption">
                <span class="timeline-body-alerttitle text-success">
                    <span class="btn btn-sm btn-default hidden-sm hidden-md hidden-lg">
                        <% if (file_type == "video") { %>
                            <i class="fa fa-file-video-o"></i>
                        <% } else if (file_type == "image") { %>
                            <i class="fa fa-file-image-o"></i>
                        <% } else if (file_type == "audio") { %>
                            <i class="fa fa-file-sound-o"></i>
                        <% } else if (file_type == "pdf") { %>
                            <i class="fa fa-file-pdf-o"></i>
                        <% } else { %>
                            <i class="fa fa-file-o"></i>
                        <% }  %>
                    </span>

                    <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
                        <i class="fa fa-arrows"></i>
                    </span>
                    <%= file.name %>
                </span>
                <!--
                <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
                -->
            </div>
            <div class="timeline-body-head-actions">
                <% if (file_type == "video") { %>
                    <span class="btn btn-sm btn-primary fileinput-button fileupload-subtitle" id="fileupload"  data-fileupload-url="/module/dropbox/upload/subtitle">
                        <i class="fa fa-language"></i>
                        <span class="hidden-xs">
                        {translateToken value="Add Subtitles"}
                        </span>
                        <input type="file" name="subtitles[]">
                    </span>
                    <span class="btn btn-sm btn-warning fileinput-button fileupload-poster" id="fileupload"  data-fileupload-url="/module/dropbox/upload/poster">
                        <i class="fa fa-file-image-o"></i>
                        <span class="hidden-xs">
                        {translateToken value="Add a Video Poster"}
                        </span>
                        <input type="file" name="poster[]">
                    </span>

                <% }  %>
                <a class="btn btn-sm btn-danger delete-file-content" href="javascript: void(0);"
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
        </div>
        <div class="clearfix"></div>
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper row">
                <div class="preview col-md-6">
                    <% if (!_.isEmpty(file.storage) && file.storage != "local_storage") { %>
                        <div class="external-view">
                        <% if (file_type == "video") { %>
                            <video src="<%= file.url %>" style="max-width: 100%;" controls="true" class="videojs">
                                
                            </video>
                        <% } else if (file_type == "image") { %>
                            <img src="<%= file.url %>" style="max-width: 100%;" />
                        <% } else if (file_type == "audio") { %>
                            <audio src="<%= file.url %>" style="max-width: 100%;" controls="true"></audio>
                        <% } else if (file_type == "pdf") { %>
                            <a href="<%= file.url %>" target="_blank">View File</a>
                        <% } else { %>
                            <a href="<%= file.url %>" target="_blank">View File</a>
                        <% } %>
                        </div>
                    <% } else { %>
                        <% if (file_type == "video") { %>
                            <!-- RENDER VIDEO VIEW -->
                            <video src="<%= file.url %>" style="max-width: 100%;" controls="true"></video>
                        <% } else if (file_type == "image") { %>
                            <img src="<%= file.url %>" style="max-width: 100%;" />
                        <% } else if (file_type == "audio") { %>
                            <audio src="<%= file.url %>" style="max-width: 100%;" controls="true"></audio>
                        <% } else if (file_type == "pdf") { %>
                            <a href="<%= file.url %>" target="_blank">View File</a>
                        <% } else { %>
                            <a href="<%= file.url %>" target="_blank">View File</a>
                        <% } %>
                    <% } %>
                </div>
                <div class="preview col-md-6">
                    <% if (file_type == "video") { %>
                    <ul class="list-group ui-sortable margin-bottom-10 content-subtitles-items"></ul>
                    <% } %>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="fileupload-upload-related-item">
    <%
        var file_type = "other";
        if (/^video\/.*$/.test(file.type)) {
            file_type = "video";
        } else if (/^image\/.*$/.test(file.type)) {
            file_type = "image";
        } else if (/^audio\/.*$/.test(file.type)) {
            file_type = "audio";
        } else if (/.*\/pdf$/.test(file.type)) {
            file_type = "pdf";
        }
    %>
    <% if (file_type == "video") { %>
        <i class="fa fa-file-video-o"></i>
    <% } else if (file_type == "image") { %>
        <i class="fa fa-file-image-o"></i>
    <% } else if (file_type == "audio") { %>
        <i class="fa fa-file-sound-o"></i>
    <% } else if (file_type == "pdf") { %>
        <i class="fa fa-file-pdf-o"></i>
    <% } else { %>
        <i class="fa fa-file-o"></i>
    <% }  %>
    <span class="text-danger">
        <%= file.name %>
    </span>
    <span class="font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
    <div class="list-file-item-options">
        <span class="btn btn-sm btn-default disabled text-loading">
            <i class="fa fa-spinner fa-spin"></i>
            <span class="load-percent">0</span>%
        </span>
        <a class="btn btn-sm btn-danger delete-file-content" href="javascript: void(0);">
            <i class="fa fa-times"></i>
        </a>
    </div>
</script>
<script type="text/template" id="fileupload-download-related-item">
    <% var file = model.file %>
    <%
        var file_type = "other";
        if (/^video\/.*$/.test(file.type)) {
            file_type = "video";
        } else if (/^image\/.*$/.test(file.type)) {
            file_type = "image";
        } else if (/^audio\/.*$/.test(file.type)) {
            file_type = "audio";
        } else if (/.*\/pdf$/.test(file.type)) {
            file_type = "pdf";
        }
    %>
    <ul class="nav nav-tabs tabs-sm">
        <li class="active">
            <a href="#subtitle-translate-<%= model.id %>" aria-controls="home" role="tab" data-toggle="tab" class="btn btn-sm tooltips">
                <i class="fa fa-language"></i>
            </a>
        </li>
        <li class="">
            <a href="#subtitle-edit-<%= model.id %>" aria-controls="home" role="tab" data-toggle="tab" class="btn btn-sm tooltips">
                <i class="fa fa-edit"></i>
            </a>
        </li>
        <li class="pull-right text-success">
            <%= file.name %>
            <a class="btn btn-sm text-danger delete-file-content" href="javascript: void(0);"
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
                style="display: inline-block;"
            >
                <i class="fa fa-trash"></i>
            </a>
        </li>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="subtitle-translate-<%= model.id %>">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label">{translateToken value="Original Language"}</label>
                        <div class="input-group ">
                            <select class="select2-me form-control" name="related[lang_from]" data-placeholder="{translateToken value="Choose language"}">
                                {foreach $T_LANGUAGES as $lang}
                                    <option value="{$lang.code}" <% if (model.language_code == '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                                {/foreach}
                            </select>
                            <div class="input-group-btn">
                                <button class="btn green save-file-content" type="button">
                                    {translateToken value="Save"}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">{translateToken value="Automatic translate to"}</label>
                        <div class="input-group ">
                            <select class="select2-me form-control" name="related[lang_to]" data-placeholder="{translateToken value="Choose language"}">
                                {foreach $T_LANGUAGES as $lang}
                                    <option value="{$lang.code}" <% if (model.language_code != '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                                {/foreach}
                            </select>
                            <div class="input-group-btn">
                                <button class="btn green translate-file-content" type="button">
                                    <i class="fa fa-language"></i>
                                    {translateToken value="Translate"}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h5 class="form-section no-margin">Translations</h5>
                    <ul class="list-group translation-container"></ul>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="subtitle-edit-<%= model.id %>">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        Not implemented yet!
                    </div>
                </div>
            </div>
        </div>

    </div>
</script>

<script type="text/template" id="fileupload-download-related-poster-item">
    <% var file = model.file %>
    <span class="">
        <i class="fa fa-file-image-o"></i>
    </span>

    <%= file.name %>

    <div class="list-file-item-options">
        <a class="btn btn-sm text-danger delete-file-content" href="javascript: void(0);"
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
            style="display: inline-block;"
        >
            <i class="fa fa-trash"></i>
        </a>
    </div>
</script>



<script type="text/template" id="fileupload-translation-related-item">
    <li class="translation-item">
        <% var file = model.file %>
        <span class="text-upper text-primary">
            <img src="{Plico_GetResource file='img/blank.png'}" class="flag flag-<%= model.language_code %>" alt="<%= model.language_code %>" />
            <strong><%= model.language_code %></strong>
        </span>
        <a href="<%= file.url %>">
            <%= file.name %>
        </a>

        <a class="btn btn-sm text-danger delete-translation-content" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
            style="display: inline-block;"
        >
            <i class="fa fa-trash"></i>
        </a>
    </li>
</script>


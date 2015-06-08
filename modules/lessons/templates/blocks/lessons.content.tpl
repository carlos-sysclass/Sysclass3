<div class="form-group" id="content-timeline">
    <!--
    <ul class="list-group ui-sortable margin-top-20">
    </ul>
    -->
    <div class="pull-left">
        <span class="btn btn-sm btn-link fileinput-button fileupload" data-fileupload-url="/module/dropbox/upload/lesson">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add File"}</span>
            <input type="file" name="files[]">
        </span>
        <span class="btn btn-sm btn-link timeline-addtext">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add Text"}</span>
        </span>
        <span class="btn btn-sm btn-link timeline-addexercise">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Add Exercises"}</span>
        </span>
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
        <a class="btn btn-sm btn-success timeline-collapse tooltips" href="javascript:void(0);" data-original-title="{translateToken value="Copy content from another lesson"}">
            <i class="fa fa-copy"></i>
            <span>{translateToken value="Copy"}</span>
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="timeline content-timeline-items">
    </div>
</div>

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
                <span class="timeline-body-time font-grey-cascade"></span>
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
                <span class="timeline-body-time font-grey-cascade"></span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default text-loading hidden">
                    <i class="fa fa-spinner fa-spin"></i>
                    {translateToken value="Saving"}
                </span>

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


                <a class="btn btn-sm btn-warning add-question" href="javascript: void(0);">
                    <i class="fa fa-database"></i>
                    {translateToken value="Question's Database"}
                </a>
                <a class="btn btn-sm btn-danger delete-text-content" href="javascript: void(0);">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper">
                <div class=" hidden">

                </div>
            </div>
        </div>
    </div>
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
            <% if (/^video\/.*$/.test(file.type)) { %>
                <i class="fa fa-file-video-o"></i>
            <% } else if (/^image\/.*$/.test(file.type)) { %>
                <i class="fa fa-file-image-o"></i>
            <% } else if (/^audio\/.*$/.test(file.type)) { %>
                <i class="fa fa-file-sound-o"></i>
            <% } else if (/.*\/pdf$/.test(file.type)) { %>
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
                <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default disabled text-loading">
                    <i class="fa fa-spinner fa-spin"></i>
                    <span class="load-percent">0</span>{translateToken value="% Complete"}
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
            <% if (file_type == "video1") { %>
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
                <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
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
        <div class="timeline-body-content">
            <div class="timeline-body-content-wrapper row">
                <div class="preview col-md-6">
                    <% if (file_type == "video") { %>
                        <video src="<%= file.url %>" style="max-width: 100%;" controls="true"></video>
                    <% } else if (file_type == "image") { %>
                        <img src="<%= file.url %>" style="max-width: 100%;" />
                    <% } else if (file_type == "audio") { %>
                        <audio src="<%= file.url %>" style="max-width: 100%;" controls="true"></audio>
                    <% } else if (file_type == "pdf") { %>
                        <a href="<%= file.url %>" target="_blank">View File</a>
                    <% } else { %>
                        <a href="<%= file.url %>" target="_blank">View File</a>
                    <% }  %>
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
                        <label class="control-label">{translateToken value="Subtitle Language"}</label>
                        <div class="input-group ">
                            <select class="select2-me form-control" name="related[lang_from]" data-rule-required="1" data-rule-min="1" data-placeholder="{translateToken value="Choose language"}">
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
                            <select class="select2-me form-control" name="related[lang_to]" data-rule-required="1" data-rule-min="1" data-placeholder="{translateToken value="Choose language"}">
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

<script type="text/template" id="fileupload-translation-related-item">
    <li class="translation-item">
        <% var file = model.file %>
        <span class="text-upper text-primary">
            <img src="{Plico_GetResource file='img/blank.png'}" class="flag flag-<%= model.language_code %>" alt="<%= model.language_code %>" />
            <strong><%= model.language_code %></strong>
        </span>
        <%= file.name %>
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


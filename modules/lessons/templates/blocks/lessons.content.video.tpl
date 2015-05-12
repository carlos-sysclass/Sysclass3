<div class="panel panel-default">
    <div class="panel-heading">
        {translateToken value="Video Based Lesson"}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Enable"}</label>
                    <input type="checkbox" name="has_video_content" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Subtitle Base Language"}</label>
                    <select class="select2-me form-control" name="subtitle_content_language_id" data-rule-required="1" data-rule-min="1" data-placeholder="{translateToken value="Subtitle Base Language"}">
                        <option value=""></option>
                        {foreach $T_LESSONS_CONTENT_TEXT_LANGUAGES as $lang}
                            <option value="{$lang.id}">{$lang.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="" id="video-file-upload-widget">
                <ul class="list-group ui-sortable">
                </ul>

                <div>
                    <span class="btn btn-primary fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>Select files...</span>
                        <input type="file" name="files_videos[]" multiple="true">
                    </span>

                    <button class="btn btn-success upload-action disabled" disabled="disabled">
                        <i class="fa fa-upload"></i>
                        <span>Upload</span>
                    </button>
                </div>

                <div class="progress progress-striped active margin-top-20">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="panel-group accordion" id="content-accordion-{$T_MODULE_ID}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#content-accordion-{$T_MODULE_ID}" href="#collapse_1">{translateToken value="Video Lesson"} </a>
            </h4>
        </div>
        <div id="collapse_1" class="panel-collapse in">

        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#content-accordion-{$T_MODULE_ID}" href="#collapse_2">{translateToken value="Materials"}</a>
            </h4>
        </div>
        <div id="collapse_2" class="panel-collapse collapse">
            <div class="panel-body" id="material-file-upload-widget">


                <ul id="material-file-list" class="list-group ui-sortable">
                </ul>

                <div>
                    <span class="btn btn-primary fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>Select files...</span>
                        <input type="file" name="files_materials[]" multiple="true">
                    </span>

                    <button class="btn btn-success upload-action disabled" disabled="disabled">
                        <i class="fa fa-upload"></i>
                        <span>Upload</span>
                    </button>
                </div>

                <div class="progress progress-striped active margin-top-20">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#content-accordion-{$T_MODULE_ID}" href="#collapse_3">{translateToken value="Exercices"}</a>
            </h4>
        </div>
        <div id="collapse_3" class="panel-collapse collapse">
            <div class="panel-body">
                {translateToken value="Under Construction"}
            </div>
        </div>
    </div>
</div>
-->
<script type="text/template" id="file-upload-widget-item">
    <li <% if (typeof index !== 'undefined') { %>data-fileindex="<%= index %>" <% } %> class="list-file-item draggable <% if (typeof url !== 'undefined') { %>green-stripe<% } else { %>red-stripe<% } %>">

        <a href="<% if (typeof url !== 'undefined') { %><%= url %><% } else { %>javascript: void(0);<% } %>" target="_blank"><%= name %></a>
        [ <%= (size / 1024) + " kb" %> ]
        <div class="list-file-item-options">
        <% if (typeof id !== 'undefined') { %>
            <a class="btn btn-sm btn-warning translate-file-action" data-file-id="<%= id %>" href="javascript: void(0);">
                <i class="fa fa-globe"></i>
            </a>
            <a class="btn btn-sm btn-danger remove-file-action" data-file-id="<%= id %>" href="javascript: void(0);">
                <i class="fa fa-trash"></i>
            </a>
        <% } %>
        </div>
    </li>
</script>

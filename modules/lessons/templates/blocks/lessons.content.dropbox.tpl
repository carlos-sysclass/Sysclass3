<div class="panel panel-default">
    <div class="panel-heading">
        {translateToken value="Video Based Lesson"}
    </div>
    <div class="panel-body">
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
<script type="text/template" id="file-upload-widget-item">
    <li <% if (typeof index !== 'undefined') { %>data-fileindex="<%= index %>" <% } %> class="list-file-item draggable <% if (typeof url !== 'undefined') { %>green-stripe<% } else { %>red-stripe<% } %>">

        <a href="<% if (typeof url !== 'undefined') { %><%= url %><% } else { %>javascript: void(0);<% } %>" target="_blank"><%= name %></a>
        [ <%= (size / 1024) + " kb" %> ]
        <div class="list-file-item-options">
        <% if (typeof id !== 'undefined') { %>
            <a class="btn btn-sm btn-warning translate-file-action tooltips" data-original-title="Add a Legend" data-file-id="<%= id %>" href="javascript: void(0);">
                <i class="fa fa-language"></i>
            </a>
            <a class="btn btn-sm btn-danger remove-file-action" data-file-id="<%= id %>" href="javascript: void(0);">
                <i class="fa fa-trash"></i>
            </a>
        <% } %>
        </div>
    </li>
</script>

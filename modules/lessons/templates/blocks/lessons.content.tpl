<div class="form-group" id="content-timeline" data-fileupload-url="/module/dropbox/upload/lesson">
    <!--
    <ul class="list-group ui-sortable margin-top-20">
    </ul>
    -->
    <div class="pull-right">
        <a class="btn btn-sm btn-primary timeline-expand" href="javascript: void();">
            <i class="fa fa-plus"></i>
            <span>{translateToken value="Expand All"}</span>
        </a>
        <a class="btn btn-sm btn-warning timeline-collapse" href="javascript: void();">
            <i class="fa fa-minus"></i>
            <span>{translateToken value="Collapse All"}</span>
        </a>
        <a class="btn btn-sm btn-warning timeline-collapse tooltips" href="javascript: void();" data-original-title="{translateToken value="Copy content from another lesson"}">
            <i class="fa fa-copy"></i>
            <span>{translateToken value="Copy"}</span>
        </a>
    </div>
    <div class="clearfix"></div>

    <div class="timeline content-timeline-items">
    </div>

    <span class="btn btn-sm btn-link fileinput-button">
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
                <span class="timeline-body-alerttitle text-primary">Content</span>
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
                <a class="btn btn-sm btn-danger delete-text-content" href="javascript: void(0);">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <div class="timeline-body-content">
            <div class="form-group wysihtml5-container">
                <textarea class="wysihtml5 form-control placeholder-no-fix" rows="6" placeholder="{translateToken value="Put your content here"}"></textarea>
            </div>
            <div class="preview hidden">

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
                <span class="timeline-body-alerttitle text-primary">Exercises</span>
                <span class="timeline-body-time font-grey-cascade"></span>
            </div>
            <div class="timeline-body-head-actions">
                <span class="btn btn-sm btn-default text-loading hidden">
                    <i class="fa fa-spinner fa-spin"></i>
                    {translateToken value="Saving"}
                </span>
                <a class="btn btn-sm btn-primary add-question" href="javascript: void(0);">
                    <i class="fa fa-plus"></i>
                    {translateToken value="Create a new Question"}
                </a>
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
            <div class=" hidden">

            </div>
        </div>
    </div>
</script>

<script type="text/template" id="fileupload-upload-timeline-item">
    <div class="timeline-item fileupload-item template-upload">
        <div class="timeline-badge">
            <div class="timeline-icon">
                <% if (/^video\/.*$/.test(file.type)) { %>
                    <i class="fa fa-file-video-o"></i>
                <% } else if (/^image\/.*$/.test(file.type)) { %>
                    <i class="fa fa-file-image-o"></i>
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
                    <span class="timeline-body-alerttitle text-danger"><%= file.name %></span>
                    <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
                </div>
                <div class="timeline-body-head-actions">
                    <span class="btn btn-sm btn-default text-loading">
                        <i class="fa fa-spinner fa-spin"></i>
                        {translateToken value="Saving"}
                    </span>
                   <% if (!index && !opt.options.autoUpload) { %>
                        <a class="btn btn-sm btn-success start" data-file-id="" href="javascript: void(0);">
                            <i class="fa fa-upload"></i>
                        </a>
                    <% } %>
                    <% if (!index) { %>
                        <a class="btn btn-sm btn-danger cancel" data-file-id="" href="javascript: void(0);">
                            <i class="fa fa-trash"></i>
                        </a>
                    <% } %>
                </div>
            </div>
            <div class="timeline-body-content">
                <div class="preview"></div>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="fileupload-download-timeline-item">
    <div class="timeline-item fileupload-item template-download">
        <div class="timeline-badge">
            <div class="timeline-icon">
                <% if (/^video\/.*$/.test(file.type)) { %>
                    <i class="fa fa-file-video-o"></i>
                <% } else if (/^image\/.*$/.test(file.type)) { %>
                    <i class="fa fa-file-image-o"></i>
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
                    <span class="timeline-body-alerttitle text-success"><%= file.name %></span>
                    <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
                </div>
                <div class="timeline-body-head-actions">
                    <% if (!index) { %>
                        <a class="btn btn-sm btn-danger delete" data-file-id="<%= file.id %>" href="javascript: void(0);">
                            <i class="fa fa-trash"></i>
                        </a>
                    <% } %>
                </div>
            </div>
            <div class="timeline-body-content">
                <div class="preview">
                <% if (/^video\/.*$/.test(file.type)) { %>
                    <video src="<%= file.url %>" style="max-width: 40%;" controls="true"></video>
                <% } else if (/^image\/.*$/.test(file.type)) { %>
                    <img src="<%= file.url %>" style="max-width: 40%;" />
                <% } else if (/.*\/pdf$/.test(file.type)) { %>
                    <a href="<%= file.url %>" target="_blank">View File</a>
                <% } else { %>
                    <a href="<%= file.url %>" target="_blank">View File</a>
                <% }  %>
                </div>
            </div>
        </div>
    </div>
</script>

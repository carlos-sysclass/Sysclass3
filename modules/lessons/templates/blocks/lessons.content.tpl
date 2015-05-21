<div class="form-group file-upload-me" id="video-file-upload-widget" data-fileupload-url="/module/dropbox/upload/lesson">
    <!--
    <ul class="list-group ui-sortable margin-top-20">
    </ul>
    -->
    <div class="timeline file-list">
    </div>

    <span class="btn btn-sm btn-link fileinput-button">
        <i class="fa fa-plus"></i>
        <span>{translateToken value="Add File"}</span>
        <input type="file" name="files[]">
    </span>
    <span class="btn btn-sm btn-link">
        <i class="fa fa-plus"></i>
        <span>{translateToken value="Add Text"}</span>
    </span>
    <span class="btn btn-sm btn-link">
        <i class="fa fa-plus"></i>
        <span>{translateToken value="Add Exercises"}</span>
    </span>

    <script type="text/template" id="file-upload-widget-upload">
        <div class="timeline-item template-upload">
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
    <script type="text/template" id="file-upload-widget-download">
        <div class="timeline-item template-upload">
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
                        <% if (/^video\/.*$/.test(file.type)) { %>
                            <% // INJECT VIDEO SPECIFIC OPTIONS %>
                            <a class="btn btn-sm btn-info add-subtitle" data-file-id="<%= file.id %>" href="javascript: void(0);">
                                <i class="fa fa-trash"></i>
                                Add subtitles
                            </a>
                        <% } %>
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
        <!--
        <li <% if (typeof index !== 'undefined') { %>data-fileindex="<%= index %>" <% } %> class="list-file-item draggable template-download <% if (file.error) { %>red-stripe<% } else { %>green-stripe<% } %>">
            <% if (file.error) { %>
                <span class="error"><%= file.error %></span>

                <div class="list-file-item-options">
                    <a class="btn btn-sm btn-danger delete" data-file-id="" href="javascript: void(0);">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>

            <% } else { %>
                <input type="hidden" name="logo_id" value="<%= file.id %>" />
                <span class="preview">
                <% if (file.thumbnailUrl) { %>
                    <img src="<%= file.thumbnailUrl %>" />
                <% } %>
                </span>
                <a href="<% if (typeof file.url !== 'undefined') { %><%= file.url %><% } else { %>javascript: void(0);<% } %>" target="_blank"><%= file.name %></a>
                [ <%= opt.formatFileSize(file.size) %> ]
                <div class="list-file-item-options">
                <% if (!index && !opt.options.autoUpload) { %>
                <% } %>
                <% if (!index) { %>
                    <a class="btn btn-sm btn-danger delete" data-file-id="<%= file.id %>" href="javascript: void(0);">
                        <i class="fa fa-trash"></i>
                    </a>
                <% } %>
                </div>
            <% } %>
        </li>
        -->
    </script>
</div>

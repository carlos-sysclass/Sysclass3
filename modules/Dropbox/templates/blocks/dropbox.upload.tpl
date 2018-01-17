
<script type="text/template" id="block-dropbox-upload-empty">
    <strong>{translateToken value="Drag your file here or click in button the to upload it. Use jpg., pdf. or png. files only."}</strong>
</script>
<script type="text/template" id="block-dropbox-upload-upload">
    <span class="preview">
    </span>

    <%= file.name %>
    [ <%= opt.formatFileSize(parseInt(file.size)) %> ]
    <div class="list-file-item-options">
        <span class="btn btn-sm btn-default text-loading">
            <i class="fa fa-spinner fa-spin"></i>
            <span class="load-percent">0</span>{translateToken value="% Complete"}
        </span>

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
</script>
<script type="text/template" id="block-dropbox-upload-download">
    <% var file = model %>
    <% if (file.error) { %>
        <span class="error"><%= file.error %></span>

        <div class="list-file-item-options">
            <a class="btn btn-sm btn-danger delete" data-file-id="" href="javascript: void(0);">
                <i class="fa fa-trash"></i>
            </a>
        </div>

    <% } else { %>
        <span class="preview-download">
            <% if (/^video\/.*$/.test(file.type)) { %>
                <video src="<%= file.url %>" style="max-width: 40%;" controls="true"></video>
            <% } else if (/^image\/.*$/.test(file.type)) { %>
                <img src="<%= file.url %>" style="max-height: 75px" class="user-profile-image" />
            <% } else if (/^audio\/.*$/.test(file.type)) { %>
                <audio src="<%= file.url %>" style="max-width: 40%;" controls="true"></audio>
            <% } else if (/.*\/pdf$/.test(file.type)) { %>
                <a href="<%= file.url %>" target="_blank">View File</a>
            <% } else { %>
                <a href="<%= file.url %>" target="_blank">View File</a>
            <% }  %>
        </span>

        <a href="<% if (typeof file.url !== 'undefined') { %><%= file.url %><% } else { %>javascript: void(0);<% } %>" target="_blank"><%= file.name %></a>
        [ <%= opt.formatFileSize(parseInt(file.size)) %> ]
        <div class="list-file-item-options">
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
    <% } %>

    <!--
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
                <span class="timeline-body-alerttitle text-success">
                    <span class="btn btn-sm btn-default drag-handler">
                        <i class="fa fa-arrows"></i>
                    </span>
                    <%= file.name %>
                </span>
                <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
            </div>
            <div class="timeline-body-head-actions">

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
            <div class="timeline-body-content-wrapper">
                <div class="preview">
                <% if (/^video\/.*$/.test(file.type)) { %>
                    <video src="<%= file.url %>" style="max-width: 40%;" controls="true"></video>
                <% } else if (/^image\/.*$/.test(file.type)) { %>
                    <img src="<%= file.url %>" style="max-width: 40%;" />
                <% } else if (/^audio\/.*$/.test(file.type)) { %>
                    <audio src="<%= file.url %>" style="max-width: 40%;" controls="true"></audio>
                <% } else if (/.*\/pdf$/.test(file.type)) { %>
                    <a href="<%= file.url %>" target="_blank">View File</a>
                <% } else { %>
                    <a href="<%= file.url %>" target="_blank">View File</a>
                <% }  %>
                </div>
            </div>
        </div>
    </div>
    -->
</script>


<script type="text/template" id="file-upload-widget-upload">
                        <li <% if (typeof index !== 'undefined') { %>data-fileindex="<%= index %>" <% } %> class="list-file-item draggable red-stripe template-upload">
                            <span class="preview"></span>
                            <a href="<% if (typeof url !== 'undefined') { %><%= url %><% } else { %>javascript: void(0);<% } %>" target="_blank"><%= file.name %></a>
                            [ <%= opt.formatFileSize(file.size) %> ]
                            <div class="list-file-item-options">
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
                        </li>
                    </script>
<script type="text/template" id="file-upload-widget-download">
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
                <!--
                <a class="btn btn-sm btn-primary" data-file-id="<%= file.id %>" href="javascript: void(0);">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
                -->
            <% } %>
            <% if (!index) { %>
                <a class="btn btn-sm btn-danger delete" data-file-id="<%= file.id %>" href="javascript: void(0);">
                    <i class="fa fa-trash"></i>
                </a>
            <% } %>
            </div>
        <% } %>
    </li>
</script>

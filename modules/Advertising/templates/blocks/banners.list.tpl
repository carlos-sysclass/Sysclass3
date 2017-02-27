<div id="advertising-banners">
    <!--
    <ul class="list-group ui-sortable margin-top-20">
    </ul>
    -->

    <div class="form-group">
        <label class="control-label">{translateToken value="Banner Size"}</label>
        <!--
        <input type="hidden" class="select2-me form-control input-block-level" name="area_id" data-placeholder="{translateToken value='Knowledge Area'}" data-url="/module/areas/items/me/combo" data-minimum-results-for-search="4" />
        -->
        <select class="select2-me form-control" name="banner_size" data-rule-required="1"  data-placeholder="{translateToken value='View Type'}">
            {foreach $T_BANNER_SIZES as $banner_size}
                <option value="{$banner_size.id}" data-width="{$banner_size.width}" data-height="{$banner_size.height}">{$banner_size.name} ({$banner_size.width}x{$banner_size.height})</option>
            {/foreach}
        </select>
    </div>

    <div class="form-group">
        <label class="control-label">{translateToken value="URL"}</label>
        <input name="global_link" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
    </div>

    <div class="form-group">
        <div class="pull-left">
            <span class="btn btn-sm btn-link fileinput-button fileupload" data-fileupload-url="/module/dropbox/upload/image">
                <i class="fa fa-plus"></i>
                <span>{translateToken value="Add File"}</span>
                <input type="file" name="files[]">
            </span>
            <span class="btn btn-sm btn-link timeline-addtext">
                <i class="fa fa-plus"></i>
                <span>{translateToken value="Add HTML"}</span>
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
        </div>
        <div class="clearfix"></div>

        <ul class="timeline content-timeline-items">
        </ul>
    </div>
</div>

<script type="text/template" id="text-banner-item">
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
    <div class="list-file-item-options">
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
    <div class="timeline-body-content">
        <div class="timeline-body-content-wrapper">
            <div class="form-group wysihtml5-container">
                <textarea class="wysihtml5 form-control placeholder-no-fix" rows="6" placeholder="{translateToken value="Put your content here"}"><%= info %></textarea>
            </div>
            <div class="preview hidden">

            </div>
        </div>
    </div>
</script>

<script type="text/template" id="fileupload-upload-banner-item">
    <span class="timeline-body-alerttitle text-danger">
        <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
            <i class="fa fa-arrows"></i>
        </span>

        <%= file.name %>
    </span>
    <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>

    <div class="list-file-item-options">
        <span class="btn btn-sm btn-default disabled text-loading">
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
    <div class="timeline-body-content">
        <div class="timeline-body-content-wrapper">
            <div class="preview"></div>
        </div>
    </div>
</script>
<script type="text/template" id="fileupload-download-banner-item">
    <% if (_.has(model, 'files')) { %>
        <% var file = _.first(model.files) %>
    <% } else { %>
        <% var file = model.file %>
    <% } %>
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

    <span class="timeline-body-alerttitle text-success">
        <span class="btn btn-sm btn-default drag-handler tooltips" data-original-title="{translateToken value="Click here to move content"}">
            <i class="fa fa-arrows"></i>
        </span>
        <%= file.name %>
    </span>
    <span class="timeline-body-time font-grey-cascade"><%= opt.formatFileSize(file.size) %></span>
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
</script>

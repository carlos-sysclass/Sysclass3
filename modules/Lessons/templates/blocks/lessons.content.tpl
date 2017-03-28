<style>
.content-video-item {
    min-height: 270px;
}
.content-video-empty {
    
}
.content-video-empty-item {
    background: #eee none repeat scroll 0 0;
    border: 1px dashed #ccc;
    display: block;
    min-height: 265px;
    position: relative;
    text-align: center;
}
.content-video-empty-item a {
    bottom: 44%;
    display: block;
    font-size: 1.5em;
    position: absolute;
    top: 44%;
    width: 100%;
}


.content-video-sidebar {
    display: none;
}

li.list-item {
    border-bottom: 1px solid #ddd;
    margin: 2px 0 10px;
    padding-bottom: 4px;
}

</style>

<div id="content-editor">
    <div id="content-video-widget" data-widget-id="unit-content-video-widget">
        <div class="row">
            <div class="col-md-12">
                <div class="unit-content-video-block">
                    <div class="panel panel-default draggable">
                        <div class="panel-heading">
                            <a class="btn btn-sm btn-default tooltips toogle-visible-item" data-original-title="{translateToken value="Expand / Collpase"}">
                                <i class="fa fa-angle-up"></i>
                            </a>
                            {translateToken value="Video"}
                        </div>
                        <div id="content-video-widget-container" class="panel-body in subitems-container">
                            <div class="row content-videos">
                                <div class="content-videos-inner">
                                </div>

                                <div class="content-video-item content-video-empty-container col-md-6 col-lg-6 col-sm-6 col-xs-12 ">
                                    <div class="content-video-empty-item">
                                        <a href="javascript:void(0)" class="content-addfile" data-library-path="library" data-library-type="video" >
                                            <i class="fa fa-cloud-upload"></i>
                                            {translateToken value="Upload video"}
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <h5 class="form-section margin-bottom-10 margin-top-10">
                                <i class="fa fa-list"></i>
                                {translateToken value="Description"}
                                <a href="javascript: void(0);" class="btn btn-sm btn-success content-save pull-right">
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
                                        <input type="hidden" class="select2-me form-control" name="tags" data-placeholder="{translateToken value="Choose tags"}" multiple="true" data-tags="true" data-allow-clear="true" data-token-separators="[',', ' ']" />
                                    </div>
                                </div>
                            </div>
                            <h5 class="form-section margin-bottom-10 margin-top-10">
                                <i class="icon-globe"></i>
                                {translateToken value="Subtitle"}

                                <a href="javascript: void(0);" class="btn btn-sm btn-primary pull-right content-addfile" data-library-path="library" data-library-type="subtitle">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    {translateToken value="Subtitle"}
                                </a>
                            </h5>
                            <div class="row content-subtitles">
                                <ul class="list-group margin-top-10 col-md-12 subtitle-container"></ul>
                            </div>

                            <div class="content-videos-poster">
                                <h5 class="form-section margin-bottom-10 margin-top-10">
                                    <i class="fa fa-file-image-o"></i>
                                    {translateToken value="Video poster"}

                                    <a href="javascript: void(0);" class="btn btn-sm btn-primary pull-right content-addfile" data-library-path="library" data-library-type="poster">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        {translateToken value="Poster"}
                                    </a>
                                </h5>
                                <div class="row content-posters">
                                    <ul class="list-group margin-top-10 col-md-12 poster-container"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content-materials-widget" data-widget-id="unit-content-material-widget">
        <div class="row">
            <div class="col-md-12">
                <div class="unit-content-video-block">
                    <div class="panel panel-default draggable">
                        <div class="panel-heading">
                            <a class="btn btn-sm btn-default tooltips toogle-visible-item" data-original-title="{translateToken value="Expand / Collpase"}">
                                <i class="fa fa-angle-up"></i>
                            </a>
                            {translateToken value="Materials"}
                            <div class="list-file-item-options">
                                <div class="dropdown pull-right">
                                    <a href="javascript: void(0);" class="btn btn-sm btn-primary content-addfile" data-library-path="library" data-library-type="file">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        {translateToken value="Material"}
                                    </a>
                                </div>
                                <!--

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

                        </div>
                        <div id="content-video-widget-container" class="panel-body in subitems-container">
                            <div class="row content-materials">
                                <ul class="list-group margin-top-10 col-md-12 materials-container"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="content-video-empty">
</script>

<script type="text/template" id="content-video-item">
    <div class="col-md-11 no-padding">
        <video src="<%= model.url %>" style="max-width: 100%; width:100%;" controls="true" class="videojs">
        </video>
    </div>
    <div class="col-md-1 content-video-sidebar">
        <div class="tooltips" data-original-title="{translateToken value='Delete'}" data-placement="top" data-container="body" style="display: inline-block;">
            <a class="btn btn-sm btn-danger delete-item" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
    </div>
</script>

<script type="text/template" id="content-subtitle-item">

    <select class="select2-me form-control" name="locale_code" data-placeholder="{translateToken value="Language"}" style="max-width: 150px;" data-format-as="country">
        <option></option>
        {foreach $T_LANGUAGES as $lang}
            <option value="{$lang.locale_code}" data-country="{$lang.country_code}" <% if (model.locale_code == '{$lang.locale_code}') { %>selected="selected"<% } %>>{$lang.local_name}</option>
        {/foreach}
    </select>

    <a href="<%= model.url %>">
        <%= model.name %>
    </a>

    <div class="list-file-item-options">
        <a class="btn btn-sm btn-default tooltips auto-translate-subtitle" data-original-title="{translateToken value='Translate...'}" data-placement="top" data-container="body" href="javascript: void(0);" style="display: inline-block;" data-
            >
                <i class="icon-globe"></i>
        </a>

        <div class="tooltips" data-original-title="{translateToken value='Delete'}" data-placement="top" data-container="body" style="display: inline-block;">
            <a class="btn btn-sm btn-danger delete-item" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
</script>

<script type="text/template" id="content-poster-item">

    <select class="select2-me form-control" name="locale_code" data-placeholder="{translateToken value="Language"}" style="max-width: 150px;" data-format-as="country">
        <option></option>
        {foreach $T_LANGUAGES as $lang}
            <option value="{$lang.locale_code}" data-country="{$lang.country_code}" <% if (model.locale_code == '{$lang.locale_code}') { %>selected="selected"<% } %>>{$lang.local_name}</option>
        {/foreach}
    </select>

    <a href="<%= model.url %>">
        <%= model.name %>
    </a>

    <div class="list-file-item-options">
        <div class="tooltips" data-original-title="{translateToken value='Delete'}" data-placement="top" data-container="body" style="display: inline-block;">
            <a class="btn btn-sm btn-danger delete-item" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
</script>

<script type="text/template" id="content-material-item">
    <% //var file = _.first(model.files); %>
    <% var file = model; %>
    <% if (!_.isUndefined(file)) { %>

        <select class="select2-me form-control" name="locale_code" data-placeholder="{translateToken value="Language"}" style="max-width: 150px;" data-format-as="country">
            <option></option>
            {foreach $T_LANGUAGES as $lang}
                <option value="{$lang.locale_code}" data-country="{$lang.country_code}" <% if (model.locale_code == '{$lang.locale_code}') { %>selected="selected"<% } %>>{$lang.local_name}</option>
            {/foreach}

        </select>

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
        <span class="">
            <% if (file_type == "video") { %>
                <i class="fa fa-lg fa-file-video-o"></i>
            <% } else if (file_type == "image") { %>
                <i class="fa fa-lg fa-file-image-o"></i>
            <% } else if (file_type == "audio") { %>
                <i class="fa fa-lg fa-file-sound-o"></i>
            <% } else if (file_type == "pdf") { %>
                <i class="fa fa-lg fa-file-pdf-o"></i>
            <% } else { %>
                <i class="fa fa-lg fa-file-o"></i>
            <% }  %>
        </span>

        <a href="<%= file.url %>">
            <%= model.name %>
        </a>
        <div class="list-file-item-options">
            <div class="tooltips" data-original-title="{translateToken value='Delete'}" data-placement="top" data-container="body" style="display: inline-block;">
                <a class="btn btn-sm btn-danger delete-item" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
        </div>
    <% } %>
</script>

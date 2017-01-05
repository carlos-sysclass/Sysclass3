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
                                <div class="content-videos-inner">
                                </div>

                                <div class="content-video-item content-video-empty-container col-md-6 col-lg-6 col-sm-6 col-xs-12 ">
                                    <div class="content-video-empty-item">
                                        <a href="javascript:void(0)" class="content-addfile" data-library-path="library" data-library-type="video" >
                                            <i class="fa fa-plus-circle"></i>
                                            {translateToken value="Add a new video"}
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
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
                            <h5 class="form-section margin-bottom-10 margin-top-10">
                                <i class="fa fa-language"></i>
                                {translateToken value="Subtitle"}

                                <a href="javascript: void(0);" class="btn btn-sm btn-primary pull-right content-addfile" data-library-path="library" data-library-type="subtitle">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    {translateToken value="Subtitle"}
                                </a>
                            </h5>
                            <div class="row content-subtitles">
                                <ul class="list-group margin-top-10 col-md-12 subtitle-container"></ul>
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

                            <div class="content-videos-poster">
                                <h5 class="form-section margin-bottom-10 margin-top-10">
                                    <i class="fa fa-language"></i>
                                    {translateToken value="Video Poster"}

                                    <a href="javascript: void(0);" class="btn btn-sm btn-primary pull-right content-addfile" data-library-path="library" data-library-type="poster">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        {translateToken value="Poster"}
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
        <a class="btn btn-sm btn-danger delete-video" href="javascript: void(0);" data-content-id="<%= model.id %>"
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

<script type="text/template" id="content-subtitle-item">

    <select class="select2-me form-control" name="country_code" data-placeholder="{translateToken value="Language"}" style="max-width: 150px;" data-format-as="country">
        {foreach $T_LANGUAGES as $lang}
            <option value="{$lang.country_code}" <% if (model.country_code == '{$lang.country_code}') { %>selected="selected"<% } %>>{$lang.name}</option>
        {/foreach}
    </select>

    <a href="<%= model.url %>">
        <%= model.name %>
    </a>


    <div class="list-file-item-options">

        <!--
        <div class="dropdown" style="display: inline-block;">
            <a href="javascript: void(0);" data-close-others="true" data-toggle="dropdown" class="dropdown-toggle btn btn-sm btn-default">
                <% if (model.language_code) { %>
                    <img src="{Plico_GetResource file='img/blank.png'}" class="flag flag-<%= model.language_code %>" alt="<%= model.language_code %>" />
                    <i class="fa fa-caret-down"></i>
                <% } else { %>
                    <img src="{Plico_GetResource file='img/blank.png'}" class="flag flag-<%= model.language_code %>" alt="<%= model.language_code %>" />
                    <i class="fa fa-caret-down"></i>
                <% } %>
            </a>
            <ul class="dropdown-menu pull-right">
                {foreach $T_LANGUAGES as $lang}
                    <option value="{$lang.code}" <% if (model.language_code == '{$lang.code}') { %>selected="selected"<% } %>>{$lang.name}</option>
                {/foreach}
            </ul>
        </div>
        -->

        <a class="btn btn-sm btn-default tooltips auto-translate-subtitle" data-original-title="{translateToken value='Automatic translate to...'}" data-placement="top" data-container="body" href="javascript: void(0);" style="display: inline-block;" data-
            >
                <i class="fa fa-language"></i>
        </a>

        <div class="tooltips" data-original-title="{translateToken value='Remove'}" data-placement="top" data-container="body" style="display: inline-block;">
            <a class="btn btn-sm btn-danger delete-subtitle" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
    <% var file = _.first(model.files); %>
    <% if (!_.isUndefined(file)) { %>

        <select class="select2-me form-control" name="country_code" data-placeholder="{translateToken value="Language"}" style="max-width: 150px;" data-format-as="country">
            {foreach $T_LANGUAGES as $lang}
                <option value="{$lang.country_code}" <% if (file.country_code == '{$lang.country_code}') { %>selected="selected"<% } %>>{$lang.name}</option>
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
            <%= model.title %>
        </a>
        <div class="list-file-item-options">
            <a class="btn btn-sm btn-danger delete-material" href="javascript: void(0);" data-content-id="<%= model.id %>"
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
    <% } %>
</script>

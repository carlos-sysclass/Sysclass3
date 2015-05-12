<div class="panel panel-default">
    <div class="panel-heading">
        {translateToken value="Text Based Lesson"}
        <div class="panel-buttons panel-buttons-sm">
            <a class="btn btn-warning btn-sm translate-trigger" href="#">
                <i class="fa fa-globe"></i>Translate
            </a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{translateToken value="Enable"}</label>
                    <input type="checkbox" name="has_text_content" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label">{translateToken value="Content Base Language"}</label>
                    <select class="select2-me form-control" name="text_content_language_id" data-rule-required="1" data-rule-min="1" data-placeholder="{translateToken value="Content Base Language"}">
                        <option value=""></option>
                        {foreach $T_LESSONS_CONTENT_TEXT_LANGUAGES as $lang}
                            <option value="{$lang.id}">{$lang.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label">{translateToken value="Content"}</label>
            <textarea class="wysihtml5 form-control placeholder-no-fix" id="text_content" name="text_content" rows="6" placeholder="{translateToken value="Put your content here"}"></textarea>
        </div>
    </div>
</div>



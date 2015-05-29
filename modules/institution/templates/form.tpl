{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-institution" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Address Book"}</a>
			</li>
			{/if}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Social Info"}</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">
				<h3 class="form-section">{translateToken value="General"}</h3>
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
				<!--
				<div class="form-group">
					<label class="control-label">{translateToken value="Full Name"}</label>
					<input name="formal_name" type="text" placeholder="Full Name" class="form-control" data-rule-required="true" data-rule-minlength="10" />
				</div>
				-->
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
				<!--
				<div class="form-group">
					<label class="control-label">{translateToken value="Observations"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="observations" name="observations" rows="6" placeholder="{translateToken value="Put your observations here..."}" data-rule-required="true"></textarea>
				</div>
				-->

				<h3 class="form-section">{translateToken value="Logo"}</h3>

				<div class="form-group file-upload-me" data-fileupload-url="/module/dropbox/upload/image" data-model-file="logo" id="file-test">
				    <ul class="list-group content-timeline-items">
				    </ul>

					<span class="btn btn-primary fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>{translateToken value="Add/Change file"}</span>
                        <input type="file" name="files[]">
                    </span>
				</div>
			</div>

			{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
			    {foreach $T_SECTION_TPL['address'] as $template}
					{include file=$template}
				{/foreach}
				</div>
			{/if}

 			<div class="tab-pane fade in" id="tab_1_3">
				<div class="form-group">
					<label class="control-label">{translateToken value="Website"}</label>
					<input name="website" value="" type="text" placeholder="Website" class="form-control" data-rule-url="true" />
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Facebook"}</label>
					<div class="input-group">
						<span class="input-group-text btn-info">  https://www.facebook.com/  </span>
						<input name="facebook" value="" type="text" placeholder="Facebook" class="form-control" />
					</div>
				</div>
			</div>
		</div>
<!--
		{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
		    {foreach $T_SECTION_TPL['permission'] as $template}
		        {include file=$template}
		    {/foreach}
		{/if}
-->
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
{/block}

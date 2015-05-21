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
				<div class="form-group file-upload-me" data-fileupload-url="/module/dropbox/upload/image" data-fileupload-field="logo_id">
					<label class="control-label">{translateToken value="Current Logo"}</label>
					<span class="preview">
					<!-- INJECT HERE CURRENT LOGO -->
					</span>
					<br />
					<label class="control-label">{translateToken value="New Logo"}</label>
					<span class="btn btn-primary fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>{translateToken value="Add file"}</span>
                        <input type="file" name="files[]">
                    </span>
	                <ul class="list-group ui-sortable margin-top-20">
	                </ul>

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
						                <i class="fa fa-check"></i>
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
				</div>
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

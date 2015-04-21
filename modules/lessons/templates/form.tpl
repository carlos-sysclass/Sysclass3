{extends file="layout/default.tpl"}
{block name="content"}
<div id="form-{$T_MODULE_ID}">
<form role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>

			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Content"}</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">

					<div class="form-group">
						<label class="control-label">{translateToken value="Name"}</label>
						<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
					</div>
					<div class="form-group">
						<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
						<label class="control-label">{translateToken value="Class"}</label>
						<select class="select2-me form-control" name="class_id" data-rule-required="1" data-rule-min="1">
							{foreach $T_CLASSES as $classe}
								<option value="{$classe.id}">{$classe.name}</option>
							{/foreach}
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">{translateToken value="Active"}</label>
						<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
					</div>
			</div>
			<div class="tab-pane fade in" id="tab_1_2">
				<div class="panel-group accordion" id="content-accordion-{$T_MODULE_ID}">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#content-accordion-{$T_MODULE_ID}" href="#collapse_1">{translateToken value="Video Lesson"} </a>
							</h4>
						</div>
						<div id="collapse_1" class="panel-collapse in">
							<div class="panel-body" id="video-file-upload-widget">
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
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#content-accordion-{$T_MODULE_ID}" href="#collapse_2">{translateToken value="Materials"}</a>
							</h4>
						</div>
						<div id="collapse_2" class="panel-collapse collapse">
							<div class="panel-body" id="material-file-upload-widget">


								<ul id="material-file-list" class="list-group ui-sortable">
								</ul>

								<div>
									<span class="btn btn-primary fileinput-button">
										<i class="fa fa-plus"></i>
										<span>Select files...</span>
										<input type="file" name="files_materials[]" multiple="true">
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
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#content-accordion-{$T_MODULE_ID}" href="#collapse_2">{translateToken value="Exercices"}</a>
							</h4>
						</div>
						<div id="collapse_3" class="panel-collapse collapse">
							<div class="panel-body">
								{translateToken value="Under Construction"}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success save-action" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
</div>
<!--
<script type="text/template" id="file-upload-new-video-item">
	<li class="row">
		<div class="col-md-9 file-name"></div>
		<div class="col-md-3">
			<div id="progress" style="height: 15px;">
				<div class="progress-bar progress-bar-success"></div>
			</div>
			<span class="btn btn-success fileinput-button" style="display: none">
				<i class="glyphicon glyphicon-plus"></i>
				<span>Select files...</span>

			</span>
		</div>
	</li>
</script>
-->
<script type="text/template" id="file-upload-widget-item">
	<li <% if (typeof index !== 'undefined') { %>data-fileindex="<%= index %>" <% } %> class="list-file-item draggable <% if (typeof url !== 'undefined') { %>green-stripe<% } else { %>red-stripe<% } %>">

	    	<a href="<% if (typeof url !== 'undefined') { %><%= url %><% } else { %>javascript: void(0);<% } %>" target="_blank"><%= name %></a>
	    	[ <%= (size / 1024) + " kb" %> ]
	    	<div class="list-file-item-options">
		    <% if (typeof id !== 'undefined') { %>
	            <a class="btn btn-sm btn-danger remove-file-action" data-file-id="<%= id %>" href="javascript: void(0);">
	                <i class="fa fa-trash"></i>
	            </a>
	        <% } %>
	        </div>

	</li>
</script>


<!--
<a id="teste" href="http://local.beta.sysclass.com/files/lessons/1/video/login-background%20%2822%29.mp4" class="btn btn-default" data-toggle="modal" data-target="#filemodal">VIDEO</a>

<div class="modal fade" id="filemodal" tabindex="-1" role="dialog" aria-labelledby="videoModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div>
          <iframe width="100%" height="350" src=""></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
-->
{/block}



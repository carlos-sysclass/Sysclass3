{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
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
							<div class="panel-body">

								<ul id="video-file-list" class="list-unstyled">

								</ul>

								<div class="video-file-upload-widget">
									<button class="btn blue upload-new-video-file input-block-level">
		            					<i class="fa fa-upload"></i>
		            					<span>Start</span>
							        </button>
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
							<div class="panel-body">
								<ul id="material-file-list" class="list-unstyled">

								</ul>

								<div class="material-file-upload-widget">
									<button class="btn blue upload-new-material-file input-block-level">
		            					<i class="fa fa-upload"></i>
		            					<span>Start</span>
							        </button>
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
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
<script type="text/template" id="file-upload-new-item">
	<li class="row">
		<div class="col-md-9 file-name"></div>
		<div class="col-md-3">
			<div id="progress" style="height: 15px;">
				<div class="progress-bar progress-bar-success"></div>
			</div>
			<span class="btn btn-success fileinput-button" style="display: none">
				<i class="glyphicon glyphicon-plus"></i>
				<span>Select files...</span>
				<input type="file" name="file_<%= index %>">
			</span>
		</div>
	</li>
</script>

<script type="text/template" id="file-upload-item">
	<li class="row">
		<div class="col-md-12">
			<a href="/module/lesson/view-file/<%= type %>?name=name" target="_blank"><%= name %></a>
		</div>
	</li>
</script>

{/block}



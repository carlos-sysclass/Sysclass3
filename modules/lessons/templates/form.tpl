{extends file="layout/default.tpl"}
{block name="content"}
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
				<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
					<div class="form-group">
						<label class="control-label">{translateToken value="Name"}</label>
						<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
					</div>
					<div class="form-group">
						<label class="control-label">{translateToken value="Active"}</label>
						<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
					</div>
				</form>
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

								<ul id="file-list" class="list-unstyled">
								</ul>

								<div class="file-upload-widget">
									<button class="btn blue upload-new-file input-block-level">
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
		<div class="col-md-9 file-name">scxccxzsadsad</div>
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
<script type="text/template" id="file-upload-file-item">
	<tr class="template-upload fade in">
        <td>
            <p class="name">20140805_224258-1-1.jpg</p>
            <strong class="error text-danger label label-danger"></strong>
        </td>
        <td>
            <div style="width:0%;" class="progress-bar progress-bar-success"></div>
            </div>
        </td>
        <td>
            <p class="size">239.11 KB</p>
            <div aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress progress-striped active">
        </td>
        <td>
	        <button class="btn blue start">
	            <i class="fa fa-upload"></i>
	            <span>Start</span>
	        </button>


	        <button class="btn red cancel">
	            <i class="fa fa-ban"></i>
	            <span>Cancel</span>
	        </button>
        </td>
	</tr>
</script>
{/block}



{extends file="layout/default.tpl"}
{block name="content"}
<form role="form" class="form-validate" method="post" id="form-{$T_MODULE_ID}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab-report-general" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			<li>
				<a href="#tab-report-definition" data-toggle="tab">{translateToken value="Criteria"}</a>
			</li>
			<!-- 
			<li>
				<a href="#tab-report-presentation" data-toggle="tab">{translateToken value="Presentation"}</a>
			</li>
			 -->
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab-report-general">
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Description"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="description" rows="6" placeholder="{translateToken value="Insert your description here..."}" data-rule-required="true"></textarea>
				</div>

				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1" data-value-unchecked="0" data-update-single="true">
				</div>
			</div>
			<div class="tab-pane fade in" id="tab-report-definition">
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Data source"}</label>
					<select class="select2-me form-control" name="datasource_id" data-rule-required="true" data-placeholder="{translateToken value="Select data source"}">
						<option value=""></option>
						{foreach $T_DATASOURCES as $datasource}
							<option value="{$datasource.datasource}">{$datasource.name}</option>
						{/foreach}
					</select>
				</div>


				<div class="dynamic-item dynamic-item-dynamic">
					<label class="control-label">{translateToken value="Filters"}</label>
					<div class="jquery-builder"></div>

					<label class="control-label">{translateToken value=""}</label>

					<style>
						.report-field-list {
							list-style: none;
							padding: 0;
							margin: 0;
							text-align: left
						}
						.report-field-list li {
						    border: 1px solid #ccc;
						    font-size: 0.9em;
						    margin: 3px 0.25%;
						    padding-top: 8px;
						    width: 33%;
						    cursor: pointer;
						}
						.report-field-list li.freeze {
							cursor: not-allowed;
						}
						.report-field-list li:nth-child(3n+1) {
							margin-left: 0;
						}
						.report-field-list li:nth-child(3n+0) {
							margin-right: 0;
						}

					</style>

					<div id="field-list-container">

	                    <h5 class="form-section margin-bottom-10 margin-top-10">
	                        <i class="fa fa-fields"></i>
	                        {translateToken value="Selected Fields"}

	                        <a href="javascript: void(0);" class="btn btn-sm btn-primary pull-right report-addfield">
	                            <i class="fa fa-plus" aria-hidden="true"></i>
	                            {translateToken value="Field"}
	                        </a>
	                    </h5>
						
						<div class="report-field-list">
							
						</div>
						<div class="clearfix"></div>
					</div>
										


                    <h5 class="form-section datasource-title">
                    </h5>

					<div class="backgrid-table" id="report-datatable-container">
						<table class="table table-striped table-bordered table-hover table-full-width data-table" id="report-datatable">
							<thead></thead>
							<tbody></tbody>
						</table>
					</div>

					<div class="builder-users-list"></div>
				</div>
			</div>
			<!-- 
			<div class="tab-pane fade in" id="tab-report-presentation">
			</div>
 			-->
			
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success save-action" type="button">{translateToken value="Save changes"}</button>
	</div>
</form>


<script type="text/template" id="dynamic-table-template">
	<table class="table table-striped table-bordered table-hover table-full-width data-table" id="report-datatable">
		<thead></thead>
		<tbody></tbody>
	</table>
</script>
<script type="text/template" id="dynamic-table-header-item-template">
	<th class="<%= field.sClass %> <% if (field.sType) { %> <%= field.sType %> <% } %>">
    	<% if (!field.label) { %>
        	<%= field.mData %>
        <% } else { %>
			<%= field.label %>
        <% } %>
    </th>
</script>

<script type="text/template" id="report-field-item-template">
	<i class="fa fa-<%= model.type %>"></i>
	<%= model.label %>
	<% if (!model.freeze) { %>
		<a href="javascript:void(0);" class="pull-right text-danger remove-field">
			<i class="fa fa-remove"></i>
		</a>
	<% } %>
</script>

{/block}
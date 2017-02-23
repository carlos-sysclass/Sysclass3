{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['behaviours']) &&  ($T_SECTION_TPL['behaviours']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Group Behaviour"}</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['users']) &&  ($T_SECTION_TPL['users']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Users"}</a>
			</li>
			{/if}
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Description"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="description" rows="6" placeholder="{translateToken value="Put your description here..."}" data-rule-required="true"></textarea>
				</div>
				<div class="ranges-container" id="ranges-container">
					<div class="form-group">
						<h5 class="form-section no-margin">
							{translateToken value="Grade Ranges"}
							<a class="btn btn-link btn-sm add-rule-action">
								<i class="icon-plus"></i>
								{translateToken value="New Rule"}
							</a>
            				<a href="javascript:void(0);" class="btn btn-warning btn-sm show-tips pull-right">
                				<i class="fa fa-question-circle"></i>
                				{translateToken value="Need help?"}
                			</a>
							</h5>
					</div>
				    <div class="alert alert-warning display-hide tips-container">
				        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
				        <p>
				            <strong>TIP!</strong>
				            You can create a unlimited number of of relative ranges, so you can assign a grade, like (A,B,C) based on user score.<br />
				            These rules can be applied on tests and units, providing a way to translate the user percentual score (0 - 100%) to your organization rules.
				        </p>
				    </div>
					<ul class="list-group ranges-rules-container">
					</ul>
				</div>
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
						<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
			</div>
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
	</div>
</form>
<script type="text/template" id="rule-slider-template">
	<div class="row">
		<div class="col-md-8 margin-top-10">
			<div class="noUi-control noUi-danger">
			</div>
		</div>
			<span class="btn btn-default btn-sm">
				<span class="range-begin">0</span>% - <span class="range-end">100</span>%
			</span>
		<div class="col-md-2">
			<input type="text" name="grade" class="form-control input-sm" placeholder="Grade" value="<%= model.grade %>" />
		</div>
		<div class="list-file-item-options">



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
		</div>

		<div class="clearfix"></div>
	</div>
</script>
{/block}

{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-news" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<input name="type" type="hidden" value="test" />
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">
					<i class="fa fa-question-circle"></i>
					{translateToken value="Permission"}
				</a>
			</li>
			{/if}
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab_1_1">
				<div class="form-group">
					<label class="control-label">{translateToken value="Title"}</label>
					<input name="title" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="10" />
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Content"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="data" name="data" rows="6" placeholder="{translateToken value="Put your content here..."}" data-rule-required="true"></textarea>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Publish Date"}</label>
							<input class="form-control input-small date-picker"  size="16" type="text" name="timestamp[date]" data-update="timestamp" data-format="date" data-format-from="unix-timestamp" data-rule-required="true" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Completion date"}</label>
							<input class="form-control input-small date-picker"  size="16" type="text" name="expire[date]" data-update="expire" data-format="date" data-format-from="unix-timestamp" />
						</div>
					</div>
				</div>
				<!--
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Start Date"}</label>
							<input class="form-control input-small date-picker"  size="16" type="text" name="timestamp[date]" data-update="timestamp" data-format="date" data-format-from="unix-timestamp" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Start Time"}</label>
							<input type="text" class="form-control timepicker-24 input-medium" name="timestamp[time]" data-update="timestamp" data-format="time" data-format-from="unix-timestamp">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Completion date"}</label>
							<input class="form-control input-small date-picker"  size="16" type="text" name="expire[date]" data-update="expire" data-format="date" data-format-from="unix-timestamp" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Expiration Time"}</label>
							<input type="text" class="form-control timepicker-24 input-medium" name="expires[time]" data-update="expires" data-format="time" data-format-from="unix-timestamp">
						</div>
					</div>
				</div>
				-->
			</div>
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
			    {foreach $T_SECTION_TPL['permission'] as $template}
			        {include file=$template}
			    {/foreach}
				</div>
			{/if}
		</div>

	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
	</div>
</form>
{/block}

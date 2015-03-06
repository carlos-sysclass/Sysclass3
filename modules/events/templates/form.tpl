{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">

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
				<div class="form-group">
					<label class="control-label">{translateToken value="Date"}</label>
					<input class="form-control input-small date-picker"  size="16" type="text" name="date" data-update="date" data-format="date" data-format-from="unix-timestamp" data-rule-required="true" />
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Type"}</label>
					<select class="select2-me form-control" name="type_id" data-rule-required="1" data-rule-min="1">
						{foreach $T_EVENT_TYPES as $id => $name}
							<option value="{$id}">{$name}</option>
						{/foreach}

					</select>
				</div>

				{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				{/if}

				<div class="clearfix"></div>
			</div>
		</div>

	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
{/block}

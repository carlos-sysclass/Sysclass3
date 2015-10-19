{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['users']) &&  ($T_SECTION_TPL['users']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Students"}</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-classes']) &&  ($T_SECTION_TPL['roadmap-classes']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Classes"}</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-grouping']) &&  ($T_SECTION_TPL['roadmap-grouping']|@count > 0))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">{translateToken value="Grouping"}</a>
			</li>
			{/if}

			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
			<li>
				<a href="#tab_1_5" data-toggle="tab">{translateToken value="Permissions"}</a>
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

				<div class="form-group">
					<label class="control-label">{translateToken value="Departament"}</label>
					<!--
					<input type="hidden" class="select2-me form-control input-block-level" name="area_id" data-placeholder="{translateToken value='Knowledge Area'}" data-url="/module/areas/items/me/combo" data-minimum-results-for-search="4" />
					-->
					<select class="select2-me form-control" name="area_id" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Departament'}">
					{foreach $T_KNOWLEDGE_AREAS as $knowledge}
							<option value="{$knowledge.id}">{$knowledge.name}</option>
					{/foreach}
					</select>
				</div>

				<div class="form-group">
					<label class="control-label">{translateToken value="Coordinators"}</label>

					<select class="select2-me form-control" name="coordinator_id" multiple="multiple">
						<option value="">{translateToken value="Please Select"}</option>
						{foreach $T_COORDINATORS as $id => $coordinator}
							<option value="{$coordinator.id}">{$coordinator.name} {$coordinator.surname}</option>
						{/foreach}
					</select>
				</div>

				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>
			</div>
			{if (isset($T_SECTION_TPL['users']) &&  ($T_SECTION_TPL['users']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
				    {foreach $T_SECTION_TPL['users'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_USERS_BLOCK_CONTEXT T_MODULE_ID=$T_USERS_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-classes']) &&  ($T_SECTION_TPL['roadmap-classes']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
				    {foreach $T_SECTION_TPL['roadmap-classes'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_ROADMAP_BLOCK_CONTEXT T_MODULE_ID=$T_ROADMAP_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-grouping']) &&  ($T_SECTION_TPL['roadmap-grouping']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_4">
				    {foreach $T_SECTION_TPL['roadmap-grouping'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_ROADMAP_BLOCK_CONTEXT T_MODULE_ID=$T_ROADMAP_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_5">
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
{/block}

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
			<li>
				<a href="#tab-group-definition" data-toggle="tab">{translateToken value="Definition"}</a>
			</li>
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

				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
						<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1" data-value-unchecked="0" data-update-single="true">
				</div>

				{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				{/if}

				<div class="clearfix"></div>
			</div>
			<div class="tab-pane fade in" id="tab_1_2">
				{if (isset($T_SECTION_TPL['behaviours']) &&  ($T_SECTION_TPL['behaviours']|@count > 0))}
				    {foreach $T_SECTION_TPL['behaviours'] as $template}
				        {include file=$template}
				    {/foreach}
				{/if}
			</div>
			<div class="tab-pane fade in" id="tab-group-definition">
				<div class="form-body">
					<h5 class="form-section margin-bottom-10 margin-top-10">
						<i class="fa fa-cogs"></i>
						{translateToken value="Group Type"}
						<span class="badge badge-warning tooltips pull-right" data-original-title="{translateToken value='For static groups, you can manually select the users inside this group. For dynamic groups, you select the criteria used to automaticaly select the users.'}">
					        <i class="fa fa-question"></i>
					    </span>
					</h5>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
					            <label>
					                <input type="radio" name="dynamic" data-update="dynamic" class="icheck-me" data-skin="square" data-color="green" value="0"> {translateToken value='Static'}
					            </label>
					        </div>
					    </div>
						<div class="col-md-6">
							<div class="form-group">
					            <label>
					                <input type="radio" name="dynamic" data-update="dynamic" class="icheck-me" data-skin="square" data-color="blue" value="1"> {translateToken value='Dynamic'}
					            </label>
					        </div>
					    </div>
					</div>
				</div>

				<div class="admittance-type-container">
					<div class="dynamic-item dynamic-item-static hidden">
						<!-- PUT HERE THE USER WIDGET TO ADD USERS -->

					    <div class="alert alert-info">
					        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
					        <p>
					            {translateToken value="Here you can select the current users inside this group."}
					        </p>
					    </div>
				        <div class="form-group">
				            <label class="">{translateToken value="Search for a User"}</label>
				            <input type="hidden" class="select2-me form-control col-md-12 user-search" name="user" data-placeholder="{translateToken value='Please Select'}" data-url="/module/groups/items/non-users/combo/"
				            data-format-as="default"
				            data-format-as-template="%(name)s %(surname)s <%(email)s>"
				             />
				        </div>
					    <div class="row margin-top-20">
					        <div class="col-md-12">
					            {include "`$smarty.current_dir`/blocks/table.tpl" 
					            T_MODULE_CONTEXT=$T_GROUP_DEFINITION_STATIC_CONTEXT
					            T_MODULE_ID=$T_GROUP_DEFINITION_STATIC_CONTEXT.block_id}
					        </div>
					    </div>
					</div>
					<div class="dynamic-item dynamic-item-dynamic hidden">


					    <div class="alert alert-info">
					        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
					        <p>
					            {translateToken value="Here you can select the criteria used to select the users inside this group."}
					        </p>
					    </div>

						<div class="jquery-builder"></div>

						<h5 class="form-section">
							<i class="fa fa-users"></i>
							{translateToken value="User List"}
						</h5>

						{include "`$smarty.current_dir`/blocks/table.tpl" T_MODULE_CONTEXT=$T_GROUP_DEFINITION_DYNAMIC_CONTEXT T_MODULE_ID=$T_GROUP_DEFINITION_DYNAMIC_CONTEXT.block_id}

						<div class="builder-users-list"></div>
					</div>
				</div>
			</div>


			{if (isset($T_SECTION_TPL['users']) &&  ($T_SECTION_TPL['users']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">

				    {foreach $T_SECTION_TPL['users'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_USERS_BLOCK_CONTEXT T_MODULE_ID=$T_USERS_BLOCK_CONTEXT.block_id FORCE_INIT=1}
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

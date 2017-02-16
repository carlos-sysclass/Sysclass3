{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="Step 1 - Description"}</a>
			</li>
			{if (isset($T_SECTION_TPL['moreinfo']) &&  ($T_SECTION_TPL['moreinfo']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">
					{translateToken value="Step 2 - Info"}
				</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-classes']) &&  ($T_SECTION_TPL['roadmap-classes']|@count > 0))}
			<li>
				<a href="#tab_1_4" data-toggle="tab">{translateToken value="Step 3 - Courses & Content"}</a>
			</li>
			{/if}
			
			{if (isset($T_SECTION_TPL['users']) &&  ($T_SECTION_TPL['users']|@count > 0))}
			<li>
				<a href="#tab_users" data-toggle="tab">{translateToken value="Users"}</a>
			</li>
			{/if}

			{if (isset($T_SECTION_TPL['roadmap-grouping']) &&  ($T_SECTION_TPL['roadmap-grouping']|@count > 0))}
			<li>
				<a href="#tab_1_5" data-toggle="tab">{translateToken value="Grouping"}</a>
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
					<label class="control-label">{translateToken value="Primary Language"}</label>
					<select name="language_id" class="form-control select2-me" data-placeholder="{translateToken value="Select..."}">
					{foreach $T_LANGUAGES as $key => $value}
						<option value="{$value.id}">{$value.name}</option>
					{/foreach}
					</select>
				</div>
				
			    <h5 class="form-section margin-bottom-10 margin-top-20">
			        <i class="fa fa-calendar"></i>
			        {translateToken value="Course Duration"}
			    </h5>
			    <div class="row">
			        <div class="col-md-12">
			            <div class="form-group form-group-inline">
			                <div class="inline-item">
			                    <label class="control-label">{translateToken value="Maximum"} </label>
			                </div>
			                <div class="inline-item">
			                    <input name="duration_units" value="" type="text" placeholder="{translateToken value="Units"}" class="form-control" data-rule-required="true" data-helper="integer" />
			                </div>
			                <div class="inline-item"> 
			                    <select class="select2-me form-control input-block-level" name="duration_type" style="min-width: 150px;" data-rule-required="true">
			                        <option value="">{translateToken value="Select a Period"}</option>
			                        <option value="week">{translateToken value="Week(s)"}</option>
			                        <option value="month">{translateToken value="Month(s)"}</option>
			                        <option value="year">{translateToken value="Year(s)"}</option>
			                    </select>
			                </div>
			            </div>
			        </div>
			    </div>

			    <h5 class="form-section margin-bottom-10 margin-top-20">
			        <i class="fa fa-money"></i>
			        {translateToken value="Course Prices"}
			    </h5>
			    <div class="row">
			        <div class="col-md-12">
			            <div class="form-group form-group-inline">
			                <div class="inline-item">
			                    <label class="control-label">{translateToken value="Total Price"}: </label>
			                </div>
			                <div class="inline-item">
			                    <input name="price_total" value="" type="text" placeholder="{translateToken value="Price"}" class="form-control" data-helper="float" />
			                </div>
			                <div class="inline-item">
			                    <label class="control-label">
			                    {translateToken value="Installments"}: </label>
			                </div>
 							<div class="inline-item">
			                    <input name="price_step_units" value="" type="text" placeholder="{translateToken value="Installments"}" class="form-control" data-helper="integer" />
			                </div>
			                <div class="inline-item">
			                    <label class="control-label">{translateToken value="Period"}: </label>
			                </div>
			                <div class="inline-item">
			                    <select class="select2-me form-control input-block-level" name="price_step_type" style="min-width: 150px;">
			                        <option>{translateToken value="Select a Period"}</option>
			                        <option value="week">{translateToken value="Week(s)"}</option>
			                        <option value="month">{translateToken value="Month(s)"}</option>
			                        <option value="year">{translateToken value="Year(s)"}</option>
			                    </select>
			                </div>
			            </div>
			        </div>
			    </div>

			    <h5 class="form-section margin-bottom-10 margin-top-20">
			        <i class="fa fa-cogs"></i>
			        {translateToken value="Details"}
			    </h5>

				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Description"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="description" name="description" rows="6" placeholder="{translateToken value="Put your description here..."}" data-rule-required="true"></textarea>
				</div>


			    <div class="row">
			        <div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Department"}</label>
							<select class="select2-me form-control" name="area_id" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Department'}">
								<option value="">{translateToken value="Please Select"}</option>
								{foreach $T_KNOWLEDGE_AREAS as $knowledge}
									<option value="{$knowledge.id}">{$knowledge.name}</option>
								{/foreach}
							</select>
						</div>
					</div>
			        <div class="col-md-6">
						<div class="form-group">
							<label class="control-label">{translateToken value="Active"}</label>
							<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
						</div>
					</div>
				</div>
				<div class="form-actions nobg">
					<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
				</div>

			</div>
			{if (isset($T_SECTION_TPL['moreinfo']) &&  ($T_SECTION_TPL['moreinfo']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
				    {foreach $T_SECTION_TPL['moreinfo'] as $template}
				        {include file=$template}
				    {/foreach}

					<div class="form-actions nobg">
						<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
					</div>
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-classes']) &&  ($T_SECTION_TPL['roadmap-classes']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_4">
				    {foreach $T_SECTION_TPL['roadmap-classes'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_ROADMAP_BLOCK_CONTEXT T_MODULE_ID=$T_ROADMAP_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['users']) &&  ($T_SECTION_TPL['users']|@count > 0))}
				<div class="tab-pane fade in" id="tab_users">
				    {foreach $T_SECTION_TPL['users'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_USERS_BLOCK_CONTEXT T_MODULE_ID=$T_USERS_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['roadmap-grouping']) &&  ($T_SECTION_TPL['roadmap-grouping']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_5">
				    {foreach $T_SECTION_TPL['roadmap-grouping'] as $template}
				        {include file=$template T_MODULE_CONTEXT=$T_ROADMAP_BLOCK_CONTEXT T_MODULE_ID=$T_ROADMAP_BLOCK_CONTEXT.block_id FORCE_INIT=1}
				    {/foreach}
				</div>
			{/if}
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_6">
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				</div>
			{/if}
		</div>
	</div>

</form>
{/block}

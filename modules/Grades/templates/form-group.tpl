{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['rules']) &&  ($T_SECTION_TPL['rules']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Rules"}</a>
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
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
						<input type="checkbox" name="active" class="form-control bootstrap-switch-me" data-wrapper-class="block" data-size="small" data-on-color="success" data-on-text="{translateToken value='ON'}" data-off-color="danger" data-off-text="{translateToken value='OFF'}" checked="checked" value="1">
				</div>

				{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				    {foreach $T_SECTION_TPL['permission'] as $template}
				        {include file=$template}
				    {/foreach}
				{/if}
				<div class="clearfix"></div>
			</div>
			{if (isset($T_SECTION_TPL['rules']) &&  ($T_SECTION_TPL['rules']|@count > 0))}
			<div class="tab-pane fade in" id="tab_1_2">
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<select multiple="multiple" class="multi-select multiple-select-me" id="my_multi_select2" name="my_multi_select2[]">
						<optgroup label="NFC EAST">
						<option>Dallas Cowboys</option>
						<option>New York Giants</option>
						<option>Philadelphia dsadasadsadsadadadsadsadsadadsadsadsadsadsadsads</option>
						<option>Washington Redskins</option>
						</optgroup>
						<optgroup label="NFC NORTH">
						<option>Chicago Bears</option>
						<option>Detroit Lions</option>
						<option>Green Bay Packers</option>
						<option>Minnesota Vikings</option>
						</optgroup>
						<optgroup label="NFC SOUTH">
						<option>Atlanta Falcons</option>
						<option>Carolina Panthers</option>
						<option>New Orleans Saints</option>
						<option>Tampa Bay Buccaneers</option>
						</optgroup>
						<optgroup label="NFC WEST">
						<option>Arizona Cardinals</option>
						<option>St. Louis Rams</option>
						<option>San Francisco 49ers</option>
						<option>Seattle Seahawks</option>
						</optgroup>
					</select>
				</div>
			</div>
			{/if}
			{if (isset($T_SECTION_TPL['behaviours']) &&  ($T_SECTION_TPL['behaviours']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
				    {foreach $T_SECTION_TPL['behaviours'] as $template}
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

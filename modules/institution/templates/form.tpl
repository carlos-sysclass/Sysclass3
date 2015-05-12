{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-institution" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#tab_1_1" data-toggle="tab">{translateToken value="General"}</a>
			</li>
			{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
			<li>
				<a href="#tab_1_2" data-toggle="tab">{translateToken value="Address Book"}</a>
			</li>
			{/if}
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
			<li>
				<a href="#tab_1_3" data-toggle="tab">{translateToken value="Permission Rules"}</a>
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
					<label class="control-label">{translateToken value="Full Name"}</label>
					<input name="formal_name" type="text" placeholder="Full Name" class="form-control" data-rule-required="true" data-rule-minlength="10" />
				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label">{translateToken value="Observations"}</label>
					<textarea class="wysihtml5 form-control placeholder-no-fix" id="observations" name="observations" rows="6" placeholder="{translateToken value="Put your observations here..."}" data-rule-required="true"></textarea>
				</div>
			</div>

			{if (isset($T_SECTION_TPL['address']) &&  ($T_SECTION_TPL['address']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_2">
			    {foreach $T_SECTION_TPL['address'] as $template}
			        {include file=$template}
			    {/foreach}
			    </div>
			{/if}
		
			{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
				<div class="tab-pane fade in" id="tab_1_3">
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

{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate form-horizontal form-row-seperated" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<ul class="nav nav-tabs">
			{foreach $T_SYSTEM_SETTINGS_GROUPS as $group}
				<li class="{if $group@first}active{/if}">
					<a href="#tab_{$group@key}" data-toggle="tab">
						{if is_null($group)}
							{translateToken value="Ungrouped"}
						{else}
							{translateToken value=$group}
						{/if}
					</a>
				</li>
			{/foreach}
		</ul>
		<div class="tab-content">
			{foreach $T_SYSTEM_SETTINGS_GROUPS as $group}
				<div class="tab-pane fade in {if $group@first}active{/if}" id="tab_{$group@key}">
					{foreach $T_SYSTEM_SETTINGS as $setting}
						{if $setting.group != $group}
							{continue}
						{/if}
    					{include "`$smarty.current_dir`/fields/`$setting.datatype`.tpl" T_FIELD=$setting}
						
					{/foreach}
						<!--
					<div class="row">

					</div>
					-->
					<div class="clearfix"></div>
				</div>
			{/foreach}
		</div>
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
{/block}
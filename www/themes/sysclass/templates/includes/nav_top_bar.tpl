<ul class="clearfix">
{foreach name = 'outer_menu' key = 'menu_key' item = 'menu' from = $T_MENU}
	<li>
		<a 
			href="{if $menu.link}{$menu.link}{else}javascript: void(0);{/if}" 
			class="{if $menu.options|@count > 0}has_dropdown{/if}">
			{* IMAGE *}
			{$menu.title|eF_truncate:30}
		</a>
		<ul class="dropdown ui-accordion ui-widget ui-helper-reset" role="tablist"  id="mag_list_menu{$menu_key}">
		{foreach name = 'options_list' key = 'option_id' item = 'option' from = $menu.options}
			{if isset($option.html)}
				<li class="ui-accordion-li-fix" {if $menu_key == 1 && $smarty.session.s_type != "administrator"}name="lessonSpecific"{/if}>{$option.html}</li>
			{else}
				<li class = "ui-accordion-li-fix">
					<a href = "{$option.link}" title="{$option.title}">{$option.title|eF_truncate:25}</a>
				</li>
			{/if}
		{/foreach}
		</ul>
	</li>
{/foreach}
{if isset($T_BAR_ADDITIONAL_ACCOUNTS)}
	<li>
		<a 
			href="javascript: void(0);" 
			class="{if $T_BAR_ADDITIONAL_ACCOUNTS|@count > 0}has_dropdown{/if}">
			{$smarty.const._SWITCHACCOUNT}
		</a>
		<ul class="dropdown ui-accordion ui-widget ui-helper-reset" role="tablist"  id="mag_list_menu{$T_MENU|@count}">
		{foreach name = 'additional_accounts' item = "user" key = "option_id" from = $T_BAR_ADDITIONAL_ACCOUNTS}
			<li class = "ui-accordion-li-fix">
				<a href="javascript: void(0);" onclick="changeAccount('{$user.login}');">{$user.login}</a>
			</li>
		{/foreach}
		</ul>
	</li>
{/if}	

</ul>
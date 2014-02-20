<div id="top-bar-container">	
	<div id="top-bar" class="container_24 dropdown_menu">
		<div id="top-bar" class="grid_24">
			<ul id="top-search">
				<li id="top-bar-logo">
				</li>
				<li id="top-search-icon">
					<a href="javascript: void(0);"></a>
				</li>
				<li id="top-search-widget">
					{if $smarty.server.PHP_SELF|basename == 'index.php' || $T_THEME_SETTINGS->options.sidebar_interface != 0}
						{*Search div*}
						{if $smarty.session.s_login}
							{if $smarty.session.s_type == 'administrator'}
								{assign var="search_form_action" value="`$smarty.const.G_SERVERNAME``$smarty.session.s_type`.php?ctg=control_panel&op=search"}
							{else}
								{assign var="search_form_action" value="`$smarty.const.G_SERVERNAME``$smarty.session.s_type`.php?ctg=lessons&op=search"}
							{/if}
								<form style="margin:0;padding:0;" action = "{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php?ctg=control_panel&op=search" method = "post">
									<span class="search-text-wrapper"></span>
									<input  
										type="text" 
										name="search_text"
										value = "{if isset($smarty.post.search_text)}{$smarty.post.search_text}{else}{$smarty.const._SEARCH}...{/if}"
										onclick="if(this.value=='{$smarty.const._SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{$smarty.const._SEARCH}...';"
										class = "searchBox" 
										style = ""/>
									<input type = "hidden" name = "current_location" id = "current_location" value = ""/>
								</form>
						{else}
							{*language div*}
							{$smarty.capture.header_language_code}
						{/if}
					{/if}			
				</li>
			</ul>
			<ul id="top-menu">
				<li id="top-menu-home-link">
					<a href="{$smarty.const.G_SERVERNAME}{$smarty.session.s_type}.php">Home</a><span class="spacer">&nbsp;|&nbsp;</span>
				</li>
				
				{foreach name = 'outer_menu' key = 'menu_key' item = 'menu' from = $T_MENU}
					{if $smarty.session.s_type == 'administrator' || $smarty.session.s_type == 'professor' }
						<li>
							<a 
								href="{if $menu.link}{$menu.link}{else}javascript: void(0);{/if}" 
								class="{if $menu.options|@count > 0}has_dropdown{/if}">
								{* IMAGE *}
								{$menu.title|sC_truncate:30}
							</a><span class="spacer">&nbsp;|&nbsp;</span>
							<ul class="dropdown ui-accordion ui-widget ui-helper-reset" role="tablist"  id="mag_list_menu{$menu_key}">
							
							{foreach name = 'options_list' key = 'option_id' item = 'option' from = $menu.options}
								{if isset($option.html)}
									<li class="ui-accordion-li-fix" {if $menu_key == 1 && $smarty.session.s_type != "administrator"}name="lessonSpecific"{/if}>{$option.html}</li>
								{else}
									<li class = "ui-accordion-li-fix">
										<a href = "{$option.link}" title="{$option.title}">{$option.title|sC_truncate:25}</a>
									</li>
								{/if}
							{/foreach}
							</ul>
						</li>
					{else}	
						{foreach name = 'options_list' key = 'option_id' item = 'option' from = $menu.options}
							{if isset($option.html)}
								<li class="ui-accordion-li-fix" {if $menu_key == 1 && $smarty.session.s_type != "administrator"}name="lessonSpecific"{/if}>{$option.html}{if $menu_key != 1}<span class="spacer">&nbsp;|&nbsp;</span>{/if}</li>
							{else}
								<li class = "ui-accordion-li-fix">
									<a href = "{$option.link}" title="{$option.title}">{$option.title|sC_truncate:25}</a><span class="spacer">&nbsp;|&nbsp;</span>
								</li>
							{/if}
						{/foreach}
					{/if}
				{/foreach}
				<li><a href = "{$smarty.session.s_type}.php?{if $smarty.session.s_type == "administrator"}ctg=users&edit_user={$smarty.session.s_login}{else}ctg=personal{/if}">
					{$smarty.const.__MYACCOUNT}
				</a><span class="spacer">&nbsp;|&nbsp;</span></li>
				{if $T_BAR_ADDITIONAL_ACCOUNTS|@count > 0}
				<li>
					<a href="javascript: void(0); ">{$smarty.const._SWITCHACCOUNT}</a><span class="spacer">&nbsp;|&nbsp;</span>
					<ul class="dropdown">
						{foreach name = 'additional_accounts' item = "item" key = "key" from = $T_BAR_ADDITIONAL_ACCOUNTS}
							<li><a href="javascript: changeAccount('{$item.login}');">#filter:login-{$item.login}#</a></li>
						{/foreach}
					</ul>
				</li>
				{/if}
				<li><a href="index.php?logout=true">{$smarty.const._LOGOUT}</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="clear"></div>
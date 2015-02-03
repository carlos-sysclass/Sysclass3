<div id="header">
	<div id="header-top-logo" class="grid_8">
		<a href = "{if $smarty.session.s_login}{$smarty.server.PHP_SELF}{else}index.php{/if}">
			<img src = "{$T_LOGO}" title = "{$T_CONFIGURATION.site_name}" alt = "{$T_CONFIGURATION.site_name}" border = "0">
		</a>
	</div>
	<div id="header-top-user-salutation" class="grid_16" align="right">
		<div>
			<img 
				src = "view_file.php?file={$T_CURRENT_USER_AVATAR.avatar}" 
				title="{$smarty.const._CURRENTAVATAR}" 
				alt="{$smarty.const._CURRENTAVATAR}" 
				width = "{$T_CURRENT_USER_AVATAR.width}" 
				height = "{$T_CURRENT_USER_AVATAR.height}"
				BORDER="1"
			/>
		</div>
		<div>
			<h3>{$smarty.const.__WELCOME} {$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</h3>
			<p>
			{if $T_CURRENT_USER->coreAccess.dashboard != 'hidden'}
				{$smarty.const.__LOGGEDINAS} 
				<a href = "{$smarty.session.s_type}.php?{if $smarty.session.s_type == "administrator"}ctg=users&edit_user={$smarty.session.s_login}{else}ctg=personal{/if}" class="headerText">{$T_CURRENT_USER_TYPE}</a>
			{else}
				{$smarty.const.__LOGGEDINAS} {$T_CURRENT_USER_TYPE}
			{/if}
			<p>
		</div>
	</div>
	
	{if !$hide_path}
	<!-- 
	<div id="header-breadcrumbs" class="grid_24">
		{$smarty.const.__YOUAREIN_} {$title|sC_formatTitlePath}
		<div id = "tab_handles_div" style = "float:right;">
			{if $T_THEME_SETTINGS->options.sidebar_interface == 0 || $T_HEADER_CLASS == 'headerHidden'}
				{$smarty.capture.t_path_additional_code}
			{/if}
		</div>
		<div id = "path_language">
		   	{$smarty.capture.header_language_code}
	   	</div>
	</div>
	 -->
	{/if}	
</div>

{if $T_CONFIGURATION.updater_period}<script> var updaterPeriod = '{$T_CONFIGURATION.updater_period}';</script>{else}<script>var updaterPeriod = 100000;</script>{/if}

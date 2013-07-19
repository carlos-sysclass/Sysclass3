{if !$smarty.get.popup && !$T_POPUP_MODE && $smarty.server.PHP_SELF|@basename != 'index.php'}
	{*include file = "includes/fixed_menu.tpl"*}

	{include file = "includes/barra_topo.tpl"}


	<!-- Content -->
	<section id="content" class="wrap">
		<div class="container_24">
			{if $T_NO_HEADER_MODE}
				<div class="logo clear">
					<img 
						src="{$T_LOGO}" 
						class="picture"
						title="{$T_CONFIGURATION.site_name}" 
						alt="{$T_CONFIGURATION.site_name}" 
						border="0"
						/>
				</div>
			{else}
				{include file = "includes/header_code.tpl"}
			{/if}
	
			{if $smarty.session.s_type != 'student' && (!$layoutClass || strpos($layoutClass, 'hideRight') !== false)}
				<div class="grid_8">
					{$smarty.capture.left_code}
				</div>
				<div class="grid_16">
					{$smarty.capture.center_code}
				</div>
			{elseif $smarty.session.s_type != 'student' && (!$layoutClass || strpos($layoutClass, 'hideLeft') !== false)}
				<div class="grid_16">
					{$smarty.capture.center_code}
				</div>
				<div class="grid_8">
					{$smarty.capture.right_code}
				</div>
			{else}
				{$smarty.capture.center_code}
			{/if}
		</div>
		{include file = "includes/common_dialogs.tpl"}
	</section>

	{include file = "includes/footer_code.tpl"}

{else}
	{$smarty.capture.center_code}
	{if $smarty.server.PHP_SELF|@basename == 'index.php'}		
		{*include file = "includes/footer_code.tpl"*}
	{/if}
{/if}
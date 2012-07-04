{if !$smarty.get.popup && !$T_POPUP_MODE && $smarty.server.PHP_SELF|@basename != 'index.php'}
	{include file = "includes/fixed_menu.tpl"}
	<div id="wrapper" class="container_24">
		{include file = "includes/header_code.tpl"}

		{$smarty.capture.center_code}
		
		{include file = "includes/footer_code.tpl"}
	</div>
{else}
		{$smarty.capture.center_code}
		{if $smarty.server.PHP_SELF|@basename == 'index.php'}		
			{include file = "includes/footer_code.tpl"}
		{/if}
{/if}
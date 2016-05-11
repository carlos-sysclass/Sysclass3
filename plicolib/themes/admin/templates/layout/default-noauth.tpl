<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
<head>
	{block name="head"}
		{include file="block/head.tpl"}
	{/block}
</head>
<body>

	<!-- Start Content -->
	<div class="container-fluid {$smarty.const.MENU_POSITION} {$T_CONFIG['container_class']}">
		{block name="navbar"}
			{include file="block/navbar-noauth.tpl"}
		{/block}
		{include file="block/message-info.tpl"}


		{block name="content"} {/block}

	</div>

	{block name="footer"}
		{include file="block/footer.tpl"}
	{/block}
</body>
</html>
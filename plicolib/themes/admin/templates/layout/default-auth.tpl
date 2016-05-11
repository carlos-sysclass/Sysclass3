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
			{include file="block/navbar-auth.tpl"}
		{/block}

		
		<div id="wrapper">
			{block name="menu"}
				{include file="block/menu-auth.tpl"}
			{/block}
			<div id="content">
				{block name="breadcrumb"}
					{include file="block/breadcrumb.tpl"}
				{/block}
				{include file="block/message-info.tpl"}
				{block name="page-title"}
					<div class="separator bottom"></div>
					<div class="heading-buttons">
						<h3>{$T_PAGE_TITLE}
						{if isset($T_PAGE_DESCRIPTION)}
						<span class="hidden-phone">| {$T_PAGE_DESCRIPTION}</span>
						{/if}
						</h3>
						{if isset($T_LINKS)}
							{foreach item="link" from=$T_LINKS}
							<div class="buttons pull-right">
								<a href="{$link.link}" class="btn {if isset($link.type)}btn-{$link.type}{else}btn-primary{/if} btn-icon glyphicons {$link.icon}  pull-left"><i></i> {$link.text}</a>
							</div>
							{/foreach}
						{/if}
					</div>
					<div class="separator bottom"></div>
				{/block}


				{block name="content"} {/block}
			<!-- End Content -->
			</div>
		<!-- End Wrapper -->
		</div>
		
	</div>

	{block name="footer"}
		{include file="block/footer.tpl"}
	{/block}
</body>
</html>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	{block name="head"}
		{include file="block/head.tpl"}
	{/block}
</head>
<body class="page-boxed page-header-fixed">
	<!-- BEGIN HEADER -->
	{block name="topbar"}
		{include file="block/topbar.tpl"}
	{/block}
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="container">
		<div class="page-container">
			<!-- BEGIN PAGE -->
			<div class="page-content">
				{if isset($T_MESSAGE)}
				<div class="alert alert-{$T_MESSAGE.type} alert-dismissable">
					<button data-dismiss="alert" class="close" type="button"></button>
					{$T_MESSAGE.message}
				</div>
				{/if}
					{block name="page-title"}
						{include file="block/page-title.tpl"}
					{/block}
					{block name="breadcrumb"}
						{include file="block/breadcrumb.tpl"}
					{/block}
				{block name="content"}
				{/block}
			</div>
			<!-- END PAGE -->
		</div>
		<!-- END CONTAINER -->
		{block name="underscore-templates"}
		{* MAKE A WAY TO INJECT TOOLTIPS ON OPTIONS OBJECTS *}
		<script type="text/template" id="datatables-options-template">
			<a class="datatable-option-<%= key %> btn <% if (item.class != undefined) { %><%= item.class %><% } else { %>btn-default<% } %>" href="<% if (item.link != undefined) { %><%= item.link %><% } else { %>#<% } %>">
				<% if (item.icon != undefined) { %>
					<i class="<%= item.icon %>"></i>
				<% } %>
				<% if (item.text != undefined) { %>
					<%= item.text %>
				<% } %>
			</a>
		</script>
		{/block}
		<!-- BEGIN FOOTER -->
		{block name="footer"}
			{include file="block/footer.tpl"}
		{/block}
		{block name="foot"}
			{include file="block/foot.tpl"}
		{/block}
	</div>
	<div id="off-windows">
	</div>

	{if (isset($T_SECTION_TPL['bottom']) &&  ($T_SECTION_TPL['bottom']|@count > 0))}
	    {foreach $T_SECTION_TPL['bottom'] as $template}
	        {include file=$template}
	    {/foreach}
	{/if}

</body>
</html>
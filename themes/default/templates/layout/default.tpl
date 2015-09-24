<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	{block name="head"}
		{include file="block/head.tpl"}
	{/block}
	<script>
		_before_init_functions = [];
		_lazy_init_functions = [];
	</script>
</head>
<body class="page-boxed page-header-fixed">
	{if (isset($T_SECTION_TPL['dialogs']) &&  ($T_SECTION_TPL['dialogs']|@count > 0))}
	    <div id="dialogs-tempĺates">
	    {foreach $T_SECTION_TPL['dialogs'] as $template}
	        {include file=$template}
	    {/foreach}
	    </div>
	{/if}

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
				<a
					class="datatable-option-<%= key %> btn <% if (item.class != undefined) { %><%= item.class %><% } else { %>btn-default<% } %>"
					href="<% if (item.link != undefined) { %><%= item.link %><% } else { %>javascript: void(0);<% } %>"
					<% if (item.action != undefined) { %>data-action-url="<%= item.action %>"<% } %>
					<% if (item.method != undefined) { %>data-action-method="<%= item.method %>"<% } %>
					<% if (key == "remove") { %>
	                    data-toggle="confirmation"
	                    data-original-title="{translateToken value="Are you sure?"}"
	                    data-placement="left"
	                    data-singleton="true"
	                    data-popout="true"
	                    data-btn-ok-icon="fa fa-trash"
	                    data-btn-ok-class="btn-sm btn-danger"
	                    data-btn-cancel-icon="fa fa-times"
	                    data-btn-cancel-class="btn-sm btn-warning"
	                    data-btn-ok-label="{translateToken value="Yes"}"
	                    data-btn-cancel-label="{translateToken value="No"}"
					<% } else { %>

					<% } %>
					<% if (item.attrs != undefined) { %>
						<% _.each(item.attrs, function(value, tag) { %>
							<%= tag %>="<%= value %>"
						<% }); %>
					<% } %>
				>
				<% if (item.icon != undefined) { %>
					<i class="<%= item.icon %>"></i>
				<% } %>
				<% if (item.text != undefined) { %>
					<%= item.text %>
				<% } %>
			</a>
		</script>
		<script type="text/template" id="datatables-options-template">
			<a
				class="datatable-option-<%= key %> btn <% if (item.class != undefined) { %><%= item.class %><% } else { %>btn-default<% } %>"
				href="<% if (item.link != undefined) { %><%= item.link %><% } else { %>#<% } %>"
				<% if (item.attrs != undefined) { %>
					<% _.each(item.attrs, function(value, tag) { %>
						<%= tag %>="<%= value %>"
					<% }); %>
				<% } %>
			>
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

	<div class="ajax-loader">
	    <div class="inner-ajax-loader">
	        <img src="{Plico_GetResource file='img/ajax-loader.gif'}">
	        <span class="hidden-xs inline">{translateToken value="Loading"}</span>
	    </div>
	</div>

</body>
</html>

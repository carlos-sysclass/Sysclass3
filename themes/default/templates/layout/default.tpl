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
<body class="page-boxed page-header-fixed backstrech-me" data-backstrech-fade="1000"  data-backstrech-duration="5000" data-backstrech-images='["{Plico_GetResource file='img/bg/default2.jpg'}"]'>

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
		<script type="text/template" id="datatables-option-default-template">
			<% if (item.key == "remove") { %>
			<div
				class="btn-group datatable-option-remove" 
				data-placement="left"
                data-toggle="confirmation"
                data-original-title="{translateToken value="Are you sure?"}"
                data-confirmation-placement="left"
                data-singleton="true"
                data-popout="true"
                data-btn-ok-icon="fa fa-trash"
                data-btn-ok-class="btn-sm btn-danger"
                data-btn-cancel-icon="fa fa-times"
                data-btn-cancel-class="btn-sm btn-warning"
                data-btn-ok-label="{translateToken value="Yes"}"
                data-btn-cancel-label="{translateToken value="No"}"
			>
			<% } %>
				<a
					class="<% if (item.key != "remove") { %>datatable-option-<%= item.key %><% } %> btn <% if (item.class != undefined) { %><%= item.class %><% } else { %>btn-default<% } %>"
					href="<% if (item.link != undefined) { %><%= item.link %><% } else { %>javascript: void(0);<% } %>"
					<% if (item.action != undefined) { %>data-action-url="<%= item.action %>"<% } %>
					<% if (item.method != undefined) { %>data-action-method="<%= item.method %>"<% } %>
					<% if (item.attrs != undefined) { %>
						<% _.each(item.attrs, function(value, tag) { %>
							<%= tag %>="<%= value %>"
						<% }); %>
					<% } %>

					<% if (item.key == "remove") { %>
					<% } else { %>
						data-datatable-action="<%= item.key %>"
					<% } %>
					data-container="body"
				>
					<% if (item.icon != undefined) { %>
						<i class="<%= item.icon %>"></i>
					<% } %>
					<% if (item.text != undefined) { %>
						<%= item.text %>
					<% } %>
				</a>
			<% if (item.key == "remove") { %>
			</div>
			<% } %>
		</script>
		<script type="text/template" id="datatables-option-switch-template">
	        <input 
	        	name="datatable-switch-<%= item.key %>"
	        	type="checkbox" 
	        	class="form-control bootstrap-switch-me datatable-option-switch"
	        	data-size="small"
				<% if (item.attrs != undefined) { %>
					<% _.each(item.attrs, function(value, tag) { %>
						<%= tag %>="<%= value %>"
					<% }); %>
				<% } %>
	        	value="1"
	        	<% if (item.state == "enabled") { %>
	        	checked="checked"
	        	<% } %>
	        	data-value-unchecked="0" />
	       
		</script>
		<!--
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
		-->

		{/block}
		{block name="quick-sidebar"}
			{if (isset($T_SECTION_TPL['sidebar']) &&  ($T_SECTION_TPL['sidebar']|@count > 0))}
				<a class="page-quick-sidebar-toggler" href="javascript:;">
    	            <i class="icon-login"></i>
	            </a>

	            <div class="page-quick-sidebar-wrapper" data-close-on-body-click="true" id="page-quick-sidebar">
	                <div class="page-quick-sidebar">
						<ul class="nav nav-tabs">
	                        <li class="active">
	                            <a data-toggle="tab" data-target="#quick_sidebar_tab_1" href="javascript:;" aria-expanded="true">{translateToken value="Chats"}
	                                <!-- <span class="badge badge-danger">2</span> -->
	                            </a>
	                        </li>
	                        <!-- 
	                        <li class="">
	                            <a data-toggle="tab" data-target="#quick_sidebar_tab_1" href="javascript:;" aria-expanded="true"> Alerts
	                        </a>
	                        </li> -->
	                    </ul>

		                {foreach $T_SECTION_TPL['sidebar'] as $template}
		                	<div id="quick_sidebar_tab_1" class="tab-pane page-quick-sidebar-chat active">
	        					{include file=$template}
	        				</div>
    					{/foreach}
	                </div>
	            </div>
        	{/if}
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

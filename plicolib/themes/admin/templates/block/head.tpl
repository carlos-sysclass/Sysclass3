	<title>{$T_CLIENT_NAME} | {$T_PAGE_TITLE}</title>
	
	<!-- Meta -->
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	<!-- Bootstrap -->
	<link href="{Plico_GetResource file='bootstrap/css/bootstrap.min.css'}" rel="stylesheet" />
	<link href="{Plico_GetResource file='bootstrap/css/bootstrap-responsive.min.css'}" rel="stylesheet" />
	
	<!-- Bootstrap Extended -->
	<link href="{Plico_GetResource file='bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css'}" rel="stylesheet">
	<link href="{Plico_GetResource file='bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css'}" rel="stylesheet">
	<link href="{Plico_GetResource file='bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css'}" rel="stylesheet">
	
	<!-- JQueryUI v1.9.2 -->
	<link rel="stylesheet" href="{Plico_GetResource file='theme/scripts/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.min.css'}" />
	
	<!-- Glyphicons -->
	<link rel="stylesheet" href="{Plico_GetResource file='theme/css/glyphicons.css'}" />
	
	<!-- Bootstrap Extended -->
	<link rel="stylesheet" href="{Plico_GetResource file='bootstrap/extend/bootstrap-select/bootstrap-select.css'}" />
	<link rel="stylesheet" href="{Plico_GetResource file='bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css'}" />
	
	<!-- Uniform -->
	<link rel="stylesheet" media="screen" href="{Plico_GetResource file='theme/scripts/pixelmatrix-uniform/css/uniform.default.css'}" />

	<!-- JQuery v1.8.2 -->
	<script src="{Plico_GetResource file='theme/scripts/jquery-1.8.2.min.js'}"></script>
	
	<!-- Modernizr -->
	<script src="{Plico_GetResource file='theme/scripts/modernizr.custom.76094.js'}"></script>

	<!-- Momentjs -->
	<script src="{Plico_GetResource file='js/moment.min.js'}"></script>
	<script src="{Plico_GetResource file='js/moment.pt-br.js'}"></script>

	<!-- select2 -->
	<link rel="stylesheet" href="{Plico_GetResource file='css/select2.css'}" />
	<script src="{Plico_GetResource file='js/select2.js'}"></script>
	<script src="{Plico_GetResource file='js/select2_locale_pt-BR.js'}"></script>
	
	<!-- MiniColors -->
	<link rel="stylesheet" media="screen" href="{Plico_GetResource file='theme/scripts/jquery-miniColors/jquery.miniColors.css'}" />
	
	<!-- Notyfy -->
	<script type="text/javascript" src="{Plico_GetResource file='theme/scripts/notyfy/jquery.notyfy.js'}"></script>
	<link rel="stylesheet" href="{Plico_GetResource file='theme/scripts/notyfy/jquery.notyfy.css'}"/>
	<link rel="stylesheet" href="{Plico_GetResource file='theme/scripts/notyfy/themes/default.css'}"/>

	<link rel="stylesheet" href="{Plico_GetResource file='css/plico-notify.css'}"/>
	
	<!-- Gritter -->
	<!--
	<link rel="stylesheet" href="{Plico_GetResource file='theme/scripts/Gritter/css/jquery.gritter.css'}"/>
	<script type="text/javascript" src="{Plico_GetResource file='theme/scripts/Gritter/js/jquery.gritter.min.js'}"></script>
	-->
	<!-- google-code-prettify -->
	<!--
	<link href="{Plico_GetResource file='theme/scripts/google-code-prettify/prettify.css'}" type="text/css" rel="stylesheet" />
	-->
{if $T_CONFIG['dev']}
	<!-- Theme -->
	<link rel="stylesheet/less" href="{Plico_GetResource file='theme/less/style.less'}?{$T_CONFIG['gen_time']}" />
	
	{if $T_CONFIG['skin']}
		<!-- Skin -->
		<link rel="stylesheet/less" href="{Plico_GetResource file="theme/skins/less/`$T_CONFIG['skin']`.less"}?{$T_CONFIG['gen_time']}" />
	{/if}
{else}
	<!-- Theme -->
	<link rel="stylesheet" href="{Plico_GetResource file='theme/css/style.css'}?{$T_CONFIG['gen_time']}" />
	{if $T_CONFIG['skin']}	
		<!-- Skin -->
		<link rel="stylesheet" href="{Plico_GetResource file="theme/skins/css/`$T_CONFIG['skin']`.min.css"}?{$T_CONFIG['gen_time']}" />
	{/if}
{/if}

{if $T_CONFIG['dev']}
	<!-- FireBug Lite -->
	<!-- <script type="text/javascript" src="https://getfirebug.com/firebug-lite-debug.js"></script> -->
	<script src="{Plico_GetResource file='theme/scripts/less-1.3.3.min.js'}"></script>
{/if}
	<!-- LESS 2 CSS -->
	

	{foreach item="css" from=$T_STYLESHEETS}
		<link rel="stylesheet" href="{Plico_GetResource file=$css}" />
	{/foreach}
	
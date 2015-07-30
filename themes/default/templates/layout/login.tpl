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
<!-- BEGIN BODY -->
<body class="login backstrech-me" data-backstrech-fade="1000"  data-backstrech-duration="5000" data-backstrech-images='["{Plico_GetResource file='img/bg/default.jpg'}"]'>
	<!-- BEGIN LOGO -->
	<div class="logo">
		<!--<img src="{Plico_GetResource file='img/logo.png'}" alt="" /> -->
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	{block name="content"}
	<div class="content"></div>
	{/block}
	<!-- END LOGIN -->
	{block name="foot"}
		{include file="block/foot.tpl"}
	{/block}
</body>
<!-- END BODY -->
</html>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	{block name="head"}
		{include file="block/head.tpl"}
	{/block}
	<link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
</head>
<!-- BEGIN BODY -->
<body>
	<!-- BEGIN LOCK -->
	{block name="content"}
	{/block}
	<!-- END LOCK -->
	{block name="foot"}
		{include file="block/foot.tpl"}
	{/block}
</body>
<!-- END BODY -->
</html>
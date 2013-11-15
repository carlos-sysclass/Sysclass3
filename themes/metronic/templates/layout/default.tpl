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
<body class="page-boxed">
   <div class="container">
   <!-- BEGIN HEADER -->
      {block name="topbar"}
         {include file="block/topbar.tpl"}
      {/block}
      <!-- END HEADER -->
      <div class="clearfix"></div>
      <!-- BEGIN CONTAINER -->
   
      <div class="page-container">
         <!-- BEGIN PAGE -->
         <div class="page-content">
         	{block name="content"}
            {/block}
         </div>
         <!-- END PAGE -->
      </div>
   
   	<!-- END CONTAINER -->
   	<!-- BEGIN FOOTER -->
   	{block name="footer"}
   		{include file="block/footer.tpl"}
   	{/block}
   	{block name="foot"}
   		{include file="block/foot.tpl"}
   	{/block}
   </div>
<!-- END BODY -->
</html>
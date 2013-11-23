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
            {block name="breadcrumb"}
            <!--
            <div class="row">
               <div class="col-md-12">
                  <ul class="page-breadcrumb breadcrumb">
                     <li class="btn-group">
                        <button data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" class="btn blue dropdown-toggle" type="button">
                        <span>Actions</span> <i class="icon-angle-down"></i>
                        </button>
                        <ul role="menu" class="dropdown-menu pull-right">
                           <li><a href="#">Action</a></li>
                           <li><a href="#">Another action</a></li>
                           <li><a href="#">Something else here</a></li>
                           <li class="divider"></li>
                           <li><a href="#">Separated link</a></li>
                        </ul>
                     </li>
                     <li>
                        <i class="icon-home"></i>
                        <a href="index.html">Home</a> 
                        <i class="icon-angle-right"></i>
                     </li>
                     <li><a href="#">Inbox</a></li>
                  </ul>
               </div>
            </div>
            -->
            {/block}
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
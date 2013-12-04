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
   <div id="off-windows">
   <!--
      <div class="chat-widget">
         <div class="portlet box dark-blue">
            <div class="portlet-title">
               <div class="caption"><i class="icon-comments"></i>test</div>
               <div class="tools">
                  <a href="javascript:;" class="expand"></a>
                  <a href="javascript:;" class="remove"></a>
               </div>
            </div>
            <div class="portlet-body" style="display: none;">
               <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                  <ul data-always-visible="1" data-height="200px" class="scroller chat-contents" style="overflow: hidden; width: auto; height: 200px;">
                     <li>
                        <div class="subject">
                           <span class="label label-default">Lisa Wong</span>
                           <span class="badge badge-primary badge-roundless pull-right">Just Now</span>
                        </div>
                        <div class="message">fdjksajfdassdsasddksljfksldjfkdls</div>
                        <hr>
                     </li>
                  </ul>
                  <div class="slimScrollBar" style="background: none repeat scroll 0% 0% rgb(161, 178, 189); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 111.111px;"></div>
                  <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: none repeat scroll 0% 0% rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
               </div>
               <div class="send-block">
                  <div class="input-icon right">
                     <i class="icon-microphone"></i>
                     <input type="text" class="form-control">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="chat-widget">
         <div class="portlet box dark-blue">
            <div class="portlet-title">
               <div class="caption"><i class="icon-comments"></i>test</div>
               <div class="tools">
                  <a href="javascript:;" class="expand"></a>
                  <a href="javascript:;" class="remove"></a>
               </div>
            </div>
            <div class="portlet-body" style="display: none;">
               <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                  <ul data-always-visible="1" data-height="200px" class="scroller chat-contents" style="overflow: hidden; width: auto; height: 200px;">
                     <li>
                        <div class="subject">
                           <span class="label label-default">Lisa Wong</span>
                           <span class="badge badge-primary badge-roundless pull-right">Just Now</span>
                        </div>
                        <div class="message">fdjksajfdassdsasddksljfksldjfkdls</div>
                        <hr>
                     </li>
                  </ul>
                  <div class="slimScrollBar" style="background: none repeat scroll 0% 0% rgb(161, 178, 189); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 111.111px;"></div>
                  <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: none repeat scroll 0% 0% rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
               </div>
               <div class="send-block">
                  <div class="input-icon right">
                     <i class="icon-microphone"></i>
                     <input type="text" class="form-control">
                  </div>
               </div>
            </div>
         </div>
      </div>
   -->
   </div>
<!-- END BODY -->
</html>
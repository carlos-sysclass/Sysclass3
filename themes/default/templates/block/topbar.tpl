<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=304180646448346";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="header navbar-inverse navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner container">
		<!-- BEGIN LOGO -->
		<a class="navbar-brand" href="/dashboard">
			<img src="{Plico_GetResource file='img/logo-sysclass.png'}" alt="logo" class="img-responsive hidden-xs" />
			<img src="{Plico_GetResource file='img/logo-sysclass-small.png'}" alt="logo" class="img-responsive visible-xs" />
		</a>

		<div class="navbar-text fb-like" data-href="https://www.facebook.com/sysclass" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<!--
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		   <img src="{Plico_GetResource file='img/menu-toggler.png'}" alt="" />
		</a>
		-->
		<!-- END RESPONSIVE MENU TOGGLER -->

		<!-- BEGIN TOP NAVIGATION MENU -->
		{*$T_TOPBAR_MENU|@json_encode*}
		<ul class="nav navbar-nav pull-right">



			{foreach $T_TOPBAR_MENU as $key => $item}
				{if $item.type == "mega"}
					{if $item.items|count > 0 } 
						<li class="dropdown mega-menu-dropdown">
							<a data-toggle="dropdown" href="javascript:;" class="dropdown-toggle" data-close-others="true">
								{if $item.icon}
					      		<i class="{$item.icon}"></i>
					      		{/if}
					      		<span class="hidden-xs">{$item.text}</span>
							</a>
							<ul class="dropdown-menu mega-menu-container">
								<li>
									<!-- Content container to add padding -->
									<div class="mega-menu-content">
										<div class="row">
											{foreach $item.items as $subkey => $subitems}
												<div class="col-md-3  col-sm-3">
													<ul class="mega-menu-submenu">
														<li>
															<h3>{$subkey}</h3>
														</li>
														{foreach $subitems as $subitem}
															<li>
																<a href="{$subitem.link}">
																	{if $subitem.icon}
														      			<i class="{$subitem.icon}"></i>
														      		{else}
														      			<i class="fa fa-angle-right"></i>
														      		{/if}
																	{$subitem.text}
																</a>
															</li>
														{/foreach}
													</ul>
												</div>
											{/foreach}
											<!--
											<div class="col-md-4">
												<ul class="mega-menu-submenu">
													<li>
														<h3>More Layouts</h3>
													</li>
													<li>
														<a href="layout_horizontal_menu2.html">
														Horizontal Mega Menu 2 </a>
													</li>
													<li>
														<a href="layout_search_on_header1.html">
														Search Box On Header 1 </a>
													</li>
													<li>
														<a href="layout_search_on_header2.html">
														Search Box On Header 2 </a>
													</li>
													<li>
														<a href="layout_sidebar_search_option1.html">
														Sidebar Search Option 1 </a>
													</li>
													<li>
														<a href="layout_sidebar_search_option2.html">
														Sidebar Search Option 2 </a>
													</li>
													<li>
														<a href="layout_sidebar_reversed.html">
														Right Sidebar Page </a>
													</li>
													<li>
														<a href="layout_sidebar_fixed.html">
														Sidebar Fixed Page </a>
													</li>
												</ul>
											</div>
											<div class="col-md-4">
												<ul class="mega-menu-submenu">
													<li>
														<h3>Even More!</h3>
													</li>
													<li>
														<a href="layout_sidebar_closed.html">
														Sidebar Closed Page </a>
													</li>
													<li>
														<a href="layout_ajax.html">
														Content Loading via Ajax </a>
													</li>
													<li>
														<a href="layout_disabled_menu.html">
														Disabled Menu Links </a>
													</li>
													<li>
														<a href="layout_blank_page.html">
														Blank Page </a>
													</li>
													<li>
														<a href="layout_boxed_page.html">
														Boxed Page </a>
													</li>
													<li>
														<a href="layout_language_bar.html">
														Language Switch Bar </a>
													</li>
												</ul>
											</div>
											-->
										</div>
									</div>
								</li>
							</ul>
						</li>
					{/if}
				{elseif $item.template && (isset($T_SECTION_TPL[$item.template]) &&  ($T_SECTION_TPL[$item.template]|@count > 0))}
					{foreach $T_SECTION_TPL[$item.template] as $template}
						{include file=$template T_MENU_ITEM=$item T_MENU_INDEX=$key}
					{/foreach}
    			{elseif $item.extended}
    			<li class="dropdown hidden-xs">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
			      		<i class="icon-{$item.icon}"></i>
			      		<span class="badge">{$item.notif}</span>
			      	</a>
			      	<ul class="dropdown-menu extended {$item.type}">
			        	<li>
			            	<p>{$item.text}</p>
			         	</li>
			         	<li>
				         	{if $item.items|@count > 0}
					            <ul class="dropdown-menu-list scroller" style="height: 250px;">
					            	{foreach $item.items as $subitem}
					            		{if $item.type == 'inbox'}
							               <li>
							                  <a href="{$subitem.link}">
							                  	{if $subitem.values|@count > 0}
								                  	<span class="photo"><img src="{Plico_GetResource file=$subitem.values.photo}" alt=""/></span>
								                  	<span class="subject">
								                  		<span class="from">{$subitem.values.from}</span>
								                  		<span class="time">{$subitem.values.time}</span>
								                  	</span>
								                  	<span class="message">
								                  		{$subitem.values.message}
								                  	</span>
							                  	{/if}
							                  </a>
							               </li>
							     		{elseif $item.type == 'notification'}
											<li>
							                  	<a href="{$subitem.link}">
							                  		<span class="label label-sm label-icon label-info">
							                  			<i class="fa fa-book"></i>
							                  		</span>
							                  		{$subitem.name}
							                  	</a>
							               </li>
							     		{/if}
					               {/foreach}
					            </ul>
				            {/if}
			         	</li>
				         {if isset($item.external)}
				         <li class="external">
				            <a href="{$item.external.link}">{$item.external.text} <i class="m-icon-swapright"></i></a>
				         </li>
				         {/if}
		      		</ul>
		      	</li>
				{else}

				<li class="{if $item.items}dropdown{else}menu-item{/if}" id="{$item.id}">
					<a href="javascript:void(0);" 
						{if $item.items}
						class="dropdown-toggle" data-toggle="dropdown" data-close-others="true"
						{else}
						class="menu-link"
						{/if}
					>
						{if $item.icon}
			      		<i class="{$item.icon}"></i>
			      		{/if}
			      		{if $item.text}
			      			<span class="hidden-xs">{$item.text}</span>
			      		{/if}
						{if isset($item.notif)}
			      		<span class="badge">{$item.notif}</span>
			      		{/if}
			      	</a>
			      	{if $item.items}
				      	<ul class="dropdown-menu {$item.type}">
						{foreach $item.items as $subitem}
							<li>
								<a href="{$subitem.link}"
									{foreach $subitem.attrs as $attr => $attr_value}
										{$attr}="{$attr_value}" 
									{/foreach}
								>{$subitem.text}</a>
							</li>
					    {/foreach}
					    </ul>
				    {/if}
				</li>
		        {/if}
		   	{/foreach}
		   <!-- BEGIN CALENDAR DROPDOWN -->
		   <!--
		   <li class="dropdown" id="header_notification_bar">
		      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
		      <i class="icon-calendar"></i>
		      <span class="badge">1</span>
		      </a>
		      <ul class="dropdown-menu extended notification">
		         <li>
		            <p>You have 1 new events</p>
		         </li>
		         <li>
		            <ul class="dropdown-menu-list scroller" style="height: 250px;">
		               <li>
		                  <a href="#">
		                  <span class="label label-sm label-icon label-warning"><i class="icon-bolt"></i></span>
		                  Please schedule your exams!
		                  </a>
		               </li>
		            </ul>
		         </li>
		         <li class="external">
		            <a href="#">See all events <i class="m-icon-swapright"></i></a>
		         </li>
		      </ul>
		   </li>
		   -->
		   <!-- END CALENDAR DROPDOWN -->
		   <!-- BEGIN ACCESS  DROPDOWN -->
		   <!-- END CALENDAR DROPDOWN -->

		   <!-- BEGIN TODO DROPDOWN -->
		   <!--
		   <li class="dropdown" id="header_task_bar">
		      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
		      <i class="icon-tasks"></i>
		      <span class="badge">5</span>
		      </a>
		      <ul class="dropdown-menu extended tasks">
		         <li>
		            <p>You have 12 pending tasks</p>
		         </li>
		         <li>
		            <ul class="dropdown-menu-list scroller" style="height: 250px;">
		               <li>
		                  <a href="#">
		                  <span class="task">
		                  <span class="desc">New release v1.2</span>
		                  <span class="percent">30%</span>
		                  </span>
		                  <span class="progress">
		                  <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
		                  <span class="sr-only">40% Complete</span>
		                  </span>
		                  </span>
		                  </a>
		               </li>
		            </ul>
		         </li>
		         <li class="external">
		            <a href="#">See all tasks <i class="m-icon-swapright"></i></a>
		         </li>
		      </ul>
		   </li>
			-->
		   <!-- END TODO DROPDOWN -->
		   <!-- BEGIN USER LOGIN DROPDOWN -->
		   {* MOVE TO MENU SYSTEM *}
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
					{if ({$T_CURRENT_USER.avatars[0].url})}
						<div class="avatar-img vertical-align hidden-xs">
							{if ({$T_CURRENT_USER.avatars[0].url})}
								<img src="{$T_CURRENT_USER.avatars[0].url}" alt="" class="user-profile-image">
							{else}
								<img width="100%" src="{Plico_GetResource file='images/placeholder/avatar.png'}" class="user-profile-image hidden" alt="" />
							{/if}
						</div>
					{/if}
					<span class="username">{$T_CURRENT_USER.name}</span>
					<i class="icon-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="/module/users/profile"><i class="icon-user"></i> {translateToken value="My Profile"}</a>
					</li>
					{foreach $T_TOPBAR_MENU as $key => $item}
				        {if isset($item.link)}
						{/if}
					{/foreach}
					<li class="divider visible-xs"></li>
					<li class="hidden-sm hidden-xs">
						<a href="javascript:;" id="trigger_fullscreen"><i class="size-fullscreen"></i> {translateToken value="Full Screen"}</a>
					</li>
					<li>
						<a href="/lock"><i class="icon-lock"></i> {translateToken value="Lock Screen"}</a>
					</li>
					<li>
						<a href="/logout"><i class="icon-key"></i> {translateToken value="Log Out"}</a>
					</li>
				</ul>
			</li>
		   <!-- END USER LOGIN DROPDOWN -->
		   <!--
		   <li class="dropdown dropdown-quick-sidebar-toggler">
                <a class="dropdown-toggle" href="javascript:;">
                    <i class="fa fa-comments"></i>
                </a>
			</li>
			-->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
<div class="clearfix"></div>
<div class="header navbar navbar-inverse navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner container">
		<!-- BEGIN LOGO -->  
		<a class="navbar-brand" href="/dashboard">
			<img src="{Plico_GetResource file='img/logo.png'}" alt="logo" class="img-responsive" />
		</a>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER --> 
		<!--
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		   <img src="{Plico_GetResource file='img/menu-toggler.png'}" alt="" />
		</a>
		-->
		<!-- END RESPONSIVE MENU TOGGLER -->

		<!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">
			{foreach $T_TOPBAR_MENU as $key => $item}
    			{if $item.extended}
    			<li class="dropdown hidden-xs">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
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
							                  		<span class="label label-sm label-icon label-info"><i class="icon-warning-sign"></i></span>
							                  		{$subitem.text}
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
				<li class="dropdown {$item.type}">
					{foreach $item.items as $subitem}
				       	{if $item.type == 'language' && isset($subitem.selected) && $subitem.selected}
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<img alt="" src="{Plico_GetResource file="img/flags/`$subitem.country_code|strtolower`.png"}"/>
								<span class="username">
									 {$subitem.country_code|@strtoupper}
								</span>
								<i class="icon-angle-down"></i>
							</a>
						{/if}
					{/foreach}
					<ul class="dropdown-menu">
					{foreach $item.items as $subitem}
				       	{if $item.type == 'language' && (!isset($subitem.selected) || !$subitem.selected)}
				       		{if isset($subitem.code) && $subitem.code}
								<li>
									<a href="#" data-callback="change-language" data-language="{$subitem.code}">
										<img alt="" src="{Plico_GetResource file="img/flags/`$subitem.country_code|strtolower`.png"}"/> {$subitem.local_name}
									</a>
								</li>
							{else}
								<li class="divider"></li>
								<li>
									<a href="{$subitem.link}">{$subitem.text}</a>
								</li>
							{/if}
						{/if}
				    {/foreach}
				    </ul>
				</li>
		        {/if}
		   </li>
		   {/foreach}
		   <!-- BEGIN CALENDAR DROPDOWN -->
		   <!--
		   <li class="dropdown" id="header_notification_bar">
		      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
		         data-close-others="true">
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
		            <a href="#">See all calendar events <i class="m-icon-swapright"></i></a>
		         </li>
		      </ul>
		   </li>
		   -->
		   <!-- END CALENDAR DROPDOWN -->
		   <!-- BEGIN ACCESS  DROPDOWN -->
		   {if $T_ADDITIONAL_ACCOUNTS|@count > 0}
		   <!--
		   <li class="dropdown" id="header_notification_bar">
		      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
		         data-close-others="true">
		         <i class="icon-user"></i>
		      </a>
		      <ul class="dropdown-menu extended notification">
		         <li>
		            <ul class="dropdown-menu-list scroller" style="height: 214px;">
						{foreach $T_ADDITIONAL_ACCOUNTS as $key => $item}
			               	<li>  
			                  <a href="javascript: changeAccount('{$item.login}');">
			                     <span class="label label-sm label-icon label-{$T_USER_TYPES_ICONS[$item.user_type].color}"><i class="icon-{$T_USER_TYPES_ICONS[$item.user_type].icon}"></i></span>
			                     #filter:login-{$item.login}#
			                  </a>
			               	</li>
		               	{/foreach}
		            </ul>
		         </li>
		      </ul>
		   </li>
		   -->
		   {/if}
		   <!-- END CALENDAR DROPDOWN -->

		   <!-- BEGIN TODO DROPDOWN -->
		   <!--
		   <li class="dropdown" id="header_task_bar">
		      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
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
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="{Plico_RelativePath file=$T_SMALL_USER_AVATAR.avatar}" width="{$T_SMALL_USER_AVATAR.width}"/>
					<span class="username">{$T_CURRENT_USER->user.name}</span>
					<i class="icon-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="/module/users/profile"><i class="icon-user"></i> My Profile</a>
					</li>
					<li class="divider"></li>
					{foreach $T_TOPBAR_MENU as $key => $item}
				        {if isset($item.link)}
							<li class="visible-xs">
								<a href="{$item.link.link}">
						      		<i class="icon-{$item.icon}"></i>
						      		<span class="badge">{$item.notif}</span>
						      		{$item.link.text}
						      	</a>
							</li>
						{/if}
					{/foreach}
					<li class="divider visible-xs"></li>
					<li class="hidden-sm hidden-xs">
						<a href="javascript:;" id="trigger_fullscreen"><i class="icon-move"></i> Full Screen</a>
					</li>
					<li>
						<a href="/lock"><i class="icon-lock"></i> Lock Screen</a>
					</li>
					<li>
						<a href="/logout"><i class="icon-key"></i> Log Out</a>
					</li>
				</ul>
			</li>
		   <!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>

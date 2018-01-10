{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
{assign var="user_details" value=$T_DATA.data.user_details}

{assign var="pointer" value=$T_DATA.data.pointer}

{assign var="days_end_term" value=$T_DATA.data.days_end_term}

<script>
_before_init_functions.push(function() {
    $SC.addResource("user_pointer", {$pointer|@json_encode nofilter});
});
</script>

<div class="row">
	<div class="col-md-3 col-sm-3 hidden-xs" id="users-avatar">

		{if ({$user_details.avatars[0].url})}
			<img class="page-lock-img" src="{$user_details.avatars[0].url}" alt="" style="width: 100%;">
		{else}
			<img class="page-lock-img" src="{Plico_GetResource file='images/placeholder/avatar.jpg'}" alt="" style="width: 100%;">
		{/if}
	
	</div>
	<div class="col-md-6 col-sm-5 col-xs-12">
		<img class="page-lock-img users-country-image" src="{$user_details.country_image}" alt="" style="">
		<img class="visible-xs inline users-profile-image" src="{$user_details.avatars[0].url}" height="40" alt="">
		<h2 class="users-panel-username clearfix">
			{$user_details.name} {$user_details.surname} <br>
			<small class="users-panel-username hidden-xs">
				<span class="course_name"></span><!--  <span class="enroll_token"></span> -->
			</small>
		</h2>

		<span class="course_name visible-xs text-center"></span>
		
		{if isset($notifications)}
			{foreach $notifications as $key => $notif}
				{if ($notif@iteration % 3 == 1 || $notif@first)}
				<div class="summary-list">
				{/if}
				
		       	<!-- <div class="col-md-4 col-sm-4 col-xs-12"> -->
		       		<div class="summary-item">
		       			<!--
						<span class="btn btn-xs btn-link text-{$notif.type}"><strong>{$notif.count nofilter}</strong></span>
						-->
						{$notif.text}
						<div class="pull-right" >
						{if isset($notif.links)}
							{if $notif.format == 'dropdown'}
								<div class="dropdown">
									<a 
										class="btn btn-xs btn-{$notif.type} dropdown-toggle {if isset($link.tooltip)}tooltips{/if}" 
										href="javascript: void(0)" 
										data-close-others="true" data-toggle="dropdown" 
										{if isset($link.target)}target="{$link.target}"{/if}
									  	{if isset($link.tooltip)}data-original-title="{$link.tooltip}"{/if}
									  	data-close-others="true" data-toggle="dropdown" 
									  	
									>
										{$notif.name nofilter}
										<i class="fa fa-caret-down"></i>
									</a>
									<ul class="dropdown-menu sumarry-item-dropdown pull-right">
										{foreach $notif.links as $link_index => $link}
											<li>
												<a 
													class="no-padding" 
													href="{$link.link}" 
													{if isset($link.target)}target="{$link.target}"
													{/if}
												>
													<span class="label label-{$notif.type}" style="display: inline-block">
													{$link.text nofilter}
													</span>
													{$link.name nofilter}
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
							{else}
								{foreach $notif.links as $link_index => $link}
									<a 
										class="btn btn-xs btn-{$notif.type} {if isset($link.tooltip)}tooltips{/if}" 
										href="{$link.link}" 
										{if isset($link.target)}target="{$link.target}"{/if}
									  	{if isset($link.tooltip)}data-original-title="{$link.tooltip}"{/if}
									>
										{$link.text nofilter}
									</a>
								{/foreach}
							{/if}
						{elseif isset($notif.link)}
							<!--
							<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
							-->
							<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}" {if isset($notif.link.target)}target="{$notif.link.target}"{/if}>{$notif.count nofilter}</a>
						{else}
							<span style="cursor: default;" class="btn btn-xs btn-{$notif.type}">{$notif.count nofilter}</span>
						{/if}
						</div>
					</div>
		       	<!-- </div> -->
			
				{if $notif@last }
				<div class="summary-item">
					{translateToken value="Days to end term"}
					<div class="pull-right">{$days_end_term}</div>
				</div>
				{/if}

				{if ($notif@iteration % 3 == 0 || $notif@last)}
				</div>
				{/if}
	       	{/foreach}
	       	
		{/if}
	</div>
	<div class="col-md-3 col-sm-4 col-xs-3 vcenter" id="progress-user" style="margin-top:20px;">
		<div class="easy-pie-chart">
			<div class="number unit" data-percent="0"><span>0</span></div>
			<!-- 
			<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Units completed"}</a> -->
		</div>
	</div>

</div>
<!--
<hr />
<div class="row" id="progress-user">
	<div class="col-md-4 col-sm-4 col-xs-4">
		<div class="easy-pie-chart">
			<div class="number course" data-percent="0"><span>0</span></div>
			<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Programs"}</a>
		</div>
	</div>
	<div class="margin-bottom-10 visible-sm"></div>
	<div class="col-md-4 col-sm-4 col-xs-4">
		<div class="easy-pie-chart">
			<div class="number class" data-percent="0"><span>0</span></div>
			<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Courses"}</a>
		</div>
	</div>
	<div class="margin-bottom-10 visible-sm"></div>
	<div class="col-md-4 col-sm-4 col-xs-4">
		<div class="easy-pie-chart">
			<div class="number lesson" data-percent="0"><span>0</span></div>
			<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Units"}</a>
		</div>
	</div>
	<div class="clearfix margin-bottom-10"></div>
</div>
-->
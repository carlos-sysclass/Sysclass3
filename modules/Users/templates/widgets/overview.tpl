{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
{assign var="user_details" value=$T_DATA.data.user_details}
<div class="row">
	<div class="col-md-3 col-sm-5 col-xs-5" id="users-avatar">

		{if ({$user_details.avatars[0].url})}
			<img class="page-lock-img" src="{$user_details.avatars[0].url}" width="100%" alt="">
		{else}
			<img class="page-lock-img" src="{Plico_GetResource file='images/placeholder/avatar.png'}" width="100%" alt="">
		{/if}
	
	</div>
	<div class="col-md-9 col-sm-7 col-xs-7">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<h3 class="users-panel-username">{$user_details.name} {$user_details.surname}</h3>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="form-group">
					<select class="select2-me form-control input-block-level" name="current_course" data-placeholder="{translateToken value='Select a course'}">
						{foreach $user_details.courses as $course}
							<option value="{$course.id}">{$course.name}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				{if isset($notifications)}
				<table class="table table-hover no-space users-panel-notification-table">
			        <thead>
						{foreach $notifications as $key => $notif}
			           	<tr>
							<td>
								<span class="btn btn-xs btn-link text-{$notif.type}"><strong>{$notif.count nofilter}</strong></span>
								{$notif.text}
							</td>
							<td align="right">
								{if isset($notif.link)}
									<!--
									<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
									-->
									<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
								{/if}
							</td>
			           	</tr>
			           	{/foreach}
			        </thead>
		        </table>
		        {else}
		        {/if}
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<table class="table table-hover no-space users-panel-notification-table user-course-details">
			        <thead>
			           	<tr>
							<td>
								<span class="btn-xs text-primary">
									<strong><i class="fa fa-folder"></i></strong>
								</span>
								{translateToken value="Classes"}
							</td>
							<td align="right">
								<span class="badge badge-primary total_classes"></span>
							</td>
			           	</tr>
			           	<tr>
							<td>
								<span class="btn-xs text-success">
									<strong><i class="fa fa-file"></i></strong>
								</span>
								{translateToken value="Lessons"}
							</td>
							<td align="right">
								<span class="badge badge-success total_lessons"></span>
							</td>
			           	</tr>
			           	<tr>
							<td>
								<span class="btn-xs text-danger">
									<strong><i class="fa fa-refresh"></i></strong>
								</span>
								{translateToken value="Progress"}
							</td>
							<td align="right">
								<span class="badge badge-danger progress-text"></span>
							</td>
			           	</tr>
			        </thead>
		        </table>
			</div>
		</div>
	</div>
</div>
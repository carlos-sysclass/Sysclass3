{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
{assign var="user_details" value=$T_DATA.data.user_details}
<div class="row">
	<div class="col-md-3 col-sm-3 hidden-xs" id="users-avatar">

		{if ({$user_details.avatars[0].url})}
			<img class="page-lock-img" src="{$user_details.avatars[0].url}" width="100%" alt="">
		{else}
			<img class="page-lock-img" src="{Plico_GetResource file='images/placeholder/avatar.png'}" width="100%" alt="">
		{/if}
	
	</div>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<h4 class="users-panel-username">
					<img class="visible-xs inline" src="{$user_details.avatars[0].url}" height="40" alt="">

					{$user_details.name} {$user_details.surname} - <span class="course_name"></span> <span class="enroll_token"></span>
				</h4>
			</div>
			<!--
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="form-group">
					<select class="select2-me form-control input-block-level" name="current_course" data-placeholder="{translateToken value='Select a course'}">
						{foreach $user_details.courses as $course}
							<option value="{$course.id}">{$course.name}</option>
						{/foreach}
					</select>
				</div>
			</div>
			-->
		</div>
		{if isset($notifications)}
		<!-- <ul class="summary-list list-unstyled list-inline"> -->
		
			{foreach $notifications as $key => $notif}
				{if ($notif@iteration % 3 == 1 || $notif@first)}
				<div class="row summary-list">
				{/if}


		       	<div class="col-md-4 col-sm-4 col-xs-12">
					<span class="btn btn-xs btn-link text-{$notif.type}"><strong>{$notif.count nofilter}</strong></span>
					{$notif.text}
					<div class="pull-right" >
					{if isset($notif.link)}
						<!--
						<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
						-->
						<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
					{/if}
					</div>
		       	</div>

				{if ($notif@iteration % 3 == 0 || $notif@last)}
				</div>
				{/if}
	       	{/foreach}
       	
       	<!--
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
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
									<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>
								{/if}
							</td>
			           	</tr>
			           	{/foreach}
			        </thead>
		        </table>
			</div>
		-->
			<!--
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
			-->
		
		{/if}
	</div>
</div>
<!--
<hr />
<div class="col-md-4 col-sm-4 col-xs-4">
  <h5 class="text-center">Courses</h5>
  	<div class="col-md-6 col-sm-6 col-xs-6">
		  <small>Time Elapsed</small>
	    <div class="progress progress-striped active progress-mini">
	        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
	            <span class="sr-only"> 40% Complete (success) </span>
	        </div>
	    </div>
  </div>
  <div class="col-md-6 col-sm-6 col-xs-6">
	    <small>Completed</small>
	    <div class="progress progress-striped active progress-mini">
	        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
	            <span class="sr-only"> 40% Complete (success) </span>
	        </div>
	    </div>
  </div>
</div>
-->


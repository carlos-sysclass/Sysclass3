{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
<div class="row">
	<div class="col-md-4">
		<img class="page-lock-img" src="{Plico_RelativePath file=$T_BIG_USER_AVATAR.avatar}" width="90%" alt="">		
	</div>
	<div class="col-md-3">
		<h4>{$T_CURRENT_USER->user.name} {$T_CURRENT_USER->user.surname}</h4>
		<p><strong>Class </strong>March 2013</p>
		<p><strong>Location </strong>Dallas, TX, USA</p>
		<p><strong>Language </strong>English</p>
		<p><strong>GPA </strong>3.9</p>
	</div>
	<div class="col-md-5">
		{if isset($notifications)}
		<table class="table table-hover">
	        <thead>
				{foreach $notifications as $key => $notif}
	           	<tr>
					<td><strong class="text-{$notif.type}">{$notif.count}</strong></td>
					<td {if !isset($notif.link) || !$notif.link}colspan="2"{/if}>{$notif.text}</td>
					<td>
						{if isset($notif.link)}
							<a class="btn btn-xs btn-{$notif.type}" href="{$notif.link.link}">{$notif.link.text}</a>	
						{/if}
					</td>
	           	</tr>
	           	{/foreach}
<!--
				<tr>
	              <td><strong>12</strong></td>
	              <td>Messages</td>
	              <td>
	              	<a href="javascript:void(0);" class="btn btn-xs btn-primary">Read</a>	
	              </td>
	           </tr>
	           <tr>
	              <td><strong>3</strong></td>
	              <td colspan="2">New Topics in the forum</td>
	           </tr>
	           <tr>
	              <td><strong>16</strong></td>
	              <td colspan="2">Credit hours completed</td>
	           </tr>
	           <tr>
	              <td><strong>7</strong></td>
	              <td colspan="2">New calendar events</td>
	           </tr>
	           <tr>
	              <td><strong class="text-danger">2</strong></td>
	              <td>Payments Due</td>
	              <td>
	              	<a class="btn btn-xs btn-danger" href="javascript:void(0);">Pay</a>	
	              </td>
	           </tr>
-->
	        </thead>
        </table>
        {else}
        {/if}
	</div>

</div>
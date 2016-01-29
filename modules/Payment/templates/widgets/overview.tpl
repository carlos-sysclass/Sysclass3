{if isset($T_DATA.data.notification)}
	{assign var="notifications" value=$T_DATA.data.notification}
{/if}
{assign var="user_details" value=$T_DATA.data.user_details}
<div class="row">
	<div class="col-md-9 col-sm-7 col-xs-7">
		
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
		</div>
	</div>
</div>
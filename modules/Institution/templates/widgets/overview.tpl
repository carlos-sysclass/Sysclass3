{assign var="context" value=$T_DATA.data}
<div class="row">
	<div class="col-lg-12 col-md-12 col-xs-12 text-center">
		<img class="" alt="" src="{$context.logo.url}" style="max-width: 88%; margin-bottom: 16px; margin-top: 11px;" />
    </div>
</div>

{$variables=[]}

{if $context.website}
	{$text=translateToken value="Website"}

	{$variables.website=['link' => $context.website,'icon'=>'fa-laptop','text'=>$text]}
{/if}
{if $context.time_at}
	{$variables.time_at=['icon'=>'fa-clock-o','text'=>$context.time_at]}
{/if}
{if $context.facebook}
	{$text=translateToken value="Facebook"}

	{$variables.facebook=['link' => "https://facebook.com/{$context.facebook}",'icon'=>'fa-facebook','text'=>$text]}
{/if}
{if $context.street && $context.street_number}
	{$text=translateToken value="View Map"}

	{$variables.address=['link' => "https://www.google.com.br/maps/place/{$context.street}, {$context.street_number} - {$context.city}",'icon'=>'fa-map','text'=>$text]}

{/if}
{if $context.skype}
	{$text=translateToken value="Skype"}

	{$variables.skype=['link' => "skype://{$context.skype}",'icon'=>'fa-skype','text'=>$text]}
{/if}
{if $context.linkedin}
	{$text=translateToken value="Linked In"}

	{$variables.linkedin=['link' => "https://www.linkedin.com/{$context.linkedin}",'icon'=>'fa-linkedin-square','text'=>$text]}
{/if}
{if $context.googleplus}
	{$text=translateToken value="Google+"}

	{$variables.googleplus=['link' => "https://plus.google.com/{$context.googleplus}",'icon'=>'fa-google-plus','text'=>$text]}
{/if}
{if $context.phone}
	{$variables.phone=['link' => "callto://+{$context.phone}",'icon'=>'fa-phone','text'=>$context.phone]}
{/if}

{$total=$variables|count}

<div class="institution-button-container total-rows-{($total/2)|ceil}">

	{foreach $variables as $variable}

		{if $variable@index is div by 2}
			<div class="row">
		{/if}
	    <div class="col-lg-6 col-md-6 col-xs-12">
	        <a href="{if $variable.link}{$variable.link}{else}javascript:void(0);{/if}" target="_blank" class="btn btn-primary btn-compressed">
	            <span class="text"><i class="fa {$variable.icon}"></i> {$variable.text}</span>
	        </a>
		</div>
		{if $variable@iteration is div by 2 || $variable@last}
			</div>
		{/if}

	{/foreach}
</div>

<!--
<div class="row"  id="institution-chat-list">
</div>
<hr />
<div class="row">
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span class=""><i class="icon-map-marker"></i>{translateToken value="Open a Ticket"}</span>
		</a>
	</div>
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm">
			<span><i><strong class="text-danger">3</strong></i>{translateToken value="Open ticket(s)"}</span>
		</a>
	</div>

</div>
<hr />
<div class="row">
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span><i><strong class="text-danger">3</strong></i>{translateToken value="Docs Pending"}</span>
		</a>
	</div>
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span><i class="icon-dropbox"></i><strong class="text-primary">3</strong> {translateToken value="Docs In Box"}</span>
		</a>
	</div>
</div>
<script type="text/template" id="institution-status-item-template">
<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
	<a href="javascript: void(0);" data-username="<%= id %>" data-status="<%= status %>" class="btn btn-default btn-sm">
		<% if (status == 'online') { %>
			<span class="text-success"><i class="icon-ok-sign"></i>
		<% } else if (status == 'busy') { %>
			<span class="text-danger"><i class="icon-minus-sign"></i>
		<% } else if (status == 'away') { %>
			<span class="text-warning"><i class="icon-time"></i>
		<% } else if (status == 'offline') { %>
			<span class="text-muted"><i class="icon-remove-sign"></i>
		<% } %><%= name %>
		</span>
	</a>
</div>
</script>
-->

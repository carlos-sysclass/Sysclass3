{assign var="context" value=$T_DATA.data}
<div class="row">
	<div class="col-lg-12 col-md-12 col-xs-12 text-center">
		<img class="" alt="" src="{$context.logo.url}" style="max-width: 88%; margin-bottom: 16px; margin-top: 11px;" />
    </div>
</div>
<div style="padding: 6px 10px;" class="institution-button-container">
	<div class="row">
		{if $context.website}
	        <div class="col-lg-6 col-md-6 col-xs-12">
	            <a href="{$context.website}" target="_blank" class="btn btn-primary btn-compressed">
	                    <span class="text"><i class="fa fa-laptop"></i> {translateToken value="Website"}</span>
	            </a>
	    	</div>       
	    {/if}

		{if $context.time_at}
	        <div class="col-lg-6 col-md-6 col-xs-12">
	            <a href="{$context.website}" target="_blank" class="btn btn-primary btn-compressed">
                    <span class="text"><i class="fa fa-clock-o"></i> {$context.time_at}</span>
	            </a>
	    	</div>       
	    {/if}
	</div>
	<div class="row">
        {if $context.facebook}
	    	<div class="col-lg-6 col-md-6 col-xs-12">
	    
		        <a href="https://facebook.com/{$context.facebook}" target="_blank" class="btn btn-primary btn-compressed">
		                <span class="text"><i class="fa fa-facebook"></i> {translateToken value="Facebook"}</span>
		        </a>
	    	</div>
        {/if}
	    {if $context.street && $context.street_number}
	    	<div class="col-lg-6 col-md-6 col-xs-12">
	        	<a href="https://www.google.com.br/maps/place/{$context.street}, {$context.street_number} - {$context.city}" target="_blank" class="btn btn-primary btn-compressed">
	                <span class="text"><i class="fa fa-map"></i> {translateToken value="View Map"}</span>
	        </a>
	    	</div>
	    {/if}
	</div>
	<div class="row">
	    {if $context.skype}
	    	<div class="col-lg-6 col-md-6 col-xs-12">
		        <a href="skype://{$context.skype}" target="_blank" class="btn btn-primary btn-compressed">
		            <span class="text"><i class="fa fa-skype"></i>
		            {translateToken value="Skype"}
		            </span>
		        </a>
	    	</div>
	    {/if}

	    {if $context.linkedin}
	    	<div class="col-lg-6 col-md-6 col-xs-12">
		        <a href="https://www.linkedin.com/{$context.linkedin}" target="_blank" class="btn btn-primary btn-compressed">
		                <span class="text"><i class="fa fa-linkedin-square"></i> {translateToken value="Linked In"}</span>
		        </a>
	    	</div>
	    {/if}
	</div>
	<div class="row">
	    {if $context.googleplus}
	    	<div class="col-lg-6 col-md-6 col-xs-12">
		        <a href="https://plus.google.com/{$context.googleplus}" target="_blank" class="btn btn-primary btn-compressed">
		                <span class="text"><i class="fa fa-google-plus"></i> {translateToken value="Google+"}</span>
		        </a>
		    </div>
	    {/if}
	    {if $context.phone}
	    	<div class="col-lg-6 col-md-6 col-xs-12">
	            <a href="callto://+{$context.phone}" target="_blank" class="btn btn-primary btn-compressed">
	                    <span class="text"><i class="fa fa-phone"></i> {$context.phone}</span>
	            </a>
	    	</div>
	    {/if}
	</div>
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

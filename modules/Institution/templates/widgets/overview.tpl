{assign var="context" value=$T_DATA.data}
<div class="row">
	<div class="col-lg-12 col-md-12 col-xs-12 text-center">
		<img class="" alt="" src="{$context.logo.url}" style="max-width: 88%; margin-bottom: 28px; margin-top: 11px;" />
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        {if $context.website}
                <a href="{$context.website}" target="_blank" class="btn btn-primary">
                        <span class="text"><i class="fa fa-laptop"></i> {translateToken value="Website"}</span>
                </a>
        {/if}
    </div>       
    <div class="col-lg-12 col-md-12 col-xs-12">
        {if $context.facebook}
                <a href="https://facebook.com/{$context.facebook}" target="_blank" class="btn btn-primary">
                        <span class="text"><i class="fa fa-facebook"></i> {translateToken value="Facebook"}</span>
                </a>
        {/if}
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        {if $context.phone}
                <a href="tel://{$context.phone}" target="_blank" class="btn btn-primary">
                        <span class="text"><i class="fa fa-phone"></i> {$context.phone}</span>
                </a>
        {/if}
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <a href="https://www.google.com.br/maps/place/{$context.address}" target="_blank" class="btn btn-primary">
                <span class="text"><i class="fa fa-map"></i> {translateToken value="View Map"}</span>
        </a>
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

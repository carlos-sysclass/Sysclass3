<div class="row" style="position: relative">
	<div class="col-md-7  col-sm-7 col-xs-6 text-center">
		<img class="img-responsive" alt="" src="{Plico_GetResource file='img/logo-institution.png'}" />
    </div>
	<div class="col-md-5 col-sm-5 col-xs-6">
		<div class="btn-group-vertical btn-group-fixed-size">
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-link"></i>Website</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-map-marker"></i>View Map</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-facebook"></i>Facebook</span>
			</a>
		</div>
	</div>
</div>
<hr />
<div class="row"  id="institution-chat-list">
</div>
<hr />
<div class="row">
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span class=""><i class="icon-map-marker"></i>Open a Ticket</span>
		</a>
	</div>
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm">
			<span><i><strong class="text-danger">3</strong></i>{translateToken value='Open ticket(s)'}</span>
		</a>
	</div>

</div>
<hr />
<div class="row">
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span><i><strong class="text-danger">3</strong></i>{translateToken value='Docs Pending'}</span>
		</a>
	</div>
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span><i class="icon-dropbox"></i><strong class="text-primary">3</strong> Docs In Box</span>
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
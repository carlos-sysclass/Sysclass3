<div class="row" style="position: relative">
	<div class="col-md-7 text-center img-vertical-middle">
		<img class="img-responsive" alt="" src="{Plico_GetResource file='img/logo-institution.png'}" />
    </div>
	<div class="col-md-5">
		<div class="btn-group-vertical btn-group-fixed-size">
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-link"></i>University Site</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-map-marker"></i>View Map</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-envelope"></i>Contact</span>
			</a>
			<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
				<span class="text-muted"><i class="icon-facebook"></i>Facebook</span>
			</a>
		</div>
	</div>
</div>
<hr />
<div class="row">
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span class=""><i class="icon-link"></i>Open a ticket</span>
		</a>
		<a href="javascript: void(0);" class="btn btn-default btn-sm disabled">
			<span class=""><i class="icon-map-marker"></i>View Map</span>
		</a>
	</div>
	<div class="col-md-6 btn-group-vertical btn-group-fixed-size" id="institution-chat-list">
	</div>
</div>
<hr />
<div class="row">
	<div class="col-md-6 btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
			<span class="text-danger">3 Documents Pending</span>
		</a>
	</div>
	<div class="col-md-6 btn-group-fixed-size">
		<a href="javascript: void(0);" class="btn btn-link btn-sm disabled">
			<span class="text-success">0 Documents In Box</span>
		</a>
	</div>
</div>
<script type="text/template" id="institution-status-item-template">
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
</script>
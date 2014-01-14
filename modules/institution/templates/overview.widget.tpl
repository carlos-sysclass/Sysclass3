<div class="row">
	<div class="col-md-5">
		<img class="img-responsive" alt="" src="{Plico_GetResource file='img/logo-institution.png'}" />
		<br />
		<p class="text-center">
			<a href="http://www.ult.edu.br" target="_blank">www.ult.edu.br</a>
		</p>
		<p  class="text-center">
			<a href="#" target="_blank">View map</a>
		</p>
    </div>    
	<div class="col-md-7">
		<div class="list-group" id="institution-chat-list"></div>
	</div>
</div>
<script type="text/template" id="institution-status-item-template">
<a class="list-group-item" href="javascript: void(0);" data-username="<%= id %>" data-status="<%= status %>">
	<% if (status == 'online') { %>
		<span class="text-success"><i class="icon-ok-sign"></i></span>
	<% } else if (status == 'busy') { %>
		<span class="text-danger"><i class="icon-minus-sign"></i></span>
	<% } else if (status == 'away') { %>
		<span class="text-warning"><i class="icon-time"></i></span>
	<% } else if (status == 'offline') { %>
		<span class=""><i class="icon-remove-sign"></i></span>
	<% } %>
	<%= name %>
</a>
</script>
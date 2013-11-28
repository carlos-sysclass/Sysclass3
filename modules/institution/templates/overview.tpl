<div class="row">
	<div class="col-md-5">
		<img class="avatar img-responsive" alt="" src="{Plico_GetResource file='img/logo-institution.png'}" />
    </div>    
	<div class="col-md-7">
		<div class="list-group" id="institution-chat-list"></div>
	</div>
</div>
<hr />
<!-- GET THIS DATA FROM PROCTORING MODULE -->
<p>Your Proctoring Center is: <strong class="text-default">North Dallas</strong></p>
<p class="text-center">
	<a class="btn btn-xs btn-warning" href="javascript:void(0);">Schedule your exams</a>	
</p>

<hr />

<!-- GET THIS DATA FROM SCHEDULE/CALENDAR MODULE -->
<p>Next meeting with your advisor: <strong class="text-default">Dr. Jonh Smith</strong></p>
<p>Will be on: <strong class="text-default">Dez, 22 at 7:35PM Central Time</strong></p>
<p class="text-center">
	<a class="btn btn-xs btn-success" href="javascript:void(0);">Confirm</a>	
	<a class="btn btn-xs btn-danger" href="javascript:void(0);">Re-Schedule</a>	
</p>


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
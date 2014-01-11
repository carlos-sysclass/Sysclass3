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
<hr />
<div class="row">
	<div class="col-md-4">
		<img class="avatar img-responsive" width="100%" alt="" src="{Plico_GetResource file='img/avatar_medium.jpg'}" />
    </div>    
	<div class="col-md-8">
		<p>
			<span>Advisor:</span>
			<strong class="text-default pull-right">Dr. Jonh Smith</strong>
		</p>
		<p>
			<span>You have chated:</span>
			<strong class="text-default pull-right">3 times</strong>
		</p>
		<p>
			<span>You have talked:</span>
			<strong class="text-default pull-right">4 times</strong>
		</p>
	</div>
</div>
<br />
<div class="row">
	<div class="col-md-12">
		<p>
			<dt>Next meeting:</dt>
			<dd>Dez, 22 at 7:35PM Central Time</dd>
		</p>

		<a class="btn btn-sm btn-success" href="javascript:void(0);">Confirm</a>	
		<a class="btn btn-sm btn-warning pull-right" href="javascript:void(0);">Re-Schedule</a>	
	</div>
</div>
<hr />
<!-- GET THIS DATA FROM SCHEDULE/CALENDAR MODULE -->
{if $T_DATA.data.polo|@count > 0}
<div class="row">
	<div class="col-md-12">
		<p>
			<span>Proctoring Center:</span>
			<strong class="text-default pull-right">{$T_DATA.data.polo['nome']}</strong>
		</p>
		<p>
			<span>Phone:</span>
			<strong class="text-default pull-right">{$T_DATA.data.polo['telefone']}</strong>
		</p>
		<p>
			<a href="#" target="_blank">View map</a>
		</p>
		<p>
			<dt>Next Exam:</dt>
			<dd>Dez, 22 at 7:35PM Central Time</dd>
		</p>
		<a class="btn btn-sm btn-success btn-disabled" href="javascript:void(0);">Confirm</a>	
		<a class="btn btn-sm btn-warning pull-right btn-disabled" href="javascript:void(0);">Re-Schedule</a>	
	</div>
</div>
{/if}
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
<div class="scroller list-group" data-height="200px" data-always-visible="1" data-rail-visible="1" id="news-links">
</div>
<div class="modal fade" id="news-dialog" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog modal-wide">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title news-title"></h4>
			</div>
			<div class="modal-body">
				<div class="news-data">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>

<script type="text/template" id="news-item-template">
   <a class="list-group-item" data-toggle="modal" href="#news-dialog" data-news-id="<%= id %>">
		<%= $SC.module("utils").toggleAt(title, 75, "lg") %>
		<span class="badge badge-info hidden-xs"><%= moment.unix(timestamp).fromNow() %></span>
		<span class="badge badge-success badge-roundless username-badge visible-lg"><%= login %></span>
   </a>
</script>
<script type="text/template" id="news-nofound-template">
   <div class="alert alert-warning">
   		<span class="text-warning"><i class="icon-warning-sign"></i></span>
   		{translateToken value='Ops! Sorry, any data found!'}
   </div>
</script>

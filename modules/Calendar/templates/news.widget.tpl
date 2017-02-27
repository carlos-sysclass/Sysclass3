<div class="scroller list-group" data-height="194px" data-always-visible="1" data-rail-visible="1">
	<ul class="widget-block-view-container"></ul>
</div>

<script type="text/template" id="news-item-template">
   <a class="list-group-item" data-toggle="modal" href="#news-dialog" data-target="#news-dialog">
		<%= $SC.module("utils").toggleAt(model.title, 75, "lg") %>
		<span class="badge badge-info hidden-xs"><%= moment.unix(model.start).fromNow() %></span>
		<span class="badge badge-success badge-roundless username-badge visible-lg"><%= model.user.login %></span>
   </a>
</script>
<script type="text/template" id="news-nofound-template">
   <div class="alert alert-warning">
   		<span class="text-warning"><i class="icon-warning-sign"></i></span>
   		{translateToken value="No data found."}
   </div>
</script>

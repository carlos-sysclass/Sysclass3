<div class="scroller list-group widget-block-view-container" data-height="200px" data-always-visible="1" data-rail-visible="1">
</div>

<script type="text/template" id="news-item-template">
   <a class="list-group-item" data-toggle="modal" href="#news-dialog" data-target="#news-dialog">
		<%= $SC.module("utils").toggleAt(model.title, 75, "lg") %>
		<span class="badge badge-info hidden-xs"><%= moment.unix(model.timestamp).fromNow() %></span>
		<span class="badge badge-success badge-roundless username-badge visible-lg"><%= model.login %></span>
   </a>
</script>
<script type="text/template" id="news-nofound-template">
   <div class="alert alert-warning">
   		<span class="text-warning"><i class="icon-warning-sign"></i></span>
   		{translateToken value="Ops! Sorry, any data found!"}
   </div>
</script>

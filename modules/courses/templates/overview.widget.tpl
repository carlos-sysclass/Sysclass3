<div class="row" id="courses-list">
	<div class="col-md-12">
		<div class="scroller list-group" data-height="238px" data-always-visible="1">
		</div>
	</div>
</div>
<div id="courses-content">
	<h4 class="text-center" id="lessons-title">jklasdf</h4>
	<div class="row">
		<div class="col-md-8">
			<p class="text-center">THE CONTENT!!!!</p>
		</div>
		<div class="col-md-4">
			<div class="list-group">
				<a href="javascript: void(0);" class="list-group-item">
	        		<dt class="text-center">Topic:</dt>
	        		<dd class="text-center text-primary">Wireless Connections</dd>
	        	</a>
	        	<a href="javascript: void(0);" class="list-group-item">
	        		<dt class="text-center">Professor:</dt>
	        		<dd class="text-center text-primary">Dr. John Smith</dd>
	        	</a>
	        	<a href="javascript: void(0);" class="list-group-item">
	        		<dt class="text-center">Conclusion:</dt>
	        		<dd class="text-center text-primary">18/60</dd>
	        	</a>

			</div>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number transactions" data-percent="55"><span>+55</span>%</div>
				<a class="title" href="#">Course Progress <i class="m-icon-swapright"></i></a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number visits" data-percent="85"><span>+85</span>%</div>
				<a class="title" href="#">Lesson Progress <i class="m-icon-swapright"></i></a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number bounce" data-percent="46"><span>+46</span>%</div>
				<a class="title" href="#">Topic Progress <i class="m-icon-swapright"></i></a>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="courses-list-item-template">
<a href="javascript: void(0);" class="list-group-item" data-entity-id="<%= id %>">
	<% if (stats.completed == 1) { %>
	<span class="text-success"><i class="icon-ok-sign"></i></span>
	<% } else { %>
	<span class="text-danger"><i class="icon-remove-sign"></i></span>
	<% } %>
	<%= name %>
	<!--
	<% if (typeof lessons != 'undefined') { %>
		<span class="badge badge-info"><%= lessons.length %></span>
	<% } %>
	-->
	<% if (typeof stats.completed_lessons != 'undefined' && stats.total_lessons != undefined) { %>
		<span class="badge badge-info"><%= stats.completed_lessons %> / <%= stats.total_lessons %></span>
	<% } %>
	<% if (typeof stats.overall_progress != 'undefined') { %>
		<% 
			if (stats.overall_progress < 40) {
				classe = "danger";
			} else if (stats.overall_progress < 70) {
				classe = "warning";
			} else if (stats.overall_progress < 100) {
				classe = "info";
			} else {
				classe = "success";
			}
		%>
		<span class="badge badge-<%= classe %>"><%= stats.overall_progress %> %</span>
	<% } %>

</a>
</script>



<style type="text/css">
	#courses-list {
	    background: white;
	    position: absolute;
	    z-index: 100;
	}

</style>
<div id="courses-list">
	<div class="scroller list-group" data-height="199px" data-always-visible="1">
	</div>
</div>

<h4 class="text-center" id="lessons-title">jklasdf</h4>
<div>
	<div class="container" id="courses-content">

	</div>
	<hr />
	<div class="row" id="progress-content">
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number courses" data-percent="0">+<span>0</span>%</div>
				<a class="title" href="#">Course Progress <i class="m-icon-swapright"></i></a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number lessons" data-percent="0">+<span>0</span>%</div>
				<a class="title" href="#">Lesson Progress <i class="m-icon-swapright"></i></a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4">
			<div class="easy-pie-chart topic">
				<div class="number topics" data-percent="0">+<span>0</span>%</div>
				<a class="title" href="#">Topic Progress <i class="m-icon-swapright"></i></a>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="courses-content-template">
	<div class="row">
		<div class="col-md-2">
			<% if (prev != null) { %>
				<a href="#" class="btn btn-primary prev">
					<i class="icon-arrow-left"></i> {translateToken value='Previous'}
				</a>
			<% } %>
		</div>
		<div class="col-md-8">
			<h5 class="text-center"><%= name %></h5>
		</div>
		<div class="col-md-2">
			<% if (next != null) { %>
				<a href="#" class="btn btn-primary pull-right next">
					<i class="icon-arrow-right"></i> {translateToken value='Next'}
				</a>
			<% } %>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-8"><p><%= data %></p></div>

		<div class="col-md-4">
			<div class="list-group">
				<a href="javascript: void(0);" class="list-group-item">
					<dt class="text-center">{translateToken value='Topic'}:</dt>
					<dd class="text-center text-primary"><%= name %></dd>
				</a>
				<a href="javascript: void(0);" class="list-group-item">
					<dt class="text-center">{translateToken value='Professor'}:</dt>
					<dd class="text-center text-primary"><%= metadata.publisher %></dd>
				</a>
				<a href="javascript: void(0);" class="list-group-item">
					<dt class="text-center">{translateToken value='Conclusion'}:</dt>
					<dd class="text-center text-primary">18/60</dd>
				</a>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="courses-progress-item-template">
	
</script>
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



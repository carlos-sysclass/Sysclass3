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

<h4 class="text-center" id="lessons-title"></h4>
<div>
	<div class="container" id="courses-content">
		<div class="row" id="courses-content-navigation"></div>
		<hr />
		<div class="row">
			<div class="col-md-12">
				<div class="tabbable-custom ">
					<ul class="nav nav-tabs ">
						<li class="active">
							<a data-toggle="tab" href="#tab_class"><i class="icon-magic"></i> Video-Class</a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#tab_materials"><i class="icon-book"></i> Materials</a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#tab_exercises"><i class="icon-pencil"></i> Exercises</a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#tab_roadmap"><i class="icon-road"></i> Roadmap</a>
						</li>
						
						<li class="pull-right">
							<a data-toggle="tab" href="#tab_class_info">
								<i class="icon-info-sign"></i> Class Info 
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="tab_class" class="tab-pane active">
						   	<div class="alert alert-info">
								<span class="text-info"><i class="icon-warning-sign"></i></span>
								Ops! There's any content for this class
						   	</div>
					   	</div>
					   	<div id="tab_materials" class="tab-pane">
							<div class="tree tree-plus-minus tree-no-line tree-unselectable">
								<div class = "tree-folder" style="display:none;">
									<div class="tree-folder-header">
										<i class="icon-folder-close"></i>
										<div class="tree-folder-name"></div>
									</div>
									<div class="tree-folder-content"></div>
									<div class="tree-loader" style="display:none"></div>
								</div>
								<div class="tree-item" style="display:none;">
									<div class="tree-item-base"></div>
									<div class="tree-item-name"></div>
								</div>
							</div>
					   	</div>
					   <div id="tab_exercises" class="tab-pane">
						   <div class="alert alert-info">
								 <span class="text-info"><i class="icon-warning-sign"></i></span>
								 Ops! There's any exercises posted for this class
						   </div>
					   </div>
					   <div id="tab_roadmap" class="tab-pane">
						   <div class="alert alert-info">
								 <span class="text-info"><i class="icon-warning-sign"></i></span>
								 Ops! There's any roadmap configured for this class
						   </div>
					   </div>
					   <div id="tab_class_info" class="tab-pane">
						   <div class="alert alert-info">
								 <span class="text-info"><i class="icon-warning-sign"></i></span>
								 Ops! There's any class info registered for this class
						   </div>
					   </div>
					</div>
				 </div>
			</div>
		</div>
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
<script type="text/template" id="courses-content-navigation-template">
	<div class="col-md-2">
		<% if (prev && prev != null) { %>
			<a href="#" class="btn btn-primary prev">
				<i class="icon-arrow-left"></i> {translateToken value='Previous'}
			</a>
		<% } %>
	</div>
	<div class="col-md-8">
		<h5 class="text-center">{translateToken value='Topic'}:</strong> <%= name %></h5>
	</div>
	<div class="col-md-2">
		<% if (next && next != null) { %>
			<a href="#" class="btn btn-primary pull-right next">
				<i class="icon-arrow-right pull-right"></i> {translateToken value='Next'}
			</a>
		<% } %>
	</div>
</script>

<script type="text/template" id="courses-content-generic-template">
	<p><%= data %></p>
</script>
<script type="text/template" id="courses-content-video-template">
	<video id="courses-content-video-<%= id %>" class="video-js vjs-default-skin vjs-big-play-centered">
        <% _.each(data.video.sources, function(src, type){ %>
        	<source src="<%= src %>" type='<%= type %>' />    
        <% }); %>
        <% _.each(data.video.tracks, function(item, kind){ %>
        	<track kind="<%= kind %>" src="<%= item.src %>" srclang="<%= item.srclang %>" label="<%= item.label %>" <% if (item.default != undefined) { %>default="<%= item.default %>"<% } %>></track>
        <% }); %>
	</video>
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



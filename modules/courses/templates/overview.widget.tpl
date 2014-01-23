<!--
<div id="courses-list">
	<div class="scroller list-group" data-height="199px" data-always-visible="1">
	</div>
</div>
-->
<div>
	<div class="container" id="courses-content">
		<div class="row" id="courses-content-navigation">
			<div class=" portlet-tabs">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#course-tab"><span class="course-title">Course</span></a>
					</li>
					<li>
						<a data-toggle="tab" href="#class-tab">
							<div class="nav-button class-prev-action">
								<i class="icon-caret-left"></i>
							</div>
							<div class="nav-title">
								<span class="tab-title class-title">Class</span> - <span class="class-index">X</span> of <span class="class-total">X</span>
							</div>
							<div class="nav-button class-next-action">
								<i class="icon-caret-right"></i>
							</div>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#lesson-tab">
							<div class="nav-button lesson-prev-action">
								<i class="icon-caret-left"></i>
							</div>
							<div class="nav-title">
								<span class="tab-title lesson-title">Class</span> - <span class="lesson-index">X</span> of <span class="lesson-total">X</span>
							</div>
							<div class="nav-button lesson-next-action">
								<i class="icon-caret-right"></i>
							</div>
						</a>
					</li>
				</ul>
			</div>			
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="tabbable-custom ">
					<ul class="nav nav-tabs ">
						<li class="active">
							<a data-toggle="tab" href="#tab_class"><i class="icon-magic"></i> Video Lesson</a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#tab_materials"><i class="icon-book"></i> Materials</a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#tab_exercises"><i class="icon-pencil"></i> Exercises</a>
						</li>
						<li class="pull-right">
							<a data-toggle="tab" href="#tab_class_info">
								<i class="icon-info-sign"></i> Lesson Info 
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

						</div>
					   <div id="tab_exercises" class="tab-pane">
						   <div class="alert alert-info">
								 <span class="text-info"><i class="icon-warning-sign"></i></span>
								 Ops! There's any exercises posted for this class
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
	<div class="row" id="progress-content">
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number topic" data-percent="0">+<span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Lesson</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number lesson" data-percent="0">+<span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Class</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number semester" data-percent="0">+<span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Semester</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number course" data-percent="0">+<span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Course</a>
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
<script type="text/template" id="courses-content-navigation-template">

</script>

<script type="text/template" id="courses-content-generic-template">
	<p><%= data.data %></p>
</script>
<script type="text/template" id="courses-content-video-template">
<div class="videocontent">
	<video id="courses-content-video-<%= id %>" class="video-js vjs-default-skin vjs-big-play-centered" width="auto"  height="auto">
		<% _.each(data.data.video.sources, function(src, type){ %>
			<source src="<%= src %>" type='<%= type %>' />    
		<% }); %>
		<% _.each(data.data.video.tracks, function(item, kind){ %>
			<track kind="<%= kind %>" src="<%= item.src %>" srclang="<%= item.srclang %>" label="<%= item.label %>" <% if (item.default != undefined) { %>default="<%= item.default %>"<% } %>></track>
		<% }); %>
	</video>
</div>
</script>

<script type="text/template" id="courses-content-materials-template">
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
			<i class="tree-dot"></i>
			<div class="tree-item-name"></div>
		</div>
	</div>
</script>




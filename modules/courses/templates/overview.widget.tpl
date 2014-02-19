<!--
<div id="courses-list">
	<div class="scroller list-group" data-height="199px" data-always-visible="1">
	</div>
</div>
-->
<div>
	<div class="container" id="courses-content">
		<div class="portlet-tabs" id="courses-content-navigation">
			<ul class="nav nav-tabs">
				<li class="the-course-tab">
					<a data-toggle="tab" href="#course-tab">
						<div class="nav-title">
							<span class="">Courses</span>
						</div>
					</a>
				</li>
				<li class="the-class-tab">
					<a data-toggle="tab" href="#class-tab">
						<div class="nav-title">
							<span class="tab-title">Classes</span>
						</div>
					</a>
				</li>
				<li class="the-lesson-tab active">
					<a data-toggle="tab" href="#lesson-tab">
						<!--
						<div class="nav-button lesson-prev-action">
							<i class="icon-caret-left"></i>
						</div>
						-->
						<div class="nav-title">
							<span class="tab-title">Lessons</span>
						</div>
						<!--
						<div class="nav-button lesson-next-action">
							<i class="icon-caret-right"></i>
						</div>
						-->
					</a>
				</li>
				<li>
					<a href="#" class="nav-prev-action">
						<i class="icon-angle-left"></i>
					</a>
				</li>
				<li>
					<a href="#" class="nav-next-action">
						<i class="icon-angle-right"></i>
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="course-tab" class="tab-pane">
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand course-title">
								 Course
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">

							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value='Previous Course'}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value='Next Course'}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
						
					<div class="tabbable-custom ">
						<ul class="nav nav-tabs ">
							<li class="active">
								<a data-toggle="tab" href="#tab_course_info"><i class="icon-info-sign"></i> Course Info </a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_course_info" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Ops! There's any info registered for this course
									</div>
								</div>
							</div>
						</div>
					 </div>
				</div>
				<div id="class-tab" class="tab-pane">
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand class-title">
								 Class
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">

							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value='Previous Class'}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value='Next Class'}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="tabbable-custom ">
						<ul class="nav nav-tabs ">
							<li class="active">
								<a data-toggle="tab" href="#tab_class_info"><i class="icon-info-sign"></i> Lesson Info </a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_roadmap"><i class="icon-road"></i> Roadmap</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_class_info" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Ops! There's any info registered for this class
									</div>
								</div>
							</div>
							<div id="tab_class_roadmap" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
							   		<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
									Ops! There's any roadmap registered for this class
							   		</div>
							   	</div>
							</div>
						</div>
					</div>
				</div>
				<div id="lesson-tab" class="tab-pane active">
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<<a href="#" class="navbar-brand disabled">
								<strong>You're in: </strong>
							</a>
							<a href="#" class="navbar-brand class-title">
								Class
							</a>
							<a href="#" class="navbar-brand">&raquo;</a>
							<a href="#" class="navbar-brand lesson-title">
								 Lessons
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value='Previous Lesson'}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value='Next Lesson'}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>
								<!--
								<li>
									<a href="#" class="nav-search-action tooltips" data-original-title="{translateToken value='Search Lessons'}" data-placement="top" data-search>
										<i class="icon-search"></i>
									</a>
								</li>
								-->
							</ul>
						</div>
					</div>

					<div class="tabbable-custom ">
						<ul class="nav nav-tabs ">
							<li class="active">
								<a data-toggle="tab" href="#tab_lesson_content"><i class="icon-magic"></i> Video Lesson</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_materials"><i class="icon-book"></i> Materials</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_exercises"><i class="icon-pencil"></i> Exercises</a>
							</li>
							<li class="pull-right">
								<a data-toggle="tab" href="#tab_lesson_info">
									<i class="icon-info-sign"></i> Lesson Info 
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_lesson_content" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Ops! There's any content for this lesson
									</div>
								</div>
							</div>
							<div id="tab_lesson_materials" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
								</div>

							</div>
						    <div id="tab_lesson_exercises" class="tab-pane">
						    	<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
								   	<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										 Ops! There's any exercises posted for this lesson
								   	</div>
							   	</div>
						   	</div>
						   	<div id="tab_lesson_info" class="tab-pane">
						   		<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
							   		<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
									 	Ops! There's any lesson info registered
							   		</div>
							   	</div>
						   </div>
						</div>
					 </div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="row" id="progress-content">
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number lesson" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Lesson</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number class" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Class</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number semester" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Semester</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number course" data-percent="0"><span>0</span>%</div>
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




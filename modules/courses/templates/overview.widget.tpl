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
				<!--
				<li class="the-class-tab">

						<a data-toggle="tab" href="#class-tab" class="nopadding-right">
							<div class="nav-title">
								<span class="tab-title2">Classes</span>
							</div>
						</a>
						<a data-toggle="dropdown" class="dropdown-toggle nopadding-left">
							<div class="nav-title">
								<i class="icon-arrow-down"></i>
							</div>
						</a>
						
						  <ul class="dropdown-menu" role="menu">
						    <li><a href="#">Action</a></li>
						    <li><a href="#">Another action</a></li>
						    <li><a href="#">Something else here</a></li>
						    <li class="divider"></li>
						    <li><a href="#">Separated link</a></li>
						  </ul>
						 

				</li>
				-->
				<li class="the-class-tab active">
					<a data-toggle="tab" href="#class-tab">
						<div class="nav-title">
							<span class="tab-title">Classes</span>

						</div>
					</a>
				</li>

				<li class="the-lesson-tab">
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
			</ul>
			<div class="clearfix"></div>
			<div class="tab-content">
				<div id="course-tab" class="tab-pane">
					<div class="clearfix"></div>
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand disabled">
								<strong>You're in: </strong>
							</a>
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
						
					<div class="tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#tab_course_description"><i class="icon-info-sign"></i> Description</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_course_classes"><i class="icon-book"></i> Classes</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_course_roadmap"><i class="icon-comments"></i> Road Map</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_course_description" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Ops! There's any info registered for this course
									</div>
								</div>
							</div>
							<div id="tab_course_classes" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>Name</th>
												<th class="text-center">Completed</th>
												<th class="text-center">Attendence</th>
												<th class="text-center">Grade</th>
												<th class="text-center">Status</th>
											</tr>
										</thead>
										<tbody>
											<tr>
										</tbody>
									</table>
									
								</div>
							</div>
							<div id="tab_course_roadmap" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div id="tab_course_roadmap-accordion">
									</div>
								</div>
							</div>
						</div>
					 </div>
				</div>
				<div id="class-tab" class="tab-pane active">
					<div class="clearfix"></div>
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand disabled">
								<strong>You're in: </strong>
							</a>
							<a href="#" class="navbar-brand course-title">
								Course
							</a>
							<a href="#" class="navbar-brand">&raquo;</a>
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

					<div class="tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#tab_class_info"><i class="icon-magic"></i> Objectives</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_instructor"><i class="icon-user"></i> Instructor</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_info"><i class="icon-info-sign"></i> Info</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_dropbox"><i class="icon-dropbox"></i> Dropbox</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_bibliography"><i class="icon-book"></i> Bibliography</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-ellipsis-horizontal"></i> More <i class="icon-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right" role="menu">
									<li class="">
										<a data-toggle="tab" href="#tab_class_attendence"><i class="icon-calendar"></i> Attendence</a>
									</li>
									<li class="">
										<a data-toggle="tab" href="#tab_class_exams"><i class="icon-pencil"></i> Exams</a>
									</li>
								</ul>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_class_info" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Sorry! Any data has been registered for this class yet.
									</div>
								</div>
							</div>
							<div id="tab_class_instructor" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
							   		<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Sorry! Any data has been registered for this class yet.
							   		</div>
							   	</div>
							</div>
							<div id="tab_class_info" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
							   		<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Sorry! Any data has been registered for this class yet.
							   		</div>
							   	</div>
							</div>
							<div id="tab_class_dropbox" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="200px">
									<!--
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>File</th>
												<th class="text-center">Date</th>
												<th class="text-center">Owner</th>
												<th class="text-center">Size</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Presentation.</td>
												<td class="text-center">Joe Walton</td>
												<td class="text-center"><span class="label label-danger">Required</span></td>
											</tr>
											<tr>
												<td>History of Maps</td>
												<td class="text-center">Mike Joshua</td>
												<td class="text-center"><span class="label label-info">Suggested</span></td>
											</tr>
										</tbody>
									</table>
									-->
									<h5>
										<a href="javascript: void(0)"> Professor files </a> - 
										<small>
											<span class="size-counter">2</span> files

										</small>
									</h5>
									<div class="tree tree-professor tree-plus-minus tree-no-line tree-unselectable">
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
								</div>
								<hr />
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="200px">
									<h5>
										<a href="javascript: void(0)">Your Files </a> - 
										<small>
											<span class="size-counter">2</span> files

										</small>
									</h5>
									<div class="tree tree-student tree-plus-minus tree-no-line tree-unselectable">
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
								</div>
							</div>
							<div id="tab_class_bibliography" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>Book</th>
												<th class="text-center">Author</th>
												<th class="text-center">Type</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>World Map</td>
												<td class="text-center">Joe Walton</td>
												<td class="text-center"><span class="label label-danger">Required</span></td>
											</tr>
											<tr>
												<td>History of Maps</td>
												<td class="text-center">Mike Joshua</td>
												<td class="text-center"><span class="label label-info">Suggested</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="tab_class_attendence" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										Sorry! Any data has been registered for this class yet.
									</div>
								</div>
							</div>
							<div id="tab_class_exams" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>Exams</th>
												<th class="text-center">Date</th>
												<th class="text-center">Status</th>
												<th class="text-center">Grade</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Test #1</td>
												<td class="text-center">13rd March, 2014</td>
												<td class="text-center"><span class="label label-danger">Closed</span></td>
												<td class="text-center"><span class="label label-warning">60</span></td>
											</tr>
											<tr>
												<td>Test #2</td>
												<td class="text-center">13rd May, 2014</td>
												<td class="text-center"><span class="label label-success">Open</span></td>
												<td class="text-center"></td>
											</tr>
											<tr>
												<td>Test #3</td>
												<td class="text-center">13rd July, 2014</td>
												<td class="text-center"><span class="label label-warning">Stand By</span></td>
												<td class="text-center"></td>
											</tr>
										</tbody>
									</table>   
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="lesson-tab" class="tab-pane">
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand disabled">
								<strong>You're in: </strong>
							</a>
							<a href="#" data-toggle="dropdown" class="navbar-brand dropdown-toggle">
								<span class="class-title">Class</span>
								<i class="icon-refresh"></i>
							</a>
							<ul class="dropdown-menu" role="menu">
						   		<li><a href="#">Action</a></li>
							    <li><a href="#">Another action</a></li>
							    <li><a href="#">Something else here</a></li>
							    <li class="divider"></li>
							    <li><a href="#">Separated link</a></li>
						  	</ul>

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
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_search"><i class="icon-search"></i> Search</a>
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
						   	<div id="tab_lesson_search" class="tab-pane">
						   		<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="400px">
							   		<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
									 	Under construction
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
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number lesson" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Lesson</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number class" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Class</a>
			</div>
		</div>
		<!--
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-3">
			<div class="easy-pie-chart">
				<div class="number semester" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Semester</a>
			</div>
		</div>
		-->
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4">
			<div class="easy-pie-chart">
				<div class="number course" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">Course</a>
			</div>
		</div>
			<div class="clearfix margin-bottom-10"></div>
	</div>
</div>

<script type="text/template" id="tab_course_description-template">
	<%= description %>
</script>


<script type="text/template" id="tab_course_classes-nofound-template">
	<td colspan="5"  class="alert alert-info">
		<span class="text-info">
			<i class="icon-warning-sign"></i>
			{translateToken value='Ops! There\'s any classes registered for this course'}
		</span>
	</td>
</script>
<script type="text/template" id="tab_course_classes-item-template">
	<td><a href="#class-tab" class="class-change-action" data-ref-id="<%= id %>" ><%= name %></a></td>
	<td class="text-center"><span class="label label-danger">No</span></td>
	<td class="text-center"></td>
	<td class="text-center"></td>
	<td class="text-center"><span class="label label-info">In Progress</span></td>
</script>

<script type="text/template" id="tab_roadmap-season-template">
	<h5>
		<i class="icon-angle-down"></i>
		<a data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>"> <%= name %> </a>
		<small>
		<% if (typeof max_classes == 'undefined') { %>
			<span class="size-counter"><%= _.size(classes) %></span> total classes
		<% } else { %>
			<span class="size-counter"><%= _.size(classes) %></span> / <%= max_classes %> classes selected
		<% } %>
		</small>
	</h5>
	<div id="season-<%= id %>" class="in">
		<ul class="list-group <% if (_.size(classes)== 0) { %>empty-list-group<% } %>">
			<% if (_.size(classes)== 0) { %>
			<% } else { %>
				<% _.each(classes, function (classe, i) { %>
				<li class="list-group-item draggable btn btn-block btn-default red-stripe" data-class-id="<%= classe.id %>">
					<p class="list-group-item-text">
						<a href="#class-tab" class="class-change-action" data-ref-id="<%= classe.id %>" >
							<%= classe.name %>
						</a>
					</p>
				</li>
				<% }) %>
			<% } %>
		</ul>
	</div>
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




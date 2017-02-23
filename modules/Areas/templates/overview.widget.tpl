<!--
<div id="courses-list">
	<div class="scroller list-group" data-height="199px" data-always-visible="1">
	</div>
</div>
-->
<div>
	<div class="courses-container" id="courses-content">
		<div class="portlet-tabs" id="courses-content-navigation">
			<ul class="nav nav-tabs">
				<li class="the-course-tab">
					<a data-toggle="tab" href="#course-tab">
						<div class="nav-title">
							<span class="">{translateToken value="Courses"}</span>
						</div>
					</a>
				</li>
				<li class="the-class-tab">
					<a data-toggle="tab" href="#class-tab">
						<div class="nav-title">
							<span class="tab-title">{translateToken value="Classes"}</span>
						</div>
					</a>
				</li>

				<li class="the-lesson-tab active">
					<a data-toggle="tab" href="#lesson-tab">
						<div class="nav-title">
							<span class="tab-title">{translateToken value="Units"}</span>
						</div>
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
								<strong>{translateToken value="You're in:"} </strong>
							</a>
							<a href="#" class="navbar-brand course-title">
								 {translateToken value="Course"}
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">

							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous course"}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next course"}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#tab_course_description"><i class="icon-info-sign"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Description"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_course_classes"><i class="icon-book"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Classes"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_course_roadmap"><i class="icon-comments"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Road map"}</span></a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_course_description" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										{translateToken value="No content posted in this course"}
									</div>
								</div>
							</div>
							<div id="tab_course_classes" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>{translateToken value="Name"}</th>
												<th class="text-center">{translateToken value="Completed"}</th>
												<th class="text-center">{translateToken value="Attendence"}</th>
												<th class="text-center">{translateToken value="Grade"}</th>
												<th class="text-center">{translateToken value="Status"}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
										</tbody>
									</table>

								</div>
							</div>
							<div id="tab_course_roadmap" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div id="tab_course_roadmap-accordion">
									</div>
								</div>
							</div>
						</div>
					 </div>
				</div>
				<div id="class-tab" class="tab-pane">

					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand disabled">
								<strong>{translateToken value="You're in:"} </strong>
							</a>
							<a href="#" class="navbar-brand course-title hidden-xs">
								{translateToken value="Course"}
							</a>
							<a href="#" class="navbar-brand hidden-xs">&raquo;</a>
							<a href="#" class="navbar-brand class-title">
								{translateToken value="Class"}
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous Class"}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next Class"}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="tabbable-custom">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#tab_class_info"><i class="icon-info-sign"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Info"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_instructor"><i class="icon-user"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Instructor"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_class_dropbox"><i class="icon-dropbox"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Dropbox"}</span></a>
							</li>

							<li class="hidden-xxs">
								<a data-toggle="tab" href="#tab_class_bibliography"><i class="icon-book"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Bibliography"}</span></a>
							</li>
							<li class="hidden-xxs hidden-md">
								<a data-toggle="tab" href="#tab_class_attendence"><i class="icon-calendar"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Attendence"}</span></a>
							</li>
							<li class="hidden-xxs hidden-md">
								<a data-toggle="tab" href="#tab_class_exams"><i class="icon-pencil"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Tests"}</span></a>
							</li>
							<li class="visible-xxs visible-md">
								<a data-toggle="dropdown" href="javascript: void(0);"><i class="icon-ellipsis-horizontal"></i> <span class="hidden-xs inline">{translateToken value="More"}</span></a>
								<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="">
									<li class="visible-xs">
										<a data-toggle="tab" href="#tab_class_bibliography"><i class="icon-book"></i> <span class="">{translateToken value="Bibliography"}</span></a>
									</li>
									<li class="">
										<a data-toggle="tab" href="#tab_class_attendence"><i class="icon-calendar"></i> <span class="">{translateToken value="Attendence"}</span></a>
									</li>
									<li class="">
										<a data-toggle="tab" href="#tab_class_exams"><i class="icon-pencil"></i> <span class="">{translateToken value="Tests"}</span></a>
									</li>
  								</ul>
							</li>
							<!--
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-ellipsis-horizontal"></i> More <i class="icon-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right" role="menu">

								</ul>
							</li>
							-->
						</ul>
						<div class="tab-content">
							<div id="tab_class_objectives" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">

								</div>
							</div>
							<div id="tab_class_info" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<h5>During this course you will...</h5>
									<p class="">... be exposed to research methods and state of the art medical devices and technologies in diferent medical fields</p>
									<p class="">... meet international researchers and interact with European users in an intercultural and interdisciplinary context</p>
									<p class="">... and much more.</p>
									<hr />
									<table class="table table-striped table-bordered table-advance table-hover">
										<tbody>
											<tr>
												<td>{translateToken value="Prerequisite(s):"}</td>
												<td><strong class="text-default pull-right"><span class="label label-success">{translateToken value="None"}</span></strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Credit hours:"}</td>
												<td><strong class="text-default pull-right">80h</strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Number of Classes:"}</td>
												<td><strong class="text-default pull-right">3 of 24</strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Tests:"}</td>
												<td><strong class="text-default pull-right">1 of 3</strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Papers:"}</td>
												<td><strong class="text-default pull-right"><span class="label label-success">None</span></strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Exams:"}</td>
												<td><strong class="text-default pull-right">0 of 4</strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Books:"}</td>
												<td><strong class="text-default pull-right">2</strong></td>
											</tr>
											<tr>
												<td>{translateToken value="Required Equipment:"}</td>
												<td><strong class="text-default pull-right"><span class="label label-success">{translateToken value="None"}</span></strong></td>
											</tr>
										</tbody>
									</table>
									<hr />
							   	</div>
							</div>
							<div id="tab_class_instructor" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
							   		<h5 class="text-danger block"><strong>Instructor Dr. Joe Walters</strong></h5>

							   		<ul class="media-list">
										<li class="media">
											<a href="#" class="pull-right">
												<img class="media-object" src="holder.js/128x128" alt="No Photo">
											</a>
											<div class="media-body">
												<p class="">
													<span>{translateToken value="Position:"}</span>
													<strong class="text-default pull-right">Emeritus Instructor</strong>
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Division/Portifolio:"}</span>
													<strong class="text-default pull-right">Division of Education, Arts and Social Sciences</strong>
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="School Unit:"}</span>
													<strong class="text-default pull-right">School of Education</strong>
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Campus:"}</span>
													<strong class="text-default pull-right">Mawson Lakes Campus</strong>
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Office:"}</span>
													<span class="text-default pull-right">G3-12</span >
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Telephone:"}</span>
													<span class="text-default pull-right">(555) 555-5555</span >
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Fax:"}</span>
													<span class="text-default pull-right">(555) 555-5555</span >
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Email:"}</span>
													<a href="javascript:void(0);" class="pull-right">joe.walters@lucent.edu</a>
												</p>
												<hr />
												<p class="">
													<span>{translateToken value="Site URL:"}</span>
													<a href="javascript:void(0);" class="pull-right">http://myname@myname.com</a>
												</p>
												<hr />

											</div>
										</li>
									</ul>
							   	</div>
							</div>
							<div id="tab_class_dropbox" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<h5>
										<a href="javascript: void(0)"> {translateToken value="Instructor files"} </a> -
										<small>
											<span class="size-counter">2</span> {translateToken value="files"}

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
											<div class="pull-right">
												<a class="btn btn-sm btn-danger" href="javascript: void(0);">{translateToken value="View"}</a>
												<a class="btn btn-sm btn-success" href="javascript: void(0);">{translateToken value="Download"}</a>
											</div>
										</div>
									</div>
									<!--
								</div>
								<hr />
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="200px">
									-->
									<h5>
										<a href="javascript: void(0)">{translateToken value="Your Files"} </a> -
										<small>
											<span class="size-counter">2</span> {translateToken value="files"}

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
											<div class="pull-right">
												<a class="btn btn-sm btn-danger" href="javascript: void(0);">{translateToken value="View"}</a>
												<a class="btn btn-sm btn-success" href="javascript: void(0);">{translateToken value="Download"}</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="tab_class_bibliography" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>{translateToken value="Book"}</th>
												<th class="text-center">{translateToken value="Author"}</th>
												<th class="text-center">{translateToken value="Type"}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>World Map</td>
												<td class="text-center">Joe Walton</td>
												<td class="text-center"><span class="label label-danger">{translateToken value="Required"}</span></td>
											</tr>
											<tr>
												<td>History of Maps</td>
												<td class="text-center">Mike Joshua</td>
												<td class="text-center"><span class="label label-info">{translateToken value="Suggested"}</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div id="tab_class_attendence" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										{translateToken value="No data has been registered for this class."}
									</div>
								</div>
							</div>
							<div id="tab_class_exams" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>{translateToken value="Exams"}</th>
												<th class="text-center">{translateToken value="Date"}</th>
												<th class="text-center">{translateToken value="Status"}</th>
												<th class="text-center">{translateToken value="Grade"}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Test #1</td>
												<td class="text-center">13rd March, 2014</td>
												<td class="text-center"><span class="label label-danger">{translateToken value="Closed"}</span></td>
												<td class="text-center"><span class="label label-warning">60</span></td>
											</tr>
											<tr>
												<td>Test #2</td>
												<td class="text-center">13rd May, 2014</td>
												<td class="text-center"><span class="label label-success">{translateToken value="Open"}</span></td>
												<td class="text-center"></td>
											</tr>
											<tr>
												<td>Test #3</td>
												<td class="text-center">13rd July, 2014</td>
												<td class="text-center"><span class="label label-warning">{translateToken value="Stand by"}</span></td>
												<td class="text-center"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="lesson-tab" class="tab-pane active">
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand disabled">
								<strong>{translateToken value="You're in:"} </strong>
							</a>
							<a href="javascript: void(0);" data-toggle="dropdown" class="navbar-brand class-title hidden-xs">
								{translateToken value="Class"}
							</a>
							<a href="javascript: void(0);" class="navbar-brand hidden-xs">&raquo;</a>
							<a href="javascript: void(0);" class="navbar-brand lesson-title">
								{translateToken value="Units"}
							</a>
							<a href="javascript: void(0);" class="navbar-brand">
								<span class="label label-success"><i class="icon-ok-sign"></i>  {translateToken value="Viewed"}</span>
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous unit"}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next unit"}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>

								<!--
								<li>
									<a href="#" class="nav-search-action tooltips" data-original-title="{translateToken value="Search Units"}" data-placement="top" data-search>
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
								<a data-toggle="tab" href="#tab_lesson_content"><i class="icon-magic"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Video Unit"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_materials"><i class="icon-book"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Materials"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_exercises"><i class="icon-pencil"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Exercises"}</span></a>
							</li>
							<!--
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_search"><i class="icon-search"></i> Search</a>
							</li>
							-->
						</ul>
						<div class="tab-content">
							<div id="tab_lesson_content" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										{translateToken value="There is no content in this lesson"}
									</div>
								</div>
							</div>
							<div id="tab_lesson_materials" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<h5>
										<a href="javascript: void(0)"> {translateToken value="Unit files"} </a> -
										<small>
											<span class="size-counter">2</span> {translateToken value="files"}
										</small>
									</h5>
									<div id="tab_lesson_materials_container"></div>
								</div>
							</div>
						    <div id="tab_lesson_exercises" class="tab-pane">
						    	<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
								   	<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										 {translateToken value="There are no exercises posted in this lesson"}
								   	</div>
							   	</div>
						   	</div>
						   	<!--
						   	<div id="tab_lesson_search" class="tab-pane">
						   		<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
							   		<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
									 	Under construction
							   		</div>
							   	</div>
						   </div>
						   -->
						</div>
					 </div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="row" id="progress-content">
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="easy-pie-chart">
				<div class="number lesson" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Unit"}</a>
			</div>
		</div>
		<div class="margin-bottom-10 visible-sm"></div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="easy-pie-chart">
				<div class="number class" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Class"}</a>
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
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="easy-pie-chart">
				<div class="number course" data-percent="0"><span>0</span>%</div>
				<a class="title btn btn-link disabled" href="javascript: void(0);">{translateToken value="Course"}</a>
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
			{translateToken value="There are no units posted in this course"}
		</span>
	</td>
</script>
<script type="text/template" id="tab_course_classes-item-template">
	<td><a href="#class-tab" class="class-change-action" data-ref-id="<%= id %>" ><%= name %></a></td>
	<td class="text-center"><span class="label label-danger">{translateToken value="No"}</span></td>
	<td class="text-center"></td>
	<td class="text-center"></td>
	<td class="text-center"><span class="label label-info">{translateToken value="In progress"}</span></td>
</script>

<script type="text/template" id="tab_roadmap-season-template">
	<h5>
		<i class="icon-angle-down"></i>
		<a data-toggle="collapse" data-parent="#tab_course_roadmap-accordion" href="#season-<%= id %>"> <%= name %> </a>
		<small>
		<% if (typeof max_classes == 'undefined') { %>
			<span class="size-counter"><%= _.size(classes) %></span> {translateToken value="total classes"}
		<% } else { %>
			<span class="size-counter"><%= _.size(classes) %></span> / <%= max_classes %> {translateToken value="classes selected"}
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
			<div class="pull-right">
				<a class="btn btn-sm btn-danger" href="javascript: void(0);">{translateToken value="View"}</a>
				<a class="btn btn-sm btn-success" href="javascript: void(0);">{translateToken value="Download"}</a>
			</div>
		</div>
	</div>
</script>




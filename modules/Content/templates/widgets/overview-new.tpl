{assign var="widget_data" value=$T_DATA.data}
<script>
_before_init_functions.push(function() {
    $SC.addResource("content_widget_data", {$widget_data|@json_encode nofilter});
});
</script>

<div>
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="col-md-12 no-padding inter-navsuper">
            <ul class="nav nav-tabs col-md-8 no-padding" role="tablist">
              <li role="presentation" class="active">
              	<a href="#tab_course_units" aria-controls="tab_course_units" role="tab" data-toggle="tab"><i class="fa fa-file-text"></i>{translateToken value="Units"}</a>
              </li>
              <li role="presentation">
              	<a href="#tab_program_courses" aria-controls="tab_program_courses" role="tab" data-toggle="tab"><i class="fa fa-sitemap"></i>{translateToken value="Courses"}</a>
              </li>
              <!--
              <li role="presentation">
              	<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-sitemap"></i>{translateToken value="Timeline"}</a>
              </li>
              -->
              <li role="presentation" class="">
              	<a href="#tab_program_description" aria-controls="tab_program_description" role="tab" data-toggle="tab"><i class="fa fa-area-chart"></i>{translateToken value="Program"}</a>
              </li>
            </ul>
            <ul class="dir-menu-bar">
              <!--
              <li><a href=""><i class="fa fa-search" aria-hidden="true"></i></a></li>
              <li><a href=""><i class="fa fa-info" aria-hidden="true"></i></a></li>
              <li><a href=""><i class="fa fa-dropbox" aria-hidden="true"></i></a></li>
              -->
            </ul>
          </div>
          <!-- Tab panes -->
        </div>
        <div class="col-sm-12 col-md-12 inter-navsuper-tabs">
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab_course_units">
              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <!-- TAGS STATUS DO SISTEMA -->
                <span class="pendente-tag">Not Avaliable<span class="pendente"></span></span>
                <span class="concluido-tag">Viewed / Done / OK<span class="concluido"></span></span>
                <span class="avalialbe-tag">Avaliable<span class="avalialbe"></span></span>
                <span class="andamento-tag">In Progress<span class="andamento"></span></span>
                <!-- <span class="fechado-tag">Disable<span class="fechado"></span></span> -->
              </div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>{translateToken value="Unit"}</th>
                    <th>{translateToken value="Video"}</th>
                    <th>{translateToken value="Material"}</th>
                    <!--
                    <th>{translateToken value="Exercise"}</th>
                    <th>{translateToken value="Test"}</th>
                    -->
                    <!--
                    <th>{translateToken value="Exam"}</th>
                    -->
                    <th>{translateToken value="Status"}</th>
                    <!-- <th></th> -->
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab_program_courses">

              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <!-- TAGS STATUS DO SISTEMA -->
                <span class="pendente-tag">Not Avalialbe<span class="pendente"></span></span>
                <span class="concluido-tag">Viewed / Done / OK<span class="concluido"></span></span>
                <span class="avalialbe-tag">Avalialbe<span class="avalialbe"></span></span>
                <span class="andamento-tag">In Progress<span class="andamento"></span></span>
                <span class="fechado-tag">Disable<span class="fechado"></span></span>
              </div>

              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form action="">
                  <input type="text" placeholder="Escreva aqui a sua pesquisa">
                </form>
              </div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th></th>
                    <th>{translateToken value="Course"}</th>
                    <th>{translateToken value="Course"}Instrutor</th>
                    <th>{translateToken value="Unit in Progress"}</th>
                    <th>{translateToken value="Cumulative Grade"}</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="profile">
              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <!-- TAGS STATUS DO SISTEMA -->
                <span class="pendente-tag">Not Avalialbe<span class="pendente"></span></span>
                <span class="concluido-tag">Viewed / Done / OK<span class="concluido"></span></span>
                <span class="avalialbe-tag">Avalialbe<span class="avalialbe"></span></span>
                <span class="andamento-tag">Attention<span class="andamento"></span></span>
                <span class="fechado-tag">Disable<span class="fechado"></span></span>
              </div>
              <table class="table table-striped">
                <thead>
                  <h3>Primeiro Semestre</h3>
                  <tr>
                    <th>Course</th>
                    <th>Periodo</th>
                    <th>Credito/Horas</th>
                    <th>Status</th>
                    <th>Final Grade</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Nome do Curso</td>
                    <td>24/10/2014 às 20:30h</td>
                    <td>X/X</td>
                    <td>
                      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
                      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
                      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
                      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
                    </td>
                    <td class="nota-tal">N/A</td>
                  </tr>
                  <tr>
                    <td>Nome do Curso</td>
                    <td>24/10/2014 às 20:30h</td>
                    <td>X/X</td>
                    <td>
                      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
                      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
                      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
                      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
                    </td>
                    <td class="nota-tal">N/A</td>
                  </tr>
                  <tr>
                    <td>Nome do Curso</td>
                    <td>24/10/2014 às 20:30h</td>
                    <td>X/X</td>
                    <td>
                      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
                      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
                      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
                      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
                    </td>
                    <td class="nota-tal">N/A</td>
                  </tr>
                </tbody>
              </table>
              <table class="table table-striped">
                <thead>
                  <h3>Primeiro Semestre</h3>
                  <tr>
                    <th>Course</th>
                    <th>Periodo</th>
                    <th>Credito/Horas</th>
                    <th>Status</th>
                    <th>Final Grade</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Nome do Curso</td>
                    <td>24/10/2014 às 20:30h</td>
                    <td>X/X</td>
                    <td>
                      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
                      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
                      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
                      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
                    </td>
                    <td class="nota-tal">N/A</td>
                  </tr>
                  <tr>
                    <td>Nome do Curso</td>
                    <td>24/10/2014 às 20:30h</td>
                    <td>X/X</td>
                    <td>
                      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
                      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
                      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
                      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
                    </td>
                    <td class="nota-tal">N/A</td>
                  </tr>
                  <tr>
                    <td>Nome do Curso</td>
                    <td>24/10/2014 às 20:30h</td>
                    <td>X/X</td>
                    <td>
                      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
                      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
                      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
                      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
                      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
                    </td>
                    <td class="nota-tal">N/A</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab_program_description">
            </div>

          </div>
        </div>
    </div>


    <!--
	<div class="courses-container" id="courses-content">
		<div class="portlet-tabs" id="courses-content-navigation">
			<ul class="nav nav-tabs">
				<li class="the-course-tab">
					<a data-toggle="tab" href="#program-tab">
						<div class="nav-title">
							<span class="">
								<i class="fa fa-graduation-cap"></i>&nbsp;
								{translateToken value="Programs"}
							</span>
							<span class="label label-sm label-success"><strong><span class="program-count"></span></strong></span>
						</div>
					</a>
				</li>
				<li class="the-class-tab">
					<a data-toggle="tab" href="#course-tab">
						<div class="nav-title">
							<span class="tab-title">
								<i class="fa fa-sitemap"></i> 
								{translateToken value="Courses"}
							</span>
							<span class="label label-sm label-success"><strong><span class="course-count"></span></strong></span>
						</div>
					</a>
				</li>
				<li class="the-lesson-tab active">
					<a data-toggle="tab" href="#unit-tab">
						<div class="nav-title">
							<span class="tab-title">
								<i class="fa fa-book"></i> 
								
							</span>
							<span class="label label-sm label-success"><strong><span class="unit-count"></span></strong></span>
						</div>
					</a>
				</li>
			</ul>
			<div class="clearfix"></div>
			<div class="tab-content">
				<div id="program-tab" class="tab-pane">
					<div class="clearfix"></div>
					<div class="navbar navbar-default navbar-lesson" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand program-title">
								 {translateToken value="Program"}
							</a>
							<a href="javascript: void(0);" class="navbar-brand viewed-status hidden">
								<span class="label label-success">
									<i class="ti-check"></i> <span class="hidden-xs">{translateToken value="Completed"}</span>
								</span>
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous"}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-info no-padding disabled">
                    					<span class="entity-current"></span> / <span class="entity-count"></span>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next"}" data-placement="top">
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
								<a data-toggle="tab" href="#tab_program_description"><i class="fa fa-list-alt"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Description"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_program_courses"><i class="fa fa-sitemap"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Courses"}</span></a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_program_description" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										{translateToken value="Ops! There's any info registered for this program"}
									</div>
								</div>
							</div>

							<div id="tab_program_courses" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>{translateToken value="Name"}</th>
												<th class="text-center">{translateToken value="Completed"}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					 </div>
				</div>
				<div id="course-tab" class="tab-pane">

					<div class="navbar navbar-default navbar-lesson" role="navigation">
						<div class="navbar-header">
							<a href="#" class="navbar-brand program-title hidden-xs">
								{translateToken value="Program"}
							</a>
							<a href="#" class="navbar-brand hidden-xs">&raquo;</a>
							<a href="#" class="navbar-brand course-title">
								{translateToken value="Course"}
							</a>
							<a href="javascript: void(0);" class="navbar-brand viewed-status hidden">
								<span class="label label-success">
									<i class="ti-check"></i> <span class="hidden-xs">{translateToken value="Completed"}</span>
								</span>
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous"}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-info no-padding disabled">
                    				<span class="entity-current"></span> / <span class="entity-count"></span>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next"}" data-placement="top">
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
								<a data-toggle="tab" href="#tab_course_info"><i class="fa fa-info-circle"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Description"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_course_instructor"><i class="icon-user"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Instructor"}</span></a>
							</li>
							<li class="">

								<a data-toggle="tab" href="#tab_course_units"><i class="fa fa-clipboard"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Units"}</span></a>
							</li>

							<li class="visible-xxs visible-md">
								<a data-toggle="dropdown" href="javascript: void(0);"><i class="icon-ellipsis-horizontal"></i> <span class="hidden-xs inline">{translateToken value="More"}</span></a>
								<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="">
  								</ul>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_course_info" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="383px">
							   	</div>
							</div>
							<div id="tab_course_instructor" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="383px">
							   	</div>
							</div>
							<div id="tab_course_units" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>{translateToken value="Name"}</th>
												<th class="text-center">{translateToken value="# Questions"}</th>
												<th class="text-center">{translateToken value="Times done"}</th>
												<th class="text-center">{translateToken value="Grade"}</th>
												<th class="text-center">{translateToken value="Completed"}</th>
												<th class="text-center">{translateToken value="Options"}</th>
											</tr>
										</thead>

										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="unit-tab" class="tab-pane active">
					<div class="navbar navbar-default navbar-lesson" role="navigation">
						<div class="navbar-header">
							<a href="javascript: void(0);" data-toggle="dropdown" class="navbar-brand course-title hidden-xs">
								{translateToken value="Course"}
							</a>
							<a href="javascript: void(0);" class="navbar-brand hidden-xs">&raquo;</a>
							<a href="javascript: void(0);" class="navbar-brand unit-title">
								{translateToken value="Unit"}
							</a>
							<a href="javascript: void(0);" class="navbar-brand viewed-status hidden">
								<span class="label label-success">
									<i class="ti-check"></i> <span class="hidden-xs">{translateToken value="Completed"}</span>
								</span>
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse">
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous"}" data-placement="top">
										<i class="icon-arrow-left"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-info no-padding disabled">
                    				<span class="entity-current"></span> / <span class="entity-count"></span>
									</a>
								</li>
								<li>
									<a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next"}" data-placement="top">
										<i class="icon-arrow-right"></i>
									</a>
								</li>

							</ul>
						</div>
					</div>

					<div class="tabbable-custom ">
						<ul class="nav nav-tabs ">
							<li class="active">
								<a data-toggle="tab" href="#tab_unit_video"><i class="fa fa-youtube-play"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Video"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_unit_materials"><i class="fa fa-book"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Materials"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_unit_tests"><i class="fa fa-list-ol"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Tests"}</span></a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="tab_unit_video" class="tab-pane active">
							</div>
							<div id="tab_unit_materials" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th class="text-center">{translateToken value="Type"}</th>
												<th class="text-center">{translateToken value="Name"}</th>
												<th class="text-center">{translateToken value="Viewed"}</th>
												<th class="text-center">{translateToken value="Options"}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div id="tab_unit_tests" class="tab-pane">
							</div>
						</div>
					 </div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	-->
</div>

<script type="text/template" id="tab_all_child-nofound-template">
	<tr>
		<td colspan="6"  class="alert alert-info">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's no data registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_program_description-template">
	<%= model.description %>
	<% if (!_.isEmpty(model.objectives)) { %>
		<hr />
		<h5>{translateToken value="Objetives"}</h5>
		
		<%= model.objectives %>
	<% } %>
	<% if (!_.isEmpty(model.coordinator)) { %>
		<hr />
		<h5>{translateToken value="Coordinator"}</h5>
		<table class="table table-striped table-bordered table-advance table-hover">
			<tr>
				<td>{translateToken value="Name"}</td>
				<td><%= model.coordinator.name %> <%= model.coordinator.surname %></td>
			</tr>
		</table>
	<% } %>
</script>
<script type="text/template" id="tab_program_courses-nofound-template">
	<tr>
		<td colspan="5"  class="alert alert-info">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's any courses registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_program_courses-item-template">
    <td><a href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i></a></td>
    <td><%= model.name %>
        <!-- STATUS DO SISTEMA CORPO -->
        <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
        <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
        <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
        <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
        <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
        <!-- DATA DE ? -->
        <span class="at-difinf">24/10/2014 às 20:30h</span>
    </td>
    <td>Carlos Oliveira <span class="at-difinf"><a href="">ver perfil</a></span></td>
    <td>1/5 Avaliação do Ciclo de Vida
      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
    </td>
    <td class="nota-tal">N/A</td>
    <td><button type="button" class="btn btn-primary">FAZER</button></td>

    <!--
	<td class="text-center">
		<% if (_.isObject(model.progress) && model.progress.factor >= 1) { %>
			<span class="label label-success">{translateToken value="Yes"}</span>
		<% } else { %>
			<span class="label label-danger">{translateToken value="No"}</span>
		<% } %>
	</td>
	-->
</script>

<script type="text/template" id="tab_courses_child-nofound-template">
	<tr>
		<td colspan="6"  class="alert alert-info">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's no data registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_courses_units-item-template">
<% console.warn(model) %>
<!-- Unidade -->
<td>
  <span class="btn btn-sm btn-circle btn-default disabled">
    <i class="fa fa-file"></i>
    Lesson
  </span>
  <%= model.name %>
</td>
<!-- Video -->
<td>
  Watch
  <span class="concluido">
    <i class="fa fa-check" aria-hidden="true"></i>
    Viewed
  </span>
</td>

  

<!-- Material -->
<td>
  <% if (_.size(model.materials) == 0) { %> 
    <span class="pendente">
      <i class="fa fa-ban" aria-hidden="true"></i>
      Not Avaliable
    </span>
  <% } else { %>
    <% _.each(model.materials, function (item, index) { %>
      <% if (item.progress.factor >= 1) { %>
        <span class="concluido">
          <i class="fa fa-check" aria-hidden="true"></i>
          Viewed
        </span>
      <% } else if (item.progress.factor > 0) { %>
      <% } else { %>
        <span class="avalialbe">
          <i class="fa fa-coffee" aria-hidden="true"></i>
          Avaliable
        </span>
      <% } %>
    <!-- <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span> -->
    <% }); %>
  <% } %>
</td>
<!-- Exercicio -->
<!--
<td>
  <% if (_.size(model.exercises) == 0) { %> 
  <% } else { %>
    <% _.each(model.exercises, function (item, index) { %>
      <% if (item.progress.factor >= 1) { %>
        <span class="concluido">
          <i class="fa fa-check-circle" aria-hidden="true"></i>
          Viewed
        </span>
      <% } else if (item.progress.factor > 0) { %>
      <% } else { %>
        <span class="avalialbe">
          <i class="fa fa-coffee" aria-hidden="true"></i>
          Avaliable
        </span>
      <% } %>
    <% }); %>
  <% } %>
</td>
-->
<!-- Teste -->
<!--
<td>
  <span class="pendente">
    <i class="fa fa-ban" aria-hidden="true"></i>
    Not Avaliable
  </span>
</td>
-->
<!-- Exame -->
<!-- <td>
  <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
  <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
  <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
</td>
 --><!-- Status -->
<td>
  <span class="concluido">
    <i class="fa fa-check" aria-hidden="true"></i>
    Completed
  </span>
</td>
<!-- Opções -->
<!-- <td><button type="button" class="btn btn-primary">FAZER</button></td> -->
	<!--
	<td><a href="javascript:void(0)" class="lesson-change-action"><%= model.name %></a></td>
	<td class="text-center"></td>
	<td class="text-center"></td>
	<td class="text-center"></td>
	<td class="text-center">
		<% if (_.isObject(model.progress) && model.progress.factor >= 1) { %>
			<span class="label label-success">{translateToken value="Yes"}</span>
		<% } else { %>
			<span class="label label-danger">{translateToken value="No"}</span>
		<% } %>
	</td>
	<td class="text-center"></td>
	-->
</script>

<script type="text/template" id="tab_courses_tests-item-template">
<!-- Unidade -->
<td>
  <span class="btn btn-sm btn-circle btn-default disabled">
    <i class="fa fa-list-ol"></i>
    Exam
  </span>
  <%= model.name %>
</td>
<!-- Video -->
<td></td>
<!-- Material -->
<td>
  <!--
  <span class="pendente">
    <i class="fa fa-ban" aria-hidden="true"></i>
    Not Avaliable
  </span>
  -->
</td>
<!-- Exercicio -->
<!--
<td>
  <span class="pendente">
    <i class="fa fa-ban" aria-hidden="true"></i>
    Not Avaliable
  </span>
</td>
-->
<!-- Teste -->
<!--
<td>
  
  <span class="pendente">
    <i class="fa fa-ban" aria-hidden="true"></i>
    Not Avaliable
  </span>
</td>
-->   
<!-- Exame -->
<!-- <td>
  <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
  <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
  <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
</td>
 --><!-- Status -->
<td>
  <span class="concluido">
    <i class="fa fa-check" aria-hidden="true"></i>
    Done
  </span>
</td>
<!-- Opções -->
<!-- <td><button type="button" class="btn btn-primary">FAZER</button></td> -->
<!--
	<%
	if (_.has(model, 'test')) {
		if (model.test.test_max_questions == 0) {
			var total_questions = _.size(model.test.questions);
		} else {
			var total_questions = model.test.test_max_questions;
		}
	} else {
		var total_questions = 0; 
	} 
	%>
	<td><a href="javascript:void(0)" class="view-test-action"><%= model.name %></a></td>
	<td class="text-center"><%= total_questions %></td>
	<td class="text-center">
		<% if (_.has(model, 'test')) { %>
			<span class="label label-danger">
			<%= _.size(model.test.executions) %>
			<% if (model.test.test_repetition > 0) { %>
				 / <%= model.test.test_repetition %>
			<% } %>
			</span>
		<% } %>
	</td>
	<td class="text-center">
		<% if (_.has(model, 'test')) { %>
			<%
			if (_.size(model.test.executions) > 0) {
				 var execution = _.last(model.test.executions);
			%>
				<span class="label label-primary"><%= execution.user_grade %></span>
				<small><%= execution.user_points %> {translateToken value="points"}</small>
			<% } %>
		<% } %>
	</span></td>
	<td class="text-center">
		<% if (_.has(model, 'test') && _.size(model.test.executions) > 0) { %>
			<span class="label label-success">{translateToken value="Yes"}</span>
		<% } else { %>
			<span class="label label-danger">{translateToken value="No"}</span>
		<% } %>
	</td>
	<td class="text-center">
		<% if (total_questions > 0 && (model.test.test_repetition <= 0 || _.size(model.test.executions) < model.test.test_repetition) ) { %>
			<a href="/module/tests/open/<%= model.id %>" class="btn btn-xs btn-primary open-test-action">
				{translateToken value="Do now!"}
			</a>
		<% } %>
		<% if (total_questions > 0) { %>
			<a href="javascript:void(0);" class="btn btn-xs btn-info view-test-action">
				{translateToken value="Details"}
			</a>
		<% } %>
	</td>
-->
</script>

<!--
<script type="text/template" id="tab_course_moreinfo-template">
	<table class="table table-striped table-bordered table-advance table-hover">
		<tbody>
			<tr>
				<td>{translateToken value="Goals"}</td>
				<td><%= model.goals %></td>
			</tr>

		</tbody>
	</table>
	<hr />
</script>
-->
<!--
<script type="text/template" id="tab_program_coordinator-template">
	<table class="table table-striped table-bordered table-advance table-hover">
		<tbody>
			<tr>
				<td>{translateToken value="Name"}</td>
				<td><%= model.coordinator.name %> <%= model.coordinator.surname %></td>
			</tr>
		</tbody>
	</table>
	<hr />
</script>
<script type="text/template" id="tab_courses_info-template">

	<%= model.description %>
	<% if (!_.isEmpty(model.objectives)) { %>
		<hr />
		<h5>{translateToken value="Objetives"}</h5>
		
		<%= model.objectives %>
	<% } %>
	<% if (!_.isEmpty(model.professor)) { %>
		<hr />
		<h5>{translateToken value="Coordinator"}</h5>
		<table class="table table-striped table-bordered table-advance table-hover">
			<tr>
				<td>{translateToken value="Name"}</td>
				<td><%= model.professor.name %> <%= model.professor.surname %></td>
			</tr>
		</table>
	<% } %>
</script>

<script type="text/template" id="tab_courses_instructor-template">
	<% var professor = model.professor; %>
	<% if (_.size(professor) > 0) { %>
	<table class="table table-striped table-bordered table-advance table-hover">
		<tbody>
			<tr>
				<td>{translateToken value="Name"}</td>
				<td><%= professor.name %> <%= professor.surname %></td>
			</tr>
		</tbody>
	</table>
	<% } %>
	<hr />
</script>
-->


<script type="text/template" id="tab_unit_video-nofound-template">
	<div class="alert alert-info">
		<span class="text-info"><i class="icon-warning-sign"></i></span>
		{translateToken value="Ops! There's any content for this lesson"}
	</div>
</script>


<script type="text/template" id="tab_unit_video-item-template">
	<div class="videocontent">
		<video id="unit-video-<%= model.id %>" class="video-js vjs-default-skin vjs-big-play-centered"
			width="auto"  height="auto"
			<% if (!_.has(model, 'poster')) { %>
				poster="{Plico_GetResource file='images/default-poster.jpg'}"
			<% } else { %>
				poster="<%= model.poster.file.url %>"
			<% } %>
			style="max-height:100%;max-width:100%;">
			<% if (_.has(model, 'file')) { %>
				<source src="<%= model.file.url %>" type='<%= model.file.type %>' />
			<% } else if (_.has(model, 'content')) { %>
				<source src="<%= model.content %>" />
			<% } %>

			<% _.each(model.childs, function(item, index){ %>
				<track kind="subtitles" src="<%= item.file.url %>" srclang="<%= item.language_code %>" label="<%= item.language_code %>"></track>
			<% }); %>
		</video>
	</div>
</script>

<script type="text/template" id="tab_unit_materials-nofound-template">
	<tr>
		<td colspan="5"  class="alert alert-info">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's any materials registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_unit_materials-item-template">
    <%
        var file_type = "other";

        if (/^video\/.*$/.test(model.file.type)) {
            file_type = "video";
        } else if (/^image\/.*$/.test(model.file.type)) {
            file_type = "image";
        } else if (/^audio\/.*$/.test(model.file.type)) {
            file_type = "audio";
        } else if (/.*\/pdf$/.test(model.file.type)) {
            file_type = "pdf";
        }
    %>
	<td class="text-center">
        <% if (file_type == "video") { %>
            <i class="fa fa-file-video-o"></i>
        <% } else if (file_type == "image") { %>
            <i class="fa fa-file-image-o"></i>
        <% } else if (file_type == "audio") { %>
            <i class="fa fa-file-sound-o"></i>
        <% } else if (file_type == "pdf") { %>
            <i class="fa fa-file-pdf-o"></i>
        <% } else { %>
            <i class="fa fa-file-o"></i>
        <% }  %>
	</td>
	<td><a href="#class-tab" class="class-change-action"><%= model['file'].name %></a></td>
	<td class="text-center">
		<% if (_.isObject(model.progress) && model.progress.factor >= 1) { %>
			<span class="label label-success">{translateToken value="Yes"}</span>
		<% } else { %>
			<span class="label label-danger">{translateToken value="No"}</span>
		<% } %>
	</td>
	<td class="text-center">
		<a target="_blank" class="view-content-action" href="<%= model['file'].url %>">View/Download</a>
	</td>
</script>

<script type="text/template" id="tab_unit_exercises-nofound-template">
	<tr>
		<td colspan="4"  class="alert alert-info">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's any exercises registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_unit_exercises-item-template">
	<td class="text-center"><%= model.model_index+1 %></th>
	<td class="text-center"><%= _.size(model.exercise) %></td>
	<td class="text-center">
		<% if (_.isObject(model.progress) && model.progress.factor >= 1) { %>
			<span class="label label-success">{translateToken value="Done"}</span>
		<% } else { %>
			<span class="label label-danger">{translateToken value="Pending"}</span>
		<% } %>
	</td>
	<td class="text-center">

		<a href="javascript:void(0);" class="btn btn-xs btn-primary open-exercise-action">
			<% if (_.isObject(model.progress) && model.progress.factor >= 1) { %>
				{translateToken value="Do it again!"}
			<% } else { %>
				{translateToken value="Do now!"}
			<% } %>
		</a>
	</td>
</script>

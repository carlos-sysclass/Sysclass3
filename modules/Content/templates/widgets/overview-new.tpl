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
                <a href="#tab_program_description" aria-controls="tab_program_description" role="tab" data-toggle="tab">
                  <i class="fa fa-graduation-cap"></i>
                  <span class="progress-indicator program-indicator">
                    <span class="counter"></span>
                    <span class="singular">{translateToken value="Program"}</span>
                    <span class="plural">{translateToken value="Programs"}</span>
                  </span>
                  
                </a>
              </li>
              <li role="presentation">
                <a href="#tab_program_courses" aria-controls="tab_program_courses" role="tab" data-toggle="tab">
                  <i class="fa fa-sitemap"></i>
                  <span class="progress-indicator course-indicator">
                    <span class="counter"></span>
                    <span class="singular">{translateToken value="Course"}</span>
                    <span class="plural">{translateToken value="Courses"}</span>
                  </span>
                </a>
              </li>
              <li role="presentation">
              	<a href="#tab_course_units" aria-controls="tab_course_units" role="tab" data-toggle="tab">
                  <i class="fa fa-book"></i>
                  <span class="progress-indicator unit-indicator">
                    <span class="counter"></span>
                    <span class="singular">{translateToken value="Unit"}</span>
                    <span class="plural">{translateToken value="Units"}</span>
                  </span>
                </a>
              </li>
              <!--
              <li role="presentation">
              	<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-sitemap"></i>{translateToken value="Timeline"}</a>
              </li>
              -->

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
            <div role="tabpanel" class="tab-pane" id="tab_course_units">
              <div class="navbar navbar-default navbar-course" role="navigation">
                <ul class="nav navbar-nav">
                  <!--
                  <a href="#" class="navbar-brand disabled">
                    <strong>{translateToken value="You're in:"} </strong>
                  </a>
                  -->

                  <li>
                    <a href="javascript:void(0);">
                      <span class="program-title">{translateToken value="Program"}</span>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);" class="no-padding-sides">
                      <span class="">&raquo;</span>
                    </a>
                  </li>
                  <li href="javascript: void(0)" class="dropdown">
                    <a data-close-others="true" data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);">
                      <span class="course-title">{translateToken value="Course"}</span>
                      <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu course-dropdown">
                    </ul>
                  </li>
        
                  <!--
                  <a href="javascript: void(0);" class="navbar-brand viewed-status hidden">
                    <span class="label label-success">
                      <i class="ti-check"></i> <span class="hidden-xs">{translateToken value="Completed"}</span>
                    </span>
                  </a>
                  -->
                </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li>
                      <a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous"}" data-placement="top">
                        <i class="fa fa-arrow-left"></i>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="nav-info no-padding-sides disabled">
                        <span class="entity-current"></span> / <span class="entity-count"></span>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next"}" data-placement="top">
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </li>
                  </ul>
              </div>

              <!--         
              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="pendente-tag">Not Avaliable<span class="pendente"></span></span>
                <span class="concluido-tag">Viewed / Done / OK<span class="concluido"></span></span>
                <span class="avalialbe-tag">Avaliable<span class="avalialbe"></span></span>
                <span class="andamento-tag">In Progress<span class="andamento"></span></span>
                <span class="fechado-tag">Disable<span class="fechado"></span></span>
              </div> 
              -->
              <div class="content-container full-video">
                <div class="unit-video-container hidden pop-out" id="unit-video-container">
                  <div class="popupcontent">
                    <div class="popupcontent-header navbar navbar-default">
                      <ul class="nav navbar-nav">
                        <li>
                          <a href="javascript:void(0);">
                            <span class="course-title">{translateToken value="Unit"}</span>
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);" class="no-padding-sides">
                            <span class="">&raquo;</span>
                          </a>
                        </li>
                        <li href="javascript: void(0)" class="dropdown">
                          <a data-close-others="true" data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);">
                            <span class="unit-title">{translateToken value="Unit"}</span>
                            <i class="fa fa-caret-down"></i>
                          </a>
                          <ul class="dropdown-menu unit-dropdown">
                          </ul>
                        </li>
                      </ul>

                      <div class="popup-header-buttons">
                        <a href="javascript: void(0);" class="btn btn-link minimize-action">
                          <i class="fa fa-compress"></i>
                        </a>
                        <a href="javascript: void(0);" class="btn btn-link fullscreen-action">
                          <i class="fa fa-desktop"></i>
                        </a>
                        <a href="javascript: void(0);" class="btn btn-link close-action">
                          <i class="fa fa-times"></i>
                        </a>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="popupcontent-body">
                    </div>
                  </div>
                </div>
                <div class="unit-material-container hidden pop-out" id="unit-material-container">
                  <div class="popupcontent">
                    <div class="popupcontent-header navbar navbar-default">
                      <ul class="nav navbar-nav">
                        <li>
                          <a href="javascript:void(0);">
                            <span class="course-title">{translateToken value="Unit"}</span>
                          </a>
                        </li>
                        <li>
                          <a href="javascript:void(0);" class="no-padding-sides">
                            <span class="">&raquo;</span>
                          </a>
                        </li>
                        <li href="javascript: void(0)" class="dropdown">
                          <a data-close-others="true" data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);">
                            <span class="unit-title">{translateToken value="Unit"}</span>
                            <i class="fa fa-caret-down"></i>
                          </a>
                          <ul class="dropdown-menu unit-ex-dropdown">
                          </ul>
                        </li>
                      </ul>

                      <div class="popup-header-buttons">
                    
                        <a href="javascript: void(0);" class="btn btn-link minimize-action">
                          <i class="fa fa-caret-up"></i>
                        </a>
                        <a href="javascript: void(0);" class="btn btn-link close-action">
                          <i class="fa fa-times"></i>
                        </a>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <!--
                      <a href="javascript:void(0);" class="close-content-sidebar btn btn-link btn-xs" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </a>
                    -->
                    <div class="popupcontent-body">
                      <table class="table table-striped unit-material-table"> 
                        <thead> 
                          <tr>
                            <th class="text-center">{translateToken value="Type"}</th>
                            <th class="text-center">{translateToken value="Name"}</th>
                            <th class="text-center">{translateToken value="Viewed"}</th>
                          </tr>
                        </thead> 
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="40%">{translateToken value="Unit"}</th>
                    <!-- <th width="20%">{translateToken value="Instructor"}</th> -->
                    <th width="15%" class="text-center">{translateToken value="Video"}</th>
                    <th width="15%" class="text-center">{translateToken value="Materials"}</th>
                    <th width="15%" class="text-center">{translateToken value="Tests"}</th>
                    <!--
                    <th><i class="fa fa-"></i>{translateToken value="Exercise"}</th>
                    -->
                    <!--
                    <th><i class="fa fa-"></i>{translateToken value="Exam"}</th>
                    -->
                    <th width="15%" class="text-center">{translateToken value="Status"}</th>
                    <!-- <th></th> -->
                  </tr>
                </thead>
              </table>
              <div class="table-content-scrollable">
                <table class="table table-striped unit-table">
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab_program_courses">
              <div class="navbar navbar-default navbar-program" role="navigation">
                <ul class="nav navbar-nav">
                  <!--
                  <a href="#" class="navbar-brand disabled">
                    <strong>{translateToken value="You're in:"} </strong>
                  </a>
                  -->

                  <li class="dropdown">
                    <a data-close-others="true" data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);">
                      <span class="program-title">{translateToken value="Program"}</span>
                      <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu program-dropdown">
                    </ul>
                  </li>
                  <!--
                  <a href="javascript: void(0);" class="navbar-brand viewed-status hidden">
                    <span class="label label-success">
                      <i class="ti-check"></i> <span class="hidden-xs">{translateToken value="Completed"}</span>
                    </span>
                  </a>
                  -->
                </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li>
                      <a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous"}" data-placement="top">
                        <i class="fa fa-arrow-left"></i>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="nav-info no-padding-sides disabled">
                        <span class="entity-current"></span> / <span class="entity-count"></span>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next"}" data-placement="top">
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </li>
                  </ul>
              </div>
              <!--
               <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="pendente-tag">Not Avaliable<span class="pendente"></span></span>
                <span class="concluido-tag">Viewed / Done / OK<span class="concluido"></span></span>
                <span class="avalialbe-tag">Avaliable<span class="avalialbe"></span></span>
                <span class="andamento-tag">In Progress<span class="andamento"></span></span>
              </div>
              -->              
              <!--
              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form action="">
                  <input type="text" placeholder="Escreva aqui a sua pesquisa">
                </form>
              </div>
              -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="40%">{translateToken value="Course"}</th>
                    <!-- <th width="20%">{translateToken value="Coordinator"}</th> -->
                    <th width="15%" class="text-center">{translateToken value="Units"}</th>
                    <th width="30%">{translateToken value="Assignments"}</th>
                    <th width="15%" class="text-center">{translateToken value="Status"}</th>
                    <!-- <th>{translateToken value="Cumulative Grade"}</th> -->
                  </tr>
                </thead>
              </table>
              <div class="table-content-scrollable">
                <table class="table table-striped course-table">
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <!--
            <div role="tabpanel" class="tab-pane" id="profile">
              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="pendente-tag">Not Avaliable<span class="pendente"></span></span>
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
            -->            
            <div role="tabpanel" class="tab-pane active" id="tab_program_description">
              <div class="navbar navbar-default navbar-program" role="navigation">
                <ul class="nav navbar-nav">
                  <!--
                  <a href="#" class="navbar-brand disabled">
                    <strong>{translateToken value="You're in:"} </strong>
                  </a>
                  -->

                  <li class="dropdown">
                    <a data-close-others="true" data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);">
                      <span class="program-title">{translateToken value="Program"}</span>
                      <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu program-dropdown">
                    </ul>
                  </li>
                  <!--
                  <a href="javascript: void(0);" class="navbar-brand viewed-status hidden">
                    <span class="label label-success">
                      <i class="ti-check"></i> <span class="hidden-xs">{translateToken value="Completed"}</span>
                    </span>
                  </a>
                  -->
                </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li>
                      <a href="#" class="nav-prev-action tooltips" data-original-title="{translateToken value="Previous"}" data-placement="top">
                        <i class="fa fa-arrow-left"></i>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="nav-info no-padding-sides disabled">
                        <span class="entity-current"></span> / <span class="entity-count"></span>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="nav-next-action tooltips" data-original-title="{translateToken value="Next"}" data-placement="top">
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </li>
                  </ul>
              </div>
              <div class="program-description-content">
                
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<script type="text/template" id="dropdown_child-item-template">
  <a href="javascript:void(0);" class="select-item">
    <% if (!_.isUndefined(model.progress)) { %>
      <% if (model.progress.factor == 1) { %>
        <span class="concluido">
          <i class="fa fa-check-circle" aria-hidden="true"></i>
        </span>
      <% } else if (model.progress.factor > 0) { %>
        <span class="andamento">
          <i class="fa fa-clock-o" aria-hidden="true"></i>
        </span>
      <% } else { %>
        <span class="avalialbe">
          <i class="fa fa-circle" aria-hidden="true"></i>
        </span>
      <% } %>
    <% } %>
    <%= model.name %>
  </a>
</script>

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
		<td colspan="6"  class="">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's any courses registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_program_courses-item-template">
    <!-- Course -->
    <td width="40%">
      <a href="javascript:void(0)" class="course-change-action"><%= model.name %></a>
      <div class="btn-group">
        <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-link dropdown-toggle" href="javascript:void(0);">
          <i class="fa fa-info-circle"></i>
        </a>
        <ul class="dropdown-menu">
          <li>
              <a class="course-info-action" href="javascript:void(0);">
              {translateToken value="Course Info"}
            </a>
          </li>
          <% if (!_.isNull(model.professor_id)) { %>
          <li role="separator" class="divider"></li>
          <li>
            <a href="javascript:void(0);">
              {translateToken value="Coordinator"}: <strong><%= model.professor.name %> <%= model.professor.surname %></strong>
            </a>
          </li>
          <li class="subchild">
            <a href="javascript:void(0);" class="dialogs-messages-send-action" data-user-id="<%= model.professor_id %>" data-mode="user">
              <i class="fa fa-paper-plane"></i> {translateToken value="Send Message"}
            </a>
          </li>
          <% } %>
        </ul>
      </div>
      <!--
      <a href="javascript:void(0)" class="course-change-action"></a>
        <a href="javascript:void(0);"  class="btn btn-info btn-xs course-info-action">
          <i class="fa fa-info" aria-hidden="true"></i>
          Info
        </a>
      -->
    </td>
    <!-- Instrutor -->
    <!--
    <td width="20%">
      <% if (!_.isNull(model.professor_id)) { %>
        <%= model.professor.name %> <%= model.professor.surname %>
        <span class="at-difinf">
          <a href="javascript:void(0);"  class="btn btn-info btn-xs">
            <i class="fa fa-info" aria-hidden="true"></i>
            {translateToken value="Info"}
          </a>
          <a href="javascript:void(0);"  class="btn btn-info btn-xs">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
            {translateToken value="Contact"}
          </a>
        </span>

      <% } %>
    </td>
    -->
    <!-- Units -->
    <td width="15%" class="text-center">
      <%= model.units_completed %> {translateToken value="of"} <%= _.size(model.units) %>
    </td>
    <!--
    1/5 Avaliação do Ciclo de Vida
      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
      <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
    -->
    <!-- Next Unit -->
    <td width="30%">
      <%
        var completed = true;
        for (var index in model.units) {
          var unit = model.units[index];
          if (_.has(unit, 'progress') && parseFloat(unit.progress.factor) >= 1) {
            continue;
          } else {
            completed = false;
          %>
            <%= unit.name %>
          <%
            break;
          }
        }
      %>
      <% if (completed) { %>
        <span class="concluido">
          <i class="fa fa-check-square-o" aria-hidden="true"></i>
          {translateToken value="Completed"}
        </span>
      <% } %>
    </td>

    <!-- Status -->
    <td  width="15%" class="text-center">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="concluido tooltips" data-original-title="{translateToken value="Completed"}" data-placement="top">
        <i class="fa fa-check-circle" aria-hidden="true"></i>
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="andamento tooltips" data-original-title="{translateToken value="In Progress"}" data-placement="top">
        <i class="fa fa-clock-o" aria-hidden="true"></i>
      </span>
    <% } else { %>
      <span class="avalialbe tooltips" data-original-title="{translateToken value="Avaliable"}" data-placement="top">
        <i class="fa fa-circle" aria-hidden="true"></i>
      </span>
    <% } %>
    </td>
    <!-- Cumulative Grade -->
    <!--
    <td class="nota-tal">N/A</td>
    -->
</script>
<script type="text/template" id="tab_courses_child-nofound-template">
	<tr>
		<td colspan="6"  class="">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="Ops! There's no data registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_courses_units-item-template">
  <!-- Unidade -->
  <td width="40%">
      <%= model.name %>
      <%
        var hasDropdown = !_.isNull(model.instructor_id) && _.size(model.professor) > 0;
        /* hasDropdown = hasDropdown && <another-condition> */
      %>
      <% if (hasDropdown) { %>
        <div class="btn-group">
          <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-link dropdown-toggle" href="javascript:void(0);">
            <i class="fa fa-info-circle"></i>
          </a>

          <ul class="dropdown-menu">
            <% if (!_.isNull(model.instructor_id) && _.size(model.professor) > 0) { %>
            <li>
              <a href="javascript:void(0);">
                {translateToken value="Instructor"}: <strong><%= model.professor.name %> <%= model.professor.surname %></strong>
              </a>
            </li>
            <li class="subchild">
              <a href="javascript:void(0);" class="dialogs-messages-send-action" data-user-id="<%=  model.instructor_id %>" data-mode="user">
                <i class="fa fa-paper-plane"></i> {translateToken value="Send Message"}
              </a>
            </li>
            <% } %>
          </ul>
        </div>
      <% } %>

  </td>
  <!--
  <td width="20%">
    <% if (!_.isNull(model.instructor_id) && _.size(model.professor) > 0) { %>
      <%= model.professor.name %> <%= model.professor.surname %>
    <% } %>
  </td>
  -->
  <!-- Video -->
  <td width="15%" class="text-center">
    <% if (!model.video) { %> 
    <% } else { %>
      <% if (model.video.progress.factor >= 1) { %>
        <a href="javascript: void(0);" class="btn btn-sm btn-done watch-video-action">
            <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            {translateToken value="Watch Again"}
        </a>
      <% } else if (model.video.progress.factor > 0) { %>
        <a href="javascript: void(0);" class="btn btn-sm btn-continue watch-video-action">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            {translateToken value="Continue"}
        </a>
      <% } else { %>
        <a href="javascript: void(0);" class="btn btn-sm btn-avaliable watch-video-action">
          <i class="fa fa-play-circle-o" aria-hidden="true"></i>
          {translateToken value="Watch"}
        </a>
      <% } %>
    <% } %>
  </td>
  <!-- Material -->
  <td width="15%" class="text-center">
    <% if (_.size(model.materials) > 0) { %>
    <div class="dropdown">
      <% if (model.materialProgress >= 1) { %>
        <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-done dropdown-toggle" href="javascript:void(0);">
            <i class="fa fa-folder-open-o" aria-hidden="true"></i>
            {translateToken value="Viewed"}
            <i class="fa fa-caret-down"></i>
        </a>
      <% } else if (model.materialProgress > 0) { %>
        <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-continue dropdown-toggle" href="javascript:void(0);">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            {translateToken value="In Progress"}
            <i class="fa fa-caret-down"></i>
        </a>
      <% } else { %>
        <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-avaliable dropdown-toggle" href="javascript:void(0);">
            <i class="fa fa-folder-o" aria-hidden="true"></i>
            {translateToken value="View"}
            <i class="fa fa-caret-down"></i>
        </a>
      <% } %>
      <ul class="dropdown-menu unit-material-dropdown">
      </ul>
    </div>
    <% } %>
    <!--
    <% if (_.size(model.materials) == 0) { %> 
    <% } else { %>
      <% _.each(model.materials, function (item, index) { %>
        <% if (item.progress.factor >= 1) { %>
          <a href="javascript: void(0);" class="list-materials-action">
            <span class="concluido">
              <i class="fa fa-folder-open-o" aria-hidden="true"></i>
              {translateToken value="Viewed"}
            </span>
          </a>
        <% } else { %>
          <a href="javascript: void(0);" class="list-materials-action">
            <span class="avalialbe">
              <i class="fa fa-folder-o" aria-hidden="true"></i>
              {translateToken value="View"}
            </span>
          </a>
        <% } %>
      <% }); %>
    <% } %>
    -->
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
  
  <td width="15%" class="text-center">
  </td>
  
  <!-- Exame -->
  <!-- <td>
    <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
    <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
    <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
  </td>
   --><!-- Status -->
  <td width="15%"  class="text-center">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Completed"}" data-placement="top">
        <i class="fa fa-check-circle concluido" aria-hidden="true"></i>
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="In Progress"}" data-placement="top">
        <i class="fa fa-clock-o andamento" aria-hidden="true"></i>
      </span>
    <% } else { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Avaliable"}" data-placement="top">
        <i class="fa fa-circle avalialbe" aria-hidden="true"></i>
      </span>
    <% } %>
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
<script type="text/template" id="dropdown_child-unit-material_item-template">
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
  <a target="_blank" class="select-item" href="<%= model['file'].url %>" class="select-item">
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
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="concluido">
        <i class="fa fa-folder-open-o" aria-hidden="true"></i>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="andamento">
        <i class="fa fa-folder-o" aria-hidden="true"></i>
    <% } else { %>
      <span class="avalialbe">
        <i class="fa fa-folder-o" aria-hidden="true"></i>
    <% } %>
      <%= model['file'].name %>
    </span>
  </a>
</script>


<script type="text/template" id="tab_courses_tests-item-template">
  <!-- Unidade -->
  <td width="40%">
    <%= model.name %>
  </td>
  <!-- Instructor -->
  <!-- <td width="15%" class="text-center"></td> -->
  <!-- Video -->
  <td width="15%" class="text-center"></td>
  <!-- Material -->
  <td width="15%" class="text-center">
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
  <td width="15%" class="text-center">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="concluido">
        <i class="fa fa-check-circle" aria-hidden="true"></i>
        {translateToken value="Completed"}
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <a href="javascript: void(0);" class="btn btn-sm btn-continue view-test-action">
          <i class="fa fa-clock-o" aria-hidden="true"></i>
          {translateToken value="In Progress"}
      </a>
    <% } else { %>
      <a href="javascript: void(0);" class="btn btn-sm btn-avaliable view-test-action">
          <i class="fa fa-check" aria-hidden="true"></i>
          {translateToken value="Avaliable"}
      </a>
    <% } %>
  </td>
  <!-- Exame -->
  <!-- <td>
    <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
    <span class="concluido"><i class="fa fa-check" aria-hidden="true"></i></span>
    <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
  </td>
   --><!-- Status -->
  <td width="15%" class="text-center">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Completed"}" data-placement="top">
        <i class="fa fa-check-circle concluido" aria-hidden="true"></i>
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="In Progress"}" data-placement="top">
        <i class="fa fa-clock-o andamento" aria-hidden="true"></i>
      </span>
    <% } else { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Avaliable"}" data-placement="top">
        <i class="fa fa-circle avalialbe" aria-hidden="true"></i>
      </span>
    <% } %>
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




<!-- USED HERE AND IN CONTENT DIALOG -->
<script type="text/template" id="tab_unit_video-nofound-template">
  <div class="alert alert-info">
    <span class="text-info"><i class="icon-warning-sign"></i></span>
    {translateToken value="Ops! There's any content for this lesson"}
  </div>
</script>
<script type="text/template" id="tab_unit_video-item-template">
    <video id="unit-video-<%= model.id %>" class="video-js vjs-default-skin vjs-big-play-centered vjs-auto-height"
      width="auto"  height="auto"
      <% if (!_.has(model, 'poster')) { %>
        poster="{Plico_GetResource file='images/default-poster.jpg'}"
      <% } else { %>
        poster="<%= model.poster.file.url %>"
      <% } %>
      >
      <% if (_.has(model, 'file')) { %>
        <source src="<%= model.file.url %>" type='<%= model.file.type %>' />
      <% } else if (_.has(model, 'content')) { %>
        <source src="<%= model.content %>" />
      <% } %>

      <% _.each(model.childs, function(item, index){ %>
        <track kind="subtitles" src="<%= item.file.url %>" srclang="<%= item.language_code %>" label="<%= item.language_code %>"></track>
      <% }); %>
    </video>
</script>




<script type="text/template" id="tab_unit_materials-nofound-template">
  <tr>
    <td colspan="5">
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
  <td>
    <a target="_blank" class="view-content-action" href="<%= model['file'].url %>">
      <%= model['file'].name %>
    </a>
  </td>
  <td class="text-center">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="concluido">
        <i class="fa fa-check" aria-hidden="true"></i>
        {translateToken value="Viewed"}
      </span>
    <% } else { %>
      <span class="avalialbe">
        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
        {translateToken value="Avaliable"}
      </span>
    <% } %>
  </td>
</script>

<!-- 
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
 -->

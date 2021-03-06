{assign var="widget_data" value=$T_DATA.data}
<script>
_before_init_functions.push(function() {
    $SC.addResource("content_widget_data", {$widget_data|@json_encode nofilter});
});
</script>

<style>
/****** Style Star Rating Widget *****/

.rating { 
  text-align: center;
  white-space: nowrap;
}

.rating > input { display: none; } 
.rating > label:before { 
  margin: 5px;
  font-size: 3.25em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\f005";
}

.rating-stars-loader {
  margin-top: 15px;
}
.rating-stars-loader .fa.fa-spin { 
  font-size: 2.25em !important;
}


.rating > .half:before { 
  content: "\f089";
  position: absolute;
}

.rating > label { 
  color: #ddd; 
}

/***** CSS Magic to Highlight Stars on Hover *****/

.rating > input:checked ~ label, /* show gold star when clicked */
.rating:not(:checked) > label:hover, /* hover current star */
.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

.rating > input:checked + label:hover, /* hover current star when changing rating */
.rating > input:checked ~ label:hover,
.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 

</style>

<div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="col-md-12 no-padding inter-navsuper">
            <ul class="nav nav-tabs col-md-8 no-padding widget-tabs-container" role="tablist">
              <li role="presentation" class="active">
                <a href="#tab_program_description" aria-controls="tab_program_description" role="tab" data-toggle="tab" data-setting-update="program">
                  <i class="fa fa-graduation-cap"></i>
                  <span class="progress-indicator program-indicator">
                    <span class="counter"></span>
                    <span class="singular hidden-xs">{translateToken value="Program"}</span>
                    <span class="plural hidden-xs">{translateToken value="Programs"}</span>
                  </span>
                </a>
              </li>
              <li role="presentation">
                <a href="#tab_program_courses" aria-controls="tab_program_courses" role="tab" data-toggle="tab" data-setting-update="course">
                  <i class="fa fa-sitemap"></i>
                  <span class="progress-indicator course-indicator">
                    <span class="counter"></span>
                    <span class="singular hidden-xs">{translateToken value="Course"}</span>
                    <span class="plural hidden-xs">{translateToken value="Courses"}</span>
                  </span>
                </a>
              </li>
              <li role="presentation">
              	<a href="#tab_course_units" aria-controls="tab_course_units" role="tab" data-toggle="tab" data-setting-update="unit">
                  <i class="fa fa-book"></i>
                  <span class="progress-indicator unit-indicator">
                    <span class="counter"></span>
                    <span class="singular hidden-xs">{translateToken value="Unit"}</span>
                    <span class="plural hidden-xs">{translateToken value="Units"}</span>
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
              <li class="content-widget-search-action-container">
                <a href="javascript: void(0);" class="content-widget-search-action tooltips" data-original-title="{translateToken value='Search'}">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </a>
                <div class="search-container">
                  <input type="text" class="form-control" name="_search" placeholder={translateToken value='Search'} />
                </div>
              </li>
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

                  <li class="hidden-xs">
                    <a href="javascript:void(0);">
                      <span class="program-title">{translateToken value="Program"}</span>
                    </a>
                  </li>
                  <li class="hidden-xs">
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
                  <ul class="nav navbar-nav navbar-right hidden-xs">
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
                      <ul class="nav navbar-nav hidden-xs">
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
                        <div class="btn-group inline-block change-view-type-dropdown" style="">
                          <a href="javascript: void(0);" data-toggle="dropdown" class="btn btn-link hidden-xs dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-video-camera"></i>
                            <span class="view-type"></span>
                            <i class="fa fa-caret-down"></i>
                          </a>
                          <ul class="pull-right dropdown-menu">
                            <li>
                              <a href="javascript: void(0);" class="btn btn-link hidden-xs change-view-type" data-view-type="pip">
                                PIP
                              </a>
                            </li>
                            <li>
                              <a href="javascript: void(0);" class="btn btn-link hidden-xs change-view-type" data-view-type="sbs">
                                SBS
                              </a>
                            </li>
                            <li class="separator"></li>
                            <!--
                            <li class="dynamic-view-item">
                              <a href="javascript: void(0);" class="btn btn-link hidden-xs change-view-type" data-view-type="only" data-view-index="0">
                                Video 1
                              </a>
                            </li>
                            <li class="dynamic-view">
                              <a href="javascript: void(0);" class="btn btn-link hidden-xs change-view-type" data-view-type="only" data-view-index="1">
                                Video 2
                              </a>
                            </li>
                            -->
                          </ul>
                        </div>

                        <a href="javascript: void(0);" class="btn btn-link minimize-action hidden-xs">
                          <i class="fa fa-compress"></i>
                        </a>
                        <a href="javascript: void(0);" class="btn btn-link fullscreen-action">
                          <i class="fa fa-arrows-alt"></i>
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

              <div class="backgrid-table">
                  <table class="table table-striped table-bordered table-hover table-full-width data-table unit-table" id="unit-table">
                      <thead>
                        <tr>
                          <th class="text-center">{translateToken value="Status"}</th>
                          <th class="text-center">{translateToken value="Units"}</th>
                          <!-- <th width="20%">{translateToken value="Instructor"}</th> -->
                          <th class="text-center">{translateToken value="Rating"}</th>
                          <th class="text-center">{translateToken value="Videos"}</th>
                          <th class="text-center">{translateToken value="Materials"}</th>
                          <th class="text-center">{translateToken value="Assignments"}</th>
                          <!--
                          <th><i class="fa fa-"></i>{translateToken value="Exercise"}</th>
                          -->
                          <!--
                          <th><i class="fa fa-"></i>{translateToken value="Exam"}</th>
                          -->
                          <!-- <th></th> -->
                        </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
              </div>

              <!--
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="40%">{translateToken value="Unit"}</th>
                    <th width="15%" class="text-center">{translateToken value="Video"}</th>
                    <th width="15%" class="text-center">{translateToken value="Materials"}</th>
                    <th width="15%" class="text-center">{translateToken value="Assignments"}</th>
                    <th width="15%" class="text-center">{translateToken value="Status"}</th>
                  </tr>
                </thead>
              </table>
              <div class="table-content-scrollable">
                <table class="table table-striped unit-table">
                  <tbody>
                  </tbody>
                </table>
              </div>
              -->
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
                  <ul class="nav navbar-nav navbar-right hidden-xs">
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
                <span class="pendente-tag">Not Available<span class="pendente"></span></span>
                <span class="concluido-tag">Viewed / Done / OK<span class="concluido"></span></span>
                <span class="avalialbe-tag">Available<span class="avalialbe"></span></span>
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

              <div class="backgrid-table">
                  <table class="table table-striped table-bordered table-hover table-full-width data-table course-table" id="course-table">
                      <thead>
                        <tr>
                          <th class="text-center">{translateToken value="Status"}</th>
                          <th class="text-center">{translateToken value="Courses"}</th>
                          <th class="text-center">{translateToken value="Units"}</th>
                          <th class="text-center">{translateToken value="Completion"}</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
              </div>
              <!--
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="40%">{translateToken value="Course"}</th>
                    <th width="15%" class="text-center">{translateToken value="Units"}</th>
                    <th width="30%">{translateToken value="Assignments"}</th>
                    <th width="15%" class="text-center">{translateToken value="Status"}</th>
                  </tr>
                </thead>
              </table>
              <div class="table-content-scrollable">
                <table class="table table-striped course-table">
                  <tbody>
                  </tbody>
                </table>
              </div>
              -->
            </div>
            <!--
            <div role="tabpanel" class="tab-pane" id="profile">
              <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="pendente-tag">Not Available<span class="pendente"></span></span>
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
                      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
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
                      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
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
                      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
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
                      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
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
                      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
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
                      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
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
                  <ul class="nav navbar-nav navbar-right hidden-xs">
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
              <div class="program-description-content-scroller">
              <div class="program-description-content">
                
              </div>
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
          <i class="fa fa-check-square-o" aria-hidden="true"></i>
        </span>
      <% } else if (model.progress.factor > 0) { %>
        <span class="andamento">
          <i class="fa fa-clock-o" aria-hidden="true"></i>
        </span>
      <% } else { %>
        <span class="avalialbe">
          <i class="fa fa-square-o" aria-hidden="true"></i>
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
				{translateToken value="There's no data registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_program_description-template">
	<%= model.description %>
	<% if (!_.isEmpty(model.objectives)) { %>
		<hr />
		<h5>{translateToken value="Objectives"}</h5>
		
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
				{translateToken value="There are no courses registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_program_courses-item-template">

    <!-- Status -->
    <td class="text-center">
    <% if ((completed && _.size(model.units) > 0) || (_.has(model, 'progress') && model.progress.factor == 1)) { %>
      <span class="concluido tooltips" data-original-title="{translateToken value="Completed"}" data-placement="top" data-container="body">
        <i class="fa fa-check-square-o" aria-hidden="true"></i>
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="andamento tooltips" data-original-title="{translateToken value="In Progress"}" data-placement="top" data-container="body">
        <i class="fa fa-clock-o" aria-hidden="true"></i>
      </span>
    <% } else if (_.size(model.units) == 0) { %>
      <span class="pendente tooltips" data-original-title="{translateToken value="Not available"}" data-placement="top" data-container="body">
        <i class="fa fa-times-circle" aria-hidden="true"></i>
      </span>
    <% } else { %>

      <span class="avalialbe tooltips" data-original-title="{translateToken value="Available"}" data-placement="top" data-container="body">
        <i class="fa fa-square-o" aria-hidden="true"></i>
      </span>
    <% } %>
    </td>
    <!-- Course -->
    <td>
      <a href="javascript:void(0)" class="course-change-action"><%= model.name %></a>
      <div class="btn-group">
        <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-link dropdown-toggle" href="javascript:void(0);">
          <i class="fa fa-info-circle"></i>
        </a>
        <ul class="dropdown-menu">
          <li>
              <a class="course-info-action" href="javascript:void(0);">
              {translateToken value="Course info"}
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
    <td class="text-center">
      <%= model.units_completed %> {translateToken value="of"} <%= _.size(model.units) %>
    </td>
    <!--
    1/5 Avaliação do Ciclo de Vida
      <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
      <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
      <span class="andamento"><i class="fa fa-info" aria-hidden="true"></i></span>
      <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
      <span class="fechado"><i class="fa fa-ban" aria-hidden="true"></i></span>
    -->
    <!-- Next Unit -->
    <td class="text-center"> 
      <span class="avalialbe">
        {translateToken value="Date not set"}
      </span>
      <!--
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
        <% if (_.size(model.units) == 0) { %>
          <span class="andamento">
            <i class="fa fa-warning" aria-hidden="true"></i>
            {translateToken value="No units available"}
          </span>
        <% } else { %>
          <span class="concluido">
            <i class="glyphicon glyphicon-edit-circle" aria-hidden="true"></i>
            {translateToken value="Completed"}
          </span>
        <% } %>
      <% } %>
      -->
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
				{translateToken value="There's no data registered for this course"}
			</span>
		</td>
	</tr>
</script>
<script type="text/template" id="tab_courses_units-item-template">
  <!-- Status -->
  <td class="text-center" width="10%">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Completed"}" data-placement="top" data-container="body">
        <i class="fa fa-check-square-o concluido" aria-hidden="true"></i>
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="In Progress"}" data-placement="top" data-container="body">
        <i class="fa fa-clock-o andamento" aria-hidden="true"></i>
      </span>
    <% } else { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Available"}" data-placement="top" data-container="body">
        <i class="fa fa-square-o avalialbe" aria-hidden="true"></i>
      </span>
    <% } %>
  </td>

  <!-- Unidade -->
  <td width="40%">
      <%= model.name %>
      <%
        var hasDropdown = !_.isNull(model.professor_id) && _.size(model.professor) > 0;
        /* hasDropdown = hasDropdown && <another-condition> */
      %>
      <% if (hasDropdown) { %>
        <div class="btn-group">
          <a data-close-others="true" data-toggle="dropdown" class="btn btn-sm btn-link dropdown-toggle" href="javascript:void(0);">
            <i class="fa fa-info-circle"></i>
          </a>

          <ul class="dropdown-menu">
            <% if (!_.isNull(model.professor_id) && _.size(model.professor) > 0) { %>
            <li>
              <a href="javascript:void(0);">
                {translateToken value="Instructor"}: <strong><%= model.professor.name %> <%= model.professor.surname %></strong>
              </a>
            </li>
            <li class="subchild">
              <a href="javascript:void(0);" class="dialogs-messages-send-action" data-user-id="<%=  model.professor_id %>" data-mode="user">
                <i class="fa fa-paper-plane"></i> {translateToken value="Send Message"}
              </a>
            </li>
            <% } %>
          </ul>
        </div>
      <% } %>

  </td>
  <!-- Rating -->
  <td class="text-center" width="10%">
    <% if (_.has(model, 'rating')) { %>
      <%
      count = 0; 
      for (var i=1; i <= model.rating; i++) {
        print ('<i class="fa fa-star rating-star"></i>');
        count++;
      } 
      if (count < model.rating) {
        var diff = model.rating - count;
        if (diff > 0) {
          print ('<i class="fa fa-star-half-o rating-star"></i>');
          count++;
        }
      }
      if (count < 5) {
        diff = 5 - count;
        for (var i=1; i <= diff; i++) {
          print ('<i class="fa fa-star-o rating-star"></i>');
          count++;
        } 
      }
      %>
    <% } else { %>
    <% } %>
  </td>
  <!--
  <td width="20%">
    <% if (!_.isNull(model.professor_id) && _.size(model.professor) > 0) { %>
      <%= model.professor.name %> <%= model.professor.surname %>
    <% } %>
  </td>
  -->
  <!-- Video -->
  <td class="text-center" width="10%">
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
  <td class="text-center" width="10%">
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
            <i class="glyphicon glyphicon-edit-circle" aria-hidden="true"></i>
            Viewed
          </span>
        <% } else if (item.progress.factor > 0) { %>
        <% } else { %>
          <span class="avalialbe">
            <i class="fa fa-coffee" aria-hidden="true"></i>
            Available
          </span>
        <% } %>
      <% }); %>
    <% } %>
  </td>
  -->
  <!-- Teste -->
  
  <td class="text-center" width="10%">
  </td>
  
  <!-- Exame -->
  <!-- <td>
    <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
    <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
    <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
  </td>
   -->
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
  <a 
    <% if (file_type == "pdf") { %>
      class="select-item open-pdf-viewer" 
      href="<%= model['file'].url %>" 
    <% } else { %>
      target="_blank" 
      class="select-item" 
      href="<%= model['file'].url %>" 
    <% } %>
  >
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
  <!-- Status -->
  <td class="text-center">
    <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Completed"}" data-placement="top" data-container="body">
        <i class="fa fa-check-square-o concluido" aria-hidden="true"></i>
      </span>
    <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="In Progress"}" data-placement="top" data-container="body">
        <i class="fa fa-clock-o andamento" aria-hidden="true"></i>
      </span>
    <% } else { %>
      <span class="btn btn-sm btn-link tooltips" data-original-title="{translateToken value="Available"}" data-placement="top" data-container="body">
        <i class="fa fa-square-o avalialbe" aria-hidden="true"></i>
      </span>
    <% } %>
  </td>

  <!-- Unidade -->
  <td >
    <%= model.name %>
  </td>
  <td class="text-center" width="10%">
    <% if (_.has(model, 'rating')) { %>
      <%
      count = 0; 
      for (var i=1; i <= model.rating; i++) {
        print ('<i class="fa fa-star rating-star"></i>');
        count++;
      } 
      if (count < model.rating) {
        var diff = model.rating - count;
        if (diff > 0) {
          print ('<i class="fa fa-star-half-o rating-star"></i>');
          count++;
        }
      }
      if (count < 5) {
        diff = 5 - count;
        for (var i=1; i <= diff; i++) {
          print ('<i class="fa fa-star-o rating-star"></i>');
          count++;
        } 
      }
      %>
    <% } else { %>
    <% } %>
  </td>
  <!-- Instructor -->
  <!-- <td width="15%" class="text-center"></td> -->
  <!-- Video -->
  <td class="text-center"></td>
  <!-- Material -->
  <td class="text-center">
    <!--
    <span class="pendente">
      <i class="fa fa-ban" aria-hidden="true"></i>
      Not Available
    </span>
    -->
  </td>
  <!-- Exercicio -->
  <!--
  <td>
    <span class="pendente">
      <i class="fa fa-ban" aria-hidden="true"></i>
      Not Available
    </span>
  </td>
  -->
  <!-- Teste -->
  <td class="text-center">
    <% if (_.has(model, 'testExecution') && !_.isNull(model.testExecution.user_grade)) { %>
      <% if (model.testExecution.pass == "1") { %>  
        <span class="concluido">
          <strong class="small-box"><%= model.testExecution.user_grade %></strong>
        </span>
      <% } else { %>
        <span class="pendente">
          <strong class="small-box"><%= model.testExecution.user_grade %></strong>
        </span>
        <% if (model.test.test_repetition <= 0 || _.size(model.executions) < model.test.test_repetition) { %>
        <a href="javascript: void(0);" class="view-test-action">
            {translateToken value="Try Again"}
        </a>
        <% } %>
      <% } %>
    <% } else { %>
      <% if (_.has(model, 'progress') && model.progress.factor == 1) { %>
        <span class="concluido">
          <i class="fa fa-check-square-o" aria-hidden="true"></i>
          {translateToken value="Completed"}
        </span>
      <% } else if (_.has(model, 'progress') && model.progress.factor > 0) { %>
        <a href="javascript: void(0);" class="btn btn-sm btn-continue view-test-action">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            {translateToken value="In Progress"}
        </a>
      <% } else { %>
        <a href="javascript: void(0);" class="btn btn-sm btn-avaliable view-test-action">
            <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
            {translateToken value="Available"}
        </a>
      <% } %>
    <% } %>
  </td>
  <!-- Exame -->
  <!-- <td>
    <span class="pendente"><i class="fa fa-exclamation" aria-hidden="true"></i></span>
    <span class="concluido"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></span>
    <span class="avalialbe"><i class="fa fa-coffee" aria-hidden="true"></i></span>
  </td>
   -->
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
  				{translateToken value="Do now"}
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
    {translateToken value="There's no content for this lesson"}
  </div>
</script>
<script type="text/template" id="tab_unit_video-item-template">
  <% console.warn("_ MODEL", model) %>
    <video crossorigin="anonymous" id="unit-video-<%= model.id %>" class="video-js vjs-default-skin vjs-big-play-centered vjs-auto-height <% if (model.is_main) { %> main-video <% } else { %> sec-video <% } %> video-index-<%= model.video_index %>"
      width="auto"  height="auto"
      <% if (_.isNull(model.poster)) { %>
        poster="{Plico_GetResource file='images/default-poster.jpg'}"
      <% } else { %>
        poster="<%= model.poster.url %>"
      <% } %>
      >
      <% if (_.has(model, 'url')) { %>
        <source src="<%= model.url %>" type='<%= model.type %>' />
      <% } else if (_.has(model, 'content')) { %>
        <!-- <source src="<%= model.content %>" /> -->
      <% } %>
      <% if (_.has(model, 'subtitles')) { %>
        <% _.each(model.subtitles, function(item, index){ %>
          <track 
            kind="subtitles" 
            src="<%= item.url %>" 
            <% if (_.isObject(item.locale)) { %>
            srclang="<%= item.locale.locale_code %>" 
            label="<%= item.locale.local_name %>"
            <% } else { %>
            <% } %>
          ></track>
        <% }); %>
      <% } %>
    </video>
</script>
<script type="text/template" id="tab_unit_video-multi-video-dropdown-item-template">
  <li class="dynamic-view-item">
    <a href="javascript: void(0);" class="btn btn-link hidden-xs change-view-type" data-view-type="only" data-view-index="<%= model.index %>">
      {translateToken value="Video"} <%= model.index + 1 %>
    </a>
  </li>
</script>

<script type="text/template" id="tab_unit_video-rating-view">
  <div class="rating-view">
    <div class="row">
      <div class="col-md-12" align="center">
        <h3>{translateToken value="Rate this unit"}</h3>
      </div>
    </div>
    <div class="row rating-stars-container">
      <div class="col-md-12 rating" align="center">
        <input type="radio" id="star5" name="content-rating" value="5" />
        <label class="full" for="star5" title=""></label>

        <input type="radio" id="star4" name="content-rating" value="4" />
        <label class="full" for="star4" title=""></label>

        <input type="radio" id="star3" name="content-rating" value="3" />
        <label class="full" for="star3" title=""></label>

        <input type="radio" id="star2" name="content-rating" value="2" />
        <label class="full" for="star2" title=""></label>

        <input type="radio" id="star1" name="content-rating" value="1" />
        <label class="full" for="star1" title=""></label>
      </div>
    </div>
    <div class="row rating-stars-loader hidden">
      <div class="col-md-12" align="center">
        <i class="fa fa-spin fa-circle-o-notch"></i>
      </div>
    </div>
    <div class="row rating-stars-message hidden">
      <div class="col-md-12" align="center">
        <h4>Thank You!</h4>
        
      </div>
    </div>
  </div>
</script>

<!--
<script type="text/template" id="tab_unit_materials-nofound-template">
  <tr>
    <td colspan="5">
      <span class="text-info">
        <i class="icon-warning-sign"></i>
        {translateToken value="There are no materials posted in this course"}
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
        <i class="fa fa-check-square-o" aria-hidden="true"></i>
        {translateToken value="Viewed"}
      </span>
    <% } else { %>
      <span class="avalialbe">
        <i class="fa fa-square-o" aria-hidden="true"></i>
        {translateToken value="Available"}
      </span>
    <% } %>
  </td>
</script>
-->




<!-- 
<script type="text/template" id="tab_unit_exercises-nofound-template">
	<tr>
		<td colspan="4"  class="alert alert-info">
			<span class="text-info">
				<i class="icon-warning-sign"></i>
				{translateToken value="There are no exercises registered for this course"}
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
				{translateToken value="Do it now"}
			<% } %>
		</a>
	</td>
</script>
 -->

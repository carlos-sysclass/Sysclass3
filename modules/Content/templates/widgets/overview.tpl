{assign var="widget_data" value=$T_DATA.data}
<script>
_before_init_functions.push(function() {
    $SC.addResource("content_widget_data", {$widget_data|@json_encode nofilter});
});
</script>

      <div class="row">
        <div class="col-sm-3 col-md-3 courses-super-nav content-bar-border-left">
          <div class="course-tab-onout">
            <div class="secure-tap closed">
              <span class="outer-line-tap one"></span>
              <span class="outer-line-tap two"></span>
              <span class="outer-line-tap three"></span>
            </div>
            <span class="click-desp">Curso</span>
          </div>
          <ul class="nav navbar-left">
            <li id="fat-menu" class="dropdown active"> 
              <a href="#" class="dropdown-toggle" id="drop3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Compras Eletrônicas</a> 
              <ul class="dropdown-menu" aria-labelledby="drop3"> 
                <li><a href="#">Avaliação do Ciclo de Vida</a></li>
                <li><a href="#">Custo Total de Posse</a></li>
                <li><a href="#">Análise da Capacidade de Fornecimento pelo Mercado</a></li>
                <li><a href="#">Legislação e Normalização Técnica</a></li>
                <li><a href="#">Teste Compras sustentáveis</a></li>
                <li role="separator" class="divider"></li>
                 <li><a href="#">Course Description</a></li>
              </ul>
            </li>
            <li id="fat-menu" class="dropdown"> 
              <a href="#" class="dropdown-toggle" id="drop3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Relacionamento com Fornecedores</span> </a> 
              <ul class="dropdown-menu" aria-labelledby="drop3"> 
                <li><a href="#">Avaliação do Ciclo de Vida</a></li>
                <li><a href="#">Custo Total de Posse</a></li>
                <li><a href="#">Análise da Capacidade de Fornecimento pelo Mercado</a></li>
                <li><a href="#">Legislação e Normalização Técnica</a></li>
                <li><a href="#">Teste Compras sustentáveis</a></li>
              </ul>
            </li>
            <li id="fat-menu" class="dropdown"> 
              <a href="#" class="dropdown-toggle" id="drop3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Compras Sustentáveis</a> 
              <ul class="dropdown-menu" aria-labelledby="drop3"> 
                <li><a href="#">Avaliação do Ciclo de Vida</a></li>
                <li><a href="#">Custo Total de Posse</a></li>
                <li><a href="#">Análise da Capacidade de Fornecimento pelo Mercado</a></li>
                <li><a href="#">Legislação e Normalização Técnica</a></li>
                <li><a href="#">Teste Compras sustentáveis</a></li>
              </ul>
            </li>
            <li id="fat-menu" class="dropdown"> 
              <a href="#" class="dropdown-toggle" id="drop3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Apresentação do Curso</a> 
              <ul class="dropdown-menu" aria-labelledby="drop3"> 
                <li><a href="#">Avaliação do Ciclo de Vida</a></li>
                <li><a href="#">Custo Total de Posse</a></li>
                <li><a href="#">Análise da Capacidade de Fornecimento pelo Mercado</a></li>
                <li><a href="#">Legislação e Normalização Técnica</a></li>
                <li><a href="#">Teste Compras sustentáveis</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="col-sm-9 col-md-9 new-content-format">
          <div class="col-md-8 no-padding">
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Status</a></li>
              <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Vídeos</a></li>
              <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Exercícios</a></li>
              <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Material</a></li>
              <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">FAQ</a></li>
            </ul>
          </div>
          <div class="col-md-4 no-padding new-content-format">
            <ul class="nav navbar-right">
              <li id="fat-menu" class="dropdown"> 
                <a href="#" class="dropdown-toggle" id="drop3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Processos de Aquisição | <span class="caret"></span> </a> 
                <ul class="dropdown-menu" aria-labelledby="drop3"> 
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </li>
            </ul>
            <div class="for-number-display">
              <span>11/4</span>
            </div>
          </div>

          <div class="displaybread">
            <span>Compras Eletrônicas  »  Processos de Aquisição  »  Vídeos</span>
          </div>
          <!-- Tab panes -->
        </div>
        <div class="col-sm-9 col-md-9 new-content-format new-content-tabs">
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="home">
              ...
            </div>
            <div role="tabpanel" class="tab-pane active" id="profile">
              <video id="my-video" class="video-js" controls preload="auto" width="640" height="264" poster="http://itaipu.sysclass.com/files/default/Thumbnail%20%288%29.png" data-setup="{}">
                <source src="http://itaipu.sysclass.com/files/lesson/3.1-Processo%20de%20Aquisicao%20%20Judite.mp4" type='video/mp4'>
                <source src="MY_VIDEO.webm" type='video/webm'>
                <p class="vjs-no-js">
                  To view this video please enable JavaScript, and consider upgrading to a web browser that
                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                </p>
              </video>
            </div>
            <div role="tabpanel" class="tab-pane" id="messages">...</div>
            <div role="tabpanel" class="tab-pane" id="settings">...</div>
          </div>
        </div>
      </div>
    </div>
	<script>
	$( document ).ready(function() {
	      $(".course-tab-onout").click(function(){
	        $(".secure-tap").toggleClass("closed");
	        $(".navbar-left").toggleClass("removed");
	        if ($(".new-content-tabs").hasClass("col-md-9")){
	          $(".new-content-tabs").addClass("col-md-12");
	          $(".new-content-tabs").removeClass("col-md-9");
	        } else {
	          $(".new-content-tabs").addClass("col-md-9");
	          $(".new-content-tabs").removeClass("col-md-12");
	        };

	        if ($(".new-content-trick").hasClass("col-md-9")){
	          $(".new-content-trick").addClass("col-md-11");
	          $(".new-content-trick").removeClass("col-md-9");
	        } else {
	          $(".new-content-trick").addClass("col-md-9");
	          $(".new-content-trick").removeClass("col-md-11");
	        };

	        if ($(".courses-super-nav").hasClass("col-md-3")){
	          $(".courses-super-nav").addClass("col-md-1");
	          $(".courses-super-nav").removeClass("col-md-3");
	        } else {
	          $(".courses-super-nav").addClass("col-md-3");
	          $(".courses-super-nav").removeClass("col-md-1");
	        };
	      });
      });
    </script>
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
								{translateToken value="Units"}
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
							<!--
							<a href="#" class="navbar-brand disabled">
								<strong>{translateToken value="You're in:"} </strong>
							</a>
							-->
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
							<!--
							<li class="">
								<a data-toggle="tab" href="#tab_program_moreinfo"><i class="fa fa-list-alt"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="More Info"}</span></a>
							</li>
							
							<li class="">
								<a data-toggle="tab" href="#tab_program_coordinator"><i class="fa fa-list-alt"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Coordinator"}</span></a>
							</li>

							<li class="">
								<a data-toggle="tab" href="#tab_course_roadmap"><i class="icon-comments"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Road Map"}</span></a>
							</li>
							-->
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
							<!-- 
							<div id="tab_course_moreinfo" class="tab-pane active">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										{translateToken value="Ops! There's any info registered for this program"}
									</div>
								</div>
							</div>
							
							<div id="tab_program_coordinator" class="tab-pane">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div class="alert alert-info">
										<span class="text-info"><i class="icon-warning-sign"></i></span>
										{translateToken value="Ops! There's any info registered for this program"}
									</div>
								</div>
							</div>
							-->
							<!--
							<div id="tab_course_roadmap" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<div id="tab_course_roadmap-accordion">
									</div>
								</div>
							</div>
							-->
						</div>
					 </div>
				</div>
				<div id="course-tab" class="tab-pane">

					<div class="navbar navbar-default navbar-lesson" role="navigation">
						<div class="navbar-header">
							<!--
							<a href="#" class="navbar-brand disabled">
								<strong>{translateToken value="You're in:"} </strong>
							</a>
							-->
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

							<!--
							<li class="">
								<a data-toggle="tab" href="#tab_class_dropbox"><i class="icon-dropbox"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Dropbox"}</span></a>
							</li>
							-->
							<!--
							<li class="hidden-xxs">
								<a data-toggle="tab" href="#tab_class_bibliography"><i class="icon-book"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Bibliography"}</span></a>
							</li>

							<li class="hidden-xxs hidden-md">
								<a data-toggle="tab" href="#tab_class_attendence"><i class="icon-calendar"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Attendence"}</span></a>
							</li>
							
							<li class="hidden-xxs hidden-md">
								<a data-toggle="tab" href="#tab_class_tests"><i class="fa fa-check-square-o"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Tests"}</span></a>
							</li>
							-->
							<li class="visible-xxs visible-md">
								<a data-toggle="dropdown" href="javascript: void(0);"><i class="icon-ellipsis-horizontal"></i> <span class="hidden-xs inline">{translateToken value="More"}</span></a>
								<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="">
									<!--
									<li class="visible-xs">
										<a data-toggle="tab" href="#tab_class_bibliography"><i class="icon-book"></i> <span class="">{translateToken value="Bibliography"}</span></a>
									</li>
									<li class="">
										<a data-toggle="tab" href="#tab_class_attendence"><i class="icon-calendar"></i> <span class="">{translateToken value="Attendence"}</span></a>
									</li>
									
									<li class="">
										<a data-toggle="tab" href="#tab_class_tests"><i class="icon-pencil"></i> <span class="">{translateToken value="Tests"}</span></a>
									</li>
									-->
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
												<!-- <th>{translateToken value="#"}</th> -->
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
							<!-- 							
							<div id="tab_class_tests" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th>{translateToken value="#"}</th>
												<th>{translateToken value="Name"}</th>
												<th class="text-center">{translateToken value="# Questions"}</th>
												<th class="text-center">{translateToken value="Times done"}</th>
												<th class="text-center">{translateToken value="Grade"}</th>
												<th class="text-center">{translateToken value="Options"}</th>
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
												<td class="text-center"><span class="label label-warning">{translateToken value="Stand By"}</span></td>
												<td class="text-center"></td>
											</tr>
											
										</tbody>
									</table>
								</div>
							</div> -->
							<!--
							<div id="tab_class_dropbox" class="tab-pane ">
								<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<h5>
										<a href="javascript: void(0)"> {translateToken value="Professor Files"} </a> -
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
										{translateToken value="Sorry! Any data has been registered for this class yet."}
									</div>
								</div>
							</div>

							-->
						</div>
					</div>
				</div>
				<div id="unit-tab" class="tab-pane active">
					<div class="navbar navbar-default navbar-lesson" role="navigation">
						<div class="navbar-header">
							<!--
							<a href="#" class="navbar-brand disabled">
								<strong>{translateToken value="You're in:"} </strong>
							</a>
							-->
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

								<!--
								<li>
									<a href="#" class="nav-search-action tooltips" data-original-title="{translateToken value="Search Lessons"}" data-placement="top" data-search>
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
								<a data-toggle="tab" href="#tab_unit_video"><i class="fa fa-youtube-play"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Video"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_unit_materials"><i class="fa fa-book"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Materials"}</span></a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#tab_unit_tests"><i class="fa fa-list-ol"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Tests"}</span></a>
							</li>
							<!-- <li class="">
								<a data-toggle="tab" href="#tab_unit_exercises"><i class="icon-pencil"></i> <span class="hidden-xs inline active-show-xs">{translateToken value="Exercises"}</span></a>
							</li> -->
							<!--
							<li class="">
								<a data-toggle="tab" href="#tab_lesson_search"><i class="icon-search"></i> Search</a>
							</li>
							-->
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
							
						    <!-- <div id="tab_unit_exercises" class="tab-pane">
						    	<div class="scroller" data-always-visible="0" data-rail-visible="1" data-height="parent">
									<table class="table table-striped table-bordered table-advance table-hover">
										<thead>
											<tr>
												<th class="text-center">{translateToken value="#"}</th>
												<th class="text-center">{translateToken value="# Questions"}</th>
												<th class="text-center">{translateToken value="Status"}</th>
												<th class="text-center">{translateToken value="Options"}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									<div class="exercises-container"></div>
							   	</div>
						   	</div> -->
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
<script type="text/template" id="tab_course_moreinfo-template">
	<table class="table table-striped table-bordered table-advance table-hover">
		<tbody>
			<tr>
				<td>{translateToken value="Goals"}</td>
				<td><%= model.goals %></td>
			</tr>

			<!--
			<tr>
				<td>{translateToken value="Credit Hours:"}</td>
				<td><strong class="text-default pull-right">80h</strong></td>
			</tr>

			<tr>
				<td>{translateToken value="Number of Classes:"}</td>
				<td><strong class="text-default pull-right"><%= model.position %> of 24</strong></td>
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
			-->
		</tbody>
	</table>
	<hr />
</script>
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
	<td><a href="#class-tab" class="class-change-action"><%= model.name %></a></td>

	<td class="text-center">
		<% if (_.isObject(model.progress) && model.progress.factor >= 1) { %>
			<span class="label label-success">{translateToken value="Yes"}</span>
		<% } else { %>
			<span class="label label-danger">{translateToken value="No"}</span>
		<% } %>
	</td>
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
	<!--
	<table class="table table-striped table-bordered table-advance table-hover">
		<tbody>
			<tr>
				<td>{translateToken value="Prerequisite(s):"}</td>
				<td><strong class="text-default pull-right"><span class="label label-success">{translateToken value="None"}</span></strong></td>
			</tr>
			<tr>
				<td>{translateToken value="Credit Hours:"}</td>
				<td><strong class="text-default pull-right">80h</strong></td>
			</tr>

			<tr>
				<td>{translateToken value="Number of Classes:"}</td>
				<td><strong class="text-default pull-right"><%= model.position %> of 24</strong></td>
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
	-->
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
	<!-- <td class="text-center"><%= model.id %></td> -->
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
</script>

<script type="text/template" id="tab_courses_tests-item-template">
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
	<!-- <td class="text-center"><%= model.id %></td>  -->
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
</script>





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
	<% console.warn(model); %>
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

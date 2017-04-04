  <div class="modal fade new-modal-v" id="content-unit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="container">
            <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown"> 
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a> 
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </li>
            </ul>
            <h4 class="modal-title" data-update="name"></h4>
            <div class="displaybread">
              <span><span data-update="course.name"></span>  »  <span data-update="name">Processos de Aquisição</span>  »  Vídeos</span>
            </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid bg-modal-black">
            <div class="container" id="unit-video-container">
            </div>
          </div>
          <div class="container inter-navsuper-tabs">
            <div class="row">
              <div class="col-md-12" id="unit-material-container">
                  <div role="alert" class="alert alert-dismissible">
                    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                    <!-- TAGS STATUS DO SISTEMA -->
                    <span class="pendente-tag">
                      {translateToken value="Not available"}
                      <span class="pendente"></span>
                    </span>
                    <span class="concluido-tag">
                      {translateToken value="Viewed"}
                      <span class="concluido"></span>
                    </span>
                    <span class="avalialbe-tag">
                      {translateToken value="Available"}
                      <span class="avalialbe"></span>
                    </span>
                  </div>
                  <table class="table table-striped"> 
                    <thead> 
                      <tr>
                        <th class="text-center">{translateToken value="Type"}</th>
                        <th class="text-center">{translateToken value="Name"}</th>
                        <th class="text-center">{translateToken value="Viewed"}</th>
                      </tr>
                    </thead> 
                    <tbody> 
                    </tbody>
                  </table>
              </div>
            </div>
            
          </div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>





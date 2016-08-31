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
                      {translateToken value="Not Avaliable"}
                      <span class="pendente"></span>
                    </span>
                    <span class="concluido-tag">
                      {translateToken value="Viewed"}
                      <span class="concluido"></span>
                    </span>
                    <span class="avalialbe-tag">
                      {translateToken value="Avaliable"}
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

  <script type="text/template" id="tab_unit_video-nofound-template">
    <div class="alert alert-info">
      <span class="text-info"><i class="icon-warning-sign"></i></span>
      {translateToken value="Ops! There's any content for this lesson"}
    </div>
  </script>

  <script type="text/template" id="tab_unit_video-item-template">
    <div class="videocontent">
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
    </div>
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
  <td>
    <a target="_blank" class="view-content-action" href="<%= model['file'].url %>">
      <%= model['file'].name %>
    </a>
  </td>
  <td class="text-center">
    <% if (model.progress.factor == 1) { %>
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

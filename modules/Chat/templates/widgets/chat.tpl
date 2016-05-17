<ul class="ver-inline-menu ver-inline-notabbable ver-inline-menu-noarrow">
    <li class="active block-title">
        <a>
            <i class="{$T_DATA.icon}"></i>
            <span class="advidor-title">{$T_DATA.header}</span>
        </a>
    </li>
    <li class="active chat-loader" style="display: none;">
        <a>
            <i class="fa">
                <span class="fa fa-circle-o-notch fa-lg fa-spin"></span>
            </i>
            Connecting
        </a>
    </li>
    <li class="active block-error" style="display: none;">
        <a>
            <i class="{$T_DATA.icon}"></i>
            <span class="advidor-title">{translateToken value="Chat not avaliable"}</span>
        </a>
    </li>
</ul>
<div class="panel-body">
    <div class="queue-container">
        
    </div>
    <div class="row" id="chat-action-container">
        <div class="col-md-12">
            <div class="text-center">

            </div>
        </div>
    </div>
</div>




<script type="text/template" id="widget-chat-queue-template">
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4 text-center no-padding-right">
            <% if (_.size(model.user.avatars) > 0) { %>
                <img class="avatar img-responsive" alt="" src="<%= model.user.avatars[0].url %>" style="width: 160px;" />
            <% } else { %>
                <img class="avatar img-responsive" alt="" src="{Plico_GetResource file='images/placeholder/avatar.png'}" style="width: 160px;" />
            <% } %>
            <p class="text-left margin-bottom-5">
                <% if (model.online) { %>
                <a href="javascript: void(0);" class="btn btn-success btn-sm pull-right start-chat-action">
                    <i class="icon-ok-sign"></i> {translateToken value="Online"}
                </a>
                <% } else { %>
                <a href="javascript: void(0);" class="btn btn-danger btn-sm pull-right start-chat-action">
                    <i class="icon-ok-sign"></i> {translateToken value="Offline"}
                </a>
                <% } %>
            </p>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-8">
            <p class="text-left margin-bottom-5">
                <!-- <span class="pull-left hidden-xs">{translateToken value="Attendee"}:</span> -->
                <strong class="text-default"><%= model.name %></strong>
            </p>
            <p class="text-left margin-bottom-5">
                <%= model.user.name %> <%= model.user.surname %>
            </p>
            
            <p class="text-left margin-bottom-5">
                <!-- <span class="pull-left hidden-xs">{translateToken value="Local Time"}:</span> -->
                <span class="text-default"><%= model.user.timezone %></span>
            </p>
            <p class="text-left margin-bottom-5"><%= model.user.language.name %></p>
        </div>
    </div>
    <hr />
</script>

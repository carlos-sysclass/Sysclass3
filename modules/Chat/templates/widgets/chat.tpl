<!--
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
            <span class="advidor-title">{translateToken value="Chat not available"}</span>
        </a>
    </li>
</ul>
-->
<div class="portlet-body chat-panel-reset">
    <div>
        <ul class="queue-container">
            
        </ul>
    </div>
</div>

<script type="text/template" id="widget-chat-queue-template">
    <div class="row">
        <% if (model.online) { %>
        <a href="javascript: void(0);" class="start-chat-action">
        <% } else { %>
        <a href="javascript: void(0);" class="start-chat-action">
        <% } %>
            <div class="col-md-4 col-sm-3 col-xs-2 text-center no-padding-right">
                <% if (_.size(model.user.avatars) > 0) { %>
                    <img class="avatar img-responsive" alt="" src="<%= model.user.avatars[0].url %>" style="width: 160px;" />
                <% } else { %>
                    <img class="avatar img-responsive" alt="" src="{Plico_GetResource file='images/placeholder/avatar.png'}" style="width: 160px;" />
                <% } %>

                <% if (model.online) { %>
                <span class="btn btn-success btn-sm">{translateToken value="Online"}</span>    
                <% } else { %>
                <span class="btn btn-danger btn-sm">{translateToken value="Offline"}</span>
                <% } %>
            </div>
            <div class="col-md-8 col-sm-9 col-xs-10">
                <h5><%= model.name %></h5>
                <small><%= model.user.name %> <%= model.user.surname %></small>
                <p>
                    <%= model.user.timezone %> <br>
                    <%= model.user.language.name %>
                </p>
            </div>
        </a>
    </div>
</script>

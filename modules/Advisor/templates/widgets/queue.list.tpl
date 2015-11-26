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
<ul class="list-group queue-list">
    <li class="list-group-item"> AAAA
        <span class="badge badge-default"> 1 </span>
    </li>
</ul>
<script type="text/template" id="queue-list-item-template">
    <% if (model.online) { %>
        <i class="fa fa-circle text-success"></i>
    <% } else { %>
        <i class="fa fa-circle text-danger"></i>
    <% } %>
    <%= model.requester.name %> (<%= model.requester.email %>)
    <div class="btn-group pull-right" style="margin-top:-6px;">
        <a aria-expanded="false" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;">
            <i class="fa fa-cog"></i>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li>
                <a href="javascript:;" class="view-chat-action">
                    <i class="fa fa-eye text-primary"></i>
                    Open Chat
                </a>
            </li>
            <li>
                <a href="javascript: alert('not disponible yet');">
                    <i class="fa fa-user text-success"></i>
                    Assign to Me 
                </a>
            </li>
            <li>
                <a href="javascript: alert('not disponible yet');">
                    <i class="fa fa-clock-o text-warning"></i>
                    Set Resolution
                </a>
            </li>
            <li>
                <a href="javascript: alert('not disponible yet');">
                    <i class="fa fa-close text-danger"></i>
                    Delete Chat
                </a>
            </li>
        </ul>
    </div>

</script>




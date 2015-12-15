<div class="tab-pane active" id="quick_sidebar_tab_1">
    <div class="page-quick-sidebar-chat-users" data-height="auto" data-rail-visible="1" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
        <!-- USE TO STICK MESSAGES
        <h3 class="list-heading">Staff</h3>
        <ul class="media-list list-items">
            <li class="media">
                <div class="media-status">
                    <span class="badge badge-success">8</span>
                </div>
                <img class="media-object" src="../assets/layouts/layout/img/avatar3.jpg" alt="...">
                <div class="media-body">
                    <h4 class="media-heading">Bob Nilson</h4>
                    <div class="media-heading-sub"> Project Manager </div>
                </div>
            </li>
            <li class="media">
                <img class="media-object" src="../assets/layouts/layout/img/avatar1.jpg" alt="...">
                <div class="media-body">
                    <h4 class="media-heading">Nick Larson</h4>
                    <div class="media-heading-sub"> Art Director </div>
                </div>
            </li>
            <li class="media">
                <div class="media-status">
                    <span class="badge badge-danger">3</span>
                </div>
                <img class="media-object" src="../assets/layouts/layout/img/avatar4.jpg" alt="...">
                <div class="media-body">
                    <h4 class="media-heading">Deon Hubert</h4>
                    <div class="media-heading-sub"> CTO </div>
                </div>
            </li>
            <li class="media">
                <img class="media-object" src="../assets/layouts/layout/img/avatar2.jpg" alt="...">
                <div class="media-body">
                    <h4 class="media-heading">Ella Wong</h4>
                    <div class="media-heading-sub"> CEO </div>
                </div>
            </li>
        </ul>
        -->

        <h3 class="list-heading">Support Requests</h3>
        <ul class="media-list list-items default-queue-list">
        </ul>
    </div>
    <div class="page-quick-sidebar-item">
        <div class="page-quick-sidebar-chat-user">

        </div>
    </div>
</div>

<script type="text/template" id="sidebar-chat-queue-template">
    <div class="media-status">
        <!--
        <% if (model.online) { %>
            <i class="fa fa-lg fa-circle text-success"></i>
        <% } else { %>
            <i class="fa fa-lg fa-circle text-danger"></i>
        <% } %>
        -->
        <button type="button" class="btn btn-circle btn-sm btn-primary hidden show-hover tooltips" data-original-title="Tooltip">
            <i class="fa fa-user"></i>
        </button>
        <button type="button" class="btn btn-circle btn-sm btn-info hidden show-hover">
            <i class="fa fa-user"></i>
        </button>
        <button type="button" class="btn btn-circle btn-sm btn-warning hidden show-hover">
            <i class="fa fa-user"></i>
        </button>
        <% if ( model.new_count > 0) { %>
            <button type="button" class="btn btn-circle btn-sm btn-danger btn-disabled">
                <%= model.new_count %>
            </button>
        <% } %>
    </div>
    <div class="media-body">
        <h4 class="media-heading"><%= model.requester.name %> <%= model.requester.surname %></h4>
        <div class="media-heading-sub"> CEO, Loop Inc </div>
        <div class="media-heading-small"><%= moment.unix(model.ping).fromNow() %></div>
    </div>
</script>

<script type="text/template" id="sidebar-chat-item-template">
<div class="post <% if (model.mine) { %>out<% } else { %>in<% } %>">
    <img class="avatar" alt="" src="../assets/layouts/layout/img/avatar3.jpg" />
    <div class="message">
        <span class="arrow"></span>
        <a href="javascript:;" class="name"><%= model.from.name %> <%= model.from.surname %></a>
        <span class="datetime"><%= moment.unix(model.sent).format("HH:mm:ss") %></span>
        <span class="body"> <%= model.message %> </span>
    </div>
</div>
</script>
<script type="text/template" id="sidebar-chat-item-info-template">
<hr />
<div class="message text-center"><i><%= model.message %></i></div>
<hr />
</script>
<script type="text/template" id="sidebar-conversation-item-template">
    <div class="page-quick-sidebar-nav">
        <button type="button" class="btn btn-circle btn-sm btn-default show-hover page-quick-sidebar-back-to-list">
            <i class="fa fa-arrow-left"></i>
          Back
        </button>

        <button type="button" class="btn btn-circle btn-sm btn-primary show-hover tooltips" data-original-title="Tooltip">
            <i class="fa fa-user"></i>
        </button>
        <button type="button" class="btn btn-circle btn-sm btn-info show-hover">
            <i class="fa fa-user"></i>
        </button>
        <button type="button" class="btn btn-circle btn-sm btn-warning show-hover">
            <i class="fa fa-user"></i>
        </button>

        <div class="margin-top-10 user-details">
            <h4 class="media-heading">&nbsp;</h4>
            <div class="media-heading-sub">&nbsp;</div>
        </div>
    </div>

    <div class="page-quick-sidebar-chat-user-messages"  data-height="auto" data-rail-visible="1" data-rail-color="#ddd">
        <div class="page-quick-sidebar-chat-user-messages-previous"></div>
        <div class="page-quick-sidebar-chat-user-messages-current"></div>
    </div>
    <div class="page-quick-sidebar-chat-user-form">
        <div class="input-group">
            <input type="text" class="form-control send-message-input" placeholder="Type a message here...">
            <div class="input-group-btn">
                <button type="button" class="btn green send-message-action">
                    <i class="fa fa-send"></i>
                </button>
            </div>
        </div>
    </div>
</script>
<div class="panel-group accordion scrollable" id="tutoria-accordion">
</div>
<form id="tutoria-widget-form" action="/module/tutoria/insert">
   <div class="chat-form">
      <div class="input-group form-group">
         <input class="form-control" name="title" type="text" placeholder="{translateToken value='Type a question here...'}" />
         <span class="input-group-btn">
            <button type="submit" class="btn blue icn-only"><i class="icon-ok icon-white"></i></button>
         </span>
      </div>
   </div>
</form>
<script type="text/template" id="tutoria-nofound-template">
   <div class="alert alert-warning">
         <span class="text-warning"><i class="icon-warning-sign"></i></span>
         {translateToken value='Ops! Sorry, any data found!'}
   </div>
</script>

<script type="text/template" id="tutoria-item-template">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4 class="panel-title">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#tutoria-accordion" href="#tutoria-accordion-panel-<%= id %>">
            <%= title %>
            <% if (approved == 0 && (answer == "" || answer == null)) { %>
               <span class="label label-danger pull-right">{translateToken value='Waiting Approval'}</span>
            <% } else if (answer == "" || answer == null) {  %>
               <span class="label label-warning pull-right">{translateToken value='Unanswered'}</span>
            <% } %>
            </a>
         </h4>
      </div>
      <div id="tutoria-accordion-panel-<%= id %>" class="panel-collapse collapse">
         <div class="panel-body">
            <ul class="chats">
               <li class="in">
                  <img class="avatar img-responsive" alt="" src="<%= question_avatar.avatar %>" width="<%= question_avatar.width %>"/>
                  <div class="message">
                     <span class="arrow"></span>
                     <a href="#" class="name"><%= question_user_name %> <%= question_user_surname %></a>
                     <span class="datetime"><%= question_timestamp %></span>
                     <span class="body">
                     <% if (question != "") { %>
                        <%= question %>
                     <% } else { %>
                        <%= title %>
                     <% } %>
                     </span>
                  </div>
               </li>
               <% if (answer != "" && answer != null) { %>
                  <li class="out">
                     <img class="avatar img-responsive" alt="" src="<%= answer_avatar.avatar %>" width="<%= answer_avatar.width %>"/>
                     <div class="message">
                        <span class="arrow"></span>
                        <a href="#" class="name"><%= answer_user_name %> <%= answer_user_surname %></a>
                        <span class="datetime"><%= answer_timestamp %></span>
                        <span class="body">
                           <%= answer %>
                        </span>
                     </div>
                  </li>
               <% } %>
            </ul>
         </div>
      </div>
   </div>
</script>

<script type="text/template" id="tutoria-chat-template">
<div class="portlet box dark-blue">
   <div class="portlet-title">
      <div class="caption">
         <% if (status == 'online') { %>
            <i class="icon-ok-sign"></i>
         <% } else if (status == 'busy') { %>
            <i class="icon-minus-sign"></i>
         <% } else if (status == 'away') { %>
            <i class="icon-time"></i>
         <% } else if (status == 'offline') { %>
            <i class="icon-remove-sign"></i>
         <% } %>
         <%= name %>
      </div>
      <div class="tools">
         <a class="collapse" href="javascript:;"></a>
         <a class="remove" href="javascript:;"></a>
      </div>
   </div>
   <div class="portlet-body">
      <ul class="scroller chat-contents" data-height="200px" data-always-visible="1">
      </ul>
      <div class="send-block">
         <div class="input-icon right">
            <i class="icon-signin"></i>
            <input type="text" class="form-control"  />
         </div>
      </div>
   </div>
</div>
</script>
<script type="text/template" id="tutoria-chat-item-template">
<div class="subject">
   <span class="label label-default"><%= from %></span>
   <!--
   <span class="badge badge-primary badge-roundless pull-right">Just Now</span>
   -->
</div>
<div class="message"><%= body %></div>
<hr />
</script>
<script type="text/template" id="tutoria-chat-status-template">
<div class="subject">
   <span class="label label-default"><%= from %></span>
   <!--
   <span class="badge badge-primary badge-roundless pull-right">Just Now</span>
   -->
</div>
<div class="status">Changed its status to <strong><% if (status != null) { %><%= status %><% } else { %><%= show %><% } %></strong></div>
<hr />
</script>

#
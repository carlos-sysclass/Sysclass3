<div class="panel-group accordion scrollable" id="tutoria-accordion">
</div>
<form id="tutoria-widget-form" action="#">
   <div class="chat-form">
      <div class="input-group form-group">
         <input class="form-control" name="title" type="text" placeholder="{translateToken value="Type a question here..."}" data-rule-required="true" data-rule-minlength="10" />
         <span class="input-group-btn">
            <button type="submit" class="btn blue icn-only"><i class="icon-ok icon-white"></i></button>
         </span>
      </div>
   </div>
</form>

<script type="text/template" id="tutoria-nofound-template">
   <div class="alert alert-warning">
         <span class="text-warning"><i class="icon-warning-sign"></i></span>
         {translateToken value="Ops! Sorry, any data found!"}
   </div>
</script>
<script type="text/template" id="tutoria-item-template">
   <% var has_answer = _.has(model, 'answer') && !_.isEmpty(model.answer.answer); %>

   <div class="panel panel-default <% if (model.approved == 0 && !has_answer) { %>danger-stripe<% } else if (!has_answer) {  %>warning-stripe<% } else { %>default-stripe<% } %>">
      <div class="panel-heading">
         <h4 class="panel-title">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#tutoria-accordion" href="#tutoria-accordion-panel-<%= model.id %>">
            <%= model.title %>
            <% if (model.approved == 0) { %>
               <span class="label label-danger pull-right hidden-xs hidden-sm">{translateToken value="Waiting Approval"}</span>
            <% } else if (!has_answer) {  %>
               <span class="label label-warning pull-right hidden-xs hidden-sm">{translateToken value="Unanswered"}</span>
            <% } %>
            </a>
         </h4>
      </div>
      <div id="tutoria-accordion-panel-<%= model.id %>" class="panel-collapse collapse">
         <div class="panel-body">
            <ul class="chats">
               <li class="in">
                  <img class="avatar img-responsive" alt="" src="/assets/sysclass.default/img/avatar_small.jpg" width=""/>

                  <div class="message">
                     <span class="arrow"></span>
                     <a href="javascript: void(0);" class="name"><%= model.question.user_name %> <%= model.question.user_surname %></a>
                     <span class="datetime"><%= moment.unix(model.question.timestamp).fromNow() %></span>
                     <span class="body">
                     <% if (model.question.question != "") { %>
                        <%= model.question.question %>
                     <% } else { %>
                        <%= model.title %>
                     <% } %>
                     </span>
                  </div>
               </li>
               <% if (has_answer) { %>
                  <li class="out">
                     <img class="avatar img-responsive" alt="" src="/assets/sysclass.default/img/avatar_medium.jpg" width=""/>
                     <div class="message">
                        <span class="arrow"></span>
                        <a href="javascript: void(0);" class="name"><%= model.answer.user_name %> <%= model.answer.user_surname %></a>
                        <span class="datetime"><%= moment.unix(model.answer.timestamp).fromNow() %></span>
                        <span class="body">
                           <%= model.answer.answer %>
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
   <div class="portlet box dark-blue" style="display:hide;">
      <div class="portlet-title">
         <div class="caption">
            <i class="icon-comments"></i>
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
               <input type="text" class="form-control" placeholder="{translateToken value="Type your message here..."}" />
            </div>
         </div>
      </div>
   </div>
</script>
<script type="text/template" id="tutoria-chat-item-template">
   <div class="subject">
      <span class="label <% if (from_me) { %>label-primary<% } else { %>label-danger<% } %>"><%= from.node %></span>
      <!--
      <span class="badge badge-primary badge-roundless pull-right">Just Now</span>
      -->
   </div>
   <div class="message"><%= body %></div>
   <hr />
</script>
<script type="text/template" id="tutoria-chat-status-template">
   <div class="subject">
      <span class="label label-danger"><%= name %></span>
      <i class="icon-arrow-right"></i>
      <div class="pull-right">
      <% if (status == 'online') { %>
         <span class="badge badge-success"><%= status %></span>
      <% } else if (status == 'busy') { %>
         <span class="badge badge-danger"><%= status %></span>
      <% } else if (status == 'away') { %>
         <span class="badge badge-warning"><%= status %></span>
      <% } else if (status == 'offline') { %>
         <span class="badge badge-default"><%= status %></span>
      <% } %>
      </div>
   </div>
   <hr />
</script>

<div id="sounds" >
   <audio id="ping">
      <source type="audio/wave" src="{Plico_GetResource file='audio/ping.wav'}">
   </audio>
</div>

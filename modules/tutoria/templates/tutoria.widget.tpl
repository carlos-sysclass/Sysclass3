<div class="panel-group accordion scrollable" id="tutoria-accordion">
</div>
<form id="tutoria-widget-form" action="/module/tutoria/insert">
   <div class="chat-form">
      <div class="input-group form-group">
         <input class="form-control" name="title" type="text" placeholder="{translateToken value='Type a question here...'}" />
         <span class="input-group-btn">
            <button type="button" class="btn blue icn-only"><i class="icon-ok icon-white"></i></button>
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
               <% if (answer != "") { %>
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
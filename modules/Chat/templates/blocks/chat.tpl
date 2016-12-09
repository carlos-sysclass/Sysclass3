<script type="text/template" id="chat-template">
   <div class="portlet box dark-blue">
      <div class="portlet-title">
         <div class="caption">
            <i class="ti-comments"></i>
            <% if (_.has(model, 'another')) { %>
               <%= model.another.name %>
            <% } else { %>
               <%= model.from.name %>
            <% } %>
         </div>
         <div class="tools">
            <a class="remove" href="javascript:;"></a>
         </div>
      </div>
      <div class="portlet-body">
         <div class="scroller chat-contents" data-height="200px" data-always-visible="1">
            <div class="chat-contents-previous">
               <div class="chat-message-item load-previous-messages-container" data-loading-text="{translateToken value='Loading...'}">
                  <div class="info"><i class="fa fa-clock-o"></i>
                     <a href="javascript: void(0);" class="load-previous-messages-action">{translateToken value="Load Previous Messages"}</a>
                  </div>
                  <hr />
               </div>

            </div>
            <div class="chat-contents-current"></div>
         </div>
         <div class="send-block">
            <div class="input-icon right">
               <i class="ti-angle-double-right"></i>
               <input type="text" class="form-control" placeholder="{translateToken value="Type your message here..."}" />
            </div>
         </div>
      </div>
   </div>
</script>
<script type="text/template" id="chat-item-template">
   <div class="subject">
      <span class="label <% if (model.mine) { %>label-primary<% } else { %>label-danger<% } %>">
         <% if (model.mine) { %>
            {translateToken value="Me"}
         <% } else { %>
            <%= model.from.name %>
         <% } %>
      </span>
      <!--
      <span class="badge badge-info badge-roundless pull-right"><%= moment.unix(model.timestamp).fromNow() %></span>
      -->
   </div>
   <% if (model.type == 'info') { %>
      <div class="info"><i><%= model.message %></i></div>
   <% } else { %>
      <div class="message"><%= model.message %></div>
   <% } %>
   
   <hr />
</script>
<script type="text/template" id="chat-item-info-template">
   <div class="info"><i><%= model.message %></i></div>
   <hr />
</script>
<!--
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
-->
<div id="sounds" >
   <audio id="ping">
      <source type="audio/wave" src="{Plico_GetResource file='audio/ping.wav'}">
   </audio>
</div>

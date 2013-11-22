{extends file="layout/default.tpl"}
{block name="content"}
<div class="row inbox" id="inbox-container">
   <div class="col-md-2">
      <ul class="inbox-nav margin-bottom-10">
         <li class="compose-btn">
            <a href="javascript:;" data-title="Compose" class="btn green"> 
            <i class="icon-edit"></i> Compose
            </a>
         </li>
      </ul>
      <div class="list-group folders-list">
      <!--
         <li class="inbox active"><a href="javascript:;" class="btn" data-title="Inbox">Inbox(3)</a><b></b></li>
         <li class="sent"><a class="btn" href="javascript:;"  data-title="Sent">Sent</a><b></b></li>
         <li class="draft"><a class="btn" href="javascript:;" data-title="Draft">Draft</a><b></b></li>
         <li class="trash"><a class="btn" href="javascript:;" data-title="Trash">Trash</a><b></b></li>
      -->
      </div>
   </div>
   <div class="col-md-10">
      <div class="inbox-header">
         <h1 class="pull-left">Inbox</h1>
         <form class="form-inline pull-right" action="index.html">
            <div class="input-group input-medium">
               <input type="text" class="form-control" placeholder="Search...">
               <span class="input-group-btn">                   
               <button type="submit" class="btn green"><i class="icon-search"></i></button>
               </span>
            </div>
         </form>
      </div>
      <div class="inbox-loading">Loading...</div>
      <div class="inbox-content">
         <table class="table table-striped table-advance table-hover" id="messages-container">
            <thead>
               <tr>
                  <th colspan="3">
                  <input type="checkbox" class="mail-checkbox mail-group-checkbox">
                     <div class="btn-group">
                        <a class="btn btn-sm blue" href="#" data-toggle="dropdown"> More
                           <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                           <li><a href="#"><i class="icon-pencil"></i> Mark as Read</a></li>
                           <li><a href="#"><i class="icon-ban-circle"></i> Spam</a></li>
                           <li class="divider"></li>
                           <li><a href="#"><i class="icon-trash"></i> Delete</a></li>
                        </ul>
                     </div>
                  </th>
                  <th class="pagination-control" colspan="3">
                     <span class="pagination-info">1-30 of 789</span>
                     <a class="btn btn-sm blue"><i class="icon-angle-left"></i></a>
                     <a class="btn btn-sm blue"><i class="icon-angle-right"></i></a>
                  </th>
               </tr>
            </thead>
         <tbody>
         </tbody>
         </table>
      </div>
   </div>
</div>
<script type="text/template" id="folder-template">
   <a class="list-group-item <%= active ? 'active' : '' %>" href="javascript:;" data-folder-id="<%= id %>" data-title="<%= pathname %>">
      <span><i class="folder-icon <%= active ? 'icon-folder-open' : 'icon-folder-close' %>"></i></span>
      <%= pathname %>
      <span class="badge badge-info"><%= messages_num %></span>
   </a>
</script>

<script type="text/template" id="message-template">
   <tr class="<%= viewed == 0 ? 'unread' : 'read' %>">
      <td class="inbox-small-cells">
        <input type="checkbox" class="mail-checkbox">
      </td>
      <td class="inbox-small-cells"><i class="icon-star"></i></td>
      <td class="view-message  hidden-xs"><%= sender %></td>
      <td class="view-message "><%= title %></td>
      <td class="view-message  inbox-small-cells"><i class="icon-paper-clip"></i></td>
      <td class="view-message  text-right"><%= f_folders_ID %> 16:30 PM</td>
   </tr>
</script>


{/block}
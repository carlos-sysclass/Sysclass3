<!--
<div class="navbar navbar-default" role="navigation">
   <div class="collapse navbar-collapse navbar-ex1-collapse">
      <form class="navbar-form navbar-left" role="search">
         <div class="input-group">
            <input type="text" class="form-control input-xlarge">
            <div class="input-group-btn">
               <button tabindex="-1" class="btn blue" type="button">Search</button>
               <button tabindex="-1" data-toggle="dropdown" class="btn blue dropdown-toggle" type="button">
               <i class="icon-angle-down"></i>
               </button>
               <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="#">Class</a></li>
                  <li><a href="#">Topic</a></li>
                  <li><a href="#">Professor</a></li>
                  <li class="divider"></li>
                  <li><a href="#">All</a></li>
               </ul>
            </div>
         </div> 
      </form>
   </div>
</div>
<div class="clearfix"></div>
-->
               
<div class="panel-group accordion scrollable" id="tutoria-accordion">
{foreach $T_DATA.data as $index => $tutoria}
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#tutoria-accordion" href="#tutoria-accordion-panel-{$index}">
            {$tutoria.title}
            </a>
         </h4>
      </div>
      <div id="tutoria-accordion-panel-{$index}" class="panel-collapse">
         <div class="panel-body">
            <ul class="chats">
               <li class="in">
                  <img class="avatar img-responsive" alt="" src="{Plico_RelativePath file=$tutoria.question_avatar.avatar}" width="{$tutoria.question_avatar.width}"/>
                  <div class="message">
                     <span class="arrow"></span>
                     <a href="#" class="name">{$tutoria.question_user_name} {$tutoria.question_user_surname}</a>
                     <span class="datetime">#filter:timestamp-{$tutoria.question_timestamp}#</span>
                     <span class="body">
                        {$tutoria.question}
                     </span>
                  </div>
               </li>
               {if $tutoria.answer != ""}
               <li class="out">
                  <img class="avatar img-responsive" alt="" src="{Plico_RelativePath file=$tutoria.answer_avatar.avatar}" width="{$tutoria.answer_avatar.width}"/>
                  <div class="message">
                     <span class="arrow"></span>
                     <a href="#" class="name">{$tutoria.answer_user_name} {$tutoria.answer_user_surname}</a>
                     <span class="datetime">#filter:timestamp-{$tutoria.answer_timestamp}#</span>
                     <span class="body">
                     {$tutoria.answer}
                     </span>
                  </div>
               </li>
               {/if}
            </ul>
         </div>
      </div>
   </div>
   {/foreach}
</div>
<div class="chat-form">
   <div class="input-cont">   
      <input class="form-control" type="text" placeholder="Type a question here..." />
   </div>
   <div class="btn-cont"> 
      <span class="arrow"></span>
      <a href="" class="btn blue icn-only"><i class="icon-ok icon-white"></i></a>
   </div>
</div>

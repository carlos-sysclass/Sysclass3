<div class="portlet-title">
   {if isset($T_DATA.title)}
      <div class="caption">
         {if isset($T_DATA.icon)}
            <i class="icon-{$T_DATA.icon}"></i>
         {/if}
      {$T_DATA.title}
      </div>
   {/if}
   {if isset($T_DATA.tools)}
   <div class="tools">
      {if isset($T_DATA.tools.filter)}
         <a class="filter glyphicon glyphicon-filter" href="javascript:void(0);"></a>
      {/if}
      {if isset($T_DATA.tools.search)}
         <a class="search glyphicon glyphicon-search" data-container="body" data-placement="left" data-html="true" data-trigger="manual" data-inject-selector="{$T_DATA.id}-search-form" href="javascript:;"></a>

         <div class="hidden" id="{$T_DATA.id}-search-form">
            <!-- Brand and toggle get grouped for better mobile display -->
            <!-- Collect the nav links, forms, and other content for toggling -->
               <form>
                  <div class="input-group">
                     <input type="text" name="portlet-tools-search-input" class="form-control" placeholder="Search...">
                     <div class="input-group-btn">
                        <button tabindex="-1" class="btn blue portlet-tools-search-btn" type="submit"><i class="icon-search"></i></button>
                        <!--
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
                        -->
                     </div>
                  </div>
               </form> 
         </div>
      {/if}
      {if isset($T_DATA.tools.collapse)}
      <a class="collapse" href="javascript:;"></a>
      {/if}
      {if isset($T_DATA.tools.config)}
      <a class="config" data-toggle="modal" href="#portlet-config"></a>
      {/if}
      {if isset($T_DATA.tools.reload)}
      <a class="reload" href="{$T_DATA.tools.reload}"></a>
      {/if}
      {if isset($T_DATA.tools.fullscreen)}
      <a class="fullscreen glyphicon glyphicon-fullscreen" href="javascript:void(0);"></a>
      {/if}
      {if isset($T_DATA.tools.remove)}
      <a class="remove" href="javascript:void(0);"></a>
      {/if}
   </div>
   {/if}
   {if isset($T_DATA.actions)}
      <div class="actions">
         {foreach $T_DATA.actions as $action_tpl}
            {include file="`$action_tpl`.tpl" T_DATA=$T_DATA}
         {/foreach}
      </div>
   {/if}
</div>
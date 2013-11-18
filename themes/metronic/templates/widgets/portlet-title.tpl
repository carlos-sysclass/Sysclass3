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

      
   </div>
   {/if}
   {if isset($T_DATA.actions)}
      <div class="actions">
      </div>
   {/if}
</div>
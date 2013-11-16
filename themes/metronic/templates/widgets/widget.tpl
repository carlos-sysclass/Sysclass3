<div class="portlet">
   {if isset($T_DATA.title) || isset($T_DATA.tools)}
   <div class="portlet-title">
      {if isset($T_DATA.title)}
         <div class="caption"><i class="icon-comments"></i>{$T_DATA.title}</div>
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
      </div>
      {/if}
   </div>
   {/if}
   <div class="portlet-body">
      {if isset($T_DATA.template)}
         {include file="`$T_DATA.template`.tpl" T_DATA=$T_DATA}
      {else}
         {include file="widgets/`$widget.type`.tpl" T_DATA=$T_DATA}
      {/if}
   </div>
</div>
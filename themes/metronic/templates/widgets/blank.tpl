<div class="portlet">
   {if isset($T_DATA.title) || isset($T_DATA.tools)}
   <div class="portlet-title">
      {if isset($T_DATA.title)}
         <div class="caption"><i class="icon-reorder"></i>{$T_DATA.title}</div>
      {/if}
      {if isset($T_DATA.tools)}
      <div class="tools">
         {if isset($T_DATA.tools.collapse)}
         <a class="collapse" href="javascript:;"></a>
         {/if}
         <a class="config" data-toggle="modal" href="#portlet-config"></a>
         <a class="reload" href="javascript:;"></a>
      </div>
      {/if}
   </div>
   {/if}
   <div class="portlet-body">
      Inject content here..
   </div>
</div>
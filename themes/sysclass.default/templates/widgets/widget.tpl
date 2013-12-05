{if isset($T_DATA.panel) && $T_DATA.panel}
   {assign var="base_class" value="panel"}
{else}
   {assign var="base_class" value="portlet"}
{/if}
<div class="{$base_class} {if isset($T_DATA.panel)}panel-{if $T_DATA.panel|@is_string}{$T_DATA.panel}{else}default{/if}{/if} {if isset($T_DATA.box)}box {$T_DATA.box}{/if}" {if isset($T_DATA.id)}id="{$T_DATA.id}"{/if} data-portlet-type="{$T_DATA.type}" data-portlet-id="{$T_DATA.id}">
   {if isset($T_DATA.title) || isset($T_DATA.tools)}
      {if isset($T_DATA.panel) && $T_DATA.panel}
         {include file="widgets/panel-title.tpl" T_DATA=$T_DATA}
      {else}
         {include file="widgets/portlet-title.tpl" T_DATA=$T_DATA}
      {/if}
   {/if}
   {if !isset($T_DATA.body) || $T_DATA.body}
   <div class="{$base_class}-body {if isset($T_DATA.body)}{$T_DATA.body}{/if}">
   {/if}
      {if isset($T_DATA.template)}
         {include file="`$T_DATA.template`.tpl" T_DATA=$T_DATA}
      {else}
         {include file="widgets/`$widget.type`.tpl" T_DATA=$T_DATA}
      {/if}
   {if !isset($T_DATA.body) || $T_DATA.body}
   </div>
   {/if}
</div>
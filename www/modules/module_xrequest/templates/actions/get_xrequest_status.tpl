{capture name="t_xrequest_body"}
{include file="$T_XREQUEST_BASEDIR/templates/actions/xrequest_menu.tpl"}

<div class="clear"></div>

<table class="sortedTable" style="width:100%">
	<tr>
		<td class="topTitle">{$smarty.const._NAME}</td>
		<td class="topTitle centerAlign noSort">{$smarty.const._OPERATIONS}</td>
	</tr>
{foreach name = 'ranges_loop' key = "id" item = "range" from = $T_REQUEST_STATUS}
	<tr id="row_{$range.id}" class="{cycle values = "oddRowColor, evenRowColor"}">
		<td>{$range.name}</td>
		<td class="centerAlign">
			<a href="{$T_GRADEBOOK_BASEURL}&edit_range={$range.id}&popup=1" target="POPUP_FRAME" onclick="eF_js_showDivPopup('{$smarty.const._GRADEBOOK_EDIT_RANGE}', 0)"><img src="{$T_GRADEBOOK_BASELINK}images/edit.png" alt="{$smarty.const._EDIT}" title="{$smarty.const._EDIT}" border="0"></a>
			<a href="javascript:void(0)" onclick="if(confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')) deleteRange(this, {$range.id});"><img src="{$T_GRADEBOOK_BASELINK}images/delete.png" alt="{$smarty.const._DELETE}" title="{$smarty.const._DELETE}" border="0"></a>
		</td>
	</tr>
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>
{/capture}

{eF_template_printBlock
	title 			= $smarty.const._XREQUEST_LIST_STATUS
	data			= $smarty.capture.t_xrequest_body
}
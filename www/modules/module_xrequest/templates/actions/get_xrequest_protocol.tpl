{capture name="t_xrequest_body"}
{include file="$T_XREQUEST_BASEDIR/templates/includes/xrequest_menu.tpl"}
<div class="clear"></div>
<table class="sortedTable" style="width:100%">
	<tr>
		<td class="topTitle">{$smarty.const._XREQUEST_PROTOCOL_ID}</td>
		<td class="topTitle">{$smarty.const._USER}</td>
		<td class="topTitle">{$smarty.const._XREQUEST_DATAOPEN}</td>
		<td class="topTitle">{$smarty.const._XREQUEST_DATAMODIFICAD}</td>
		<td class="topTitle">{$smarty.const._XREQUEST_TYPE}</td>
		<td class="topTitle">{$smarty.const._XREQUEST_STATUS}</td>
		
	</tr>
{foreach name = 'ranges_loop' key = "id" item = "range" from = $T_REQUEST_PROTCOL_LIST}
	<tr id="row_{$range.id}" class="{cycle values = "oddRowColor, evenRowColor"}">
		<td><a href="{$T_REQUEST_BASEURL}{$T_TYPE_USER}.php?ctg=module&op=module_xrequest&action=get_xrequest_protocol_unit&id={$range.id}">{$range.id}</td>
		<td>{$range.user_name}</td>
		<td><a href=""></a>{$range.data_open}</td>
		
		{if $range.data_modificado == null }
		<td>{$smarty.const._NODATAFOUND}</td>		
		{else}
		<td>{$range.data_modificado}</td>
		{/if}
		
		<td>{$range.desc_type}</td>
		<td>{$range.status}</td>
	</tr>
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>
{/capture}


{sC_template_printBlock
	title 			= $smarty.const._REQUEST_LIST_PROTOCOL
	data			= $smarty.capture.t_xrequest_body
}
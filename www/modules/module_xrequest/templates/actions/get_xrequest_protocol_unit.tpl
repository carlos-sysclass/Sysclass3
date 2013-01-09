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
		<td>{$range.id}</td>
		<td>{$range.user_name}</td>
		<td>{$range.data_open}</td>
		{if $range.data_modificado == null }
		<td>{$smarty.const._NODATAFOUND}</td>		
		{else}
		<td>{$range.data_modificado}</td>
		{/if}
		<td>{$range.desc_type}</td>
		<td>{$range.status}</td>
		
	</tr>
	{if $range.descricao == null }
		<tr><td colspan="6">{$smarty.const.__XREQUEST_NAME_TYPE}: {$smarty.const._NODATAFOUND}</td>
		</tr>		
		{else}
		<tr><td colspan="6">{$smarty.const.__XREQUEST_NAME_TYPE}: {$range.descricao}</td>
		</tr>
	{/if}
	
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>
<div class="clear"></div>
<br />
{$smarty.const.__XREQUEST_HISTORIC}
<table class="sortedTable" style="width:100%">
	<tr>
		<td class="topTitle">{$smarty.const.__XREQUEST_HISTORIC_DESC}</td>
		<td class="topTitle">{$smarty.const.__XREQUEST_HISTORIC_ADD_USER}</td>
		<td class="topTitle">{$smarty.const.__XREQUEST_HISTORIC_DATA}</td>
		
	</tr>
{foreach key = "id" item = "historic" from = $T_LIST_HISTORIC}
	<tr id="row_{$historic.id}" class="{cycle values = "oddRowColor, evenRowColor"}">
		<td>{$historic.historic}</td>
		<td>{$historic.user_name}</td>
		<td>{$historic.data_historic}</td>
		
	</tr>
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>


<br />

{if $T_VIEW_FORM_HISTORIC == 0}
<div class="clear"></div>
<div class="blockContents">
 	{$T_XREQUEST_HISTORIC_FORM.javascript}
	<form {$T_XREQUEST_HISTORIC_FORM.attributes}>
		{$T_XREQUEST_HISTORIC_FORM.hidden}
		<div class="grid_12">

			<label>{$T_XREQUEST_HISTORIC_FORM.historic.label}:</label><br />
			{$T_XREQUEST_HISTORIC_FORM.historic.html}
			{$T_XREQUEST_HISTORIC_FORM.historic.error}
			<br />
			{if $T_TYPE_USER == 'administrator'}
			<label>{$T_XREQUEST_HISTORIC_FORM.finaliza.label}:</label>
			{$T_XREQUEST_HISTORIC_FORM.finaliza.html}
			{/if}
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<input class="button_colour round_all" type="submit" name="{$T_XREQUEST_HISTORIC_FORM.submit_xrequest.name}" value="{$T_XREQUEST_HISTORIC_FORM.submit_xrequest.value}">
		</div>
		<div class="clear"></div>
	</form>
</div>
{/if}

{/capture}

{eF_template_printBlock
	title 			= $smarty.const.__XREQUEST_PROTOCOL
	data			= $smarty.capture.t_xrequest_body
}

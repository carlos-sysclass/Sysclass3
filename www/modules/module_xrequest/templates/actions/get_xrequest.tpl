{if $T_XREQUEST_MESSAGE}
	<script>
		re = /\?/;
		!re.test(parent.location) ? parent.location = parent.location+'?message={$T_XREQUEST_MESSAGE}&message_type=success' : parent.location = parent.location+'&message={$T_XREQUEST_MESSAGE}&message_type=success';
	</script>
{/if}

{if $smarty.get.edit_range}
	{capture name = 't_add_edit_range_code'}
		{$T_XREQUEST_ADD_EDIT_RANGE_FORM.javascript}
	<form {$T_XREQUEST_ADD_EDIT_RANGE_FORM.attributes}>
		{$T_XREQUEST_ADD_EDIT_RANGE_FORM.hidden}
		<table style="margin-left:100px">
			<tr>
				<td class="labelCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.range_from.label}:&nbsp;</td>
				<td class="elementCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.range_from.html}</td>
			</tr>
			<tr>
				<td class="labelCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.range_to.label}:&nbsp;</td>
				<td class="elementCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.range_to.html}</td>
			</tr>
			<tr>
				<td class="labelCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.grade.label}:&nbsp;</td>
				<td class="elementCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.grade.html}</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td class="elementCell">{$T_XREQUEST_ADD_EDIT_RANGE_FORM.submit.html}</td>
			</tr>
		</table>
	</form>
	{/capture}

	{if $smarty.get.add_range}
	{sC_template_printBlock title=$smarty.const._XREQUEST_ADD_RANGE data=$smarty.capture.t_add_edit_range_code image=$T_XREQUEST_BASELINK|cat:'images/XREQUEST_logo.png' absoluteImagePath = 1}
	{else}
	{sC_template_printBlock title=$smarty.const._XREQUEST_EDIT_RANGE data=$smarty.capture.t_add_edit_range_code image=$T_XREQUEST_BASELINK|cat:'images/XREQUEST_logo.png' absoluteImagePath = 1}
	{/if}

{else}




{capture name="t_xrequest_body"}

{include file="$T_XREQUEST_BASEDIR/templates/includes/xrequest_menu.tpl"}
<div class="clear"></div>

<table class="sortedTable" style="width:100%">
	<tr>
		<td class="topTitle">{$smarty.const._NAME}</td>
		<td class="topTitle">{$smarty.const.__XREQUEST_VALOR_TYPE}</td>
		<td class="topTitle centerAlign">{$smarty.const.__XREQUEST_DIASPRAZO}</td>
		<td class="topTitle centerAlign">{$smarty.const.__XREQUEST_EMAIL}</td>
		<td class="topTitle centerAlign noSort">{$smarty.const._OPERATIONS}</td>
	</tr>
{foreach name = 'ranges_loop' key = "id" item = "range" from = $T_REQUEST_TYPES}
	<tr id="row_{$range.id}" class="{cycle values = "oddRowColor, evenRowColor"}">
		<td>{$range.name}</td>
		<td>R$ {$range.price}</td>
		<td>{$range.dias_prazo}</td>
		
		{if $range.email == null }
		<td>{$smarty.const._NODATAFOUND}</td>
		{else}
		<td>{$range.email}</td>
		{/if}
		
		<td class="centerAlign">
			<a href="{$T_XREQUEST_BASEURL}&action=edit_xrequest&id={$range.id}&popup=1" target="POPUP_FRAME" onclick="sC_js_showDivPopup('{$smarty.const._XREQUEST_EDIT_RANGE}', 0)">
			<img class="sprite16 sprite16-edit" src="{$T_XREQUEST_BASELINK}images/transparent.png" alt="{$smarty.const._EDIT}" title="{$smarty.const._EDIT}" border="0">
			
			</a>
			<!-- <a href="javascript:void(0)" onclick="if(confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')) deleteRange(this, {$range.id});"><img src="{$T_XREQUEST_BASELINK}images/delete.png" alt="{$smarty.const._DELETE}" title="{$smarty.const._DELETE}" border="0"></a>  -->
		</td>
		
	</tr>
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan = "100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>
{/capture}

{sC_template_printBlock
	title 			= $smarty.const.__XREQUEST_TYPES
	data			= $smarty.capture.t_xrequest_body
}

<script>
{literal}
	function deleteRange(el, id){

		Element.extend(el);
		url = '{/literal}{$T_XREQUEST_BASEURL}{literal}&delete_range='+id;

		var img = new Element('img', {id:'img_'+id, src:'{/literal}{$T_XREQUEST_BASELINK}{literal}images/progress1.gif'}).setStyle({position:'absolute'});
		img_id = img.identify();
		el.up().insert(img);

		new Ajax.Request(url, {
			method: 'get',
			asynchronous: true,
			onFailure: function(transport){
				img.writeAttribute({src:'{/literal}{$T_XREQUEST_BASELINK}{literal}images/delete.png', title:transport.responseText}).hide();
				new Effect.Appear(img_id);
				window.setTimeout('Effect.Fade("'+img_id+'")', 10000);
			},
			onSuccess: function(transport){
				img.hide();
				new Effect.Fade(el.up().up(), {queue:'end'});
			}
		});
	}
{/literal}
</script>
{/if}
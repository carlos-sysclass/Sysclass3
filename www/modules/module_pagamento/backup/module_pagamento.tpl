{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/payment_options.tpl"}



{if $T_SELECTED_OPTION == 'get_fatura'}
	{foreach name = 'action_list' key = "actionIndex" item = "actionItemHtml" from = $T_ACTION_VIEWS}
		<div>{$actionItemHtml}</div>
	{/foreach} 
{else}
	{foreach name = 'action_list' key = "actionIndex" item = "actionItem" from = $T_ACTION_VIEWS}
		{capture name= $actionIndex }
			{if $T_ACTION_VIEWS_OPTIONS[$actionIndex].istemplate}
				{include file=$actionItem}
			{else}
				{$actionItem}
			{/if}
		{/capture}
	{/foreach}
	
	{capture name="t_action_tabs"}
		<div class="tabber">
			{foreach name = 'action_list' key = "actionIndex" item = "actionItem" from = $T_ACTION_VIEWS_OPTIONS}
				{eF_template_printBlock 
					tabber = $actionIndex 
					title = $actionItem.title
					data = $smarty.capture.$actionIndex
				}
			{/foreach}
		</div>
	{/capture}
	
	{eF_template_printBlock 
		title=$smarty.const._PAGAMENTO_CONFIG_TIPOS
		data=$smarty.capture.t_action_tabs
		link = $T_MODULE_PAGAMENTO_BASEURL
		links = $T_MODULE_PAGAMENTO_OPTIONS 
	}
{/if}

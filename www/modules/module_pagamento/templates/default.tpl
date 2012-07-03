{if $T_PAGAMENTO_BASEDIR}
	{include file="$T_PAGAMENTO_BASEDIR/templates/includes/javascript.tpl"}
	
	{include file="$T_PAGAMENTO_BASEDIR/templates/includes/payment.options.tpl"}
	
	{if $T_PAGAMENTO_MAIN_TEMPLATE}
		{include file="$T_PAGAMENTO_BASEDIR/templates/$T_PAGAMENTO_MAIN_TEMPLATE"}
	{elseif $T_PAGAMENTO_TEMPLATES && $T_PAGAMENTO_TEMPLATES|@count == 1}
		{assign var="item" value=$T_PAGAMENTO_TEMPLATES[0]}
			
		{capture name=$index}
			{include file=$item.template}
		{/capture}
				
		{eF_template_printBlock
			title 			= $item.title
			data			= $smarty.capture.$index
			contentclass	= $item.contentclass
			class			= $item.class
		}
	{elseif $T_PAGAMENTO_TEMPLATES && $T_PAGAMENTO_TEMPLATES|@count > 1}
		{capture name="t_enrollment_tabbers"}
			{foreach name="edit_user_iteration" key="index" item="item" from=$T_PAGAMENTO_TEMPLATES}
				{capture name=$index}
					{include file=$item.template}
				{/capture}
			
				{eF_template_printBlock
					tabber 			= $item.title 
					title 			= $item.title
					data			= $smarty.capture.$index
					class			= $item.class
					contentclass	= $item.contentclass
				}
			{/foreach}
		{/capture}
		
		{eF_template_printBlock 
			title=$smarty.const.__ENROLLMENT_TABS
			data=$smarty.capture.t_enrollment_tabbers
			tabs = $T_PAGAMENTO_TEMPLATES
		}
	{else}
		{include file="$T_PAGAMENTO_BASEDIR/templates/actions/$T_PAGAMENTO_ACTION.tpl"}
	{/if}
	
{else}

	{if $T_MODULE_PAGAMENTO_MAIN_TEMPLATE}
		{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/$T_MODULE_PAGAMENTO_MAIN_TEMPLATE"}
	{else}
		{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/actions/$T_MODULE_PAGAMENTO_ACTION.tpl"}
	{/if}

	{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/javascript.tpl"}
{/if}


